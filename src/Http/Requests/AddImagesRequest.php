<?php

namespace Faithgen\Testimonies\Http\Requests;

use Faithgen\Testimonies\Services\TestimoniesService;
use Faithgen\Testimonies\Testimonies;
use Illuminate\Foundation\Http\FormRequest;

class AddImagesRequest extends FormRequest
{
    private TestimoniesService $testimoniesService;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param \Faithgen\Testimonies\Services\TestimoniesService $testimoniesService
     *
     * @return bool
     */
    public function authorize(TestimoniesService $testimoniesService)
    {
        $this->testimoniesService = $testimoniesService;

        return $this->testimoniesService->getTestimony()
            && $this->user()->can('update', $this->testimoniesService->getTestimony());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'images'   => 'required|array|max:'.$this->getRemainingImages(),
            'images.*' => 'base64image',
        ];
    }

    /**
     * Calculates the number of images the user should upload so that they do not
     * exceed the limit set for their ministries.
     *
     * @return int number of images
     */
    private function getRemainingImages(): int
    {
        $currentImagesCount = $this->testimoniesService->getTestimony()->images()->count();
        $subscriptionLevel = auth()->user()->account->level;
        if ($subscriptionLevel === 'Free') {
            abort(403, 'You are not allowed to add images on this testimony');
        }
        if ($subscriptionLevel === 'Premium') {
            return Testimonies::$premiumImageCount - $currentImagesCount;
        }

        return Testimonies::$premiumPlusImageCount - $currentImagesCount;
    }

    /**
     * Converts image string array to usable string in the validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        if (is_string($this->images)) {
            $this->merge([
                'images' => json_decode($this->images, true),
            ]);
        }
    }
}
