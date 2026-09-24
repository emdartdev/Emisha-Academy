<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'education' => ['nullable', 'string', 'max:150'],
            'occupation' => ['nullable', 'string', 'max:150'],
            'github_url' => ['nullable', 'string', 'url'],
            'linkedin_url' => ['nullable', 'string', 'url'],
            'facebook_url' => ['nullable', 'string', 'url'],
            'website_url' => ['nullable', 'string', 'url'],
        ];
    }
}
