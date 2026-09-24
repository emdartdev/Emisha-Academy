<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Models\WebinarRegistration;
use App\Models\WebinarSpeaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminWebinarController extends Controller
{
    /**
     * List all webinars with search, status filtering, and counts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Webinar::with(['speakers'])->withCount('registrations');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title_bn', 'like', $search)
                    ->orWhere('title_en', 'like', $search)
                    ->orWhere('subtitle_bn', 'like', $search)
                    ->orWhere('subtitle_en', 'like', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $webinars = $query->latest('id')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'webinars' => $webinars->items(),
                'pagination' => [
                    'current_page' => $webinars->currentPage(),
                    'last_page' => $webinars->lastPage(),
                    'total' => $webinars->total(),
                ],
            ],
        ]);
    }

    /**
     * Store a newly created webinar.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:webinars,slug',
            'subtitle_bn' => 'nullable|string',
            'subtitle_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'event_datetime' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:10',
            'platform' => 'nullable|string|max:100',
            'meeting_link' => 'nullable|string',
            'recording_url' => 'nullable|string',
            'registration_fee' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'max_participants' => 'nullable|integer|min:1',
            'registered_count' => 'nullable|integer|min:0',
            'highlights_bn' => 'nullable|array',
            'highlights_en' => 'nullable|array',
            'agenda_bn' => 'nullable|array',
            'agenda_en' => 'nullable|array',
            'certificate_title_bn' => 'nullable|string|max:255',
            'certificate_title_en' => 'nullable|string|max:255',
            'certificate_note_bn' => 'nullable|string',
            'certificate_note_en' => 'nullable|string',
            'lab_upsell_title_bn' => 'nullable|string|max:255',
            'lab_upsell_title_en' => 'nullable|string|max:255',
            'lab_upsell_desc_bn' => 'nullable|string',
            'lab_upsell_desc_en' => 'nullable|string',
            'status' => 'nullable|in:upcoming,live,past,cancelled',
            'is_featured' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $slug = Str::slug($validated['title_en']);
            $originalSlug = $slug;
            $count = 1;
            while (Webinar::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $validated['slug'] = $slug;
        }

        $validated['duration_minutes'] = $validated['duration_minutes'] ?? 90;
        $validated['platform'] = $validated['platform'] ?? 'Zoom Live & Mirpur Lab';
        $validated['registration_fee'] = $validated['registration_fee'] ?? 0;
        $validated['is_free'] = $validated['is_free'] ?? true;
        $validated['max_participants'] = $validated['max_participants'] ?? 100;
        $validated['registered_count'] = $validated['registered_count'] ?? 0;
        $validated['status'] = $validated['status'] ?? 'upcoming';
        $validated['is_featured'] = $validated['is_featured'] ?? false;

        $webinar = Webinar::create($validated);

        // Optional speakers passed during creation
        if ($request->has('speakers') && is_array($request->speakers)) {
            foreach ($request->speakers as $idx => $spData) {
                if (!empty($spData['name_bn']) || !empty($spData['name_en'])) {
                    $webinar->speakers()->create([
                        'name_bn' => $spData['name_bn'] ?? ($spData['name_en'] ?? ''),
                        'name_en' => $spData['name_en'] ?? ($spData['name_bn'] ?? ''),
                        'designation_bn' => $spData['designation_bn'] ?? '',
                        'designation_en' => $spData['designation_en'] ?? '',
                        'organization' => $spData['organization'] ?? 'Emisha Academy',
                        'avatar' => $spData['avatar'] ?? null,
                        'bio_bn' => $spData['bio_bn'] ?? '',
                        'bio_en' => $spData['bio_en'] ?? '',
                        'linkedin_url' => $spData['linkedin_url'] ?? null,
                        'order_index' => $idx + 1,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'মাস্টারক্লাস / ওয়েবিনার সফলভাবে তৈরি হয়েছে।',
            'data' => $webinar->load('speakers'),
        ], 201);
    }

    /**
     * Show single webinar details.
     */
    public function show(int $id): JsonResponse
    {
        $webinar = Webinar::with(['speakers', 'registrations'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $webinar,
        ]);
    }

    /**
     * Update an existing webinar.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $webinar = Webinar::findOrFail($id);

        $validated = $request->validate([
            'title_bn' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:webinars,slug,' . $id,
            'subtitle_bn' => 'nullable|string',
            'subtitle_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'description_en' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'event_datetime' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:10',
            'platform' => 'nullable|string|max:100',
            'meeting_link' => 'nullable|string',
            'recording_url' => 'nullable|string',
            'registration_fee' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'max_participants' => 'nullable|integer|min:1',
            'registered_count' => 'nullable|integer|min:0',
            'highlights_bn' => 'nullable|array',
            'highlights_en' => 'nullable|array',
            'agenda_bn' => 'nullable|array',
            'agenda_en' => 'nullable|array',
            'certificate_title_bn' => 'nullable|string|max:255',
            'certificate_title_en' => 'nullable|string|max:255',
            'certificate_note_bn' => 'nullable|string',
            'certificate_note_en' => 'nullable|string',
            'lab_upsell_title_bn' => 'nullable|string|max:255',
            'lab_upsell_title_en' => 'nullable|string|max:255',
            'lab_upsell_desc_bn' => 'nullable|string',
            'lab_upsell_desc_en' => 'nullable|string',
            'status' => 'nullable|in:upcoming,live,past,cancelled',
            'is_featured' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = $webinar->slug;
        }

        $webinar->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'মাস্টারক্লাসের তথ্য সফলভাবে আপডেট হয়েছে।',
            'data' => $webinar->fresh()->load('speakers'),
        ]);
    }

    /**
     * Delete a webinar.
     */
    public function destroy(int $id): JsonResponse
    {
        $webinar = Webinar::findOrFail($id);
        $webinar->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'ওয়েবিনার সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Get speakers for a webinar.
     */
    public function speakers(int $webinarId): JsonResponse
    {
        $speakers = WebinarSpeaker::where('webinar_id', $webinarId)
            ->orderBy('order_index')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $speakers,
        ]);
    }

    /**
     * Store a new speaker for a webinar.
     */
    public function storeSpeaker(Request $request, int $webinarId): JsonResponse
    {
        $webinar = Webinar::findOrFail($webinarId);

        $validated = $request->validate([
            'name_bn' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'designation_bn' => 'required|string|max:200',
            'designation_en' => 'required|string|max:200',
            'organization' => 'nullable|string|max:200',
            'avatar' => 'nullable|string',
            'bio_bn' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'linkedin_url' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer',
        ]);

        $validated['webinar_id'] = $webinar->id;
        $validated['order_index'] = $validated['order_index'] ?? ($webinar->speakers()->count() + 1);

        $speaker = WebinarSpeaker::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'স্পিকার সফলভাবে যুক্ত হয়েছে।',
            'data' => $speaker,
        ], 201);
    }

    /**
     * Update a speaker.
     */
    public function updateSpeaker(Request $request, int $speakerId): JsonResponse
    {
        $speaker = WebinarSpeaker::findOrFail($speakerId);

        $validated = $request->validate([
            'name_bn' => 'required|string|max:150',
            'name_en' => 'required|string|max:150',
            'designation_bn' => 'required|string|max:200',
            'designation_en' => 'required|string|max:200',
            'organization' => 'nullable|string|max:200',
            'avatar' => 'nullable|string',
            'bio_bn' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'linkedin_url' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer',
        ]);

        $speaker->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'স্পিকার তথ্য আপডেট হয়েছে।',
            'data' => $speaker,
        ]);
    }

    /**
     * Delete a speaker.
     */
    public function destroySpeaker(int $speakerId): JsonResponse
    {
        $speaker = WebinarSpeaker::findOrFail($speakerId);
        $speaker->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'স্পিকার সফলভাবে মুছে ফেলা হয়েছে।',
        ]);
    }

    /**
     * Get attendee registrations for a webinar.
     */
    public function registrations(Request $request, int $webinarId): JsonResponse
    {
        $query = WebinarRegistration::where('webinar_id', $webinarId);

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('phone', 'like', $search)
                    ->orWhere('ticket_number', 'like', $search);
            });
        }

        $registrations = $query->latest('id')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => [
                'registrations' => $registrations->items(),
                'pagination' => [
                    'current_page' => $registrations->currentPage(),
                    'last_page' => $registrations->lastPage(),
                    'total' => $registrations->total(),
                ],
            ],
        ]);
    }

    /**
     * Toggle attended status or update registration.
     */
    public function updateRegistration(Request $request, int $registrationId): JsonResponse
    {
        $reg = WebinarRegistration::findOrFail($registrationId);

        $validated = $request->validate([
            'has_attended' => 'nullable|boolean',
            'status' => 'nullable|in:confirmed,cancelled',
        ]);

        $reg->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'রেজিস্ট্রেশন তথ্য আপডেট হয়েছে।',
            'data' => $reg,
        ]);
    }

    /**
     * Delete an attendee registration.
     */
    public function destroyRegistration(int $registrationId): JsonResponse
    {
        $reg = WebinarRegistration::findOrFail($registrationId);
        $webinarId = $reg->webinar_id;
        $reg->delete();

        // Decrement registered count
        Webinar::where('id', $webinarId)->where('registered_count', '>', 0)->decrement('registered_count');

        return response()->json([
            'status' => 'success',
            'message' => 'রেজিস্ট্রেশন রেকর্ড মুছে ফেলা হয়েছে।',
        ]);
    }
}
