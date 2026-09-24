<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Webinar;
use App\Models\WebinarRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebinarController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->get('status', 'all');

        $query = Webinar::with('speakers');

        if ($status === 'upcoming') {
            $query->whereIn('status', ['upcoming', 'live'])->orderBy('event_datetime');
        } elseif ($status === 'past') {
            $query->where('status', 'past')->orderByDesc('event_datetime');
        } else {
            $query->where('status', '!=', 'cancelled')->orderBy('event_datetime');
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title_bn', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('subtitle_bn', 'like', "%{$search}%")
                    ->orWhere('subtitle_en', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 24);
        $webinars = $query->paginate($perPage);

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

    public function show(string $slug): JsonResponse
    {
        $webinar = Webinar::with('speakers')->where('slug', $slug)->first();

        if (!$webinar) {
            return response()->json([
                'status' => 'error',
                'message' => 'ওয়েবিনারটি পাওয়া যায়নি।',
            ], 404);
        }

        $related = Webinar::where('id', '!=', $webinar->id)
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_datetime', 'asc')
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'webinar' => $webinar,
                'related_webinars' => $related,
            ],
        ]);
    }

    public function register(Request $request, string $slug): JsonResponse
    {
        $webinar = Webinar::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $existing = WebinarRegistration::where('webinar_id', $webinar->id)
            ->where('email', $request->email)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'info',
                'message' => 'আপনি ইতিমধ্যে এই ওয়েবিনারে রেজিস্ট্রেশন করেছেন। আপনার টিকেট নম্বর: ' . $existing->ticket_number,
                'data' => $existing,
            ]);
        }

        $ticket = 'WEB-' . strtoupper(Str::random(6));

        $registration = WebinarRegistration::create([
            'webinar_id' => $webinar->id,
            'user_id' => $request->user('sanctum')?->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'ticket_number' => $ticket,
            'status' => 'confirmed',
        ]);

        $webinar->increment('registered_count');

        // Automatically sync into Unified Lead CRM Pipeline
        $leadType = $webinar->is_seminar ? 'seminar' : 'webinar';
        $lead = \App\Models\Lead::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? 'N/A',
            'whatsapp_number' => $request->phone,
            'lead_type' => $leadType,
            'source_content_type' => 'webinar',
            'source_content_id' => $webinar->id,
            'source_content_title' => $webinar->title_bn ?: $webinar->title_en,
            'source_content_slug' => $webinar->slug,
            'source_url' => "/webinars/{$webinar->slug}",
            'source' => $webinar->is_seminar ? 'seminar_registration' : 'webinar_registration',
            'status' => 'new',
            'priority' => 'normal',
            'notes' => "[রেজিস্ট্রেশন টিকিট: {$ticket}]",
        ]);

        \App\Models\LeadActivity::create([
            'lead_id' => $lead->id,
            'action' => 'webinar_registered',
            'description' => ($webinar->is_seminar ? "সেমিনার রেজিস্ট্রেশন: " : "ওয়েবিনার রেজিস্ট্রেশন: ") . ($webinar->title_bn ?: $webinar->title_en),
            'properties' => [
                'ticket_number' => $ticket,
                'webinar_id' => $webinar->id,
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => ($webinar->is_seminar ? 'সেমিনার' : 'ওয়েবিনার') . ' রেজিস্ট্রেশন সফল হয়েছে! আপনার টিকেট: ' . $ticket,
            'data' => $registration,
        ], 201);
    }
}
