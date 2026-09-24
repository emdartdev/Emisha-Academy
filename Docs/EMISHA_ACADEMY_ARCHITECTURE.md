# Emisha Academy --- ARCHITECTURE.md

**Project:** Emisha Academy\
**Architecture Version:** 1.0\
**Product:** Bilingual Bangla-first EdTech Platform\
**Backend:** PHP + Laravel\
**Frontend:** Vue 3 + TypeScript\
**Database:** MySQL / InnoDB\
**Primary Auth:** Laravel Sanctum\
**Primary Theme:** Dark\
**Primary Language:** Bangla\
**Secondary Language:** English

------------------------------------------------------------------------

# 1. Architecture Objective

Emisha Academy must be built as a **modular, secure, content-managed,
permission-controlled EdTech platform** rather than as a collection of
independent CRUD pages.

The architecture must support four major concerns:

``` text
Public Experience
        +
Learning Experience
        +
Content Management
        +
Business / Commerce
```

The system must remain maintainable when the academy later adds:

-   more courses;
-   more instructors;
-   more staff;
-   more languages;
-   more payment gateways;
-   certificates;
-   quizzes;
-   assignments;
-   mobile applications;
-   advanced analytics;
-   AI-powered learning.

The architecture therefore favors **clear domain boundaries, server-side
authorization, transactional business logic, reusable Vue components,
and CMS-driven content**.

------------------------------------------------------------------------

# 2. Architecture Principles

## 2.1 Backend Owns Business Rules

Laravel must remain the source of truth for:

-   pricing;
-   discounts;
-   course capacity;
-   enrollment;
-   permissions;
-   order state;
-   payment state;
-   review eligibility;
-   publishing state;
-   private resource access.

The Vue client must never be trusted for these decisions.

------------------------------------------------------------------------

## 2.2 Frontend Owns Presentation

Vue should manage:

-   rendering;
-   local UI state;
-   form interaction;
-   filters;
-   client-side navigation;
-   optimistic UI only where safe;
-   theme;
-   locale;
-   dashboard presentation.

Vue must not independently determine whether a user is authorized.

------------------------------------------------------------------------

## 2.3 API-First Internal Boundary

Even if Laravel and Vue live in one repository, the application should
have a clean API/service boundary.

Conceptually:

``` text
Vue Application
      ↓
HTTP API
      ↓
Laravel Controllers
      ↓
Requests / Policies
      ↓
Actions / Services
      ↓
Models / Database
```

------------------------------------------------------------------------

# 3. High-Level System Architecture

``` text
┌─────────────────────────────────────────────────────┐
│                    CLIENT LAYER                     │
│                                                     │
│  Public Vue App     Student App     Staff Apps      │
│  Mobile / Desktop / Tablet / Responsive             │
└───────────────────────┬─────────────────────────────┘
                        │ HTTPS
                        ▼
┌─────────────────────────────────────────────────────┐
│                  WEB / API LAYER                    │
│                                                     │
│ Nginx / Reverse Proxy                               │
│ Laravel Application                                 │
│ ├── Authentication                                  │
│ ├── Authorization                                   │
│ ├── Controllers                                     │
│ ├── Validation                                      │
│ ├── API Resources                                   │
│ └── Routing                                         │
└───────────────────────┬─────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────┐
│                 APPLICATION LAYER                   │
│                                                     │
│ Actions / Services / Policies / Events / Jobs       │
│                                                     │
│ Course │ Enrollment │ Order │ Payment               │
│ Webinar│ Ebook      │ Blog  │ Review                │
│ CMS    │ Media      │ Lead  │ Notification          │
└───────────────┬───────────────────┬─────────────────┘
                │                   │
                ▼                   ▼
       ┌────────────────┐   ┌────────────────┐
       │ MySQL / InnoDB │   │ Redis          │
       │ Primary Data   │   │ Cache / Queue  │
       └────────────────┘   └────────────────┘
                │
                ▼
       ┌──────────────────────────┐
       │ Object / File Storage    │
       │ Images / PDFs / Videos    │
       │ Private Learning Files   │
       └──────────────────────────┘
```

------------------------------------------------------------------------

# 4. Recommended Technology Stack

## Core

``` text
PHP 8.3+ or project-supported current PHP
Laravel 13.x/current supported Laravel release
Vue 3
TypeScript
Vite
MySQL 8.4 LTS
```

The exact Laravel/PHP versions should follow the versions officially
supported at implementation time.

Laravel's current documentation provides SPA/API integration patterns,
while Sanctum is designed for SPA authentication using Laravel's
cookie-based session authentication. citeturn0search2

## Supporting

``` text
Redis
Nginx
Supervisor / process manager
Queue Worker
Scheduler
Git
GitHub
Docker
CI/CD
Object Storage
SMTP / transactional email
Payment gateway
Monitoring
```

------------------------------------------------------------------------

# 5. Frontend Architecture

Recommended frontend:

``` text
Vue 3
Composition API
TypeScript
Vue Router
Pinia
Axios / Fetch
Vite
```

------------------------------------------------------------------------

# 6. Frontend Folder Structure

``` text
resources/js/
│
├── app/
│   ├── bootstrap.ts
│   ├── router.ts
│   └── providers.ts
│
├── assets/
│   ├── images/
│   ├── icons/
│   └── styles/
│
├── components/
│   ├── ui/
│   │   ├── Button/
│   │   ├── Input/
│   │   ├── Select/
│   │   ├── Modal/
│   │   ├── Drawer/
│   │   ├── Tabs/
│   │   ├── Accordion/
│   │   ├── Badge/
│   │   ├── Pagination/
│   │   └── Toast/
│   │
│   ├── course/
│   ├── webinar/
│   ├── ebook/
│   ├── blog/
│   ├── review/
│   ├── instructor/
│   ├── homepage/
│   ├── navigation/
│   └── dashboard/
│
├── composables/
│   ├── useAuth.ts
│   ├── useTheme.ts
│   ├── useLocale.ts
│   ├── usePagination.ts
│   ├── useFilters.ts
│   ├── usePermissions.ts
│   ├── useToast.ts
│   └── useMediaQuery.ts
│
├── layouts/
│   ├── PublicLayout.vue
│   ├── StudentLayout.vue
│   ├── WorkerLayout.vue
│   ├── ManagerLayout.vue
│   └── AdminLayout.vue
│
├── pages/
│   ├── public/
│   ├── auth/
│   ├── student/
│   ├── worker/
│   ├── manager/
│   └── admin/
│
├── stores/
│   ├── auth.ts
│   ├── cart.ts
│   ├── theme.ts
│   ├── locale.ts
│   ├── notification.ts
│   └── user.ts
│
├── services/
│   ├── api.ts
│   ├── auth.ts
│   ├── courses.ts
│   ├── webinars.ts
│   ├── ebooks.ts
│   ├── blogs.ts
│   ├── orders.ts
│   └── uploads.ts
│
├── types/
│   ├── auth.ts
│   ├── course.ts
│   ├── webinar.ts
│   ├── ebook.ts
│   ├── blog.ts
│   ├── order.ts
│   └── common.ts
│
├── i18n/
│   ├── bn/
│   └── en/
│
└── utils/
    ├── currency.ts
    ├── dates.ts
    ├── permissions.ts
    ├── slug.ts
    └── validation.ts
```

------------------------------------------------------------------------

# 7. Backend Architecture

Recommended Laravel structure:

``` text
app/
├── Actions/
│   ├── Courses/
│   ├── Enrollment/
│   ├── Orders/
│   ├── Payments/
│   ├── Webinars/
│   ├── Ebooks/
│   ├── Reviews/
│   ├── CMS/
│   └── Users/
│
├── Console/
├── Enums/
├── Events/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── Public/
│   │   │   ├── Auth/
│   │   │   ├── Student/
│   │   │   ├── Worker/
│   │   │   ├── Manager/
│   │   │   └── Admin/
│   │
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
│
├── Jobs/
├── Listeners/
├── Mail/
├── Models/
├── Notifications/
├── Policies/
├── Providers/
├── Rules/
├── Services/
└── Support/
```

------------------------------------------------------------------------

# 8. Domain-Driven Organization

The application should be mentally organized into domains.

``` text
Identity
 ├── Users
 ├── Roles
 └── Permissions

Education
 ├── Courses
 ├── Modules
 ├── Lessons
 ├── Batches
 ├── Instructors
 └── Enrollments

Commerce
 ├── Cart
 ├── Orders
 ├── Payments
 └── Refunds

Events
 ├── Webinars
 ├── Seminars
 └── Registrations

Digital Products
 └── Ebooks

Content
 ├── Blogs
 ├── FAQs
 ├── Homepage
 ├── Pages
 └── Organizations

Engagement
 ├── Reviews
 ├── Leads
 └── Notifications

Infrastructure
 ├── Media
 ├── SEO
 ├── Settings
 ├── Localization
 └── Audit Logs
```

------------------------------------------------------------------------

# 9. Authentication Architecture

Use Laravel Sanctum for first-party Vue SPA authentication.

Sanctum's SPA mode uses Laravel's cookie-based session authentication
rather than requiring API tokens for the application's own SPA. It also
integrates with CSRF protection. citeturn0search2

Flow:

``` text
Vue
 ↓
GET /sanctum/csrf-cookie
 ↓
POST /api/v1/auth/login
 ↓
Laravel Session Cookie
 ↓
GET /api/v1/auth/me
 ↓
Authenticated Vue State
```

Logout:

``` text
POST /api/v1/auth/logout
↓
Invalidate session
↓
Clear auth state
```

------------------------------------------------------------------------

# 10. Authentication Routes

``` text
GET    /sanctum/csrf-cookie

POST   /api/v1/auth/register
POST   /api/v1/auth/login
POST   /api/v1/auth/logout

GET    /api/v1/auth/me

POST   /api/v1/auth/forgot-password
POST   /api/v1/auth/reset-password

POST   /api/v1/auth/email/verification-notification
GET    /api/v1/auth/email/verify/{id}/{hash}

POST   /api/v1/auth/change-password
```

------------------------------------------------------------------------

# 11. User Model

Core fields:

``` text
id
uuid
name
email
phone
password
avatar_media_id
locale
theme
status
email_verified_at
last_login_at
created_at
updated_at
deleted_at
```

Do not store:

-   plaintext passwords;
-   raw payment secrets;
-   unnecessary sensitive data.

------------------------------------------------------------------------

# 12. Role & Permission Architecture

Do not rely exclusively on:

``` php
$user->role === 'admin'
```

Use explicit permissions.

Core tables:

``` text
roles
permissions
role_permissions
user_roles
```

Optional:

``` text
user_permissions
```

for direct exceptions.

------------------------------------------------------------------------

# 13. Roles

Initial roles:

``` text
student
worker
manager
admin
```

Roles are labels.

Permissions are the actual authorization mechanism.

------------------------------------------------------------------------

# 14. Permission Naming Convention

Use:

``` text
resource.action
```

Examples:

``` text
courses.view
courses.create
courses.update
courses.delete
courses.publish

webinars.view
webinars.create
webinars.update
webinars.delete
webinars.publish

blogs.view
blogs.create
blogs.update
blogs.delete
blogs.publish

ebooks.view
ebooks.create
ebooks.update
ebooks.delete
ebooks.publish

reviews.view
reviews.moderate
reviews.publish
reviews.delete
```

------------------------------------------------------------------------

# 15. Scoped Worker Authorization

Worker access must support assignment.

Example:

``` text
worker:
    blogs.update = true
    blogs.update_all = false
```

The worker can update only:

``` text
assigned_to_user_id = current_user_id
```

Authorization should therefore check:

``` text
Permission
+
Resource ownership/assignment
+
Resource status
```

------------------------------------------------------------------------

# 16. Authorization Pipeline

Every protected mutation:

``` text
Request
 ↓
Authentication
 ↓
Role/Permission Middleware
 ↓
Form Request Validation
 ↓
Policy Authorization
 ↓
Action / Service
 ↓
Database Transaction if required
 ↓
Audit Log
 ↓
Response
```

Frontend permissions are only for UX.

Backend policies remain authoritative.

------------------------------------------------------------------------

# 17. Laravel Policies

Create policies for:

``` text
CoursePolicy
WebinarPolicy
EbookPolicy
BlogPolicy
ReviewPolicy
PackagePolicy
BatchPolicy
UserPolicy
OrderPolicy
MediaPolicy
HomepagePolicy
SettingsPolicy
```

Authorization should use policies/gates at the resource boundary.

------------------------------------------------------------------------

# 18. Route Groups

Conceptual API route structure:

``` text
/api/v1/public/*
/api/v1/auth/*
/api/v1/student/*
/api/v1/worker/*
/api/v1/manager/*
/api/v1/admin/*
```

Example:

``` text
/api/v1/public/courses
/api/v1/public/courses/{slug}

/api/v1/student/courses
/api/v1/student/enrollments

/api/v1/manager/courses
/api/v1/manager/courses/{course}

/api/v1/admin/roles
/api/v1/admin/permissions
```

------------------------------------------------------------------------

# 19. Public API Rules

Public API can expose only:

``` text
published
active
public
non-sensitive
```

Never expose:

-   draft content;
-   private files;
-   internal notes;
-   staff data;
-   unpublished reviews;
-   payment secrets;
-   private webinar links.

------------------------------------------------------------------------

# 20. API Response Contract

Success:

``` json
{
  "success": true,
  "message": "Success",
  "data": {},
  "meta": {}
}
```

Validation:

``` json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": [
      "The title field is required."
    ]
  }
}
```

Authorization:

``` json
{
  "success": false,
  "message": "You are not authorized to perform this action."
}
```

------------------------------------------------------------------------

# 21. Database Architecture

Use:

``` text
MySQL 8.4
InnoDB
utf8mb4
```

InnoDB provides transactions, row-level locking, MVCC and foreign-key
support, making it appropriate for course capacity, enrollment and
commerce integrity. citeturn0search1

Foreign keys should be used for important relational integrity. MySQL
requires appropriate indexes for foreign-key relationships and supports
referential actions such as RESTRICT, CASCADE and SET NULL.
citeturn0search0

------------------------------------------------------------------------

# 22. Primary Keys

Recommended:

``` text
BIGINT UNSIGNED auto increment
```

plus optional public:

``` text
uuid
```

Use UUIDs for public-facing identifiers when exposing IDs externally is
undesirable.

Example:

``` text
id = 102
uuid = 550e8400-e29b-41d4-a716-446655440000
```

------------------------------------------------------------------------

# 23. Core Database Tables

## Identity

``` text
users
roles
permissions
role_permissions
user_roles
```

## Courses

``` text
courses
course_categories
course_tags
course_tag
course_modules
lessons
instructors
course_instructor
batches
enrollments
course_reviews
```

## Packages

``` text
packages
package_course
```

## Webinars

``` text
webinars
webinar_speakers
webinar_speaker
webinar_registrations
```

## Ebooks

``` text
ebooks
ebook_categories
ebook_authors
ebook_author
ebook_files
```

## Blogs

``` text
blogs
blog_categories
blog_tags
blog_tag
```

## CMS

``` text
homepage_sections
homepage_items
pages
menus
menu_items
organizations
faqs
```

## Commerce

``` text
carts
cart_items
orders
order_items
payments
refunds
```

## Engagement

``` text
reviews
leads
contact_inquiries
notifications
```

## Infrastructure

``` text
media
media_folders
seo_metadata
site_settings
audit_logs
```

------------------------------------------------------------------------

# 24. Course Schema

Conceptual:

``` text
courses
---------
id
uuid
category_id
title_bn
title_en
slug
short_description_bn
short_description_en
description_bn
description_en
course_type
level
language
duration_value
duration_unit
total_lessons
base_price
sale_price
currency
capacity
status
visibility
featured
published_at
created_by
updated_by
created_at
updated_at
deleted_at
```

Use integer minor units for money.

------------------------------------------------------------------------

# 25. Course Categories

``` text
course_categories
------------------
id
parent_id
name_bn
name_en
slug
description_bn
description_en
image_id
sort_order
status
created_at
updated_at
```

Self-referencing categories permit future nesting.

------------------------------------------------------------------------

# 26. Course Tags

``` text
course_tags
------------
id
name_bn
name_en
slug
```

Pivot:

``` text
course_tag
-----------
course_id
tag_id
```

------------------------------------------------------------------------

# 27. Course Modules

``` text
course_modules
--------------
id
course_id
title_bn
title_en
description_bn
description_en
sort_order
status
created_at
updated_at
```

Relationship:

``` text
Course 1 ─── N Modules
```

------------------------------------------------------------------------

# 28. Lessons

``` text
lessons
-------
id
module_id
title_bn
title_en
slug
type
description_bn
description_en
video_url
content
duration_seconds
is_preview
sort_order
status
published_at
created_at
updated_at
```

Future types:

``` text
video
text
pdf
external
quiz
assignment
```

------------------------------------------------------------------------

# 29. Instructors

``` text
instructors
-----------
id
user_id nullable
name_bn
name_en
slug
avatar_media_id
designation_bn
designation_en
short_bio_bn
short_bio_en
bio_bn
bio_en
experience_text_bn
experience_text_en
status
created_at
updated_at
```

Pivot:

``` text
course_instructor
-----------------
course_id
instructor_id
role
sort_order
```

------------------------------------------------------------------------

# 30. Batches

Because Emisha Academy limits student capacity, batches should be
first-class entities.

``` text
batches
-------
id
course_id
name_bn
name_en
start_at
end_at
capacity
enrolled_count
status
registration_open_at
registration_close_at
created_at
updated_at
```

Do not rely solely on `courses.capacity`.

A course can have different batch capacities and schedules.

------------------------------------------------------------------------

# 31. Enrollment

``` text
enrollments
-----------
id
uuid
student_id
course_id
batch_id
order_id
status
enrolled_at
started_at
completed_at
progress_percent
created_at
updated_at
```

Statuses:

``` text
pending
active
paused
completed
cancelled
expired
```

Unique business rule:

``` text
student + batch
```

should normally not create duplicate active enrollments.

------------------------------------------------------------------------

# 32. Capacity Model

Capacity should be calculated from:

``` text
batch.capacity
-
active/reserved enrollments
```

Do not rely on a frontend count.

------------------------------------------------------------------------

# 33. Concurrency-Safe Enrollment

Critical flow:

``` text
BEGIN TRANSACTION

SELECT batch
FOR UPDATE

check:
capacity > active_enrollment_count

create enrollment

create order association

COMMIT
```

InnoDB uses row-level locking and transaction mechanisms suitable for
this pattern. citeturn0search1turn0search9

The exact Laravel implementation should use the framework's database
transaction and locking APIs.

------------------------------------------------------------------------

# 34. Package Schema

``` text
packages
--------
id
title_bn
title_en
slug
description_bn
description_en
regular_price
package_price
currency
valid_from
valid_until
status
featured
created_by
updated_by
created_at
updated_at
```

Pivot:

``` text
package_course
--------------
package_id
course_id
sort_order
```

------------------------------------------------------------------------

# 35. Webinar Schema

``` text
webinars
--------
id
uuid
title_bn
title_en
slug
short_description_bn
short_description_en
description_bn
description_en
start_at
end_at
timezone
language
level
prerequisites_bn
prerequisites_en
format
location
meeting_url
registration_deadline
capacity
status
price
currency
featured
published_at
created_at
updated_at
```

Private `meeting_url` must never be returned by public API unless
explicitly intended.

------------------------------------------------------------------------

# 36. Webinar Registration

``` text
webinar_registrations
---------------------
id
uuid
webinar_id
student_id
order_id
status
registered_at
created_at
updated_at
```

Unique:

``` text
webinar_id + student_id
```

for active registration.

------------------------------------------------------------------------

# 37. Ebook Schema

``` text
ebooks
------
id
uuid
category_id
title_bn
title_en
slug
subtitle_bn
subtitle_en
author_id
version
page_count
language
description_bn
description_en
target_countries
cover_media_id
price
currency
status
published_at
created_at
updated_at
deleted_at
```

For target countries, a normalized relation is preferred if country
filtering becomes important.

------------------------------------------------------------------------

# 38. Ebook Files

``` text
ebook_files
-----------
id
ebook_id
media_id
format
file_size
checksum
version
is_active
created_at
updated_at
```

Private ebook files should not be stored as guessable public URLs.

------------------------------------------------------------------------

# 39. Blog Schema

``` text
blogs
-----
id
uuid
author_id
category_id
title_bn
title_en
slug
excerpt_bn
excerpt_en
content_bn
content_en
cover_media_id
status
published_at
scheduled_at
reading_time_minutes
created_by
updated_by
created_at
updated_at
deleted_at
```

------------------------------------------------------------------------

# 40. FAQ Schema

``` text
faqs
----
id
question_bn
question_en
answer_bn
answer_en
category
sort_order
status
created_at
updated_at
```

------------------------------------------------------------------------

# 41. Homepage Architecture

Do not store the entire homepage as one giant HTML blob.

Use structured sections.

``` text
homepage_sections
-----------------
id
type
key
title_bn
title_en
subtitle_bn
subtitle_en
sort_order
is_enabled
settings_json
created_at
updated_at
```

Section types:

``` text
hero
courses
ebooks
why_choose
packages
free_classes
reviews
faq
ecosystem
final_cta
```

------------------------------------------------------------------------

# 42. Homepage Items

For repeatable CMS content:

``` text
homepage_items
--------------
id
homepage_section_id
reference_type
reference_id
sort_order
settings_json
```

This allows sections to reference:

-   courses;
-   ebooks;
-   packages;
-   free classes;
-   reviews;
-   organizations.

------------------------------------------------------------------------

# 43. Organization / Ecosystem

``` text
organizations
-------------
id
name_bn
name_en
slug
description_bn
description_en
logo_media_id
cover_media_id
website_url
category
sort_order
status
created_at
updated_at
```

------------------------------------------------------------------------

# 44. Review Architecture

Use one review model for multiple review contexts.

``` text
reviews
-------
id
user_id
reviewable_type
reviewable_id
type
rating
title
content
media_id
video_url
status
is_verified
published_at
moderated_by
moderated_at
created_at
updated_at
```

Polymorphic review target:

``` text
Course
Webinar
```

if future requirements need it.

Review types:

``` text
text
video
image
```

------------------------------------------------------------------------

# 45. Review Verification

`is_verified = true` only when system evidence exists.

Example:

``` text
user_id
+
course_id
+
active/completed enrollment
```

Do not allow staff to arbitrarily label testimonials as verified without
an audit trail.

------------------------------------------------------------------------

# 46. Free Class Architecture

``` text
free_classes
------------
id
course_id nullable
instructor_id nullable
title_bn
title_en
slug
description_bn
description_en
thumbnail_media_id
video_url
duration_seconds
status
published_at
created_at
updated_at
```

------------------------------------------------------------------------

# 47. Media Architecture

``` text
media
-----
id
uuid
disk
path
original_name
mime_type
extension
size
width
height
alt_text_bn
alt_text_en
caption_bn
caption_en
visibility
uploaded_by
created_at
updated_at
```

Folders:

``` text
media_folders
-------------
id
parent_id
name
slug
```

------------------------------------------------------------------------

# 48. Private File Access

Private files:

``` text
course resources
paid ebooks
private lesson assets
certificates
private webinar materials
```

must use an authorization endpoint.

Example:

``` text
GET /api/v1/student/ebooks/{ebook}/download
```

Server verifies:

``` text
authenticated
+
owns ebook
+
access active
```

then streams or redirects to a short-lived signed URL.

------------------------------------------------------------------------

# 49. Commerce Architecture

Entities:

``` text
Cart
CartItem
Order
OrderItem
Payment
Refund
```

The architecture must separate:

``` text
Product price
Order price
Payment amount
```

An order should retain the historical purchase price even if the course
price changes later.

------------------------------------------------------------------------

# 50. Cart

``` text
carts
-----
id
user_id nullable
session_token nullable
currency
created_at
updated_at
```

``` text
cart_items
----------
id
cart_id
purchasable_type
purchasable_id
quantity
unit_price_snapshot
created_at
updated_at
```

------------------------------------------------------------------------

# 51. Order

``` text
orders
------
id
uuid
user_id
order_number
status
currency
subtotal
discount
tax
total
billing_snapshot_json
paid_at
cancelled_at
created_at
updated_at
```

Statuses:

``` text
pending
awaiting_payment
paid
failed
cancelled
refunded
partially_refunded
```

------------------------------------------------------------------------

# 52. Order Items

``` text
order_items
-----------
id
order_id
purchasable_type
purchasable_id
title_snapshot
quantity
unit_price
discount
total
metadata_json
created_at
updated_at
```

Store snapshots because product metadata can change after purchase.

------------------------------------------------------------------------

# 53. Payment

``` text
payments
--------
id
uuid
order_id
gateway
gateway_transaction_id
amount
currency
status
payload_hash
paid_at
failed_at
metadata_json
created_at
updated_at
```

Never trust payment success based solely on frontend redirect.

------------------------------------------------------------------------

# 54. Payment Gateway Interface

Use an abstraction:

``` php
interface PaymentGatewayInterface
{
    public function initiate(Order $order): PaymentInitiation;
    public function verify(Payment $payment): PaymentVerification;
    public function refund(Payment $payment, int $amount): RefundResult;
    public function handleCallback(array $payload): PaymentCallbackResult;
}
```

Potential implementations:

``` text
BkashGateway
NagadGateway
SSLCommerzGateway
StripeGateway
```

depending on actual business requirements.

------------------------------------------------------------------------

# 55. Payment Callback

Flow:

``` text
Gateway
 ↓
Laravel Callback
 ↓
Verify signature / transaction
 ↓
Find payment
 ↓
Validate amount/order
 ↓
DB Transaction
 ↓
Mark Payment
 ↓
Mark Order
 ↓
Create Enrollment / Access
 ↓
Dispatch Notification
```

Never activate enrollment from an unverified browser callback.

------------------------------------------------------------------------

# 56. Refund Architecture

``` text
refunds
-------
id
payment_id
order_id
amount
reason
gateway_refund_id
status
processed_by
processed_at
created_at
updated_at
```

------------------------------------------------------------------------

# 57. Course Enrollment After Payment

For paid course:

``` text
Order Paid
 ↓
Validate Course / Batch
 ↓
Reserve Capacity
 ↓
Create Enrollment
 ↓
Grant Access
 ↓
Send Confirmation
```

If capacity is no longer available:

-   payment should not silently create an impossible enrollment;
-   business-specific refund/hold policy must execute.

------------------------------------------------------------------------

# 58. Transaction Boundary

Critical operations requiring transactions:

``` text
Enrollment
Payment confirmation
Refund
Package purchase
Capacity reservation
Role/permission assignment
Bulk publishing
Bulk assignment
```

------------------------------------------------------------------------

# 59. Service Layer

Recommended services:

``` text
CourseService
BatchService
EnrollmentService
CapacityService
PackageService
WebinarService
EbookService
BlogService
ReviewService
HomepageService
MediaService
OrderService
PaymentService
RefundService
NotificationService
SearchService
SeoService
AuditService
LocalizationService
```

Services should not become giant god classes.

Prefer smaller Actions for single business operations.

------------------------------------------------------------------------

# 60. Action Classes

Examples:

``` text
CreateCourse
UpdateCourse
PublishCourse
ArchiveCourse

ReserveBatchSeat
EnrollStudent

CreateOrder
ConfirmPayment
RefundOrder

PublishBlog
RegisterWebinar

ApproveReview
RejectReview

UploadMedia
GenerateMediaVariants
```

------------------------------------------------------------------------

# 61. Form Requests

Every mutation should use Form Requests.

Examples:

``` text
StoreCourseRequest
UpdateCourseRequest
StoreWebinarRequest
StoreEbookRequest
StoreBlogRequest
StoreReviewRequest
StoreContactRequest
CheckoutRequest
UpdateProfileRequest
```

Validation must occur server-side even when Vue validates client-side.

------------------------------------------------------------------------

# 62. API Resources

Use Laravel API Resources to prevent accidental exposure of internal
fields.

Example:

``` text
CourseResource
CourseDetailResource
StudentCourseResource
AdminCourseResource
```

Public CourseResource must not include:

-   internal IDs;
-   audit fields;
-   internal notes;
-   unpublished fields.

------------------------------------------------------------------------

# 63. Pagination

All large public/admin collections must paginate.

Recommended API:

``` text
?page=1
&per_page=12
```

Maximum `per_page` should be enforced.

------------------------------------------------------------------------

# 64. Filtering Architecture

Courses:

``` text
q
category
level
language
min_price
max_price
duration
instructor
availability
sort
```

Webinars:

``` text
q
status
category
level
language
date_from
date_to
format
```

Blogs:

``` text
q
category
author
tag
date
```

Ebooks:

``` text
q
category
author
language
country
version
price
```

------------------------------------------------------------------------

# 65. Query Objects / Filters

Avoid controllers containing large conditional query logic.

Use dedicated query/filter classes.

Example:

``` text
CourseFilters
WebinarFilters
BlogFilters
EbookFilters
```

Concept:

``` php
CourseQuery::fromRequest($request)
    ->published()
    ->search(...)
    ->filter(...)
    ->sort(...)
    ->paginate();
```

------------------------------------------------------------------------

# 66. Search Architecture

V1:

``` text
MySQL indexed search
```

Future:

``` text
Meilisearch
Typesense
Algolia
OpenSearch
```

The frontend should not care which engine is used.

Expose one application-level search interface.

------------------------------------------------------------------------

# 67. Indexing Strategy

Index based on real query patterns.

Examples:

``` text
courses.slug UNIQUE
courses.status
courses.published_at
courses.category_id
courses.level
courses.price
courses.featured

batches.course_id
batches.start_at
batches.status

enrollments.student_id
enrollments.batch_id
enrollments.status

orders.user_id
orders.status
orders.created_at
```

Avoid creating an index on every column. MySQL notes that unnecessary
indexes consume space and increase insert/update/delete cost.
citeturn0search4turn0search7

------------------------------------------------------------------------

# 68. Composite Indexes

Use composite indexes for recurring multi-column queries.

Examples:

``` text
courses(status, published_at)
batches(course_id, status, start_at)
enrollments(batch_id, status)
orders(user_id, status, created_at)
reviews(reviewable_type, reviewable_id, status)
```

The exact indexes should be validated against production query plans.

------------------------------------------------------------------------

# 69. Foreign Key Strategy

Use foreign keys for:

-   user relationships;
-   course/category;
-   course/modules;
-   module/lessons;
-   batch/course;
-   enrollment/student;
-   enrollment/batch;
-   order/order items;
-   payment/order.

Use:

``` text
CASCADE
RESTRICT
SET NULL
```

intentionally based on business ownership.

For example:

``` text
course → modules
ON DELETE CASCADE
```

can be appropriate.

But:

``` text
user → orders
```

should normally not cascade-delete historical financial records.

------------------------------------------------------------------------

# 70. Soft Deletes

Use soft deletion where history matters:

``` text
users
courses
ebooks
blogs
instructors
reviews
```

Avoid deleting:

``` text
orders
payments
refunds
audit_logs
```

physically under ordinary admin operations.

------------------------------------------------------------------------

# 71. Localization Architecture

Recommended content structure:

``` text
title_bn
title_en

description_bn
description_en
```

For system UI:

``` text
lang/bn/*.json
lang/en/*.json
```

For future scale, translation tables can be introduced for entities that
require many languages.

------------------------------------------------------------------------

# 72. Locale Middleware

Determine locale using:

``` text
Authenticated user preference
↓
Stored cookie/localStorage
↓
Accept-Language
↓
Application default = bn
```

Backend responses should return localized content according to requested
locale.

------------------------------------------------------------------------

# 73. Theme Architecture

Theme is primarily client-side.

Store:

``` text
theme = dark | light
```

For authenticated users, optionally persist in:

``` text
users.theme
```

Guest:

``` text
localStorage
```

------------------------------------------------------------------------

# 74. SEO Architecture

Create a reusable SEO model/service.

``` text
seo_metadata
------------
id
seoable_type
seoable_id
title
description
canonical_url
og_title
og_description
og_image_id
robots
schema_json
```

Use a polymorphic relation.

------------------------------------------------------------------------

# 75. Slug Service

Every public content type should use a slug service.

Requirements:

-   normalization;
-   uniqueness;
-   reserved words;
-   collision handling;
-   redirect creation.

Reserved paths:

``` text
admin
manager
worker
student
api
login
register
courses
webinars
blogs
ebooks
```

must not become content slugs.

------------------------------------------------------------------------

# 76. Redirect Architecture

When a published slug changes:

``` text
url_redirects
-------------
id
from_path
to_path
status_code
created_at
```

Use 301 where appropriate.

------------------------------------------------------------------------

# 77. Navigation Architecture

Tables:

``` text
menus
menu_items
```

Fields:

``` text
menu_items
----------
id
menu_id
parent_id
label_bn
label_en
url
route_name
target
sort_order
is_active
visibility
```

Never permit arbitrary JavaScript URLs.

------------------------------------------------------------------------

# 78. Footer Architecture

Footer can be CMS-driven:

``` text
footer_groups
footer_links
```

or controlled through structured settings.

Footer content should be editable without modifying Vue code.

------------------------------------------------------------------------

# 79. Site Settings

``` text
site_settings
-------------
id
key
value
type
group
is_public
updated_by
updated_at
```

Examples:

``` text
academy.name
academy.logo
contact.phone
contact.email
contact.address
social.facebook
seo.default_title
business.currency
```

Never expose private settings through public API.

------------------------------------------------------------------------

# 80. Configuration vs Settings

Use `.env` / config for:

-   secrets;
-   infrastructure;
-   database;
-   mail;
-   payment keys.

Use database settings for:

-   business content;
-   contact details;
-   public preferences;
-   CMS configuration.

Never store secrets in `site_settings`.

------------------------------------------------------------------------

# 81. Media Processing Pipeline

Upload:

``` text
Vue
 ↓
Laravel upload endpoint
 ↓
Validate
 ↓
Store original
 ↓
Create media record
 ↓
Queue optimization
 ↓
Generate variants
 ↓
Return media resource
```

Variants:

``` text
thumbnail
card
medium
large
original
```

------------------------------------------------------------------------

# 82. Image Optimization

Use:

-   WebP/AVIF where supported;
-   responsive sizes;
-   lazy loading;
-   explicit dimensions;
-   optimized thumbnails.

Do not process large images synchronously if it causes request latency.

------------------------------------------------------------------------

# 83. Video Architecture

Do not upload very large public video files directly into the Laravel
application filesystem without an appropriate storage strategy.

Prefer:

``` text
Video Provider / Object Storage
```

with:

``` text
thumbnail
duration
provider
video_id
privacy
```

Private learning videos should use authorized playback or signed URLs
where supported.

------------------------------------------------------------------------

# 84. Queue Architecture

Use Redis-backed queues where available.

Jobs:

``` text
SendEmail
SendNotification
OptimizeImage
GenerateThumbnail
GenerateSitemap
PublishScheduledContent
SendWebinarReminder
GenerateInvoice
ProcessPaymentCallback
```

Queue workers must be monitored.

------------------------------------------------------------------------

# 85. Scheduler

Scheduled operations:

``` text
PublishScheduledBlogs
PublishScheduledCourses
SendWebinarReminders
ExpireOffers
GenerateSitemap
CleanTemporaryMedia
CleanupOldNotifications
ProcessPendingPayments
```

Use Laravel's scheduler.

------------------------------------------------------------------------

# 86. Notification Architecture

Notification model:

``` text
notifications
-------------
id
user_id
type
title
body
action_url
data_json
read_at
created_at
```

Channels:

``` text
database
mail
sms
whatsapp
```

depending on installed integrations.

------------------------------------------------------------------------

# 87. Notification Events

Examples:

``` text
UserRegistered
EnrollmentCreated
PaymentConfirmed
PaymentFailed
WebinarRegistered
WebinarReminderDue
ReviewApproved
CoursePublished
```

Listeners can dispatch notifications.

------------------------------------------------------------------------

# 88. Event-Driven Boundaries

Use events for side effects.

Example:

``` text
PaymentConfirmed
    ├── CreateEnrollment
    ├── SendConfirmationEmail
    ├── CreateNotification
    └── RecordAnalyticsEvent
```

The critical financial state change must remain transactional.

------------------------------------------------------------------------

# 89. Audit Architecture

``` text
audit_logs
----------
id
actor_user_id
action
auditable_type
auditable_id
before_json
after_json
ip_address
user_agent
request_id
created_at
```

Actions:

``` text
created
updated
deleted
published
unpublished
approved
rejected
assigned
permission_changed
login
logout
payment_updated
```

------------------------------------------------------------------------

# 90. Audit Requirements

Audit:

-   role changes;
-   permission changes;
-   user status changes;
-   course publishing;
-   price changes;
-   capacity changes;
-   payment status changes;
-   refund;
-   content deletion;
-   review moderation;
-   system setting changes.

------------------------------------------------------------------------

# 91. Security Architecture

Use an OWASP ASVS-aligned baseline for application security
verification.

Core controls:

``` text
HTTPS
CSRF
XSS protection
SQL injection prevention
authorization
authentication
rate limiting
secure cookies
password hashing
file validation
private file access
audit logging
secret management
security headers
```

------------------------------------------------------------------------

# 92. Authentication Security

Staff:

-   strong password policy;
-   email verification;
-   optional/required 2FA;
-   session timeout;
-   suspicious login monitoring.

Admin should have the strongest authentication policy.

------------------------------------------------------------------------

# 93. Rate Limiting

Apply rate limits to:

``` text
login
register
password reset
contact form
review submission
public search
API
```

Sensitive endpoints should have stricter limits.

------------------------------------------------------------------------

# 94. CSRF

First-party Vue SPA authentication should use Sanctum's CSRF/session
model.

Do not disable CSRF protections merely because the frontend is Vue.

Sanctum's SPA documentation specifically describes initializing
`/sanctum/csrf-cookie` and using the CSRF token for authenticated SPA
requests. citeturn0search2

------------------------------------------------------------------------

# 95. XSS

Never render unsanitized HTML from:

-   blog editor;
-   course descriptions;
-   webinar descriptions;
-   review content;
-   user profiles.

Rich-text HTML must be sanitized.

------------------------------------------------------------------------

# 96. File Upload Security

Validate:

``` text
MIME
extension
size
dimensions
file signature where appropriate
```

Reject:

``` text
.php
.phtml
.exe
.sh
.js
```

and any other dangerous types unless a specific trusted workflow
requires them.

------------------------------------------------------------------------

# 97. Authorization Against IDOR

Never assume:

``` text
/user/123
```

means the current user can access user 123.

Every private resource must verify:

``` text
authenticated user
+
permission
+
ownership/access relationship
```

------------------------------------------------------------------------

# 98. Student Data Boundary

Student can access:

``` text
own profile
own enrollments
own orders
own reviews
own purchased ebooks
own webinar registrations
own notifications
```

Student cannot access another student's:

-   profile;
-   order;
-   enrollment;
-   review management data;
-   private resources.

------------------------------------------------------------------------

# 99. Worker Data Boundary

Worker sees only:

``` text
permitted modules
+
assigned resources
+
allowed student/support information
```

Workers must not automatically access:

-   payment configuration;
-   roles;
-   permissions;
-   security settings;
-   complete audit logs.

------------------------------------------------------------------------

# 100. Manager Data Boundary

Manager may access:

``` text
academy operational data
```

but not:

``` text
root security configuration
administrator privilege management
secret credentials
```

unless explicitly granted by a future policy.

------------------------------------------------------------------------

# 101. Admin Boundary

Admin has highest application privileges.

However, even Admin should not receive:

-   raw password hashes through APIs;
-   secret payment keys in UI;
-   plaintext environment secrets.

"Admin" means application governance, not unrestricted infrastructure
shell access.

------------------------------------------------------------------------

# 102. Payment Security

Payment secrets live only in environment/secret management.

Never:

``` text
store gateway secret in database
send secret to Vue
log secret
return gateway secret in API
```

Verify:

``` text
transaction ID
order ID
amount
currency
gateway signature
```

where supported.

------------------------------------------------------------------------

# 103. Enrollment Integrity

Enrollment should only become active after business rules succeed.

State machine:

``` text
pending
 ↓
active
 ↓
completed

or

pending → cancelled
pending → failed
active → paused
```

------------------------------------------------------------------------

# 104. Course Progress

Create:

``` text
lesson_progress
---------------
id
student_id
lesson_id
enrollment_id
completed_at
progress_seconds
last_position
created_at
updated_at
```

Unique:

``` text
student_id + lesson_id + enrollment_id
```

This supports resumable learning.

------------------------------------------------------------------------

# 105. Course Access Policy

Student can access lesson if:

``` text
authenticated
AND
active enrollment
AND
lesson belongs to enrolled course
AND
lesson is published
AND
lesson release rules pass
```

Preview lessons can be public.

------------------------------------------------------------------------

# 106. Future Drip Content

Architecture can later support:

``` text
lesson.release_at
lesson.release_offset_days
```

without redesigning course structure.

------------------------------------------------------------------------

# 107. Certificates --- Future Ready

Potential future entities:

``` text
certificates
certificate_templates
certificate_verifications
```

Public verification route:

``` text
/verify/certificate/{code}
```

Do not implement until business requirements are finalized.

------------------------------------------------------------------------

# 108. Quiz --- Future Ready

Potential:

``` text
quizzes
questions
question_options
quiz_attempts
quiz_answers
```

Do not couple initial course lesson architecture to quiz-specific UI.

------------------------------------------------------------------------

# 109. Assignment --- Future Ready

Potential:

``` text
assignments
assignment_submissions
submission_files
grades
```

Student/Instructor authorization must remain separate from general
course access.

------------------------------------------------------------------------

# 110. Recommendation Architecture

V1 deterministic:

``` text
same category
same tag
same level
same instructor
manual selection
```

Service:

``` text
RecommendationService
```

Future provider:

``` text
AIRecommendationProvider
```

No AI dependency should be required for core site functionality.

------------------------------------------------------------------------

# 111. Analytics Architecture

Frontend emits events:

``` text
course_viewed
course_cta_clicked
add_to_cart
checkout_started
purchase_completed
free_class_started
webinar_viewed
webinar_registered
ebook_viewed
ebook_downloaded
contact_submitted
```

Backend should record critical business events independently of browser
analytics.

------------------------------------------------------------------------

# 112. SEO Architecture

Generate:

``` text
sitemap.xml
robots.txt
canonical
Open Graph
Twitter/X metadata
JSON-LD
```

Routes:

``` text
/
 /courses
 /courses/{slug}
 /webinars
 /webinars/{slug}
 /blogs
 /blogs/{slug}
 /ebooks
 /ebooks/{slug}
 /about
 /contact
```

Exclude:

``` text
/dashboard/*
/admin/*
/manager/*
/worker/*
/student/*
/cart
/checkout
```

------------------------------------------------------------------------

# 113. Structured Data

Use appropriate schemas:

``` text
Organization
Course
Article
Event
FAQPage
```

Only generate structured data that accurately represents visible page
information.

------------------------------------------------------------------------

# 114. Caching Architecture

Use Redis where appropriate.

Cache:

``` text
homepage sections
navigation
footer
categories
published FAQs
featured courses
featured ebooks
public settings
```

Do not cache user-specific/private responses globally.

------------------------------------------------------------------------

# 115. Cache Invalidation

Invalidate on:

``` text
course published
course updated
course archived
homepage changed
FAQ updated
footer changed
navigation changed
```

Avoid manually clearing the entire cache for every content update.

------------------------------------------------------------------------

# 116. HTTP Caching

Public pages/API responses may use appropriate cache headers.

Authenticated responses should generally avoid shared public caching.

------------------------------------------------------------------------

# 117. Database Transactions

Use transaction boundaries for:

``` text
order creation
payment confirmation
enrollment
seat reservation
refund
package enrollment
bulk permission update
```

Example conceptual:

``` php
DB::transaction(function () {
    // lock capacity
    // validate
    // create enrollment
    // create related records
});
```

------------------------------------------------------------------------

# 118. Queue vs Transaction

Do not put essential state changes only in a queue.

Bad:

``` text
Payment callback
→ queue enrollment
→ return success
```

If the queue fails, payment may be successful but enrollment absent.

Better:

``` text
Payment verification
→ transaction
→ payment state
→ order state
→ enrollment/access
→ commit
→ queue notifications
```

------------------------------------------------------------------------

# 119. Database Integrity

Use:

-   foreign keys;
-   unique constraints;
-   check constraints where supported/appropriate;
-   transactions;
-   application validation.

MySQL/InnoDB supports foreign-key integrity and transactional behavior
appropriate for this architecture. citeturn0search0turn0search1

------------------------------------------------------------------------

# 120. Data Retention

Define retention for:

``` text
audit logs
notifications
contact inquiries
failed payment payloads
temporary media
sessions
```

Do not delete financial/audit records casually.

------------------------------------------------------------------------

# 121. API Versioning

Start with:

``` text
/api/v1
```

Future:

``` text
/api/v2
```

Avoid breaking existing clients.

------------------------------------------------------------------------

# 122. API Documentation

Use OpenAPI/Swagger or equivalent.

Document:

-   endpoint;
-   authentication;
-   parameters;
-   request;
-   response;
-   errors;
-   permissions.

------------------------------------------------------------------------

# 123. Error Architecture

Define application exceptions:

``` text
CourseFullException
EnrollmentNotAllowedException
PaymentVerificationException
UnauthorizedActionException
ResourceNotPublishedException
PrivateFileAccessException
```

Map to appropriate HTTP status codes.

------------------------------------------------------------------------

# 124. HTTP Status Conventions

``` text
200 OK
201 Created
204 No Content
400 Bad Request
401 Unauthenticated
403 Forbidden
404 Not Found
409 Conflict
422 Validation Error
429 Too Many Requests
500 Internal Server Error
```

Capacity conflict should generally use `409 Conflict`.

------------------------------------------------------------------------

# 125. Logging

Application logs:

``` text
storage/logs
```

Capture:

-   exceptions;
-   failed jobs;
-   payment verification errors;
-   authentication anomalies;
-   critical business errors.

Never log:

-   passwords;
-   tokens;
-   payment secrets;
-   raw sensitive credentials.

------------------------------------------------------------------------

# 126. Correlation ID

Each request should have a request ID.

Example:

``` text
X-Request-ID
```

Include it in logs and, when appropriate, error responses.

------------------------------------------------------------------------

# 127. Monitoring

Monitor:

``` text
CPU
RAM
disk
MySQL
Redis
queue
HTTP errors
slow requests
payment failures
job failures
storage
```

------------------------------------------------------------------------

# 128. Health Checks

Expose protected/controlled health endpoints.

Example:

``` text
/health
```

Check:

``` text
application
database
cache
queue
storage
```

Do not expose sensitive diagnostic details publicly.

------------------------------------------------------------------------

# 129. Testing Architecture

## Unit

Test:

``` text
PricingService
CapacityService
PermissionService
SlugService
RecommendationService
```

## Feature

Test:

``` text
authentication
course CRUD
webinar CRUD
ebook CRUD
blog CRUD
RBAC
enrollment
payment
review moderation
```

## E2E

Test:

``` text
Visitor → Course → Checkout → Enrollment
Student → Dashboard → Lesson
Worker → Assigned Content → Update
Manager → Publish Course
Admin → Role → Permission
```

------------------------------------------------------------------------

# 130. Authorization Tests

Must test negative cases.

Examples:

``` text
Student → Admin endpoint = 403
Worker → Role management = 403
Manager → Permission architecture = 403
Worker → Unassigned course edit = 403
Student → Other student's order = 403/404
Guest → Private ebook = 401
```

------------------------------------------------------------------------

# 131. Concurrency Tests

Explicitly test:

``` text
25-seat batch
26 simultaneous enrollment attempts
```

Expected:

``` text
maximum active enrollment = 25
```

No duplicate or over-capacity enrollment.

------------------------------------------------------------------------

# 132. Database Testing

Use factories and seeders for:

``` text
users
roles
courses
batches
lessons
orders
payments
reviews
blogs
ebooks
webinars
```

Create realistic test scenarios.

------------------------------------------------------------------------

# 133. CI/CD

Pipeline:

``` text
Git Push
 ↓
Install dependencies
 ↓
Lint
 ↓
Type check
 ↓
Unit tests
 ↓
Feature tests
 ↓
Build Vue
 ↓
Security checks
 ↓
Deploy staging
 ↓
Smoke tests
 ↓
Production approval
 ↓
Deploy production
```

------------------------------------------------------------------------

# 134. Environment Variables

Example:

``` text
APP_ENV
APP_KEY
APP_URL

DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

REDIS_HOST
REDIS_PASSWORD
REDIS_PORT

MAIL_MAILER
MAIL_HOST
MAIL_USERNAME
MAIL_PASSWORD

PAYMENT_GATEWAY_KEY
PAYMENT_GATEWAY_SECRET

FILESYSTEM_DISK
STORAGE_BUCKET
```

Secrets must be injected through secure environment/secret management.

------------------------------------------------------------------------

# 135. Docker Architecture

Recommended containers:

``` text
nginx
php-fpm
queue
scheduler
mysql
redis
node/build
```

Development may use:

``` text
Laravel Sail
```

or a custom Docker Compose setup.

Production containers should be immutable where practical.

------------------------------------------------------------------------

# 136. Production Network

Conceptual:

``` text
Internet
 ↓
CDN / WAF
 ↓
Nginx
 ↓
PHP-FPM
 ↓
MySQL / Redis
```

Database and Redis should not be publicly exposed.

------------------------------------------------------------------------

# 137. Storage Architecture

Use:

``` text
public storage
private storage
```

Public:

``` text
logos
public course thumbnails
blog covers
public gallery
```

Private:

``` text
paid ebooks
course resources
student files
private certificates
```

------------------------------------------------------------------------

# 138. Backup Architecture

Database:

``` text
daily backup
weekly retention
monthly retention
off-site copy
restore testing
```

Media:

``` text
replication
versioning
backup
```

------------------------------------------------------------------------

# 139. Deployment Migration Safety

Before production migration:

``` text
backup
↓
migration dry-run/staging
↓
verify
↓
production migration
↓
smoke test
```

Avoid destructive migrations without an explicit migration plan.

------------------------------------------------------------------------

# 140. Database Migration Rules

Migration files should be:

-   small;
-   reversible where practical;
-   ordered;
-   production-safe.

Do not manually edit production database structure.

------------------------------------------------------------------------

# 141. Seed Data

Seed:

``` text
roles
permissions
default admin
categories
system settings
```

Default admin credentials must never be committed.

Production admin should be created through a secure setup process.

------------------------------------------------------------------------

# 142. Authorization Middleware

Possible middleware:

``` text
auth:sanctum
verified
active
role
permission
```

But middleware must not replace resource Policies.

Use both:

``` text
coarse route protection
+
fine-grained policy authorization
```

------------------------------------------------------------------------

# 143. Permission Cache

Permissions can be cached for performance.

Invalidate permission cache when:

``` text
role updated
permission updated
user role changed
direct permission changed
```

Do not allow stale permissions to persist indefinitely.

------------------------------------------------------------------------

# 144. User Status Middleware

Before protected operations:

``` text
active user
```

Suspended/blocked users should be denied.

------------------------------------------------------------------------

# 145. Content Status State Machine

Recommended:

``` text
draft
 ↓
review
 ↓
approved
 ↓
published
 ↓
archived
```

Not every content type must use every state.

------------------------------------------------------------------------

# 146. Publishing Authorization

Worker:

``` text
draft/edit
```

Manager:

``` text
approve/publish
```

Admin:

``` text
full override
```

unless explicit permissions modify this.

------------------------------------------------------------------------

# 147. Staff Assignment

Assignment tables can be generalized:

``` text
content_assignments
-------------------
id
user_id
assignable_type
assignable_id
role
assigned_by
created_at
```

This allows:

``` text
Worker A → Blog 12
Worker B → Course 4
Worker C → Webinar 8
```

------------------------------------------------------------------------

# 148. Manager Workload

Manager dashboard can show:

``` text
Worker
Assigned
Pending
Overdue
Completed
```

This makes the platform operationally useful.

------------------------------------------------------------------------

# 149. CMS Preview Architecture

Unpublished content preview:

``` text
GET /preview/{type}/{uuid}?token=...
```

Token must:

-   expire;
-   be scoped;
-   not expose editing permissions;
-   be revocable if required.

------------------------------------------------------------------------

# 150. Content Versioning --- Future

For critical content:

``` text
course_versions
blog_versions
page_versions
```

V1 can use:

``` text
updated_by
updated_at
```

and audit logs.

------------------------------------------------------------------------

# 151. Localization Fallback

If Bangla exists:

``` text
show Bangla
```

If Bangla missing and English exists:

``` text
fallback English
```

But dashboard should warn content managers when a required translation
is missing.

------------------------------------------------------------------------

# 152. Translation Completion Indicator

CMS editor:

``` text
বাংলা ✓
English ⚠ Missing
```

This prevents incomplete bilingual publishing.

------------------------------------------------------------------------

# 153. Public Language URL Strategy

Two viable options:

### Option A

Same URL with locale preference:

``` text
/courses
```

### Option B

Locale-prefixed SEO URLs:

``` text
/bn/courses
/en/courses
```

For a Bangla-first SEO platform, locale-prefixed URLs can become useful
if bilingual SEO becomes a major acquisition channel.

The final decision should be made before production SEO implementation.

------------------------------------------------------------------------

# 154. Recommended Initial Locale Strategy

For V1:

``` text
default = bn
secondary = en
```

Keep route generation abstract enough that `/en` prefixes can be added
later without rewriting content models.

------------------------------------------------------------------------

# 155. Search Language Strategy

Search must accept:

``` text
Bangla
English
mixed terms
```

Examples:

``` text
গ্রাফিক ডিজাইন
Graphic Design
Laravel
ওয়েব ডেভেলপমেন্ট
```

------------------------------------------------------------------------

# 156. Dashboard Search

Admin global search can later search:

``` text
students
courses
orders
blogs
webinars
ebooks
leads
```

V1 may keep module-specific search.

------------------------------------------------------------------------

# 157. Public Recommendation Query

Recommended courses:

``` text
same category
+
matching tags
+
same level
-
current course
```

Limit:

``` text
4–8
```

Manual override takes priority.

------------------------------------------------------------------------

# 158. Course Capacity Display API

Course detail should return a computed object:

``` json
{
  "capacity": 25,
  "enrolled": 21,
  "remaining": 4,
  "status": "available"
}
```

Do not calculate remaining seats in Vue.

------------------------------------------------------------------------

# 159. Pricing Response

Return:

``` json
{
  "currency": "BDT",
  "base_price": 10000,
  "sale_price": 8500,
  "discount_amount": 1500,
  "discount_percent": 15
}
```

Server computes all monetary values.

------------------------------------------------------------------------

# 160. Dashboard API Aggregation

Avoid making the dashboard call 20 endpoints on first load.

Use aggregated endpoints:

``` text
/api/v1/student/dashboard
/api/v1/manager/dashboard
/api/v1/admin/dashboard
```

Each returns a carefully scoped dashboard payload.

------------------------------------------------------------------------

# 161. API Performance

Avoid N+1 queries.

Use:

``` text
eager loading
select specific columns
pagination
aggregate queries
```

Example:

``` text
Course
with category
with instructors
with active batch
```

rather than querying each relation individually.

------------------------------------------------------------------------

# 162. Database Query Safety

Use Eloquent/query builder parameter binding.

Never concatenate user input into SQL.

------------------------------------------------------------------------

# 163. Cache Key Strategy

Use namespaced keys:

``` text
academy:homepage:v1
academy:courses:featured:v1
academy:course:{id}:detail:v1
academy:faq:published:v1
```

Invalidate specific keys.

------------------------------------------------------------------------

# 164. Session Strategy

Use secure cookies:

``` text
HttpOnly
Secure
SameSite
```

Production must run over HTTPS.

------------------------------------------------------------------------

# 165. CORS

If Vue and Laravel use the same top-level domain, keep CORS narrow.

Allowed origins:

``` text
production frontend
staging frontend
development frontend
```

Do not use:

``` text
*
```

for authenticated APIs.

------------------------------------------------------------------------

# 166. API Security Headers

Production should consider:

``` text
Content-Security-Policy
X-Content-Type-Options
Referrer-Policy
Permissions-Policy
Strict-Transport-Security
```

Exact CSP must be tested against Vue, analytics, payment and media
providers.

------------------------------------------------------------------------

# 167. Admin Session Security

Admin session should support:

-   shorter timeout;
-   optional re-authentication for sensitive settings;
-   2FA;
-   audit;
-   session revocation.

------------------------------------------------------------------------

# 168. Sensitive Actions

Require confirmation/re-authentication for:

``` text
change role
change permissions
change payment settings
delete user
refund payment
delete course
```

------------------------------------------------------------------------

# 169. Audit Before Mutation

For critical mutations:

``` text
capture before
→ mutate
→ capture after
→ audit
```

Audit should occur only after successful state change, preferably in the
same transaction where consistency is required.

------------------------------------------------------------------------

# 170. Public Contact Form

Flow:

``` text
Visitor
 ↓
POST /api/v1/public/contact
 ↓
Rate limit
 ↓
Validate
 ↓
Spam protection
 ↓
Create inquiry
 ↓
Queue notification
 ↓
Return success
```

------------------------------------------------------------------------

# 171. Lead Conversion

Lead can be converted to:

``` text
Student
```

if the user registers/enrolls.

Maintain source attribution:

``` text
lead.source
```

such as:

``` text
homepage
course_page
free_class
webinar
facebook
google
direct
```

------------------------------------------------------------------------

# 172. Analytics Attribution

Optional:

``` text
utm_source
utm_medium
utm_campaign
utm_content
utm_term
```

Store on lead/order where useful.

------------------------------------------------------------------------

# 173. Email Architecture

Use Laravel Mail/Notifications.

Templates:

``` text
emails/
notifications/
```

Localization:

``` text
bn
en
```

Queue all non-critical outbound mail.

------------------------------------------------------------------------

# 174. Webinar Reminder Architecture

Scheduler identifies:

``` text
webinars starting in:
24h
1h
15m
```

Then dispatches notifications to eligible registered users.

Avoid duplicate reminders using:

``` text
notification key / sent marker
```

------------------------------------------------------------------------

# 175. Course Update Notification

When a lesson is published:

``` text
LessonPublished
 ↓
Find active enrollments
 ↓
Queue notification
```

Do not send one synchronous request per student.

------------------------------------------------------------------------

# 176. Data Export

Admin may eventually export:

``` text
students
orders
enrollments
leads
reviews
```

Exports should be:

-   permission protected;
-   queued for large datasets;
-   audited.

------------------------------------------------------------------------

# 177. Import

Future import support:

``` text
students CSV
courses CSV
```

must validate every row.

Never allow imported rows to bypass business rules.

------------------------------------------------------------------------

# 178. Rate Limit Categories

Suggested logical buckets:

``` text
auth
public
search
student
staff
admin
payment
upload
```

------------------------------------------------------------------------

# 179. API Idempotency

Payment callbacks and critical creation endpoints should support
idempotency where appropriate.

Examples:

``` text
payment callback
order creation
webinar registration
enrollment
```

------------------------------------------------------------------------

# 180. Idempotent Payment Handling

If the same gateway callback arrives twice:

``` text
first → process
second → detect already processed
       → return safe success
```

Never create duplicate enrollments.

------------------------------------------------------------------------

# 181. Order Idempotency

Checkout should prevent:

``` text
double-click
network retry
duplicate order
```

from creating unintended duplicate orders.

------------------------------------------------------------------------

# 182. Webhook Architecture

Payment webhooks:

``` text
POST /api/v1/webhooks/{provider}
```

Requirements:

-   verify signature;
-   rate limit;
-   log safe metadata;
-   idempotency;
-   transaction;
-   fast response;
-   queue non-critical side effects.

------------------------------------------------------------------------

# 183. Admin Content Deletion

Default behavior:

``` text
Archive
```

rather than hard delete.

Hard deletion:

-   admin only;
-   explicit confirmation;
-   dependency check;
-   audit.

------------------------------------------------------------------------

# 184. Dependency Checks

Before deleting:

``` text
Course
```

check:

``` text
enrollments
orders
lessons
reviews
batches
```

If historical dependencies exist:

``` text
archive
```

instead.

------------------------------------------------------------------------

# 185. Public Content Cache Strategy

Recommended:

``` text
CDN
+
Redis
+
HTTP cache
```

for highly public pages.

Authenticated pages should bypass shared caching.

------------------------------------------------------------------------

# 186. Frontend Error Boundary

Vue application should have global error handling.

Handle:

-   route errors;
-   API errors;
-   component exceptions;
-   network failure.

Provide user-friendly recovery.

------------------------------------------------------------------------

# 187. Offline / Network UX

If request fails:

``` text
Network unavailable.
Please check your connection and try again.
```

Forms should preserve entered values where safe.

------------------------------------------------------------------------

# 188. Form Draft Protection

For long admin forms:

-   warn before leaving;
-   optionally autosave draft;
-   restore unsaved content where practical.

------------------------------------------------------------------------

# 189. Admin Editor Autosave --- Future

Could support:

``` text
Draft saved 10:32 AM
```

especially for:

-   blog;
-   course;
-   ebook;
-   webinar.

------------------------------------------------------------------------

# 190. Content Sanitization

Allowed rich text:

``` text
p
h2
h3
strong
em
ul
ol
li
a
blockquote
img
```

depending on editor.

Strip:

``` text
script
iframe
style
event handlers
```

unless a controlled embed system explicitly permits them.

------------------------------------------------------------------------

# 191. Embed Architecture

For YouTube/Vimeo or other trusted media:

Store:

``` text
provider
video_id
```

rather than raw arbitrary HTML.

Render through controlled component.

------------------------------------------------------------------------

# 192. SEO-Friendly Rendering Strategy

Public content should be crawlable.

Recommended architecture should ensure public content can be rendered in
an SEO-friendly way.

If using a fully client-rendered Vue SPA, verify:

-   crawlability;
-   metadata generation;
-   initial HTML;
-   social preview rendering.

If SEO performance becomes a critical acquisition channel, consider an
SSR/SSG-compatible Vue layer while retaining Laravel as backend.

------------------------------------------------------------------------

# 193. Recommended Public Rendering Direction

Because Emisha Academy is content-heavy, the architecture should keep
public pages **SSR/SEO-ready** even if V1 launches with a Laravel + Vue
SPA structure.

The exact rendering mode should be finalized during implementation based
on hosting and SEO requirements.

------------------------------------------------------------------------

# 194. Dashboard Rendering

Dashboards do not require public SEO.

They can be fully client-rendered.

------------------------------------------------------------------------

# 195. Public vs Private Architecture

``` text
PUBLIC
├── SEO
├── caching
├── published content
└── anonymous access

PRIVATE
├── authentication
├── authorization
├── no-index
├── personalized data
└── strict caching
```

------------------------------------------------------------------------

# 196. API Domain Separation

Public:

``` text
PublicCourseController
PublicWebinarController
PublicBlogController
PublicEbookController
```

Admin:

``` text
AdminCourseController
AdminWebinarController
AdminBlogController
AdminEbookController
```

Do not create one enormous controller with dozens of permission
branches.

------------------------------------------------------------------------

# 197. Controller Rule

Controllers should be thin.

Good:

``` text
validate
authorize
call action
return resource
```

Bad:

``` text
controller contains pricing
capacity
payment
notifications
database loops
```

------------------------------------------------------------------------

# 198. Model Rule

Eloquent Models should contain:

-   relationships;
-   casts;
-   scopes;
-   small domain helpers.

Avoid putting the entire business process inside Models.

------------------------------------------------------------------------

# 199. Service Rule

Services orchestrate business operations.

Actions represent focused operations.

This makes testing easier.

------------------------------------------------------------------------

# 200. Event Rule

Events represent meaningful domain changes.

Example:

``` text
CoursePublished
EnrollmentCreated
PaymentConfirmed
ReviewApproved
```

Listeners handle side effects.

------------------------------------------------------------------------

# 201. Queue Rule

Queues are for:

-   external calls;
-   email;
-   notifications;
-   media processing;
-   reports;
-   large exports.

Do not queue a critical database state change without a reliable
transaction/outbox strategy.

------------------------------------------------------------------------

# 202. Outbox Pattern --- Future

If event reliability becomes important:

``` text
outbox_events
```

can guarantee that committed domain events are eventually dispatched.

Not required for initial V1.

------------------------------------------------------------------------

# 203. Database Naming Convention

Use:

``` text
snake_case
plural table names
```

Examples:

``` text
course_categories
course_modules
webinar_registrations
```

------------------------------------------------------------------------

# 204. Model Naming

Use singular StudlyCase:

``` text
Course
CourseCategory
CourseModule
WebinarRegistration
```

------------------------------------------------------------------------

# 205. Enum Architecture

Use PHP enums for finite state.

Examples:

``` text
UserStatus
CourseStatus
EnrollmentStatus
OrderStatus
PaymentStatus
ReviewStatus
ContentStatus
WebinarStatus
```

This prevents arbitrary string values.

------------------------------------------------------------------------

# 206. JSON Fields

Use JSON only where flexibility is appropriate.

Good candidates:

``` text
settings_json
metadata_json
billing_snapshot_json
analytics_data
```

Do not use JSON for core relational data that needs:

-   filtering;
-   joins;
-   integrity;
-   reporting.

------------------------------------------------------------------------

# 207. Country Architecture

For ebook target countries and future learner geography:

``` text
countries
```

can become a reference table.

Potential:

``` text
id
code
name_en
name_bn
status
```

Pivot:

``` text
ebook_country
```

------------------------------------------------------------------------

# 208. Language Architecture

Potential:

``` text
languages
```

but V1 can keep:

``` text
bn
en
```

as controlled enum/config values.

------------------------------------------------------------------------

# 209. Course Type

Use controlled enum:

``` text
live
recorded
hybrid
workshop
bootcamp
```

Future types can be added.

------------------------------------------------------------------------

# 210. Webinar Format

``` text
online
offline
hybrid
```

------------------------------------------------------------------------

# 211. Instructor Experience

Do not store only a number.

Allow:

``` text
experience_text_bn
experience_text_en
```

because staff may need descriptive experience information.

------------------------------------------------------------------------

# 212. Course Duration

Use:

``` text
duration_value
duration_unit
```

Examples:

``` text
8 weeks
24 classes
3 months
```

Total lessons should remain a separate value.

------------------------------------------------------------------------

# 213. Course Level

Controlled:

``` text
beginner
intermediate
advanced
all_levels
```

Public labels localized.

------------------------------------------------------------------------

# 214. Course Language

Controlled:

``` text
bn
en
bn_en
```

------------------------------------------------------------------------

# 215. Price Precision

Store monetary amounts in integer minor units where practical.

Example:

``` text
850000
```

for ৳8,500.00 in a two-decimal representation.

Never use floating point for financial calculations.

------------------------------------------------------------------------

# 216. Currency Architecture

Default:

``` text
BDT
```

But all monetary records should store:

``` text
amount
currency
```

so future currencies can be introduced.

------------------------------------------------------------------------

# 217. Order Snapshot Principle

When an order is created, snapshot:

``` text
title
price
discount
currency
product metadata
```

This preserves historical accuracy.

------------------------------------------------------------------------

# 218. Enrollment Snapshot Principle

Enrollment can retain:

``` text
course_title_snapshot
batch_name_snapshot
instructor_snapshot
```

where historical reporting needs it.

------------------------------------------------------------------------

# 219. Student Dashboard Data Security

Dashboard API should never return:

``` text
other users
internal notes
staff permissions
raw payment payload
audit data
```

unless explicitly required.

------------------------------------------------------------------------

# 220. Admin Dashboard Aggregations

Use SQL aggregation rather than fetching every row.

Example:

``` text
COUNT students
COUNT active enrollments
SUM paid orders
COUNT pending reviews
```

------------------------------------------------------------------------

# 221. Analytics Caching

Dashboard analytics can cache:

``` text
5 minutes
15 minutes
```

depending on operational needs.

Transactional pages must use current state.

------------------------------------------------------------------------

# 222. Real-Time Requirements

V1 does not require WebSockets.

Optional future:

-   live notification;
-   live seat availability;
-   live class;
-   admin activity feed.

Laravel broadcasting can be introduced later.

------------------------------------------------------------------------

# 223. WebSocket Safety

Private channels must verify authorization.

Do not broadcast:

``` text
student private information
payment information
staff private data
```

to public channels.

------------------------------------------------------------------------

# 224. Accessibility Architecture

Vue components must support:

``` text
ARIA
keyboard
focus
screen reader
reduced motion
```

Design system components should provide accessible defaults.

------------------------------------------------------------------------

# 225. Component Contracts

Each Vue component should define:

``` text
Props
Emits
Slots
States
Accessibility
```

Avoid implicit global behavior.

------------------------------------------------------------------------

# 226. TypeScript Contracts

Generate or maintain typed API interfaces:

``` text
Course
CourseDetail
CourseFilters
Enrollment
Order
Webinar
Ebook
Blog
Review
User
Permission
```

Avoid:

``` ts
any
```

for core domain objects.

------------------------------------------------------------------------

# 227. API Client

Central Axios/fetch client:

``` text
services/api.ts
```

Responsibilities:

-   base URL;
-   credentials;
-   CSRF;
-   interceptors;
-   error normalization;
-   request ID;
-   locale header.

------------------------------------------------------------------------

# 228. Authentication Store

Pinia:

``` text
useAuthStore()
```

State:

``` text
user
authenticated
loading
permissions
```

Methods:

``` text
login
logout
fetchUser
register
refresh
```

------------------------------------------------------------------------

# 229. Permission Composable

Example conceptual:

``` text
can('courses.update')
```

and:

``` text
canAny([...])
canAll([...])
```

This only controls UI visibility.

Backend still validates authorization.

------------------------------------------------------------------------

# 230. Route Guards

Vue Router guards can redirect:

``` text
guest → login
student → student dashboard
worker → worker dashboard
manager → manager dashboard
admin → admin dashboard
```

But route guards are not security boundaries.

------------------------------------------------------------------------

# 231. Permission-Based UI

Hide/disable:

``` text
Edit
Delete
Publish
Refund
Manage Roles
```

according to current permissions.

This improves UX but does not replace backend authorization.

------------------------------------------------------------------------

# 232. Form Submission Flow

``` text
User submits
 ↓
Client validation
 ↓
API request
 ↓
Server validation
 ↓
Policy
 ↓
Action
 ↓
Response
 ↓
Toast / field errors
```

------------------------------------------------------------------------

# 233. Optimistic UI

Use only for low-risk operations:

``` text
mark notification read
toggle local preference
```

Avoid optimistic behavior for:

``` text
payment
enrollment
delete
publish
role changes
```

------------------------------------------------------------------------

# 234. Dashboard Data Refresh

Use:

``` text
manual refresh
polling only when needed
cache invalidation
```

Avoid aggressive polling.

------------------------------------------------------------------------

# 235. Accessibility of Data Tables

Provide:

-   table headers;
-   sort labels;
-   keyboard navigation;
-   mobile card fallback;
-   status text.

------------------------------------------------------------------------

# 236. Accessibility of Tabs

Use semantic tab pattern:

``` text
tablist
tab
tabpanel
```

Keyboard:

``` text
Arrow Left
Arrow Right
Home
End
```

------------------------------------------------------------------------

# 237. Accessibility of Accordions

Use buttons for accordion triggers.

Expose:

``` text
aria-expanded
aria-controls
```

------------------------------------------------------------------------

# 238. Accessibility of Modals

Must:

-   trap focus;
-   restore focus;
-   close with Escape;
-   have accessible title;
-   prevent background interaction.

------------------------------------------------------------------------

# 239. Accessibility of Forms

Each input:

``` text
label
id
error association
description association
```

Errors should be programmatically associated.

------------------------------------------------------------------------

# 240. Internationalization Technical Rule

Do not concatenate translated strings like:

``` text
"Course " + title + " is..."
```

Use complete translation templates.

------------------------------------------------------------------------

# 241. Date Localization

Use locale-aware formatting.

Bangla UI example:

``` text
২১ সেপ্টেম্বর ২০২৬
```

English:

``` text
21 September 2026
```

Keep database values normalized.

------------------------------------------------------------------------

# 242. Number Formatting

Currency:

``` text
৳ 8,500
```

English locale may use:

``` text
৳8,500
```

Choose one consistent display convention.

------------------------------------------------------------------------

# 243. Accessibility and Bangla

Test:

-   Bengali screen reader behavior;
-   mixed Latin/Bangla;
-   numerals;
-   punctuation;
-   line breaks.

------------------------------------------------------------------------

# 244. SEO and Localization

If separate locale URLs are adopted:

``` text
hreflang
canonical
alternate links
```

must be implemented correctly.

------------------------------------------------------------------------

# 245. Sitemap Localization

If locale-prefixed routes are used:

``` text
/bn/courses/...
/en/courses/...
```

include alternate language relationships where appropriate.

------------------------------------------------------------------------

# 246. Deployment Environments

``` text
local
dev
staging
production
```

Configuration must differ by environment.

------------------------------------------------------------------------

# 247. Staging

Staging should use:

-   test payment credentials;
-   non-production email;
-   test storage;
-   separate database;
-   restricted access.

------------------------------------------------------------------------

# 248. Production

Production:

-   HTTPS;
-   backups;
-   monitoring;
-   queue workers;
-   scheduler;
-   secure secrets;
-   database not publicly exposed.

------------------------------------------------------------------------

# 249. Deployment Order

Recommended:

``` text
1. Maintenance mode
2. Pull release
3. Install dependencies
4. Build assets
5. Run safe migrations
6. Cache config/routes/views
7. Restart workers
8. Smoke tests
9. Disable maintenance
```

Use zero/minimal downtime deployment techniques as the infrastructure
matures.

------------------------------------------------------------------------

# 250. Queue Worker Deployment

After deploy:

``` text
restart queue workers
```

so they load the new application code.

------------------------------------------------------------------------

# 251. Scheduler Deployment

Scheduler should run continuously through:

``` text
cron
```

or a supported process supervisor.

------------------------------------------------------------------------

# 252. Database Backup Before Migration

Every production migration that can affect schema/data should have a
rollback/recovery plan.

------------------------------------------------------------------------

# 253. Disaster Recovery

Define:

``` text
RPO
RTO
```

for production.

At minimum:

``` text
database backup
media backup
off-site storage
restore procedure
```

------------------------------------------------------------------------

# 254. Architecture Quality Checklist

## Backend

-   [ ] Thin controllers
-   [ ] Form Requests
-   [ ] Policies
-   [ ] Actions
-   [ ] Services
-   [ ] Events
-   [ ] Queues
-   [ ] Transactions
-   [ ] Audit logs

## Database

-   [ ] Foreign keys
-   [ ] Unique constraints
-   [ ] Indexes
-   [ ] Soft deletes where appropriate
-   [ ] Transactional tables
-   [ ] Historical snapshots

## Frontend

-   [ ] Vue 3
-   [ ] TypeScript
-   [ ] Pinia
-   [ ] Router
-   [ ] Reusable components
-   [ ] Theme
-   [ ] Localization
-   [ ] Responsive

## Security

-   [ ] Sanctum
-   [ ] CSRF
-   [ ] RBAC
-   [ ] Policies
-   [ ] Rate limits
-   [ ] File validation
-   [ ] Secure cookies
-   [ ] Audit

------------------------------------------------------------------------

# 255. Architecture Decision Records

Create an ADR directory:

``` text
docs/adr/
```

Potential ADRs:

``` text
ADR-001 Laravel + Vue architecture
ADR-002 Sanctum authentication
ADR-003 RBAC design
ADR-004 MySQL/InnoDB
ADR-005 Payment abstraction
ADR-006 Storage strategy
ADR-007 Localization strategy
ADR-008 SEO rendering strategy
ADR-009 Search engine
ADR-010 Queue architecture
```

------------------------------------------------------------------------

# 256. Recommended Repository Structure

``` text
emisha-academy/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
│
├── docs/
│   ├── PRD.md
│   ├── DESIGN.md
│   ├── ARCHITECTURE.md
│   ├── WIREFRAME.md
│   └── adr/
│
├── docker/
├── docker-compose.yml
├── Dockerfile
├── package.json
├── composer.json
└── README.md
```

------------------------------------------------------------------------

# 257. Documentation Rules

Every major domain should have:

``` text
README
API notes
business rules
permission rules
test notes
```

Documentation should be updated when behavior changes.

------------------------------------------------------------------------

# 258. Recommended Implementation Sequence

``` text
01 Foundation
02 Authentication
03 RBAC
04 Design System
05 Public Layout
06 CMS
07 Courses
08 Batches
09 Enrollment
10 Webinars
11 Ebooks
12 Blogs
13 Reviews
14 Commerce
15 Student Dashboard
16 Worker Dashboard
17 Manager Dashboard
18 Admin Dashboard
19 SEO
20 Notifications
21 Analytics
22 Security Hardening
23 Testing
24 Deployment
```

------------------------------------------------------------------------

# 259. Foundation Phase

Implement:

``` text
Laravel
Vue
TypeScript
Vite
MySQL
Redis
Docker
environment configuration
logging
exception handling
API response standard
```

------------------------------------------------------------------------

# 260. Identity Phase

Implement:

``` text
users
roles
permissions
authentication
email verification
password reset
Sanctum
profile
status
```

------------------------------------------------------------------------

# 261. RBAC Phase

Implement:

``` text
role_permissions
user_roles
policies
permission middleware
assignment scopes
admin role editor
audit logs
```

------------------------------------------------------------------------

# 262. CMS Phase

Implement:

``` text
homepage
pages
navigation
footer
FAQ
organizations
media
SEO
settings
```

------------------------------------------------------------------------

# 263. Education Phase

Implement:

``` text
courses
categories
tags
instructors
modules
lessons
batches
```

------------------------------------------------------------------------

# 264. Enrollment Phase

Implement:

``` text
cart
orders
payments
capacity
enrollment
progress
student course access
```

------------------------------------------------------------------------

# 265. Event Phase

Implement:

``` text
webinars
seminars
speakers
registrations
reminders
```

------------------------------------------------------------------------

# 266. Digital Content Phase

Implement:

``` text
ebooks
files
blogs
authors
categories
related content
```

------------------------------------------------------------------------

# 267. Engagement Phase

Implement:

``` text
reviews
free classes
leads
contact
notifications
```

------------------------------------------------------------------------

# 268. Dashboard Phase

Implement independently:

``` text
Student
Worker
Manager
Admin
```

with shared dashboard primitives.

------------------------------------------------------------------------

# 269. Quality Phase

Run:

``` text
Unit
Feature
E2E
Accessibility
Security
Performance
SEO
Responsive
```

------------------------------------------------------------------------

# 270. Launch Gate

Do not launch until:

``` text
Authentication secure
RBAC tested
Payment verified
Enrollment concurrency tested
Private files protected
Backups working
Monitoring active
SEO checked
Mobile checked
Bangla content checked
English content checked
Admin workflows tested
```

------------------------------------------------------------------------

# 271. Architecture Anti-Patterns to Avoid

Do NOT:

-   put all logic in controllers;
-   put all logic in Vue;
-   use role names as the only authorization mechanism;
-   trust frontend prices;
-   trust frontend capacity;
-   expose private file URLs;
-   hard-code homepage content;
-   duplicate components;
-   use one giant API controller;
-   use one giant service class;
-   store secrets in database CMS settings;
-   delete financial history;
-   create excessive indexes;
-   rely on frontend route guards for security.

------------------------------------------------------------------------

# 272. Critical Business Rules Summary

``` text
Rule 1:
Backend is authoritative.

Rule 2:
Student can access only own authorized resources.

Rule 3:
Worker permissions are scoped.

Rule 4:
Manager controls operations, not root security.

Rule 5:
Admin controls system governance.

Rule 6:
Course capacity is transactional.

Rule 7:
Payment must be server-verified.

Rule 8:
Private resources require authorization.

Rule 9:
Historical orders/payments are immutable/auditable.

Rule 10:
Published content is the only public content.

Rule 11:
All sensitive staff actions are audited.

Rule 12:
Content should be editable without code.
```

------------------------------------------------------------------------

# 273. Final Architecture Blueprint

``` text
                         EMISHA ACADEMY
                               │
                  ┌────────────┴────────────┐
                  │                         │
             PUBLIC WEB                 AUTHENTICATED
                  │                         │
          ┌───────┼────────┐       ┌────────┼─────────┐
          │       │        │       │        │         │
       Courses Webinars Blogs   Student   Worker   Manager/Admin
          │       │        │       │        │         │
          └───────┴────────┘       └────────┼─────────┘
                                            │
                                      Vue Application
                                            │
                                         HTTPS
                                            │
                                     Laravel API
                                            │
                         ┌──────────────────┼─────────────────┐
                         │                  │                 │
                    Auth/RBAC            CMS             Commerce
                         │                  │                 │
                    Sanctum            Content          Orders/Payments
                         │                  │                 │
                         └──────────────────┼─────────────────┘
                                            │
                              Actions / Services / Policies
                                            │
                         ┌──────────────────┼─────────────────┐
                         │                  │                 │
                       MySQL              Redis            Storage
                      InnoDB          Cache / Queue       Media/Files
                         │                  │                 │
                         └──────────────────┼─────────────────┘
                                            │
                              Mail / Payment / Analytics
```

------------------------------------------------------------------------

# 274. Final Architecture Statement

Emisha Academy's architecture should be implemented as a **modular
Laravel + Vue platform with MySQL/InnoDB as the transactional source of
truth, Sanctum for first-party SPA authentication, explicit
permission-based authorization, domain-oriented services/actions,
CMS-driven public content, transaction-safe enrollment and commerce,
protected private resources, and a responsive Vue design system**.

The architecture must make it possible for the academy to grow from:

``` text
Website
```

into:

``` text
Website
+
Learning Management System
+
Digital Product Store
+
Webinar Platform
+
Student Portal
+
Content Management System
+
Operational Management Platform
```

without requiring a fundamental architectural rewrite.

------------------------------------------------------------------------

# 275. Technical Research Basis

The architecture has been cross-checked against current official
technical documentation.

-   Laravel Sanctum documents cookie-based SPA authentication, CSRF
    initialization and protected Sanctum routes. citeturn0search2
-   MySQL 8.4 InnoDB provides transactions, row-level locking, MVCC and
    foreign-key support, which are central to enrollment/capacity and
    commerce integrity. citeturn0search1turn0search0
-   MySQL documentation recommends intentional index selection because
    excessive indexes consume resources and increase write costs.
    citeturn0search4turn0search7
-   MySQL foreign-key constraints provide database-level referential
    integrity and require appropriate indexing for referenced
    relationships. citeturn0search0

------------------------------------------------------------------------

# 276. Handoff to WIREFRAME.md

The final document in this series is:

``` text
WIREFRAME.md
```

It should translate the PRD + DESIGN + ARCHITECTURE into actual
screen-level layouts for:

``` text
Mobile
Tablet
Laptop
Desktop
Large Desktop
```

It should cover:

### Public

``` text
Home
Courses
Course Detail
Webinars
Webinar Detail
About
Contact
Blogs
Blog Detail
Ebooks
Ebook Detail
```

### Authentication

``` text
Login
Register
Forgot Password
Reset Password
Email Verification
```

### Student

``` text
Dashboard
My Courses
Learning View
My Webinars
My Ebooks
Orders
Reviews
Notifications
Profile
Settings
```

### Worker

``` text
Dashboard
Assigned Content
Course Management
Webinar Management
Blog Management
Ebook Management
Review Moderation
Leads
Media
```

### Manager

``` text
Dashboard
Courses
Packages
Batches
Instructors
Webinars
Ebooks
Blogs
Reviews
Students
Leads
Orders
Reports
Staff
```

### Admin

``` text
Dashboard
Users
Roles
Permissions
All Content
Homepage
Pages
Menus
Footer
Media
Payments
Notifications
Translations
SEO
Settings
Audit Logs
```

The wireframe document must show **responsive behavior, component
placement, information hierarchy, navigation behavior, forms, tables,
drawers, modals, empty states, loading states and mobile adaptations**,
not merely list page names.
