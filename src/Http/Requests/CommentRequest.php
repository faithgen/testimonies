<?php

namespace Faithgen\Testimonies\Http\Requests;

use FaithGen\SDK\Helpers\Helper;
use FaithGen\SDK\Models\Ministry;
use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(TestimoniesService $testimoniesService)
    {
        if (auth()->user() instanceof Ministry) {
            return $this->user()->can('view', $testimoniesService->getTestimony());
        }

        return true;
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
            'comment' => 'required|string',
        ];
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You do not have access to this testimony');
    }
}
