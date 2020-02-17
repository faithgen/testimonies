<?php

namespace Faithgen\Testimonies\Http\Controllers;

use Illuminate\Http\Request;
use FaithGen\SDK\Models\User;
use Illuminate\Routing\Controller;
use InnoFlash\LaraStart\Http\Helper;
use Faithgen\Testimonies\Models\Testimony;
use InnoFlash\LaraStart\Traits\APIResponses;
use Illuminate\Foundation\Bus\DispatchesJobs;
use InnoFlash\LaraStart\Http\Requests\IndexRequest;
use Faithgen\Testimonies\Http\Requests\CreateRequest;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Faithgen\Testimonies\Http\Resources\TestimonyDetails;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Faithgen\Testimonies\Http\Requests\ToggleApprovalRequest;
use Faithgen\Testimonies\Http\Resources\Testimony as TestimonyResource;

/**
 * Controlls testimonies
 */
final class TestimonyController extends Controller
{
    use ValidatesRequests, AuthorizesRequests, DispatchesJobs, APIResponses;

    /**
     * Injects the testimonies service class
     *
     * @var TestimoniesService
     */
    private TestimoniesService $testimoniesService;

    /**
     * Injects the service class
     * 
     * @param TestimoniesService $testimoniesService 
     */
    public function __construct(TestimoniesService $testimoniesService)
    {
        $this->testimoniesService = $testimoniesService;
    }

    /**
     * Creates a testimony for the given user
     *
     * @param CreateRequest $request
     * @return void
     */
    public function create(CreateRequest $request)
    {
        $testifier = auth('web')->user();
        $params = array_merge($request->validated(), [
            'ministry_id' => auth()->user()->id
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
     * Fetches the testimonies
     *
     * @param IndexRequest $request
     * @return void
     */
    public function index(IndexRequest $request)
    {
        $testimonies = auth()->user()
            ->testimonies()
            ->with(['user', 'images'])
            ->where('title', 'LIKE', '%' . $request->filter_text . '%')
            ->orWhere('created_at', 'LIKE', '%' . $request->filter_text . '%')
            ->orWhereHas('user',  function ($user) use ($request) {
                return $user->where('name', 'LIKE', '%' . $request->filter_text . '%');
            })
            ->approved()
            ->latest()
            ->paginate(Helper::getLimit($request));
        TestimonyResource::wrap('testimonies');
        return TestimonyResource::collection($testimonies);
    }

    /**
     * Retrieves the testimony details
     * 
     * Shows only to the owner ministry
     *
     * @param Testimony $testimony
     * @return void
     */
    public function show(Testimony $testimony)
    {
        $this->authorize('view', $testimony);
        TestimonyDetails::withoutWrapping();
        return new TestimonyDetails($testimony);
    }

    /**
     * Deletes the testimony
     *
     * @param Testimony $testimony
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
     * Approves and disapprove a testimony
     *
     * @param ToggleApprovalRequest $request
     * @return void
     */
    public function toggleApproval(ToggleApprovalRequest $request)
    {
        return $this->testimoniesService->update($request->validated(), 'Testimony approval status updated');
    }

    /**
     * Fetches testimonies for a given user id who belongs to the authenticated ministry
     *
     * @param Request $request You may include a limit in the request
     * @param User $user
     * @return void
     */
    public function userTestimonies(Request $request, User $user)
    {
        if (auth()->user()->ministryUsers()->where('user_id', $user->id)->first()) {
            $testimonies =  auth()->user()
                ->testimonies()
                ->with(['user', 'images'])
                ->where('user_id', $user->id)
                ->approved($user)
                ->latest()
                ->paginate(Helper::getLimit($request));
            TestimonyResource::wrap('testimonies');
            return TestimonyResource::collection($testimonies);
        }
        return abort(403, 'You are not allowed to view testimonies from this user');
    }
}
