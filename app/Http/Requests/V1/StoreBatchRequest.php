<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('batches.manage') || $this->user()?->hasRole(['SuperAdmin', 'Admin', 'Manager']);
    }

    public function rules(): array
    {
        return [
            'batch_number' => ['required', 'string', 'max:50'],
            'title_bn' => ['nullable', 'string', 'max:150'],
            'title_en' => ['nullable', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'enrollment_deadline' => ['nullable', 'date'],
            'class_days' => ['nullable', 'string', 'max:100'],
            'class_time' => ['nullable', 'string', 'max:100'],
            'seat_capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'status' => ['required', 'in:upcoming,enrolling,ongoing,completed,closed'],
        ];
    }
}
