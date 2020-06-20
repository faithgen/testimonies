<?php

namespace Faithgen\Testimonies\Http\Requests;

use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class ToggleApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param \Faithgen\Testimonies\Services\TestimoniesService $testimoniesService
     *
     * @return bool
     */
    public function authorize(TestimoniesService $testimoniesService)
    {
        return $testimoniesService->getTestimony() && $this->user()->can('update', $testimoniesService->getTestimony());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'approved' => 'required|boolean',
        ];
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You are not permitted to transact on this testimony.');
    }
}
