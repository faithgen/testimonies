<?php

namespace Faithgen\Testimonies\Http\Requests;

use FaithGen\SDK\Helpers\Helper;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Foundation\Http\FormRequest;

class DeleteImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(TestimoniesService $testimoniesService)
    {
        return $this->user()->can('update', $testimoniesService->getTestimony());
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
            'image_id' => Helper::$idValidation,
        ];
    }
}
