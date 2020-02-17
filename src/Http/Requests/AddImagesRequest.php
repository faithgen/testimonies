<?php

namespace Faithgen\Testimonies\Http\Requests;

use FaithGen\SDK\Helpers\Helper;
use Faithgen\Testimonies\Testimonies;
use Illuminate\Foundation\Http\FormRequest;
use Faithgen\Testimonies\Services\TestimoniesService;

class AddImagesRequest extends FormRequest
{
    private TestimoniesService $testimoniesService;
    /**
     * Determine if the user is authorized to make this request.
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
            'testimony_id' => Helper::$idValidation,
            'images' => 'required|array|max:' . $this->getRemainingImages(),
            'images.*' => 'base64image'
        ];
    }

    /**
     * Calculates the number of images the user should upload so that they do not
     * exceed the limit set for their ministries
     *
     * @return integer number of images
     */
    private function getRemainingImages(): int
    {
        $currentImagesCount = $this->testimoniesService->getTestimony()->images()->count();
        $subscriptionLevel = auth()->user()->account->level;
        if ($subscriptionLevel === 'Free')
            abort(403, 'You are not allowed to add images on this testimony');
        if ($subscriptionLevel === 'Premium')
            return Testimonies::$premiumImageCount - $currentImagesCount;
        return Testimonies::$premiumPlusImageCount - $currentImagesCount;
    }
}
