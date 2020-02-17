<?php

namespace Faithgen\Testimonies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    private $primaryRules = [
        'title' => 'required|string',
        'testimony' => 'required|string',
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
        if ($subscriptionLevel === 'PremiumPlus')
            return $this->getPremiumRules();
        return $this->primaryRules;
    }
}
