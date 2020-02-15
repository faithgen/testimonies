<?php

namespace Faithgen\Testimonies\Http\Controllers;

use Faithgen\Testimonies\Http\Requests\CreateRequest;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use InnoFlash\LaraStart\Http\Helper;
use InnoFlash\LaraStart\Http\Requests\IndexRequest;
use InnoFlash\LaraStart\Traits\APIResponses;
use Faithgen\Testimonies\Http\Resources\Testimony as TestimonyResource;
use Faithgen\Testimonies\Http\Resources\TestimonyDetails;
use Faithgen\Testimonies\Models\Testimony;

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
        $testimonies = $this->testimoniesService
            ->getTestimony()
            ->with(['user', 'images'])
            ->where('title', 'LIKE', '%' . $request->filter_text . '%')
            ->orWhere('created_at', 'LIKE', '%' . $request->filter_text . '%')
            ->orWhereHas('user',  function ($user) use ($request) {
                return $user->where('name', 'LIKE', '%' . $request->filter_text . '%');
            })->latest()
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
}
