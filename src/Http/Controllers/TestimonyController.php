<?php

namespace Faithgen\Testimonies\Http\Controllers;

use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestimonyController extends Controller
{
    use ValidatesRequests, AuthorizesRequests, DispatchesJobs;
    protected $testimoniesService;

    public function __construct(TestimoniesService $testimoniesService)
    {
        $this->testimoniesService = $testimoniesService;
    }
}
