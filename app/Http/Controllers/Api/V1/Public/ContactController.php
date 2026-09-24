<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\EbookDownload;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Webinar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Store contact inquiry.
     */
    public function submitContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $inquiry = ContactInquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'আপনার বার্তাটি আমরা পেয়েছি। শীঘ্রই আমাদের প্রতিনিধি যোগাযোগ করবেন।',
            'data' => $inquiry,
        ], 201);
    }

    /**
     * Store admission, webinar, or ebook lead with exact content attribution.
     */
    public function captureLead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'lead_type' => ['nullable', 'string', 'in:course,webinar,seminar,ebook,general'],
            'source_type' => ['nullable', 'string'],
            'source_content_type' => ['nullable', 'string', 'in:course,webinar,seminar,ebook'],
            'source_content_id' => ['nullable', 'integer'],
            'source_content_slug' => ['nullable', 'string', 'max:255'],
            'course_id' => ['nullable', 'integer'],
            'webinar_id' => ['nullable', 'integer'],
            'ebook_id' => ['nullable', 'integer'],
            'source_url' => ['nullable', 'string', 'max:1000'],
            'interested_topic' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
        ]);

        $leadType = $validated['lead_type'] ?? 'general';
        $contentType = $validated['source_content_type'] ?? null;
        $contentId = $validated['source_content_id'] ?? null;
        $contentSlug = $validated['source_content_slug'] ?? null;
        $contentTitle = null;
        $sourceUrl = $validated['source_url'] ?? $request->header('Referer') ?? null;
        $downloadData = null;

        // 1. Resolve & Validate Course Attribution (Only when targeted)
        if ($contentType === 'course' || $leadType === 'course' || !empty($validated['course_id'])) {
            $targetCourseId = $validated['course_id'] ?? ($contentType === 'course' ? $contentId : null);
            $courseQuery = Course::query();
            if ($targetCourseId) {
                $courseQuery->where('id', $targetCourseId);
            } elseif ($contentSlug) {
                $courseQuery->where('slug', $contentSlug);
            }
            $course = $courseQuery->first();
            if ($course) {
                $leadType = 'course';
                $contentType = 'course';
                $contentId = $course->id;
                $contentTitle = $course->title_bn ?: $course->title_en;
                $contentSlug = $course->slug;
                if (!$sourceUrl) {
                    $sourceUrl = "/courses/{$course->slug}";
                }
            }
        }
        // 2. Resolve & Validate Webinar / Seminar Attribution (Only when targeted)
        elseif ($contentType === 'webinar' || in_array($leadType, ['webinar', 'seminar']) || !empty($validated['webinar_id'])) {
            $targetWebinarId = $validated['webinar_id'] ?? ($contentType === 'webinar' ? $contentId : null);
            $webinarQuery = Webinar::query();
            if ($targetWebinarId) {
                $webinarQuery->where('id', $targetWebinarId);
            } elseif ($contentSlug) {
                $webinarQuery->where('slug', $contentSlug);
            }
            $webinar = $webinarQuery->first();
            if ($webinar) {
                $contentType = 'webinar';
                $contentId = $webinar->id;
                $contentTitle = $webinar->title_bn ?: $webinar->title_en;
                $contentSlug = $webinar->slug;
                $leadType = ($leadType === 'seminar' || $webinar->is_seminar) ? 'seminar' : 'webinar';
                if (!$sourceUrl) {
                    $sourceUrl = "/webinars/{$webinar->slug}";
                }
            }
        }
        // 3. Resolve & Validate Ebook Attribution (Only when targeted)
        elseif ($contentType === 'ebook' || $leadType === 'ebook' || !empty($validated['ebook_id'])) {
            $targetEbookId = $validated['ebook_id'] ?? ($contentType === 'ebook' ? $contentId : null);
            $ebookQuery = Ebook::query();
            if ($targetEbookId) {
                $ebookQuery->where('id', $targetEbookId);
            } elseif ($contentSlug) {
                $ebookQuery->where('slug', $contentSlug);
            }
            $ebook = $ebookQuery->first();
            if ($ebook) {
                $leadType = 'ebook';
                $contentType = 'ebook';
                $contentId = $ebook->id;
                $contentTitle = $ebook->title_bn ?: $ebook->title_en;
                $contentSlug = $ebook->slug;
                if (!$sourceUrl) {
                    $sourceUrl = "/ebooks/{$ebook->slug}";
                }

                // Track Ebook download metric
                EbookDownload::create([
                    'ebook_id' => $ebook->id,
                    'user_id' => $request->user('sanctum')?->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'downloaded_at' => now(),
                ]);
                $ebook->increment('download_count');

                $downloadData = [
                    'file_path' => $ebook->file_path,
                    'preview_pdf_path' => $ebook->preview_pdf_path,
                    'download_count' => $ebook->download_count,
                ];
            }
        }

        $whatsapp = $validated['whatsapp_number'] ?? $validated['phone'];
        $interestedTopic = $validated['interested_topic'] ?? $contentTitle;

        // 4. Duplicate Check within 24 Hours for the same phone + content
        $existingLead = Lead::where('phone', $validated['phone'])
            ->where('source_content_type', $contentType)
            ->where('source_content_id', $contentId)
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        if ($existingLead) {
            // Update last contact and add note/activity instead of creating a clone
            $existingLead->update([
                'name' => $validated['name'],
                'whatsapp_number' => $whatsapp,
                'email' => $validated['email'] ?? $existingLead->email,
                'notes' => $validated['notes'] ? ($existingLead->notes . "\n[পুনরায় আবেদন] " . $validated['notes']) : $existingLead->notes,
                'updated_at' => now(),
            ]);

            LeadActivity::create([
                'lead_id' => $existingLead->id,
                'action' => 'lead_resubmitted',
                'description' => "গ্রাহক পুনরায় একই কনটেন্টে আবেদন জমা দিয়েছেন: {$contentTitle}",
                'properties' => [
                    'source_url' => $sourceUrl,
                    'ip' => $request->ip(),
                ],
            ]);

            $lead = $existingLead;
        } else {
            // 5. Create Fresh Attributed Lead
            $lead = Lead::create([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'whatsapp_number' => $whatsapp,
                'lead_type' => $leadType,
                'source_content_type' => $contentType,
                'source_content_id' => $contentId,
                'source_content_title' => $contentTitle,
                'source_content_slug' => $contentSlug,
                'source_url' => $sourceUrl,
                'course_id' => $contentType === 'course' ? $contentId : null,
                'interested_topic' => $interestedTopic,
                'source' => $validated['source'] ?? ($leadType . '_lead_form'),
                'status' => 'new',
                'priority' => 'normal',
                'notes' => $validated['notes'] ?? null,
                'utm_source' => $validated['utm_source'] ?? null,
                'utm_medium' => $validated['utm_medium'] ?? null,
                'utm_campaign' => $validated['utm_campaign'] ?? null,
            ]);

            // 6. Log Initial Activity Timeline
            $actionLabel = match ($leadType) {
                'course' => "কোর্স ভর্তি আবেদন: {$contentTitle}",
                'webinar' => "ওয়েবিনার আগ্রহ/রেজিস্ট্রেশন: {$contentTitle}",
                'seminar' => "সেমিনার আগ্রহ/রেজিস্ট্রেশন: {$contentTitle}",
                'ebook' => "ইবুক ডাউনলোড রিকোয়েস্ট: {$contentTitle}",
                default => "সাধারণ লিড জমা হয়েছে",
            };

            LeadActivity::create([
                'lead_id' => $lead->id,
                'action' => 'lead_created',
                'description' => $actionLabel,
                'properties' => [
                    'lead_type' => $leadType,
                    'content_title' => $contentTitle,
                    'content_id' => $contentId,
                    'source_url' => $sourceUrl,
                    'ip' => $request->ip(),
                ],
            ]);
        }

        // Return pleasant localized message
        $successMessage = match ($leadType) {
            'ebook' => 'ধন্যবাদ! আপনার ইবুক ডাউনলোড শুরু হয়েছে। আমাদের এক্সপার্ট টিম প্রয়োজনে আপনার সাথে যোগাযোগ করবে।',
            'webinar', 'seminar' => 'ধন্যবাদ! আপনার রেজিস্ট্রেশন সফল হয়েছে। শীঘ্রই জুম লিংক ও বিস্তারিত জানানো হবে।',
            default => 'ধন্যবাদ! আপনার ভর্তির আবেদনটি গৃহীত হয়েছে। আমাদের সিনিয়র কাউন্সিলর শীঘ্রই যোগাযোগ করবেন।',
        };

        if ($downloadData) {
            $lead->setAttribute('download', $downloadData);
        }

        return response()->json([
            'status' => 'success',
            'message' => $successMessage,
            'data' => $lead,
        ], 201);
    }
}
