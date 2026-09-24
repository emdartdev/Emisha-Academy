<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\CmsTestimonial;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseFaq;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use App\Models\CourseReview;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\Instructor;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Webinar;
use App\Models\WebinarRegistration;
use App\Models\WebinarSpeaker;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructor = Instructor::first();
        $studentUser = User::where('email', 'student@emishaacademy.com')->first();
        $adminUser = User::where('email', 'admin@emishaacademy.com')->first();

        // 1. Course Categories (Aviation, Visa, Graphic Design, Digital Marketing)
        $catAviation = CourseCategory::updateOrCreate(
            ['slug' => 'air-ticketing-aviation'],
            [
                'name_bn' => 'এয়ার টিকেটিং ও এভিয়েশন',
                'name_en' => 'Air Ticketing & Aviation',
                'track_title_bn' => 'Sabre & Galileo GDS',
                'track_title_en' => 'Sabre & Galileo GDS',
                'badge_text_bn' => '১',
                'badge_text_en' => '1 Course',
                'icon' => 'Plane',
                'description_bn' => 'Sabre ও Galileo GDS সফটওয়্যার সহ এয়ার টিকেটিং এর হ্যান্ডস-অন প্র্যাকটিক্যাল ট্রেনিং।',
                'description_en' => 'Hands-on practical training in Sabre, Galileo GDS and airline ticketing systems.',
                'order_index' => 1,
                'is_active' => true,
                'status' => 'active',
            ]
        );

        $catVisa = CourseCategory::updateOrCreate(
            ['slug' => 'visa-processing-tourism'],
            [
                'name_bn' => 'ভিসা প্রসেসিং ও ট্যুরিজম',
                'name_en' => 'Visa Processing & Tourism',
                'track_title_bn' => 'গ্লোবাল ভিসা প্রসেসিং',
                'track_title_en' => 'Global Visa Processing',
                'badge_text_bn' => 'আসন্ন',
                'badge_text_en' => 'Upcoming',
                'icon' => 'Passport',
                'description_bn' => 'ইউরোপ, আমেরিকা, কানাডা, যুক্তরাজ্য, এশিয়া ও শেঞ্জেন ভিসা প্রসেসিং ও ডকুমেন্টেশন।',
                'description_en' => 'Global tourist visa processing, Schengen, USA, UK, Canada, Australia and Hajj/Umrah.',
                'order_index' => 2,
                'is_active' => true,
                'status' => 'upcoming',
            ]
        );

        $catDesign = CourseCategory::updateOrCreate(
            ['slug' => 'graphic-design'],
            [
                'name_bn' => 'গ্রাফিক ডিজাইন (আসন্ন)',
                'name_en' => 'Graphic Design (Upcoming)',
                'track_title_bn' => 'ক্রিয়েটিভ ডিজাইন',
                'track_title_en' => 'Creative Design',
                'badge_text_bn' => 'আসন্ন',
                'badge_text_en' => 'Upcoming',
                'icon' => 'Palette',
                'description_bn' => 'প্রফেশনাল ক্রিয়েটিভ ডিজাইন ও ব্র্যান্ডিং (শীঘ্রই আসছে)।',
                'description_en' => 'Professional creative design, branding and visual communication (Coming Soon).',
                'order_index' => 3,
                'is_active' => true,
                'status' => 'upcoming',
            ]
        );

        $catMarketing = CourseCategory::updateOrCreate(
            ['slug' => 'digital-marketing'],
            [
                'name_bn' => 'ডিজিটাল মার্কেটিং (আসন্ন)',
                'name_en' => 'Digital Marketing (Upcoming)',
                'track_title_bn' => 'ডিজিটাল গ্রোথ',
                'track_title_en' => 'Digital Growth',
                'badge_text_bn' => 'আসন্ন',
                'badge_text_en' => 'Upcoming',
                'icon' => 'TrendingUp',
                'description_bn' => 'সোশ্যাল মিডিয়া ও পারফরম্যান্স মার্কেটিং (শীঘ্রই আসছে)।',
                'description_en' => 'Social media, agency growth, and performance marketing (Coming Soon).',
                'order_index' => 4,
                'is_active' => true,
                'status' => 'upcoming',
            ]
        );

        // 2. Primary Flagship Course: Air Ticketing & Visa Processing Professional Course
        $course1 = Course::firstOrCreate(
            ['slug' => 'air-ticketing-and-visa-processing-professional-course'],
            [
                'category_id' => $catAviation->id,
                'instructor_id' => $instructor?->id,
                'title_bn' => 'এয়ার টিকেটিং ও ভিসা প্রসেসিং প্রফেশনাল কোর্স',
                'title_en' => 'Air Ticketing & Visa Processing Professional Course',
                'subtitle_bn' => 'সম্পূর্ণ Practical, Job-Oriented & Industry-Focused Training — এভিয়েশন ও ট্রাভেল এজেন্সিতে সফল ক্যারিয়ার গড়ার পূর্ণাঙ্গ কোর্স',
                'subtitle_en' => 'Complete Practical, Job-Oriented & Industry-Focused Aviation, GDS & Global Visa Training',
                'description_bn' => 'ইমিশা একাডেমি নিয়ে এসেছে এভিয়েশন ও ট্রাভেল ইন্ডাস্ট্রির সবচেয়ে বাস্তবমুখী ও প্র্যাকটিক্যাল কোর্স। এই কোর্সে প্রতিটি শিক্ষার্থীর জন্য নিজস্ব কম্পিউটার ল্যাবে Sabre ও Galileo GDS সফটওয়্যারে লাইভ টিকেটিং, বুকিং, ফেয়ার ক্যালকুলেশন, এশিয়া, শেঞ্জেন, ইউএসএ, ইউকে, কানাডা, অস্ট্রেলিয়া ভিসা প্রসেসিং, হোটেল বুকিং এবং হজ্ব ও ওমরাহ প্রসেসিং হাতে-কলমে শেখানো হয়।',
                'description_en' => 'Emisha Academy presents the most comprehensive Air Ticketing & Visa Processing Course with individual computer workstation lab, live GDS (Sabre, Galileo) software training, worldwide tourist visa documentation (Asia, Schengen, USA, Canada, UK, Australia), hotel reservations, and Hajj & Umrah processing.',
                'thumbnail' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800',
                'promo_video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'level' => 'all_levels',
                'format' => 'hybrid',
                'regular_price' => 30000.00,
                'sale_price' => 16500.00,
                'is_free' => false,
                'duration_weeks' => '8',
                'total_hours' => 32,
                'total_classes' => 16,
                'total_projects' => 8,
                'features_bn' => [
                    'Computer Lab ভিত্তিক Practical Class (প্রত্যেক শিক্ষার্থীর জন্য আলাদা Computer)',
                    'Sabre ও Galileo GDS Live Software Practice',
                    'Tourist Visa Processing (Asia, Schengen, USA, Canada, UK, Australia)',
                    'Hotel Booking & Reservation Management',
                    'Hajj & Umrah Processing & Package Design',
                    'Unlimited Practice Facility & Backup Class সুবিধা',
                    'অভিজ্ঞ Mentor-এর সরাসরি তত্ত্বাবধান ও বাস্তব কাজ শেখার সুযোগ',
                    'কোর্স সমাপ্তির ভেরিফায়েড প্রফেশনাল সার্টিফিকেট',
                ],
                'features_en' => [
                    'Computer Lab based Practical Classes (Individual PC for each student)',
                    'Sabre & Galileo GDS Live Software Practice',
                    'Global Tourist Visa Processing (Asia, Schengen, USA, Canada, UK, Australia)',
                    'Hotel Booking & Reservation Management',
                    'Hajj & Umrah Processing & Package Operations',
                    'Unlimited Practice Facility & Backup Class Guarantee',
                    'Direct Supervision by Industry Mentors with Real-World Agency Experience',
                    'Verified Course Certificate & Job Placement Assistance',
                ],
                'prerequisites_bn' => ['কম্পিউটার ও বেসিক ইন্টারনেট চালানোর ধারণা', 'ট্রাভেল ও এভিয়েশন সেক্টরে ক্যারিয়ার গড়ার আগ্রহ'],
                'prerequisites_en' => ['Basic computer and internet browsing skills', 'Interest in aviation, tourism, and travel agency career'],
                'target_audience_bn' => [
                    'ট্রাভেল এজেন্সি ও এয়ারলাইন্সে চাকরিপ্রত্যাশী',
                    'ভিসা কনসালটেন্সি ও ট্যুরিজম উদ্যোক্তা',
                    'নিজস্ব ট্রাভেল এজেন্সি শুরু করতে ইচ্ছুক যে কেউ',
                    'বিদেশগমনেচ্ছু এবং ফ্রিল্যান্স ভিসা প্রসেসিং কর্মী',
                ],
                'target_audience_en' => [
                    'Aspiring Travel Agents & Airline Professionals',
                    'Visa Consultants & Tourism Entrepreneurs',
                    'Anyone planning to launch their own Travel Agency',
                    'Freelance Travel Specialists & Visa Expeditors',
                ],
                'average_rating' => 4.96,
                'total_reviews' => 64,
                'enrolled_count' => 180,
                'is_featured' => true,
                'is_popular' => true,
                'status' => 'published',
                'published_at' => now(),
            ]
        );

        // Attach Multiple Instructors/Mentors to the flagship course
        $allInstructors = Instructor::orderBy('id')->get();
        if ($allInstructors->isNotEmpty()) {
            $syncInstructors = [];
            foreach ($allInstructors as $idx => $inst) {
                $roleBn = match($idx) {
                    0 => 'লিড এভিয়েশন ও জিডিএস ট্রেইনার (Lead Trainer)',
                    1 => 'সিনিয়র ভিসা ও ডকুমেন্টেশন কনসালটেন্ট (Senior Visa Consultant)',
                    2 => 'হজ্ব, ওমরাহ ও হোটেল রিজার্ভেশন স্পেশালিস্ট (Hajj/Umrah Specialist)',
                    default => 'কোর্স মেন্টর (Mentor)'
                };
                $roleEn = match($idx) {
                    0 => 'Lead Aviation & GDS Trainer',
                    1 => 'Senior Visa & Documentation Consultant',
                    2 => 'Hajj, Umrah & Hospitality Specialist',
                    default => 'Course Mentor'
                };
                $syncInstructors[$inst->id] = [
                    'role_bn' => $roleBn,
                    'role_en' => $roleEn,
                    'order_index' => $idx,
                ];
            }
            $course1->instructors()->sync($syncInstructors);
        }

        // Course Batches (Offline Batches & Online Batch)
        // 1. Offline Evening Batch
        Batch::firstOrCreate(
            ['course_id' => $course1->id, 'batch_number' => 'Offline-Batch-01'],
            [
                'title_bn' => 'অফলাইন উইকেন্ড ইভনিং ব্যাচ (বিকাল ৪:০০ – ৬:০০)',
                'title_en' => 'Offline Weekend Evening Batch (4:00 PM – 6:00 PM)',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(67),
                'enrollment_deadline' => now()->addDays(5),
                'class_days' => 'শুক্রবার ও শনিবার',
                'class_time' => 'বিকাল ৪:০০ – ৬:০০',
                'seat_capacity' => 15,
                'enrolled_students' => 11,
                'status' => 'enrolling',
            ]
        );

        // 2. Offline Night Batch
        Batch::firstOrCreate(
            ['course_id' => $course1->id, 'batch_number' => 'Offline-Batch-02'],
            [
                'title_bn' => 'অফলাইন উইকেন্ড নাইট ব্যাচ (সন্ধ্যা ৬:০০ – রাত ৮:০০)',
                'title_en' => 'Offline Weekend Night Batch (6:00 PM – 8:00 PM)',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(67),
                'enrollment_deadline' => now()->addDays(5),
                'class_days' => 'শুক্রবার ও শনিবার',
                'class_time' => 'সন্ধ্যা ৬:০০ – রাত ৮:০০',
                'seat_capacity' => 15,
                'enrolled_students' => 9,
                'status' => 'enrolling',
            ]
        );

        // 3. Offline Morning Batch
        Batch::firstOrCreate(
            ['course_id' => $course1->id, 'batch_number' => 'Offline-Batch-03'],
            [
                'title_bn' => 'অফলাইন উইকেন্ড মর্নিং ব্যাচ (সকাল ১০:০০ – ১২:০০)',
                'title_en' => 'Offline Weekend Morning Batch (10:00 AM – 12:00 PM)',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(67),
                'enrollment_deadline' => now()->addDays(5),
                'class_days' => 'শুক্রবার ও শনিবার',
                'class_time' => 'সকাল ১০:০০ – ১২:০০',
                'seat_capacity' => 15,
                'enrolled_students' => 8,
                'status' => 'enrolling',
            ]
        );

        // 4. Online Live Batch
        Batch::firstOrCreate(
            ['course_id' => $course1->id, 'batch_number' => 'Online-Batch-01'],
            [
                'title_bn' => 'অনলাইন লাইভ ব্যাচ (বুধবার ও বৃহস্পতিবার রাত ৮:০০ – ১০:০০)',
                'title_en' => 'Online Live Batch (Wed & Thu 8:00 PM – 10:00 PM)',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(70),
                'enrollment_deadline' => now()->addDays(8),
                'class_days' => 'বুধবার ও বৃহস্পতিবার',
                'class_time' => 'রাত ৮:০০ – ১০:০০',
                'seat_capacity' => 30,
                'enrolled_students' => 18,
                'status' => 'enrolling',
            ]
        );

        // Course Modules & Lessons (Detailed Authentic Syllabus)
        // Module 1: Air Ticketing & Sabre GDS
        $module1 = CourseModule::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 1],
            [
                'title_bn' => 'মডিউল ০১: এয়ার টিকেটিং বেসিক ও Sabre GDS লাইভ প্র্যাকটিস',
                'title_en' => 'Module 01: Air Ticketing Fundamentals & Sabre GDS Live Practice',
                'summary_bn' => 'ইন্টারন্যাশনাল এয়ারলাইন্স কোডস, এয়ারপোর্ট কোডস, Sabre GDS সাইন-ইন ও PNR ক্রিয়েশন।',
                'summary_en' => 'IATA codes, Sabre system login, flight availability, and PNR creation.',
            ]
        );

        CourseLesson::firstOrCreate(
            ['module_id' => $module1->id, 'order_index' => 1],
            [
                'title_bn' => '০১. এভিয়েশন ইন্ডাস্ট্রি পরিচিতি ও বেসিক টার্মিনোলজি',
                'title_en' => '01. Aviation Industry Overview & Basic Terminology',
                'duration' => '30 মিনিট',
                'video_provider' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'IATA ও Non-IATA এয়ারলাইন্স, সিটি ও এয়ারপোর্ট কোডস, রাউটিং ও ফ্লাইটের ধরন পরিচিতি।',
                'is_free_preview' => true,
            ]
        );

        CourseLesson::firstOrCreate(
            ['module_id' => $module1->id, 'order_index' => 2],
            [
                'title_bn' => '০২. Sabre GDS সিস্টেমে ফ্লাইট সার্চ, বুকিং ও PNR তৈরি',
                'title_en' => '02. Flight Search, Booking & PNR Creation in Sabre GDS',
                'duration' => '45 মিনিট',
                'video_provider' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'হাতে-কলমে Sabre GDS এভেইলেবিলিটি চেক, প্যাসেঞ্জার ডাটা এন্ট্রি ও টিকিট ইস্যুর পূর্বপ্রস্তুতি।',
                'is_free_preview' => true,
            ]
        );

        // Module 2: Galileo GDS & Fare Calculation
        $module2 = CourseModule::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 2],
            [
                'title_bn' => 'মডিউল ০২: Galileo (Travelport) GDS লাইভ প্র্যাকটিস ও টিকিট ইস্যু',
                'title_en' => 'Module 02: Galileo (Travelport) GDS Live Practice & Ticket Issuance',
                'summary_bn' => 'গ্যালিলিও সিস্টেমে ফেয়ার রুলস, রি-ইস্যু, রিফান্ড ও ই-টিকিট জেনারেশন।',
                'summary_en' => 'Galileo commands, fare quote, re-issuance, refund policy and e-ticket generation.',
            ]
        );

        CourseLesson::firstOrCreate(
            ['module_id' => $module2->id, 'order_index' => 1],
            [
                'title_bn' => '০১. Galileo GDS সিস্টেমের গুরুত্বপূর্ণ কমান্ড ও ফ্লাইট বুকিং',
                'title_en' => '01. Essential Galileo GDS Commands & Flight Booking',
                'duration' => '40 মিনিট',
                'video_provider' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'গ্যালিলিও সফটওয়্যারের কমান্ড স্ট্রাকচার, সিট সিলেকশন ও স্পেশাল সার্ভিস রিকোয়েস্ট (SSR)।',
                'is_free_preview' => false,
            ]
        );

        // Module 3: Global Tourist Visa Processing
        $module3 = CourseModule::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 3],
            [
                'title_bn' => 'মডিউল ০৩: গ্লোবাল ট্যুরিস্ট ভিসা প্রসেসিং (এশিয়া, ইউরোপ, আমেরিকা ও অন্যান্য)',
                'title_en' => 'Module 03: Global Tourist Visa Processing (Asia, Schengen, USA, UK, Canada, Australia)',
                'summary_bn' => 'দেশভিত্তিক ভিসা রিকোয়ারমেন্টস, অনলাইন ফর্ম পূরণ, অ্যাপয়েন্টমেন্ট শিডিউলিং ও ফাইল রেডি করা।',
                'summary_en' => 'Country-wise visa requirements, online applications, appointment scheduling and document verification.',
            ]
        );

        CourseLesson::firstOrCreate(
            ['module_id' => $module3->id, 'order_index' => 1],
            [
                'title_bn' => '০১. এশিয়ান দেশসমূহের ই-ভিসা ও স্টিকার ভিসা প্রসেসিং (থাইল্যান্ড, মালয়েশিয়া, সিঙ্গাপুর, ভারত)',
                'title_en' => '01. Asian Countries E-Visa & Sticker Visa Processing (Thailand, Malaysia, Singapore, India)',
                'duration' => '50 মিনিট',
                'video_provider' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'ব্যাংক স্টেটমেন্ট, সলভেন্সি, নো অবজেকশন সার্টিফিকেট (NOC) ও অনলাইন ভিসা আবেদন পদ্ধতি।',
                'is_free_preview' => false,
            ]
        );

        CourseLesson::firstOrCreate(
            ['module_id' => $module3->id, 'order_index' => 2],
            [
                'title_bn' => '০২. শেঞ্জেন (ইউরোপ), ইউএসএ, কানাডা ও ইউকে ভিজিট ভিসা ফাইল প্রিপারেশন',
                'title_en' => '02. Schengen (Europe), USA DS-160, Canada & UK Visitor Visa Preparation',
                'duration' => '60 মিনিট',
                'video_provider' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'DS-160 ফর্ম ফিলাপ, শেঞ্জেন কভার লেটার, ট্রাভেল আইটিনেরারি ও ভিএফএস গ্লোবাল অ্যাপয়েন্টমেন্ট প্রসেস।',
                'is_free_preview' => false,
            ]
        );

        // Module 4: Hotel Booking, Hajj & Umrah Processing
        $module4 = CourseModule::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 4],
            [
                'title_bn' => 'মডিউল ০৪: হোটেল বুকিং, হজ্ব ও ওমরাহ প্রসেসিং এবং ট্রাভেল এজেন্সি বিজনেস',
                'title_en' => 'Module 04: Hotel Booking, Hajj & Umrah Processing & Agency Operations',
                'summary_bn' => 'আন্তর্জাতিক হোটেল রিজার্ভেশন, নুসুক (Nusuk) পোর্টাল, ওমরাহ ভিসা ও প্যাকেজ ডিজাইন।',
                'summary_en' => 'Global hotel portals, Nusuk platform, Umrah visa processing and full agency business guide.',
            ]
        );

        CourseLesson::firstOrCreate(
            ['module_id' => $module4->id, 'order_index' => 1],
            [
                'title_bn' => '০১. হজ্ব ও ওমরাহ ভিসা প্রসেসিং এবং হোটেল ও ট্রান্সপোর্টেশন ম্যানেজমেন্ট',
                'title_en' => '01. Hajj & Umrah Visa Processing & Package Management',
                'duration' => '45 মিনিট',
                'video_provider' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content' => 'সৌদি ওমরাহ ভিসা নিয়মাবলী, নুসুক প্ল্যাটফর্ম পরিচালনা ও সফল ট্রাভেল এজেন্সি পরিচালনার গাইডলাইন।',
                'is_free_preview' => false,
            ]
        );

        // Course FAQs
        CourseFaq::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 1],
            [
                'question_bn' => 'আমি কি ল্যাবে প্র্যাকটিস করার জন্য আলাদা কম্পিউটার পাব?',
                'question_en' => 'Will I get an individual computer in the lab for practice?',
                'answer_bn' => 'হ্যাঁ! ইমিশা একাডেমির শীতাতপ নিয়ন্ত্রিত কম্পিউটার ল্যাবে প্রতিটি শিক্ষার্থীর জন্য নির্ধারিত পৃথক কম্পিউটার এবং আনলিমিটেড প্র্যাকটিস সুবিধা রয়েছে।',
                'answer_en' => 'Yes! Emisha Academy provides an individual modern computer workstation for every student in our fully equipped computer lab with unlimited practice hours.',
            ]
        );

        CourseFaq::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 2],
            [
                'question_bn' => 'অফলাইন ও অনলাইন কোর্সের পার্থক্য কী এবং ফি কত?',
                'question_en' => 'What is the difference between offline and online batches, and what are the fees?',
                'answer_bn' => 'অফলাইন কোর্সে সরাসরি আমাদের মিরপুর কাজীপাড়া ল্যাবে কম্পিউটার ভিত্তিক প্র্যাকটিক্যাল ক্লাস হবে (বিশেষ অফার ফি মাত্র ১৬,৫০০ টাকা, নিয়মিত ৩০,০০০ টাকা)। অনলাইন ব্যাচে গুগল মিট/জুমে লাইভ ক্লাস হবে (ফি ১০,০০০ টাকা)।',
                'answer_en' => 'Offline batches take place at our Mirpur Kazipara computer lab with hands-on live GDS practice (Special Offer: BDT 16,500, Regular BDT 30,000). Online batches take place via live interactive video (Fee: BDT 10,000).',
            ]
        );

        CourseFaq::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 3],
            [
                'question_bn' => 'কোর্স শেষে কি চাকরির সুযোগ বা সার্টিফিকেট পাওয়া যাবে?',
                'question_en' => 'Will I receive a verified certificate and job support after course completion?',
                'answer_bn' => 'হ্যাঁ, কোর্স সফলভাবে সম্পন্ন করার পর অফিসিয়াল ভেরিফায়েড সার্টিফিকেট প্রদান করা হবে এবং বিভিন্ন স্বনামধন্য ট্রাভেল এজেন্সি ও এয়ারলাইন্সে ইন্টার্নশিপ ও জব প্লেসমেন্টে সার্বিক সহযোগিতা দেওয়া হবে।',
                'answer_en' => 'Yes, upon successful completion you will receive a verified professional certificate, alongside portfolio reviews and placement recommendations across reputed travel agencies and airlines.',
            ]
        );

        // Course Review
        if ($studentUser) {
            CourseReview::firstOrCreate(
                ['course_id' => $course1->id, 'user_id' => $studentUser->id],
                [
                    'rating' => 5,
                    'comment' => 'ইমিশা একাডেমি থেকে এয়ার টিকেটিং ও ভিসা প্রসেসিং কোর্সটি করে আমি এখন একটি স্বনামধন্য ট্রাভেল এজেন্সিতে কর্মরত। প্রত্যেক শিক্ষার্থীর জন্য আলাদা কম্পিউটার ও Sabre/Galileo লাইভ প্র্যাকটিস ছিল অসাধারণ!',
                    'is_approved' => true,
                ]
            );
        }

        // 3. Webinars & Masterclasses
        $webinar1 = Webinar::updateOrCreate(
            ['slug' => 'aviation-and-travel-agency-career-guideline-2026'],
            [
                'title_bn' => 'এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার ও ভিসা কনসালটেন্সি কর্মশালা',
                'title_en' => 'Aviation, Travel Agency Career & Visa Consultancy Workshop',
                'subtitle_bn' => 'কীভাবে ট্রাভেল ও এয়ার টিকেটিং সেক্টরে দ্রুত প্রফেশনাল ক্যারিয়ার গড়বেন তার সম্পূর্ণ রোডম্যাপ।',
                'subtitle_en' => 'A complete practical roadmap to starting and scaling a career in air ticketing and visa operations.',
                'description_bn' => 'এই লাইভ মাস্টারক্লাসে আন্তর্জাতিক এয়ারলাইন্স টিকেটিংয়ে Sabre এবং Galileo সিস্টেমের ব্যবহারিক গুরুত্ব, এজেন্সি ব্যবসা শুরুর আইনি ধাপসমূহ এবং এম্বাসি স্ট্যান্ডার্ড ভিসা ফাইল তৈরির প্র্যাকটিক্যাল কলাকৌশল সরাসরি তুলে ধরা হবে।',
                'description_en' => 'This live session explores how to operate Sabre and Galileo GDS terminals, navigate airline fare rules, and prepare compliant embassy visa dossiers.',
                'thumbnail' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200',
                'banner_image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1600',
                'event_datetime' => '2026-09-29 19:00:00',
                'duration_minutes' => 90,
                'platform' => 'Zoom Live & Mirpur Lab',
                'meeting_link' => 'https://zoom.us/j/emisha-academy-live',
                'recording_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'registration_fee' => 0.00,
                'is_free' => true,
                'max_participants' => 100,
                'registered_count' => 78,
                'status' => 'upcoming',
                'is_featured' => true,
                'highlights_bn' => [
                    'Sabre ও Galileo GDS সফটওয়্যারে লাইভ স্ক্রিন অপারেশন।',
                    'আন্তর্জাতিক এয়ারলাইন্স কোডস ও ফেয়ার ক্যালকুলেশন রুলস।',
                    'সরাসরি লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার ফাইল প্রসেসিং।',
                    'শেঞ্জেন ও ইউএসএ ভিসা ফাইল প্রস্তুতকরণের সঠিক নির্দেশিকা।',
                    'এয়ার টিকেটিং শিখে ট্রাভেল এজেন্সিতে জব ও ক্যারিয়ার অপরচুনিটি।',
                    'সরাসরি স্পিকারদের সাথে উন্মুক্ত লাইভ প্রশ্নোত্তর পর্ব (Q&A)।',
                ],
                'highlights_en' => [
                    'Live software screen demonstration of Sabre & Galileo GDS.',
                    'International airline codes, routing, and fare construction rules.',
                    'Hands-on live PNR creation and passenger profile workflows.',
                    'Step-by-step compliant tourist visa dossier preparation.',
                    'Career guidance on landing agency jobs and scaling agency profits.',
                    'Open interactive live Q&A session with senior mentors.',
                ],
                'agenda_bn' => [
                    [
                        'part' => 1,
                        'title' => 'অংশ ১: এভিয়েশন ও ট্রাভেল এজেন্সির বর্তমান গ্লোবাল মার্কেট',
                        'desc' => 'চাহিদাসম্পন্ন স্কিলস ও এয়ারলাইন্স টিকেটিং ক্যারিয়ারের সুযোগসমূহ।',
                        'time' => '১৫ মিনিট',
                    ],
                    [
                        'part' => 2,
                        'title' => 'অংশ ২: Sabre ও Galileo সিস্টেমে লাইভ সফটওয়্যার ডেমো',
                        'desc' => 'কমান্ড লাইন, ফ্লাইট সার্চ, সিট বুকিং ও ফেয়ার কোটেশন প্র্যাকটিস।',
                        'time' => '৪৫ মিনিট',
                    ],
                    [
                        'part' => 3,
                        'title' => 'অংশ ৩: এম্বাসি ভিসা ফাইলিং ও রিজেকশন এড়ানোর উপায়',
                        'desc' => 'কভার লেটার, ট্রাভেল আইটিনারি ও ব্যাংক স্টেটমেন্ট প্রস্তুতকরণ।',
                        'time' => '১৫ মিনিট',
                    ],
                    [
                        'part' => 4,
                        'title' => 'অংশ ৪: উন্মুক্ত প্রশ্নোত্তর পর্ব ও সার্টিফিকেট বিতরণ গাইড',
                        'desc' => 'শিক্ষার্থীদের সরাসরি প্রশ্নের উত্তর ও প্র্যাকটিস শিট অ্যাক্সেস।',
                        'time' => '১৫ মিনিট',
                    ],
                ],
                'agenda_en' => [
                    [
                        'part' => 1,
                        'title' => 'Part 1: Global Aviation & Travel Industry Outlook',
                        'desc' => 'In-demand competencies and high-growth agency roles.',
                        'time' => '15 Mins',
                    ],
                    [
                        'part' => 2,
                        'title' => 'Part 2: Live Sabre & Galileo Software Screen Demo',
                        'desc' => 'Terminal commands, flight availability, and live PNR creation.',
                        'time' => '45 Mins',
                    ],
                    [
                        'part' => 3,
                        'title' => 'Part 3: Embassy Visa Dossier Audit & Best Practices',
                        'desc' => 'Cover letter templates, travel plans, and financial compliance.',
                        'time' => '15 Mins',
                    ],
                    [
                        'part' => 4,
                        'title' => 'Part 4: Interactive Live Q&A & Certificate Distribution',
                        'desc' => 'Direct participant questions and downloadable practice materials.',
                        'time' => '15 Mins',
                    ],
                ],
                'certificate_title_bn' => 'ডিজিটাল ভেরিফাইড সার্টিফিকেট নিশ্চয়তা',
                'certificate_title_en' => 'Verified Digital Participation Certificate',
                'certificate_note_bn' => 'সম্পূর্ণ সেশনে উপস্থিত থাকলে আপনি একটি ডিজিটাল সার্টিফিকেট ও প্র্যাকটিস শিট সম্পূর্ণ ফ্রিতে পাবেন।',
                'certificate_note_en' => 'Attending the full session entitles you to a verifiable certificate and digital cheat sheet.',
                'lab_upsell_title_bn' => 'মিরপুর ক্যাম্পাসে আলাদা কম্পিউটার ল্যাবে পূর্ণাঙ্গ কোর্স শিখুন',
                'lab_upsell_title_en' => 'Learn on Dedicated PC Workstations at Mirpur Campus',
                'lab_upsell_desc_bn' => '১ জন শিক্ষার্থী = ১টি কম্পিউটার ল্যাব এবং লাইভ Sabre ও Galileo সফটওয়্যার অ্যাক্সেস।',
                'lab_upsell_desc_en' => '1 Student = 1 Workstation with live airline ticketing software access.',
            ]
        );

        WebinarSpeaker::updateOrCreate(
            ['webinar_id' => $webinar1->id, 'name_en' => 'Tanvir Rahman'],
            [
                'name_bn' => 'তানভীর রহমান',
                'name_en' => 'Tanvir Rahman',
                'designation_bn' => 'লিড এভিয়েশন ট্রেইনার ও Sabre/Galileo স্পেশালিস্ট',
                'designation_en' => 'Lead Aviation Trainer & Sabre/Galileo Specialist',
                'organization' => 'Emisha Tours & Travels',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150',
                'bio_bn' => 'বিগত ১০+ বছর ধরে দেশী-বিদেশী এয়ারলাইন্স টিকেটিং ও ট্রাভেল এজেন্সিতে কর্মরত। ৩,২০০+ সফল শিক্ষার্থীকে প্রশিক্ষণ দিয়েছেন।',
                'bio_en' => '10+ years in commercial aviation ticketing and GDS training. Mentored over 3,200 successful alumni.',
                'order_index' => 1,
            ]
        );

        // Additional demo registrations
        WebinarRegistration::firstOrCreate(
            ['webinar_id' => $webinar1->id, 'email' => 'student.demo@emisha.academy'],
            [
                'name' => 'মোঃ আরিফুল ইসলাম',
                'phone' => '01896459001',
                'ticket_number' => 'WEB-AR7890',
                'has_attended' => false,
                'status' => 'confirmed',
            ]
        );

        // 4. Ebooks / Study Guides
        $ebookCat1 = EbookCategory::updateOrCreate(
            ['slug' => 'aviation-travel-guide'],
            ['name_bn' => 'এভিয়েশন ও GDS গাইডবুক', 'name_en' => 'Aviation & GDS Guides']
        );
        $ebookCat2 = EbookCategory::updateOrCreate(
            ['slug' => 'visa-checklists'],
            ['name_bn' => 'ভিসা চেকলিস্ট ও গাইডলাইন', 'name_en' => 'Visa Checklists']
        );
        $ebookCat3 = EbookCategory::updateOrCreate(
            ['slug' => 'umrah-tourism-manuals'],
            ['name_bn' => 'ওমরাহ ও ট্যুরিজম ম্যানুয়াল', 'name_en' => 'Umrah & Tourism Manuals']
        );
        $ebookCat4 = EbookCategory::updateOrCreate(
            ['slug' => 'agency-business-guides'],
            ['name_bn' => 'এজেন্সি বিজনেস গাইড', 'name_en' => 'Agency Business Guides']
        );

        Ebook::updateOrCreate(
            ['slug' => 'practical-air-ticketing-and-gds-handbook'],
            [
                'category_id' => $ebookCat1->id,
                'title_bn' => 'এয়ার টিকেটিং ও GDS (Sabre & Galileo) প্র্যাকটিক্যাল হ্যান্ডবুক',
                'title_en' => 'Practical Air Ticketing & GDS (Sabre & Galileo) Handbook',
                'author_name_bn' => 'ইমিশা একাডেমি রিসার্চ টিম',
                'author_name_en' => 'Emisha Academy Research Team',
                'author_designation_bn' => 'এভিয়েশন ফ্যাকাল্টি ও ট্রাভেল অপারেশনস টিম',
                'author_designation_en' => 'Aviation Faculty & Travel Operations Team',
                'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200',
                'edition_badge_bn' => 'অফিশিয়াল পিডিএফ ই-বুক সংস্করণ (সর্বশেষ সংস্করণ)',
                'edition_badge_en' => 'Official High-Resolution PDF Handbook (Latest Revision)',
                'summary_bn' => 'আন্তর্জাতিক এয়ারলাইন্স কোডস, কমান্ড রেফারেন্স এবং PNR ক্রিয়েশনের পূর্ণাঙ্গ শর্টকাট গাইডবুক।',
                'summary_en' => 'Comprehensive reference guide for IATA airline codes, Sabre/Galileo commands, and fare calculation rules.',
                'description_bn' => 'এই হ্যান্ডবুকটিতে Sabre ও Galileo সিস্টেমের প্রতিটি প্রয়োজনীয় কমান্ড, PNR ক্রিয়েশন শর্টকাট, ফেয়ার ক্যালকুলেশন এবং বিশ্বের প্রধান দেশসমূহের ভিসা ডকুমেন্টেশন চেকলিস্ট বিশদভাবে সাজানো হয়েছে।',
                'description_en' => 'Designed specifically for beginner trainees and agency officers to master Sabre & Galileo command syntax, live fare quotation, date re-issue penalties, and cancellation compliance.',
                'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600',
                'file_path' => 'ebooks/air-ticketing-handbook.pdf',
                'pages_count' => 95,
                'file_size' => '6.8 MB',
                'regular_price' => 500.00,
                'sale_price' => 250.00,
                'is_free' => false,
                'download_count' => 421,
                'rating' => 4.95,
                'reviews_count' => 84,
                'is_featured' => true,
                'status' => 'published',
                'highlights_bn' => [
                    'Sabre এবং Galileo সিস্টেমের ১০০+ প্রয়োজনীয় কমান্ড শর্টকাট।',
                    'আন্তর্জাতিক এয়ারলাইন্স ফেয়ার ক্যালকুলেশন ও ট্যাক্স কোটেশন রুলস।',
                    'সরাসরি লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার হিস্ট্রি ট্র্যাকিং।',
                    'টিকিট রি-ইস্যু (Re-issue), ডেট চেঞ্জ এবং রিফান্ড পলিসি কমপ্লায়েন্স।',
                    'ট্যুরিস্ট ও বিজনেস ভিসা ফাইল প্রস্তুতকরণের পূর্ণাঙ্গ চেকলিস্ট।',
                    'ক্লায়েন্ট হ্যান্ডলিং ও এজেন্সির প্রফিট মার্জিন বাড়ানোর কার্যকরী কৌশল।',
                ],
                'highlights_en' => [
                    '100+ Essential Sabre & Galileo command shortcuts and cheat codes.',
                    'International airline fare construction and tax calculation rules.',
                    'Hands-on live PNR creation and passenger profile tracking.',
                    'Ticket re-issue, date change penalty calculations and refund policies.',
                    'Complete checklist for tourist and business visa file preparation.',
                    'Effective client counseling and agency profit margin optimization strategies.',
                ],
                'chapters_bn' => [
                    [
                        'chapter_num' => 1,
                        'title' => 'অধ্যায় ০১: আন্তর্জাতিক এভিয়েশন কাঠামো ও এয়ারলাইন্স কোডস',
                        'summary' => 'IATA ও ICAO পরিচিতি, বিশ্বের প্রধান এয়ারলাইন্স ও সিটি কোডস এবং ট্রাভেল জিওগ্রাফি কনসেপ্ট।',
                        'subtopics' => [
                            'IATA এরিয়া ১, ২, ৩ এবং টাইম জোন গণনা',
                            '২-লেটার ও ৩-লেটার এয়ারলাইন্স ও এয়ারপোর্ট কোডস',
                            'ফ্লাইট টাইপস: নন-স্টপ, ডাইরেক্ট ও কানেক্টিং ফ্লাইট',
                        ],
                    ],
                    [
                        'chapter_num' => 2,
                        'title' => 'অধ্যায় ০২: Sabre ও Galileo GDS সিস্টেম এনভায়রনমেন্ট',
                        'summary' => 'GDS সিস্টেমে সাইন-ইন, ওয়ার্ক এরিয়া এবং টার্মিনাল কমান্ডের বেসিক স্ট্রাকচার।',
                        'subtopics' => [
                            'Agent Sign-In, Sign-Out ও Area Switch',
                            'Encode & Decode Cities, Airlines ও Equipment',
                            'Flight Availability Display ও সিট স্ট্যাটাস কোড',
                        ],
                    ],
                    [
                        'chapter_num' => 3,
                        'title' => 'অধ্যায় ০৩: লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার ম্যানেজমেন্ট',
                        'summary' => 'বাস্তবসম্মত PNR এর ৫টি বাধ্যতামূলক উপাদান এবং সার্ভিস রিকোয়েস্ট যোগ করার নিয়ম।',
                        'subtopics' => [
                            'PRINT এলিমেন্টস (Phone, Received, Itinerary, Name, Ticketing)',
                            'Special Service Request (SSR) ও OSI মেসেজ',
                            'Split PNR ও প্যাসেঞ্জার হিস্ট্রি চেক',
                        ],
                    ],
                    [
                        'chapter_num' => 4,
                        'title' => 'অধ্যায় ০৪: ফেয়ার কোটেশন, টিকিট ইস্যু ও রি-ইস্যু প্রসেসিং',
                        'summary' => 'স্বয়ংক্রিয় ফেয়ার ডিসপ্লে, ফেয়ার রুলস এবং ডেট চেঞ্জ পেনাল্টি ক্যালকুলেশন।',
                        'subtopics' => [
                            'Lowest Fare Search (WPNCB / FQ)',
                            'Electronic Ticket Issuance ও ইনভয়েস জেনারেশন',
                            'Re-issue, Date Change ও Penalty Calculation',
                        ],
                    ],
                    [
                        'chapter_num' => 5,
                        'title' => 'অধ্যায় ০৫: এম্বাসি ভিসা ফাইলিং ও এজেন্সি বিজনেস গাইডলাইন',
                        'summary' => 'ভিসা ফাইল প্রসেসিং এবং ট্রাভেল এজেন্সির বিটুবি টিকেটিং বিজনেস সেটআপ।',
                        'subtopics' => [
                            'Schengen ও USA ভিসা কভার লেটার ড্রাফট',
                            'এয়ার টিকেটিং পোর্টাল ও B2B অপারেশনস',
                            'এজেন্সি লাভজনক করার কৌশল ও কাস্টমার সার্ভিস',
                        ],
                    ],
                ],
                'chapters_en' => [
                    [
                        'chapter_num' => 1,
                        'title' => 'Chapter 01: Global Aviation Framework & Airline Codes',
                        'summary' => 'IATA & ICAO overview, major world airport city codes, and global travel geography.',
                        'subtopics' => [
                            'IATA Areas 1, 2, 3 and timezone calculations',
                            '2-letter airline codes & 3-letter airport codes',
                            'Flight routing: Non-stop, Direct & Connecting',
                        ],
                    ],
                    [
                        'chapter_num' => 2,
                        'title' => 'Chapter 02: Sabre & Galileo GDS System Environment',
                        'summary' => 'Sign-in procedures, work area partitioning, and basic terminal command structures.',
                        'subtopics' => [
                            'Agent Sign-In, Sign-Out & Area Switch',
                            'Encode & Decode Cities, Airlines & Equipment',
                            'Flight Availability Display & Seat Status Codes',
                        ],
                    ],
                    [
                        'chapter_num' => 3,
                        'title' => 'Chapter 03: Live PNR Creation & Passenger Management',
                        'summary' => '5 mandatory PNR elements, special service requests, and history tracking.',
                        'subtopics' => [
                            'PRINT Elements (Phone, Received, Itinerary, Name, Ticketing)',
                            'Special Service Request (SSR) & OSI Messages',
                            'Split PNR & Passenger Profile Audit',
                        ],
                    ],
                    [
                        'chapter_num' => 4,
                        'title' => 'Chapter 04: Fare Quotation, Ticketing & Re-issue Processing',
                        'summary' => 'Automated fare pricing, mileage rules, and date-change penalty computations.',
                        'subtopics' => [
                            'Lowest Fare Search (WPNCB / FQ)',
                            'Electronic Ticket Issuance & Billing',
                            'Re-issue, Date Change & Penalty Calculations',
                        ],
                    ],
                    [
                        'chapter_num' => 5,
                        'title' => 'Chapter 05: Embassy Visa Filing & Agency Business Setup',
                        'summary' => 'Visa dossier preparation and establishing a profitable B2B travel agency business.',
                        'subtopics' => [
                            'Schengen & USA Visa Cover Letter Drafting',
                            'B2B Airline Ticketing Portals',
                            'Agency Profit Margins & Customer Retention',
                        ],
                    ],
                ],
                'target_audience_bn' => [
                    [
                        'icon' => 'plane',
                        'title' => 'টিকেটিং এক্সিকিউটিভ',
                        'desc' => 'এয়ারলাইন্স বা ট্রাভেল এজেন্সিতে কর্মরত বা চাকরিপ্রার্থী।',
                    ],
                    [
                        'icon' => 'briefcase',
                        'title' => 'এজেন্সি উদ্যোক্তা',
                        'desc' => 'নতুন ট্রাভেল ব্যবসা বা ভিসা কনসালটেন্সি শুরু করতে চান।',
                    ],
                    [
                        'icon' => 'cap',
                        'title' => 'শিক্ষার্থী ও প্রফেশনাল',
                        'desc' => 'প্র্যাকটিক্যাল স্কিল অর্জন করে ক্যারিয়ার শুরু করতে চান।',
                    ],
                ],
                'target_audience_en' => [
                    [
                        'icon' => 'plane',
                        'title' => 'Ticketing Officers',
                        'desc' => 'Working in travel agencies or aspiring airline ticketing staff.',
                    ],
                    [
                        'icon' => 'briefcase',
                        'title' => 'Agency Entrepreneurs',
                        'desc' => 'Planning to launch a travel agency or visa consultancy.',
                    ],
                    [
                        'icon' => 'cap',
                        'title' => 'Students & Professionals',
                        'desc' => 'Looking for high-demand, skill-based international career.',
                    ],
                ],
                'lab_upsell_title_bn' => 'শুধু বই পড়ে নয়, কম্পিউটারে সরাসরি লাইভ সফটওয়্যার শিখুন!',
                'lab_upsell_title_en' => 'Move Beyond Theory: Learn Live GDS on Dedicated Workstations',
                'lab_upsell_desc_bn' => 'ইমিশা একাডেমির মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য রয়েছে আলাদা কম্পিউটার এবং Sabre ও Galileo সিস্টেমের লাইভ সফটওয়্যার অ্যাক্সেস।',
                'lab_upsell_desc_en' => 'Join our physical classroom batches at Mirpur Kazipara. 1 Student = 1 Workstation with unlimited lab practice guarantee.',
                'lab_upsell_btn_text_bn' => 'প্র্যাকটিক্যাল কোর্সসমূহ দেখুন →',
                'lab_upsell_btn_text_en' => 'Explore Flagship Courses →',
                'lab_upsell_btn_link' => '/courses',
            ]
        );

        // 5. Blog Categories & Posts
        $blogCat1 = BlogCategory::updateOrCreate(
            ['slug' => 'travel-visa-guidelines'],
            ['name_bn' => 'ভিসা ও ট্রাভেল গাইডলাইন', 'name_en' => 'Visa & Travel Guidelines']
        );

        $blogCat2 = BlogCategory::updateOrCreate(
            ['slug' => 'air-ticketing-gds'],
            ['name_bn' => 'এয়ার টিকেটিং ও GDS', 'name_en' => 'Air Ticketing & GDS']
        );

        $blogCat3 = BlogCategory::updateOrCreate(
            ['slug' => 'umrah-tourism'],
            ['name_bn' => 'ওমরাহ ও ট্যুরিজম', 'name_en' => 'Umrah & Tourism']
        );

        BlogPost::updateOrCreate(
            ['slug' => 'how-to-build-career-in-air-ticketing-and-visa-processing'],
            [
                'category_id' => $blogCat1->id,
                'author_id' => $adminUser?->id,
                'author_name_bn' => 'ইমিশা অ্যাডমিন',
                'author_name_en' => 'Emisha Admin',
                'author_designation_bn' => 'এভিয়েশন ট্রেইনার ও ট্রাভেল রিসার্চার',
                'author_designation_en' => 'Aviation Trainer & Travel Researcher',
                'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200',
                'title_bn' => 'এয়ার টিকেটিং ও ভিসা প্রসেসিং শিখে কীভাবে দ্রুত জব ও এজেন্সি ব্যবসা শুরু করবেন?',
                'title_en' => 'How to Build a High-Demand Career in Air Ticketing & Visa Processing',
                'summary_bn' => 'Sabre ও Galileo সফটওয়্যারে দক্ষতা অর্জন করে এভিয়েশন এবং ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়ার সহজ ও প্র্যাকটিক্যাল গাইড।',
                'summary_en' => 'A practical roadmap to becoming an in-demand air ticketing officer and tourist visa consultant.',
                'content_bn' => '<p class="text-base font-medium">বর্তমান সময়ে ভ্রমণ ও আন্তর্জাতিক যাতায়াত কয়েকগুণ বৃদ্ধি পেয়েছে। ট্রাভেল এজেন্সি, এয়ারলাইন্স ও ট্যুরিজম কোম্পানিতে দক্ষ এয়ার টিকেটার এবং ভিসা প্রসেসরের বিপুল চাহিদা রয়েছে...</p><h2 class="text-xl font-black pt-4 pb-2 border-b border-[var(--border-subtle)]">১. আন্তর্জাতিক এয়ার টিকেটিংয়ে Sabre ও Galileo সফটওয়্যারের ভূমিকা</h2><p>এয়ার টিকেটিং পেশার মূল ভিত্তি হলো গ্লোবাল ডিস্ট্রিবিউশন সিস্টেম (GDS)। বিশ্বের ৮৫% এরও বেশি এয়ারলাইন্স ও ট্রাভেল এজেন্সি তাদের ফ্লাইট বুকিং, সিট অ্যাভেইলেবিলিটি চেক, ফেয়ার ক্যালকুলেশন এবং টিকিট ইস্যু করতে Sabre অথবা Galileo সিস্টেম ব্যবহার করে থাকে।</p><div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] my-3"><h4 class="font-bold">জরুরি বিষয়সমূহ যা প্র্যাকটিক্যাল ক্লাসে শেখা প্রয়োজন:</h4><ul class="list-disc pl-5 mt-2 space-y-1 text-xs"><li>PNR ক্রিয়েশন এবং প্যাসেঞ্জার প্রোফাইল ম্যানেজমেন্ট।</li><li>স্বয়ংক্রিয় ফেয়ার কোটেশন, ট্যাক্স ব্রেকডাউন ও বেস্ট রুট প্ল্যানিং।</li><li>টিকিট রি-ইস্যু (Re-issue), ডেট চেঞ্জ পেনাল্টি ক্যালকুলেশন ও ভয়েড রুলস।</li></ul></div><h2 class="text-xl font-black pt-4 pb-2 border-b border-[var(--border-subtle)]">২. গ্লোবাল ভিসা প্রসেসিং ও এম্বাসি কমপ্লায়েন্স</h2><p>শেঞ্জেন, ইউএসএ, কানাডা, অস্ট্রেলিয়া ও এশিয়ার প্রধান দেশসমূহের ভিসা আবেদন সঠিকভাবে প্রস্তুত করতে এম্বাসি চেকলিস্ট, ব্যাংক স্টেটমেন্ট ও কভার লেটার প্রস্তুতকরণ অপরিহার্য।</p>',
                'content_en' => '<p class="text-base font-medium">In today\'s globalized travel market, aviation and international travel agency operations offer one of the most lucrative and resilient career pathways...</p><h2 class="text-xl font-black pt-4 pb-2 border-b border-[var(--border-subtle)]">1. Role of Sabre & Galileo GDS in Modern Aviation</h2><p>Global Distribution Systems form the foundation of airline bookings, automated fare calculation, and ticketing across travel agencies worldwide.</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1200',
                'reading_time' => '5 মিনিট',
                'views_count' => 1243,
                'tags' => ['Air Ticketing', 'Visa Processing', 'Sabre GDS', 'Galileo', 'Career Guide'],
                'is_featured' => true,
                'status' => 'published',
                'published_at' => '2026-09-21 10:00:00',
            ]
        );

        BlogPost::updateOrCreate(
            ['slug' => 'sabre-vs-galileo-which-gds-is-best-for-travel-agency'],
            [
                'category_id' => $blogCat2->id,
                'author_id' => $adminUser?->id,
                'author_name_bn' => 'তানভীর রহমান',
                'author_name_en' => 'Tanvir Rahman',
                'author_designation_bn' => 'লিড এভিয়েশন ট্রেইনার',
                'author_designation_en' => 'Lead Aviation Trainer',
                'author_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150',
                'title_bn' => 'Sabre বনাম Galileo GDS: ট্রাভেল এজেন্সির জন্য কোনটি বেশি কার্যকরী?',
                'title_en' => 'Sabre vs Galileo GDS: Which System is More Crucial for Travel Agencies?',
                'summary_bn' => 'আন্তর্জাতিক এয়ারলাইন্স টিকেটিংয়ে Sabre এবং Galileo সিস্টেমের মূল পার্থক্য, কমান্ড শর্টকাট এবং উভয় সফটওয়্যারে দক্ষতার গুরুত্ব।',
                'summary_en' => 'Detailed comparison of Sabre vs Galileo command structures, fare calculation, and dual-system advantages.',
                'content_bn' => '<p>আন্তর্জাতিক এয়ারলাইন্স টিকেটিংয়ে Sabre এবং Galileo উভয় সিস্টেমেরই ব্যাপক চাহিদা রয়েছে। মধ্যপ্রাচ্য ও আমেরিকার ফ্লাইটের জন্য Sabre এবং ইউরোপ ও এশিয়ার জন্য Galileo বিশেষভাবে সমাদৃত...</p>',
                'content_en' => '<p>Both Sabre and Galileo dominate the airline reservation ecosystem. Knowing both systems gives travel agencies and ticketing professionals an unmatched competitive edge...</p>',
                'thumbnail' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=1200',
                'reading_time' => '6 মিনিট',
                'views_count' => 980,
                'tags' => ['Sabre', 'Galileo', 'GDS', 'Aviation Training'],
                'is_featured' => false,
                'status' => 'published',
                'published_at' => '2026-09-18 14:00:00',
            ]
        );

        // 6. Testimonials
        $testimonialsData = [
            [
                'student_name_bn' => 'রাশেদুল করিম',
                'student_name_en' => 'Rashedul Karim',
                'student_role_bn' => 'টিকেটিং এক্সিকিউটিভ, গ্লোবাল ট্রাভেলস',
                'student_role_en' => 'Ticketing Executive, Global Travels',
                'avatar' => '/images/testimonials/rashedul-karim.jpg',
                'course_name_bn' => 'এয়ার টিকেটিং ও ভিসা প্রসেসিং প্রফেশনাল কোর্স',
                'course_name_en' => 'Air Ticketing & Visa Processing Professional Course',
                'quote_bn' => 'ইমিশা একাডেমিতে প্রত্যেক শিক্ষার্থীর জন্য আলাদা কম্পিউটার থাকায় Sabre এবং Galileo সফটওয়্যারে লাইভ কাজ শিখে খুব দ্রুত আত্মবিশ্বাস পেয়েছি। কোর্স শেষেই আমার চাকরি হয়ে যায়।',
                'quote_en' => 'Having an individual computer at Emisha Academy for live Sabre & Galileo practice gave me immense hands-on confidence. Landed my travel agency job right after graduation.',
                'rating' => 5,
                'is_featured' => true,
                'order_index' => 1,
            ],
            [
                'student_name_bn' => 'তানজিলা আক্তার',
                'student_name_en' => 'Tanjila Akter',
                'student_role_bn' => 'ভিসা কনসালটেন্ট, স্কাইলাইন হলিডেজ',
                'student_role_en' => 'Visa Consultant, Skyline Holidays',
                'avatar' => '/images/testimonials/tanjila-akter.jpg',
                'course_name_bn' => 'প্রফেশনাল ভিসা প্রসেসিং ও এম্বাসি ডকুমেন্টেশন',
                'course_name_en' => 'Professional Visa Processing & Embassy Dossier',
                'quote_bn' => 'শেঞ্জেন ও ইউএসএ ভিসা ফাইলিংয়ের কভার লেটার ড্রাফট ও ব্যাংক সলভেন্সি নিয়মগুলো এতো সহজভাবে শিখানো হয়েছে যে এখন আত্মবিশ্বাসের সাথে ক্লায়েন্টদের শতভাগ নির্ভুল ফাইল রেডি করতে পারি।',
                'quote_en' => 'The step-by-step guidance on Schengen & US visa cover letters and bank solvency compliance enabled me to prepare 100% error-free embassy dossiers confidently.',
                'rating' => 5,
                'is_featured' => true,
                'order_index' => 2,
            ],
            [
                'student_name_bn' => 'মাহমুদুল হাসান',
                'student_name_en' => 'Mahmudul Hasan',
                'student_role_bn' => 'উদ্যোক্তা ও প্রতিষ্ঠাতা, ট্রাভেল বিডি ২৪',
                'student_role_en' => 'Founder & Entrepreneur, Travel BD 24',
                'avatar' => '/images/testimonials/mahmudul-hasan.jpg',
                'course_name_bn' => 'ট্রাভেল এজেন্সি বিজনেস ও B2B টিকেটিং',
                'course_name_en' => 'Travel Agency Business & B2B Ticketing',
                'quote_bn' => 'চাকরি ছেড়ে ট্রাভেল এজেন্সি বিজনেস শুরুর সব ভয় দূর হয়েছে ইমিশা একাডেমির গাইডলাইনে। কীভাবে লাইসেন্স পেতে হয় এবং B2B টিকেটিং প্ল্যাটফর্মে ভালো প্রফিট করা যায় তা হাতে-কলমে শিখেছি।',
                'quote_en' => 'Emisha Academy gave me the exact commercial roadmap and legal clarity to launch my own successful travel agency and scale profit margins.',
                'rating' => 5,
                'is_featured' => true,
                'order_index' => 3,
            ],
            [
                'student_name_bn' => 'ফারহানা ইসলাম',
                'student_name_en' => 'Farhana Islam',
                'student_role_bn' => 'সিনিয়র রিজার্ভেশন অফিসার, এয়ার এশিয়া এজেন্সি পার্টনার',
                'student_role_en' => 'Senior Reservation Officer, Air Asia Partner Agency',
                'avatar' => '/images/testimonials/farhana-islam.jpg',
                'course_name_bn' => 'অ্যাডভান্সড Sabre ও Galileo GDS মাস্টারি',
                'course_name_en' => 'Advanced Sabre & Galileo GDS Mastery',
                'quote_bn' => 'ক্লাসে সরাসরি লাইভ PNR ক্রিয়েশন, ফেয়ার কোটেশন ও টিকেট রি-ইস্যু করার জটিল বিষয়গুলো মেন্টররা পরম যত্নে শিখিয়েছেন। এভিয়েশন সেক্টরে ক্যারিয়ার গড়তে ইমিশা একাডেমি সত্যিই সেরা।',
                'quote_en' => 'Learning complex PNR creation, fare construction, and ticket re-issuance under senior airline mentors gave my aviation career the perfect launchpad.',
                'rating' => 5,
                'is_featured' => true,
                'order_index' => 4,
            ],
            [
                'student_name_bn' => 'আরিফুর রহমান',
                'student_name_en' => 'Arifur Rahman',
                'student_role_bn' => 'ওমরাহ ও ট্যুর প্যাকেজ অপারেশনস লিড',
                'student_role_en' => 'Umrah & Tour Operations Lead',
                'avatar' => '/images/testimonials/arifur-rahman.jpg',
                'course_name_bn' => 'ওমরাহ ও হজ্ব প্যাকেজ ম্যানেজমেন্ট',
                'course_name_en' => 'Umrah & Hajj Package Management',
                'quote_bn' => 'সৌদি নুশুক পোর্টাল, গ্রুপ ফ্লাইট বুকিং ও ওমরাহ হোটেল রিজার্ভেশনের বাস্তব অভিজ্ঞতা পেয়েছি। এখন আমাদের ট্রাভেল এজেন্সিতে সরাসরি লাইভ ওমরাহ গ্রুপের কাজ পরিচালনা করছি।',
                'quote_en' => 'Gained hands-on proficiency on Saudi Nusuk portal workflows, group airfares, and Makkah-Madinah hotel contracting. Exceptional practical training.',
                'rating' => 5,
                'is_featured' => true,
                'order_index' => 5,
            ],
            [
                'student_name_bn' => 'সাকিব আল মাহমুদ',
                'student_name_en' => 'Sakib Al Mahmud',
                'student_role_bn' => 'টিকেটিং অফিসার, গ্যালাক্সি ট্রাভেলস',
                'student_role_en' => 'Ticketing Officer, Galaxy Travels',
                'avatar' => '/images/testimonials/sakib-mahmud.jpg',
                'course_name_bn' => 'এয়ার টিকেটিং ও ভিসা প্রসেসিং প্রফেশনাল কোর্স',
                'course_name_en' => 'Air Ticketing & Visa Processing Professional Course',
                'quote_bn' => 'মিরপুর ক্যাম্পাসের ল্যাব এনভায়রনমেন্ট অসাধারণ। ট্রেইনাররা সবসময় পাশে থেকে গাইড করেছেন এবং ইন্টারভিউয়ের জন্য স্পেশাল টিপস দেওয়ায় প্রথম ইন্টারভিউতেই চাকরি নিশ্চিত হয়।',
                'quote_en' => 'The dedicated workstation lab at Mirpur campus and mock interview sessions were game changers. Cleared my first travel agency interview seamlessly.',
                'rating' => 5,
                'is_featured' => true,
                'order_index' => 6,
            ],
        ];

        foreach ($testimonialsData as $tData) {
            CmsTestimonial::updateOrCreate(
                ['student_name_en' => $tData['student_name_en']],
                $tData
            );
        }

        // 8. Ebook Categories & Study Handbooks
        $ebCat1 = EbookCategory::updateOrCreate(
            ['slug' => 'aviation-travel-guide'],
            ['name_bn' => 'এভিয়েশন ও GDS গাইডবুক', 'name_en' => 'Aviation & GDS Guides']
        );

        $ebCat2 = EbookCategory::updateOrCreate(
            ['slug' => 'visa-checklists'],
            ['name_bn' => 'ভিসা চেকলিস্ট ও গাইডলাইন', 'name_en' => 'Visa Checklists & Guidelines']
        );

        $ebCat3 = EbookCategory::updateOrCreate(
            ['slug' => 'umrah-tourism-manuals'],
            ['name_bn' => 'ওমরাহ ও ট্যুরিজম ম্যানুয়াল', 'name_en' => 'Umrah & Tourism Manuals']
        );

        $ebCat4 = EbookCategory::updateOrCreate(
            ['slug' => 'agency-business-guides'],
            ['name_bn' => 'এজেন্সি বিজনেস গাইড', 'name_en' => 'Agency Business Guides']
        );

        Ebook::updateOrCreate(
            ['slug' => 'practical-air-ticketing-and-gds-handbook'],
            [
                'category_id' => $ebCat1->id,
                'title_bn' => 'এয়ার টিকেটিং ও GDS (Sabre & Galileo) প্র্যাকটিক্যাল হ্যান্ডবুক',
                'title_en' => 'Practical Air Ticketing & GDS (Sabre & Galileo) Handbook',
                'author_name_bn' => 'ইমিশা একাডেমি রিসার্চ টিম',
                'author_name_en' => 'Emisha Academy Research Team',
                'author_designation_bn' => 'এভিয়েশন ফ্যাকাল্টি ও ট্রাভেল অপারেশনস টিম',
                'author_designation_en' => 'Aviation Faculty & Travel Operations Team',
                'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200',
                'edition_badge_bn' => 'অফিশিয়াল পিডিএফ ই-বুক সংস্করণ (সর্বশেষ সংস্করণ)',
                'edition_badge_en' => 'Official High-Resolution PDF Handbook (Latest Revision)',
                'summary_bn' => 'এই হ্যান্ডবুকটিতে Sabre ও Galileo সিস্টেমের প্রতিটি প্রয়োজনীয় কমান্ড, PNR ক্রিয়েশন শর্টকাট, ফেয়ার ক্যালকুলেশন এবং বিশ্বের প্রধান দেশসমূহের ভিসা ডকুমেন্টেশন চেকলিস্ট বিশদভাবে সাজানো হয়েছে।',
                'summary_en' => 'Comprehensive reference handbook with 100+ Sabre & Galileo command shortcuts, fare calculation rules, live PNR steps, and visa documentation checklist.',
                'description_bn' => 'এই হ্যান্ডবুকটিতে Sabre ও Galileo সিস্টেমের প্রতিটি প্রয়োজনীয় কমান্ড, PNR ক্রিয়েশন শর্টকাট, ফেয়ার ক্যালকুলেশন এবং বিশ্বের প্রধান দেশসমূহের ভিসা ডকুমেন্টেশন চেকলিস্ট বিশদভাবে সাজানো হয়েছে।',
                'description_en' => 'This handbook meticulously covers all essential commands for Sabre and Galileo systems, PNR creation shortcuts, international fare calculations, and visa documentation checklists for major destination countries.',
                'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600',
                'preview_pdf_path' => '/downloads/preview-handbook.pdf',
                'file_path' => '/downloads/air-ticketing-gds-practical-handbook.pdf',
                'pages_count' => 95,
                'file_size' => '6.8 MB',
                'regular_price' => 500.00,
                'sale_price' => 250.00,
                'is_free' => false,
                'download_count' => 421,
                'rating' => 4.95,
                'reviews_count' => 84,
                'is_featured' => true,
                'status' => 'published',
                'highlights_bn' => [
                    'Sabre এবং Galileo সিস্টেমের ১০০+ প্রয়োজনীয় কমান্ড শর্টকাট।',
                    'আন্তর্জাতিক এয়ারলাইন্স ফেয়ার ক্যালকুলেশন ও ট্যাক্স কোটেশন রুলস।',
                    'সরাসরি লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার হিস্ট্রি ট্র্যাকিং।',
                    'টিকিট রি-ইস্যু (Re-issue), ডেট চেঞ্জ এবং রিফান্ড পলিসি কমপ্লায়েন্স।',
                    'ট্যুরিস্ট ও বিজনেস ভিসা ফাইল প্রস্তুতকরণের পূর্ণাঙ্গ চেকলিস্ট।',
                    'ক্লায়েন্ট হ্যান্ডলিং ও এজেন্সির প্রফিট মার্জিন বাড়ানোর কার্যকরী কৌশল।',
                ],
                'highlights_en' => [
                    '100+ Essential Sabre & Galileo command shortcuts.',
                    'International airline fare construction & tax breakdown rules.',
                    'Hands-on live PNR creation & passenger profile management.',
                    'Re-issue, date change penalty calculations & refund procedures.',
                    'Complete tourist & business visa dossier preparation checklist.',
                    'Client counseling & profit optimization strategies for agencies.',
                ],
                'chapters_bn' => [
                    [
                        'chapter_num' => 1,
                        'title' => 'অধ্যায় ০১: আন্তর্জাতিক এভিয়েশন কাঠামো ও এয়ারলাইন্স কোডস',
                        'summary' => 'IATA ও ICAO পরিচিতি, বিশ্বের প্রধান এয়ারলাইন্স ও সিটি কোডস এবং ট্রাভেল জিওগ্রাফি কনসেপ্ট।',
                        'subtopics' => [
                            'IATA এরিয়া ১, ২, ৩ এবং টাইম জোন গণনা',
                            '২-লেটার ও ৩-লেটার এয়ারলাইন্স ও এয়ারপোর্ট কোডস',
                            'ফ্লাইট টাইপস: নন-স্টপ, ডাইরেক্ট ও কানেক্টিং ফ্লাইট',
                        ],
                    ],
                    [
                        'chapter_num' => 2,
                        'title' => 'অধ্যায় ০২: Sabre ও Galileo GDS সিস্টেম এনভায়রনমেন্ট',
                        'summary' => 'GDS সিস্টেমে সাইন-ইন, ওয়ার্ক এরিয়া এবং টার্মিনাল কমান্ডের বেসিক স্ট্রাকচার।',
                        'subtopics' => [
                            'Agent Sign-In, Sign-Out ও Area Switch',
                            'Encode & Decode Cities, Airlines ও Equipment',
                            'Flight Availability Display ও সিট স্ট্যাটাস কোড',
                        ],
                    ],
                    [
                        'chapter_num' => 3,
                        'title' => 'অধ্যায় ০৩: লাইভ PNR ক্রিয়েশন ও প্যাসেঞ্জার ম্যানেজমেন্ট',
                        'summary' => 'বাস্তবসম্মত PNR এর ৫টি বাধ্যতামূলক উপাদান এবং সার্ভিস রিকোয়েস্ট যোগ করার নিয়ম।',
                        'subtopics' => [
                            'PRINT এলিমেন্টস (Phone, Received, Itinerary, Name, Ticketing)',
                            'Special Service Request (SSR) ও OSI মেসেজ',
                            'Split PNR ও প্যাসেঞ্জার হিস্ট্রি চেক',
                        ],
                    ],
                    [
                        'chapter_num' => 4,
                        'title' => 'অধ্যায় ০৪: ফেয়ার কোটেশন, টিকিট ইস্যু ও রি-ইস্যু প্রসেসিং',
                        'summary' => 'স্বয়ংক্রিয় ফেয়ার ডিসপ্লে, ফেয়ার রুলস এবং ডেট চেঞ্জ পেনাল্টি ক্যালকুলেশন।',
                        'subtopics' => [
                            'Lowest Fare Search (WPNCB / FQ)',
                            'Electronic Ticket Issuance ও ইনভয়েস জেনারেশন',
                            'Re-issue, Date Change ও Penalty Calculation',
                        ],
                    ],
                    [
                        'chapter_num' => 5,
                        'title' => 'অধ্যায় ০৫: এম্বাসি ভিসা ফাইলিং ও এজেন্সি বিজনেস গাইডলাইন',
                        'summary' => 'ভিসা ফাইল প্রসেসিং এবং ট্রাভেল এজেন্সির বিটুবি টিকেটিং বিজনেস সেটআপ।',
                        'subtopics' => [
                            'Schengen ও USA ভিসা কভার লেটার ড্রাফট',
                            'এয়ার টিকেটিং পোর্টাল ও B2B অপারেশনস',
                            'এজেন্সি লাভজনক করার কৌশল ও কাস্টমার সার্ভিস',
                        ],
                    ],
                ],
                'chapters_en' => [
                    [
                        'chapter_num' => 1,
                        'title' => 'Chapter 01: Global Aviation Framework & Airline Codes',
                        'summary' => 'IATA & ICAO overview, major world airport city codes, and global travel geography.',
                        'subtopics' => [
                            'IATA Areas 1, 2, 3 and timezone calculations',
                            '2-letter airline codes & 3-letter airport codes',
                            'Flight routing: Non-stop, Direct & Connecting',
                        ],
                    ],
                    [
                        'chapter_num' => 2,
                        'title' => 'Chapter 02: Sabre & Galileo GDS System Environment',
                        'summary' => 'Terminal sign-in workflows, command syntaxes, and availability displays.',
                        'subtopics' => [
                            'Agent Sign-in & Work Area Navigation',
                            'Encode & Decode commands for cities & carriers',
                            'Live Availability Displays & status codes',
                        ],
                    ],
                    [
                        'chapter_num' => 3,
                        'title' => 'Chapter 03: Live PNR Creation & Itinerary Management',
                        'summary' => '5 Mandatory PNR elements, auxiliary SSR/OSI services, and booking modifications.',
                        'subtopics' => [
                            'Mandatory PRINT elements breakdown',
                            'SSR special meal, wheelchair & passport data',
                            'Split PNR and booking modification rules',
                        ],
                    ],
                    [
                        'chapter_num' => 4,
                        'title' => 'Chapter 04: Fare Quotation, Ticketing & Re-Issuance',
                        'summary' => 'Automated fare construction, tax breakdowns, and ticket re-issue calculations.',
                        'subtopics' => [
                            'Best Buy & lowest fare search commands',
                            'Electronic ticket issuance and billing',
                            'Date change re-issue penalty computations',
                        ],
                    ],
                    [
                        'chapter_num' => 5,
                        'title' => 'Chapter 05: Embassy Visa Filing & Agency Operations',
                        'summary' => 'Visa dossier drafting and B2B travel agency business operations.',
                        'subtopics' => [
                            'Compliant cover letter & itinerary templates',
                            'B2B flight booking portals & ticketing setup',
                            'Agency profit scaling & operational best practices',
                        ],
                    ],
                ],
                'target_audience_bn' => [
                    ['icon' => 'plane', 'title' => 'টিকেটিং এক্সিকিউটিভ', 'desc' => 'এয়ারলাইন্স বা ট্রাভেল এজেন্সিতে কর্মরত বা চাকরিপ্রার্থী।'],
                    ['icon' => 'briefcase', 'title' => 'এজেন্সি উদ্যোক্তা', 'desc' => 'নতুন ট্রাভেল ব্যবসা বা ভিসা কনসালটেন্সি শুরু করতে চান।'],
                    ['icon' => 'cap', 'title' => 'শিক্ষার্থী ও প্রফেশনাল', 'desc' => 'প্র্যাকটিক্যাল স্কিল অর্জন করে ক্যারিয়ার শুরু করতে চান।'],
                ],
                'target_audience_en' => [
                    ['icon' => 'plane', 'title' => 'Ticketing Officers', 'desc' => 'Working in travel agencies or aspiring airline ticketing staff.'],
                    ['icon' => 'briefcase', 'title' => 'Agency Entrepreneurs', 'desc' => 'Planning to launch a travel agency or visa consultancy.'],
                    ['icon' => 'cap', 'title' => 'Students & Professionals', 'desc' => 'Looking for high-demand, skill-based international career.'],
                ],
                'lab_upsell_title_bn' => 'শুধু বই পড়ে নয়, কম্পিউটারে সরাসরি লাইভ সফটওয়্যার শিখুন!',
                'lab_upsell_title_en' => 'Move Beyond Theory: Learn Live GDS on Dedicated Workstations',
                'lab_upsell_desc_bn' => 'ইমিশা একাডেমির মিরপুর ক্যাম্পাসে প্রতিটি শিক্ষার্থীর জন্য রয়েছে আলাদা কম্পিউটার এবং Sabre ও Galileo সিস্টেমের লাইভ সফটওয়্যার অ্যাক্সেস।',
                'lab_upsell_desc_en' => 'Join our physical classroom batches at Mirpur Kazipara. 1 Student = 1 Workstation with unlimited lab practice guarantee.',
                'lab_upsell_btn_text_bn' => 'প্র্যাকটিক্যাল কোর্সসমূহ দেখুন →',
                'lab_upsell_btn_text_en' => 'Explore Flagship Courses →',
                'lab_upsell_btn_link' => '/courses',
            ]
        );

        Ebook::updateOrCreate(
            ['slug' => 'global-tourist-visa-documentation-checklist-2026'],
            [
                'category_id' => $ebCat2->id,
                'title_bn' => 'গ্লোবাল ট্যুরিস্ট ভিসা প্রসেসিং ও এম্বাসি ডকুমেন্টেশন চেকলিস্ট',
                'title_en' => 'Global Tourist Visa Processing & Embassy Dossier Checklist',
                'author_name_bn' => 'নুসরাত জাহান ও ভিসা রিসার্চ টিম',
                'author_name_en' => 'Nusrat Jahan & Visa Research Team',
                'author_designation_bn' => 'সিনিয়র ভিসা প্রসেসিং কনসালটেন্ট',
                'author_designation_en' => 'Senior Visa Processing Consultant',
                'author_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200',
                'edition_badge_bn' => 'অফিশিয়াল ফ্রি ই-বুক সংস্করণ (২০২৬ আপডেট)',
                'edition_badge_en' => 'Official Free Ebook Edition (2026 Update)',
                'summary_bn' => 'শেঞ্জেন, ইউএসএ, কানাডা, ইউকে, জাপান ও এশিয়ার প্রধান দেশসমূহের ভিসা ফাইল চেকলিস্ট ও কভার লেটার ফরম্যাট।',
                'summary_en' => 'Complete checklist and cover letter templates for Schengen, USA, Canada, UK, and Asian countries.',
                'description_bn' => 'শেঞ্জেন ও পশ্চিমা দেশসমূহের ভিসা রিজেকশন এড়াতে এম্বাসি স্ট্যান্ডার্ড ফাইলিং, ব্যাংক সলভেন্সি ফরম্যাট, কভার লেটার ড্রাফটিং ও ট্রাভেল প্ল্যান তৈরির পূর্ণাঙ্গ নির্দেশিকা।',
                'description_en' => 'Comprehensive guidance to avoid visa refusals, prepare compliant financial statements, draft winning cover letters, and organize embassy dossiers.',
                'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=600',
                'preview_pdf_path' => '/downloads/preview-visa.pdf',
                'file_path' => '/downloads/visa-processing-checklist.pdf',
                'pages_count' => 72,
                'file_size' => '5.2 MB',
                'regular_price' => 400.00,
                'sale_price' => 0.00,
                'is_free' => true,
                'download_count' => 2850,
                'rating' => 4.96,
                'reviews_count' => 62,
                'is_featured' => false,
                'status' => 'published',
                'highlights_bn' => [
                    'শেঞ্জেন ও ইউএস ভিসা কভার লেটার ড্রাফটিং স্ট্যান্ডার্ড।',
                    'ব্যাংক সলভেন্সি সার্টিফিকেট ও স্টেটমেন্ট ভ্যালিডেশন চেকলিস্ট।',
                    'স্পন্সরশিপ ও ইনভাইটেশন লেটার ফরম্যাট।',
                ],
                'highlights_en' => [
                    'Schengen & US Visa cover letter drafting standard.',
                    'Bank solvency certificate & statement validation checklist.',
                    'Sponsorship & invitation letter templates.',
                ],
                'chapters_bn' => [
                    [
                        'chapter_num' => 1,
                        'title' => 'অধ্যায় ০১: শেঞ্জেন ভিসা ফাইলিং গাইডলাইন',
                        'summary' => 'ভিজিট ভিসা ফাইলের সকল প্রয়োজনীয় ডকুমেন্টস ও অ্যাপয়েন্টমেন্ট বুকিং।',
                        'subtopics' => ['চেকলিস্ট', 'কভার লেটার', 'ট্রাভেল ইন্স্যুরেন্স'],
                    ],
                ],
                'target_audience_bn' => [
                    ['icon' => 'briefcase', 'title' => 'ভিসা কনসালটেন্ট', 'desc' => 'ভিসা প্রসেসিং সংক্রান্ত পেশাজীবী।'],
                ],
                'lab_upsell_title_bn' => 'প্র্যাকটিক্যাল ভিসা প্রসেসিং কোর্স',
                'lab_upsell_desc_bn' => 'ইমিশা একাডেমিতে সরাসরি ক্লাসরুমে ফাইল প্রসেসিং শিখুন।',
                'lab_upsell_btn_text_bn' => 'ভিসা কোর্স দেখুন →',
                'lab_upsell_btn_link' => '/courses',
            ]
        );

        Ebook::updateOrCreate(
            ['slug' => 'travel-agency-business-startup-and-iata-guide'],
            [
                'category_id' => $ebCat4->id,
                'title_bn' => 'ট্রাভেল এজেন্সি ব্যবসা শুরু ও IATA অ্যাক্রিডিটেশন গাইডলাইন',
                'title_en' => 'Travel Agency Business Setup & IATA Accreditation Guide',
                'author_name_bn' => 'ইমিশা বিজনেস রিসার্চ টিম',
                'author_name_en' => 'Emisha Business Research Team',
                'author_designation_bn' => 'এজেন্সি কনসালটেন্সি উইং',
                'author_designation_en' => 'Agency Consultancy Wing',
                'author_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200',
                'edition_badge_bn' => 'প্র্যাকটিক্যাল বিজনেস গাইডলাইন সংস্করণ',
                'edition_badge_en' => 'Practical Business Guideline Edition',
                'summary_bn' => 'ট্রেড লাইসেন্স, সিভিল এভিয়েশন পারমিট, নন-আইয়াটা থেকে আইয়াটা কনভার্সন ও বিটুবি টিকেটিং বিজনেস গাইড।',
                'summary_en' => 'Legal trade permits, civil aviation clearance, B2B ticketing portals, and scaling agency revenue.',
                'description_bn' => 'বাংলাদেশে বৈধভাবে ট্রাভেল এজেন্সি প্রতিষ্ঠা, ট্রেড লাইসেন্স, সিভিল এভিয়েশন এনওসি এবং সফলভাবে বিটুবি ব্যবসায় লাভজনক অপারেশন পরিচালনার স্টেপ-বাই-স্টেপ রোডম্যাপ।',
                'description_en' => 'Step-by-step legal, commercial, and operational roadmap to establish and scale a compliant travel agency in Bangladesh.',
                'cover_image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=600',
                'preview_pdf_path' => '/downloads/preview-agency.pdf',
                'file_path' => '/downloads/travel-agency-guide.pdf',
                'pages_count' => 85,
                'file_size' => '6.1 MB',
                'regular_price' => 600.00,
                'sale_price' => 300.00,
                'is_free' => false,
                'download_count' => 2210,
                'rating' => 4.98,
                'reviews_count' => 54,
                'is_featured' => false,
                'status' => 'published',
                'highlights_bn' => [
                    'ট্রেড লাইসেন্স ও সিভিল এভিয়েশন পারমিট চেকলিস্ট।',
                    'B2B টিকেটিং পোর্টাল কানেক্টিভিটি।',
                    'এজেন্সির প্রফিট মার্জিন বাড়ানোর কার্যকরী কৌশল।',
                ],
                'chapters_bn' => [
                    [
                        'chapter_num' => 1,
                        'title' => 'অধ্যায় ০১: এজেন্সি লাইসেন্সিং ও লিগ্যাল ফ্রেমওয়ার্ক',
                        'summary' => 'বাংলাদেশে এজেন্সি চালুর সরকারি নিয়মাবলী।',
                        'subtopics' => ['লাইসেন্স প্রসেস', 'এনওসি প্রাপ্তি'],
                    ],
                ],
                'target_audience_bn' => [
                    ['icon' => 'briefcase', 'title' => 'এজেন্সি উদ্যোক্তা', 'desc' => 'নতুন ট্রাভেল ব্যবসা শুরু করতে আগ্রহী।'],
                ],
                'lab_upsell_title_bn' => 'কম্পিউটার ল্যাবে প্র্যাকটিক্যাল ট্রেনিং',
                'lab_upsell_desc_bn' => 'নিজস্ব এজেন্সির জন্য সরাসরি টিকেটিং সফটওয়্যার চালানো শিখুন।',
                'lab_upsell_btn_text_bn' => 'সকল কোর্স দেখুন →',
                'lab_upsell_btn_link' => '/courses',
            ]
        );
    }
}
