<?php

namespace Faithgen\Testimonies\Http\Requests;

use Faithgen\Testimonies\Testimonies;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    private $primaryRules = [
        'title' => 'required|string',
        'testimony' => 'required|string',
    ];

    /**
     * Gets the rules for a ministry with a premium subscription
     *
     * @return array an set of premium attributes
     */
    private function getPremiumRules(): array
    {
        return array_merge($this->primaryRules, [
            'images' => 'array|max:' . Testimonies::$premiumImageCount,
            'images.*' => 'base64image'
        ]);
    }

    /**
     * Gets the rules for a ministry with a premuim+ subscription
     *
     * @return array
     */
    private function getPremiumPlusRules(): array
    {
        return array_merge($this->getPremiumRules(), [
            'images' => 'array|max:' . Testimonies::$premiumPlusImageCount,
            'resource' => 'url'
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth('web')->user();
        if ($user && $user->active) return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $subscriptionLevel = auth()->user()->account->level;
        if ($subscriptionLevel === 'Free')
            return  $this->primaryRules;
        if ($subscriptionLevel === 'Premium')
            return $this->getPremiumRules();
        return $this->getPremiumPlusRules();
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('You are not allowed to be posting testimonies here!');
    }
}
