<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('curriculum.manage') || $this->user()?->hasRole(['SuperAdmin', 'Admin', 'Manager', 'Counselor', 'Worker', 'Moderator']);
    }

    public function rules(): array
    {
        return [
            'title_bn' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'summary_bn' => ['nullable', 'string', 'max:500'],
            'summary_en' => ['nullable', 'string', 'max:500'],
            'order_index' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
