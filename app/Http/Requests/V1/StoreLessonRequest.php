<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
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
            'lesson_type' => ['nullable', 'in:video,text,pdf,audio,quiz,live'],
            'duration' => ['nullable', 'string', 'max:50'],
            'video_provider' => ['nullable', 'in:youtube,vimeo,bunny,html5'],
            'video_url' => ['nullable', 'string'],
            'media_url' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'is_free_preview' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'is_locked' => ['nullable', 'boolean'],
            'order_index' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
