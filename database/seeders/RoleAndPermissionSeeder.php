<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Granular Permissions
        $permissions = [
            // Courses & Batches
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.delete',
            'batches.manage',
            'curriculum.manage',
            // Webinars
            'webinars.view',
            'webinars.create',
            'webinars.edit',
            'webinars.delete',
            // Ebooks
            'ebooks.view',
            'ebooks.create',
            'ebooks.edit',
            'ebooks.delete',
            // Orders & Payments
            'orders.view',
            'orders.manage',
            'payments.verify',
            'payments.refund',
            // Leads & Inquiries
            'leads.view',
            'leads.manage',
            'inquiries.manage',
            // Content & CMS
            'blogs.manage',
            'testimonials.manage',
            'cms.manage',
            // User Management & Roles
            'users.view',
            'users.manage',
            'roles.manage',
            'audit_logs.view',
            'settings.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // 2. Define Roles and Assign Permissions
        // SuperAdmin - gets all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'courses.view', 'courses.create', 'courses.edit', 'courses.delete',
            'batches.manage', 'curriculum.manage',
            'webinars.view', 'webinars.create', 'webinars.edit', 'webinars.delete',
            'ebooks.view', 'ebooks.create', 'ebooks.edit', 'ebooks.delete',
            'orders.view', 'orders.manage', 'payments.verify', 'payments.refund',
            'leads.view', 'leads.manage', 'inquiries.manage',
            'blogs.manage', 'testimonials.manage', 'cms.manage',
            'users.view', 'users.manage', 'audit_logs.view', 'settings.manage',
        ]);

        // Manager
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'courses.view', 'courses.edit', 'batches.manage', 'curriculum.manage',
            'webinars.view', 'webinars.create', 'webinars.edit',
            'ebooks.view', 'ebooks.create', 'ebooks.edit',
            'orders.view', 'payments.verify',
            'leads.view', 'leads.manage', 'inquiries.manage',
            'blogs.manage', 'testimonials.manage',
            'users.view',
        ]);

        // Moderator / Worker / Counselor
        $moderatorRole = Role::firstOrCreate(['name' => 'Moderator', 'guard_name' => 'web']);
        $moderatorPermissions = [
            'courses.view',
            'webinars.view',
            'ebooks.view',
            'leads.view', 'leads.manage',
            'inquiries.manage',
            'blogs.manage',
            'testimonials.manage',
            'orders.view',
        ];
        $moderatorRole->syncPermissions($moderatorPermissions);

        $workerRole = Role::firstOrCreate(['name' => 'Worker', 'guard_name' => 'web']);
        $workerRole->syncPermissions($moderatorPermissions);

        // Instructor
        $instructorRole = Role::firstOrCreate(['name' => 'Instructor', 'guard_name' => 'web']);
        $instructorRole->syncPermissions([
            'courses.view', 'curriculum.manage',
            'webinars.view',
        ]);

        // Student
        $studentRole = Role::firstOrCreate(['name' => 'Student', 'guard_name' => 'web']);
        $studentRole->syncPermissions([
            'courses.view',
            'webinars.view',
            'ebooks.view',
        ]);

        // 3. Clean up old demo staff users if present
        User::whereIn('email', [
            'admin@emishaacademy.com',
            'manager@emishaacademy.com',
            'counselor@emishaacademy.com',
        ])->delete();

        // 4. Create New Emisha Academy Official Accounts

        // Admin -> admin@emisha.academy / Emisha@01805464291#Naznin#@
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@emisha.academy'],
            [
                'name' => 'Naznin Alam',
                'phone' => '+8801805464291',
                'password' => Hash::make('Emisha@01805464291#Naznin#@'),
                'status' => 'active',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150',
            ]
        );
        $adminUser->syncRoles(['SuperAdmin', 'Admin']);
        UserProfile::updateOrCreate(
            ['user_id' => $adminUser->id],
            [
                'headline' => 'Super Administrator at Emisha Academy',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
            ]
        );

        // Manager -> manager@emisha.academy / Emisha@01805464291#@Treker#@
        $managerUser = User::updateOrCreate(
            ['email' => 'manager@emisha.academy'],
            [
                'name' => 'Manager ET&T&A',
                'phone' => '+8801805464292',
                'password' => Hash::make('Emisha@01805464291#@Treker#@'),
                'status' => 'active',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150',
            ]
        );
        $managerUser->syncRoles(['Manager']);
        UserProfile::updateOrCreate(
            ['user_id' => $managerUser->id],
            [
                'headline' => 'Operations & Academic Manager',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
            ]
        );

        // Moderators 1 to 6
        $moderators = [
            [
                'email' => 'm1@emisha.academy',
                'password' => 'Emisha@583214#@Treker#@',
                'name' => 'Moderator ET&T&A1',
                'phone' => '+8801805464293',
            ],
            [
                'email' => 'm2@emisha.academy',
                'password' => 'Emisha@741906#@Treker#@',
                'name' => 'Moderator ET&T&A2',
                'phone' => '+8801805464294',
            ],
            [
                'email' => 'm3@emisha.academy',
                'password' => 'Emisha@326857#@Treker#@',
                'name' => 'Moderator ET&T&A3',
                'phone' => '+8801805464295',
            ],
            [
                'email' => 'm4@emisha.academy',
                'password' => 'Emisha@914263#@Treker#@',
                'name' => 'Moderator ET&T&A4',
                'phone' => '+8801805464296',
            ],
            [
                'email' => 'm5@emisha.academy',
                'password' => 'Emisha@67053#@Treker#@',
                'name' => 'Moderator ET&T&A5',
                'phone' => '+8801805464297',
            ],
            [
                'email' => 'm6@emisha.academy',
                'password' => 'Emisha@458729#@Treker#@',
                'name' => 'Moderator ET&T&A6',
                'phone' => '+8801805464298',
            ],
        ];

        foreach ($moderators as $mod) {
            $modUser = User::updateOrCreate(
                ['email' => $mod['email']],
                [
                    'name' => $mod['name'],
                    'phone' => $mod['phone'],
                    'password' => Hash::make($mod['password']),
                    'status' => 'active',
                    'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150',
                ]
            );
            $modUser->syncRoles(['Moderator', 'Worker']);
            UserProfile::updateOrCreate(
                ['user_id' => $modUser->id],
                [
                    'headline' => 'Content & Student Support Moderator',
                    'city' => 'Dhaka',
                    'country' => 'Bangladesh',
                ]
            );
        }

        // 5. Instructors
        // 1. Lead Aviation & GDS Instructor
        $instructorUser1 = User::updateOrCreate(
            ['email' => 'instructor@emishaacademy.com'],
            [
                'name' => 'তানভীর রহমান',
                'phone' => '01805464293',
                'password' => Hash::make('password'),
                'status' => 'active',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=85',
            ]
        );
        $instructorUser1->syncRoles(['Instructor']);
        UserProfile::updateOrCreate(
            ['user_id' => $instructorUser1->id],
            [
                'headline' => 'Lead Aviation, GDS & Visa Processing Specialist',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
            ]
        );
        Instructor::updateOrCreate(
            ['user_id' => $instructorUser1->id],
            [
                'name_bn' => 'তানভীর রহমান',
                'name_en' => 'Tanvir Rahman',
                'title_bn' => 'লিড এভিয়েশন ট্রেইনার, জিডিএস ও এয়ার টিকেটিং স্পেশালিস্ট',
                'title_en' => 'Lead Aviation Trainer, GDS & Air Ticketing Specialist',
                'bio_bn' => '১০+ বছরের আন্তর্জাতিক এয়ারলাইন্স ও Sabre/Galileo GDS সিস্টেমে সরাসরি কাজের অভিজ্ঞতা। ৩,২০০+ শিক্ষার্থীকে এয়ার টিকেটিং ও গ্লোবাল রিজার্ভেশনে প্রশিক্ষণ দিয়ে এভিয়েশন সেক্টরে ক্যারিয়ার গঠনে নেতৃত্ব দিয়েছেন।',
                'bio_en' => 'Senior Aviation & GDS Specialist with 10+ years of hands-on airline booking and ticketing operations in Sabre and Galileo platforms. Mentored 3,200+ professionals.',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=85',
                'experience_years' => '10+',
                'organization' => 'Emisha Tours & Travels',
                'linkedin_url' => 'https://linkedin.com',
                'facebook_url' => 'https://facebook.com',
                'rating' => 4.96,
                'total_students' => 3200,
                'is_featured' => true,
            ]
        );

        // 2. Global Visa & Immigration Consultant
        $instructorUser2 = User::updateOrCreate(
            ['email' => 'ishtiaq.visa@emishaacademy.com'],
            [
                'name' => 'ইশতিয়াক আহমেদ',
                'phone' => '01805464294',
                'password' => Hash::make('password'),
                'status' => 'active',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=85',
            ]
        );
        $instructorUser2->syncRoles(['Instructor']);
        UserProfile::updateOrCreate(
            ['user_id' => $instructorUser2->id],
            [
                'headline' => 'Senior Visa Consultant & Global Documentation Expert',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
            ]
        );
        Instructor::updateOrCreate(
            ['user_id' => $instructorUser2->id],
            [
                'name_bn' => 'ইশতিয়াক আহমেদ',
                'name_en' => 'Ishtiaq Ahmed',
                'title_bn' => 'সিনিয়র ভিসা কনসালটেন্ট ও গ্লোবাল ডকুমেন্টেশন স্পেশালিস্ট',
                'title_en' => 'Senior Visa Consultant & Global Documentation Specialist',
                'bio_bn' => '৮+ বছরের আন্তর্জাতিক ভিসা প্রসেসিং অভিজ্ঞতা। ইউএসএ, ইউকে, কানাডা, অস্ট্রেলিয়া, শেঞ্জেন ও এশিয়া দেশসমূহের ট্যুরিস্ট ভিসা ফাইল রেডি, স্পনসরশিপ ও ডকুমেন্টেশন ভেরিফিকেশনে বিশেষ দক্ষ।',
                'bio_en' => 'Senior Visa & Immigration Consultant with 8+ years of expertise in Schengen, USA, UK, Canada, Australia and Asian tourist visa dossier preparation and embassy compliance.',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=85',
                'experience_years' => '8+',
                'organization' => 'Emisha Global Visa Consultancy',
                'linkedin_url' => 'https://linkedin.com',
                'facebook_url' => 'https://facebook.com',
                'rating' => 4.92,
                'total_students' => 2400,
                'is_featured' => true,
            ]
        );

        // 3. Hajj, Umrah & Hotel Operations Specialist
        $instructorUser3 = User::updateOrCreate(
            ['email' => 'mahmudul.hajj@emishaacademy.com'],
            [
                'name' => 'মাহমুদুল হাসান',
                'phone' => '01805464295',
                'password' => Hash::make('password'),
                'status' => 'active',
                'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=85',
            ]
        );
        $instructorUser3->syncRoles(['Instructor']);
        UserProfile::updateOrCreate(
            ['user_id' => $instructorUser3->id],
            [
                'headline' => 'Hajj & Umrah Operations Lead & Nusuk Portal Trainer',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
            ]
        );
        Instructor::updateOrCreate(
            ['user_id' => $instructorUser3->id],
            [
                'name_bn' => 'মাহমুদুল হাসান',
                'name_en' => 'Mahmudul Hasan',
                'title_bn' => 'হজ্ব ও ওমরাহ প্রসেসিং স্পেশালিস্ট এবং নুসুক পোর্টাল ট্রেইনার',
                'title_en' => 'Hajj & Umrah Operations Specialist & Nusuk Portal Trainer',
                'bio_bn' => '৬+ বছরের হজ্ব ও ওমরাহ প্যাকেজ ডিজাইন, গ্রুপ বুকিং, সৌদি নুসুক (Nusuk) পোর্টাল হ্যান্ডলিং এবং মক্কা-মদিনা হোটেল রিজার্ভেশন ম্যানেজমেন্টের বাস্তব অভিজ্ঞতা সম্পন্ন প্রশিক্ষক।',
                'bio_en' => 'Hajj & Umrah Operations Specialist with 6+ years managing Nusuk B2B portal, Saudi ministry visas, and luxury hospitality booking logistics across Makkah & Madinah.',
                'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=85',
                'experience_years' => '6+',
                'organization' => 'Emisha Tours & Travels',
                'linkedin_url' => 'https://linkedin.com',
                'facebook_url' => 'https://facebook.com',
                'rating' => 4.98,
                'total_students' => 1950,
                'is_featured' => true,
            ]
        );

        // 6. Student Demo User
        $student = User::updateOrCreate(
            ['email' => 'student@emishaacademy.com'],
            [
                'name' => 'তানভীর আহমেদ',
                'phone' => '+8801700000005',
                'password' => Hash::make('password'),
                'status' => 'active',
                'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
            ]
        );
        $student->syncRoles(['Student']);
        UserProfile::updateOrCreate(
            ['user_id' => $student->id],
            [
                'headline' => 'Aspiring Travel & Aviation Professional',
                'city' => 'Chittagong',
                'country' => 'Bangladesh',
            ]
        );
    }
}
