<?php

namespace Faithgen\Testimonies\Http\Controllers;

use Faithgen\Testimonies\Http\Requests\CreateRequest;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Controlls testimonies
 */
final class TestimonyController extends Controller
{
    use ValidatesRequests, AuthorizesRequests, DispatchesJobs;

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

    public function create(CreateRequest $request)
    {
    }
}
