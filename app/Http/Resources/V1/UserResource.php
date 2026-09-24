<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'status' => $this->status,
            'roles' => $this->getRoleNames(),
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'profile' => [
                'headline' => $this->profile?->headline,
                'bio' => $this->profile?->bio,
                'city' => $this->profile?->city,
                'country' => $this->profile?->country,
                'education' => $this->profile?->education,
                'occupation' => $this->profile?->occupation,
                'github_url' => $this->profile?->github_url,
                'linkedin_url' => $this->profile?->linkedin_url,
                'facebook_url' => $this->profile?->facebook_url,
                'website_url' => $this->profile?->website_url,
            ],
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
