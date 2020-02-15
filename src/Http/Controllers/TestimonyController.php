<?php

namespace Faithgen\Testimonies\Http\Controllers;

use Faithgen\Testimonies\Http\Requests\CreateRequest;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use InnoFlash\LaraStart\Traits\APIResponses;

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
}
