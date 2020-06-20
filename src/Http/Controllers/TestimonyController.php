<?php

namespace Faithgen\Testimonies\Http\Controllers;

use FaithGen\SDK\Helpers\CommentHelper;
use FaithGen\SDK\Http\Requests\CommentRequest;
use FaithGen\SDK\Models\User;
use Faithgen\Testimonies\Http\Requests\AddImagesRequest;
use Faithgen\Testimonies\Http\Requests\CreateRequest;
use Faithgen\Testimonies\Http\Requests\DeleteImageRequest;
use Faithgen\Testimonies\Http\Requests\ToggleApprovalRequest;
use Faithgen\Testimonies\Http\Requests\UpdateRequest;
use Faithgen\Testimonies\Http\Resources\Testimony as TestimonyResource;
use Faithgen\Testimonies\Http\Resources\TestimonyDetails;
use Faithgen\Testimonies\Jobs\ProcessImages;
use Faithgen\Testimonies\Jobs\S3Upload;
use Faithgen\Testimonies\Jobs\UploadImages;
use Faithgen\Testimonies\Models\Testimony;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use InnoFlash\LaraStart\Helper;
use InnoFlash\LaraStart\Http\Requests\IndexRequest;
use InnoFlash\LaraStart\Traits\APIResponses;

/**
 * Controlls \testimonies.
 */
final class TestimonyController extends Controller
{
    use ValidatesRequests, AuthorizesRequests, DispatchesJobs, APIResponses;

    /**
     * Injects the testimonies service class.
     *
     * @var TestimoniesService
     */
    private TestimoniesService $testimoniesService;

    /**
     * Injects the service class.
     *
     * @param  TestimoniesService  $testimoniesService
     */
    public function __construct(TestimoniesService $testimoniesService)
    {
        $this->testimoniesService = $testimoniesService;
    }

    /**
     * Creates a testimony for the given user.
     *
     * @param  CreateRequest  $request
     *
     * @return void
     */
    public function create(CreateRequest $request)
    {
        $testifier = auth('web')->user();
        $params = array_merge($request->validated(), [
            'ministry_id' => auth()->user()->id,
        ]);
        unset($params['images']);
        try {
            $testifier->testimonies()->create($params);

            return $this->successResponse('Testimony was posted successfully, waiting for admin to approve!');
        } catch (\Exception $exc) {
            return abort(500, $exc->getMessage());
        }
    }

    /**
     * Fetches the testimonies.
     *
     * @param  IndexRequest  $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(IndexRequest $request)
    {
        $testimonies = auth()
            ->user()
            ->testimonies()
            ->latest()
            ->where(function ($testimony) use ($request) {
                return $testimony->search(['title', 'created_at'], $request->filter_text)
                    ->orWhereHas('user', fn ($user) => $user->search('name', $request->filter_text));
            })
            ->with(['user.image', 'images'])
            ->exclude(['testimony', 'resource'])
            ->approved()
            ->paginate(Helper::getLimit($request));

        TestimonyResource::wrap('testimonies');

        return TestimonyResource::collection($testimonies);
    }

    /**
     * Retrieves the testimony details.
     *
     * Shows only to the owner ministry
     *
     * @param  Testimony  $testimony
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return TestimonyDetails
     */
    public function show(Testimony $testimony)
    {
        $this->authorize('view', $testimony);
        TestimonyDetails::withoutWrapping();

        return new TestimonyDetails($testimony);
    }

    /**
     * Deletes the testimony.
     *
     * @param  Testimony  $testimony
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return void
     */
    public function destroy(Testimony $testimony)
    {
        $this->authorize('delete', $testimony);
        try {
            $testimony->delete();

            return $this->successResponse('Testimony deleted!');
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }

    /**
     * Approves and disapprove a testimony.
     *
     * @param  ToggleApprovalRequest  $request
     *
     * @return void
     */
    public function toggleApproval(ToggleApprovalRequest $request)
    {
        return $this->testimoniesService->update($request->validated(), 'Testimony approval status updated');
    }

    /**
     * Fetches testimonies for a given user id who belongs to the authenticated ministry.
     *
     * @param  Request  $request  You may include a limit in the request
     * @param  User  $user
     *
     * @return void
     */
    public function userTestimonies(Request $request, User $user)
    {
        if (auth()->user()->ministryUsers()->where('user_id', $user->id)->first()) {
            $testimonies = auth()
                ->user()
                ->testimonies()
                ->where(function ($testimony) use ($request, $user) {
                    return $testimony->where('user_id', $user->id);
                })
                ->with(['user', 'images'])
                ->approved($user)
                ->latest()
                ->paginate(Helper::getLimit($request));

            TestimonyResource::wrap('testimonies');

            return TestimonyResource::collection($testimonies);
        }

        return abort(403, 'You are not allowed to view testimonies from this user');
    }

    /**
     * Updates the user,s testimony here.
     *
     * @param  UpdateRequest  $request
     *
     * @return void
     */
    public function update(UpdateRequest $request)
    {
        return $this->testimoniesService->update($request->validated(), 'Testimony updated successfully!');
    }

    /**
     * Deletes an image from a testimony.
     *
     * @param  DeleteImageRequest  $request
     *
     * @return void
     */
    public function destroyImage(DeleteImageRequest $request)
    {
        $image = $this->testimoniesService->getTestimony()->images()->findOrFail($request->image_id);
        try {
            unlink(storage_path('app/public/testimonies/100-100/'.$image->name));
            unlink(storage_path('app/public/testimonies/50-50/'.$image->name));
            unlink(storage_path('app/public/testimonies/original/'.$image->name));

            return $this->successResponse('Image deleted!');
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        } finally {
            $image->delete();
        }
    }

    /**
     * Uploads images attaching them to a given testimony.
     *
     * @param  AddImagesRequest  $request
     *
     * @return void
     */
    public function addImages(AddImagesRequest $request)
    {
        UploadImages::withChain([
            new ProcessImages($this->testimoniesService->getTestimony()),
            new S3Upload($this->testimoniesService->getTestimony()),
        ])->dispatch(
            $this->testimoniesService->getTestimony(),
            $request->images
        );

        return $this->successResponse('Images uploaded, processing them now');
    }

    /**
     * Fetches comments for the given testimony.
     *
     * @param  Request  $request
     * @param  Testimony  $testimony
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function comments(Request $request, Testimony $testimony)
    {
        $this->authorize('view', $testimony);

        return CommentHelper::getComments($testimony, $request);
    }

    /**
     * This sends a comment to the given testimony.
     *
     * @param  CommentRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(CommentRequest $request)
    {
        return CommentHelper::createComment($this->testimoniesService->getTestimony(), $request);
    }
}
