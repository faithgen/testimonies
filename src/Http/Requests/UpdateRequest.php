<?php

namespace Faithgen\Testimonies\Http\Requests;

use Faithgen\Testimonies\Services\TestimoniesService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    private $primaryRules = [
        'title' => 'required|string',
        'testimony' => 'required|string',
        'testimony_id' => 'required|string'
    ];

    /**
     * Used for an ministry at PremiumPlus subscription
     *
     * @return array
     */
    private function getPremiumPlusRules(): array
    {
        return array_merge($this->primaryRules, [
            'resource' => 'url'
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(TestimoniesService $testimoniesService)
    {
        return $this->user()->can('update', $testimoniesService->getTestimony());
        $user = auth('web')->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $subscriptionLevel = auth()->user()->account->level;
        if ($subscriptionLevel === 'PremiumPlus')
            return $this->getPremiumRules();
        return $this->primaryRules;
    }

    function failedAuthorization()
    {
        throw new AuthorizationException('You are not allowed to update this testimony');
    }
}
