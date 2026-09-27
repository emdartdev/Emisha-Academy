<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('courses.create') || $this->user()?->hasRole(['SuperAdmin', 'Admin', 'Manager', 'Counselor', 'Worker', 'Moderator']);
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:course_categories,id'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'instructor_ids' => ['nullable', 'array'],
            'instructor_ids.*' => ['exists:instructors,id'],
            'title_bn' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'subtitle_bn' => ['nullable', 'string', 'max:500'],
            'subtitle_en' => ['nullable', 'string', 'max:500'],
            'description_bn' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:2048', 'regex:/^(https?:\/\/|\/storage\/)/i'],
            'promo_video_url' => ['nullable', 'string', 'url'],
            'level' => ['required', 'in:beginner,intermediate,advanced,all_levels'],
            'format' => ['required', 'in:live,recorded,hybrid'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['boolean'],
            'duration_weeks' => ['nullable', 'string', 'max:50'],
            'total_hours' => ['nullable', 'integer', 'min:1'],
            'total_classes' => ['nullable', 'integer', 'min:1'],
            'total_projects' => ['nullable', 'integer', 'min:0'],
            'features_bn' => ['nullable', 'array'],
            'features_en' => ['nullable', 'array'],
            'prerequisites_bn' => ['nullable', 'array'],
            'prerequisites_en' => ['nullable', 'array'],
            'target_audience_bn' => ['nullable', 'array'],
            'target_audience_en' => ['nullable', 'array'],
            'is_featured' => ['boolean'],
            'is_popular' => ['boolean'],
            'status' => ['required', 'in:draft,published,archived'],
        ];
    }
}
