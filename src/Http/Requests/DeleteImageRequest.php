<?php

namespace Faithgen\Testimonies\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Faithgen\Testimonies\Services\TestimoniesService;

class DeleteImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(TestimoniesService $testimoniesService)
    {
        $user = auth('web')->user();
        if (($user && $user->active)
            && ($testimoniesService->getTestimony() && $testimoniesService->getTestimony()->user_id === $user->id)
        ) return true;


        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
