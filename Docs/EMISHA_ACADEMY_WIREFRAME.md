# Emisha Academy --- WIREFRAME.md

**Project:** Emisha Academy\
**Product:** Bangla-first EdTech Platform\
**Theme:** Dark-first + Light\
**Languages:** Bangla / English\
**Primary Devices:** Mobile, Tablet, Laptop, Desktop, Large Desktop, 4K,
TV / Smart Display\
**Related Documents:** `PRD.md`, `DESIGN.md`, `ARCHITECTURE.md`

------------------------------------------------------------------------

## 1. Purpose

This document defines the structural wireframes, responsive behavior,
information hierarchy, navigation, states, and device adaptations for
the complete Emisha Academy platform.

It is a **layout/UX specification**, not a visual mockup. Colors,
typography, radius, shadows, animation and component styling follow
`DESIGN.md`; data, API, authentication and permissions follow
`ARCHITECTURE.md`.

------------------------------------------------------------------------

# 2. Device Matrix

  Device                 Reference Width Primary Layout
  -------------------- ----------------- --------------------------
  Small Mobile                320--359px 1 column
  Mobile                      360--389px 1 column
  Large Mobile                390--480px 1 column / compact 2-col
  Small Tablet                600--767px 2 columns
  Tablet                     768--1023px 2 columns
  Laptop                    1024--1279px 3 columns / split
  Desktop                   1280--1439px 3--4 columns
  Large Desktop             1440--1919px 4 columns
  4K                        1920--2559px 4 columns + whitespace
  TV / Large Display             2560px+ 4--5 columns, large type

Test at minimum:

``` text
360×800
390×844
412×915
768×1024
820×1180
1024×768
1280×720
1366×768
1440×900
1536×864
1920×1080
2560×1440
3840×2160
```

------------------------------------------------------------------------

# 3. Responsive Rules

``` text
Mobile → focus
Tablet → balance
Laptop → efficiency
Desktop → productivity
Large Desktop → breathing room
4K → presentation
TV → visibility + focus
```

Use a fluid layout with a maximum content width. Do not create separate
websites for each device.

Recommended containers:

``` text
Mobile: viewport - 32px
Tablet: viewport - 48px
Desktop: max 1280px
Large screen: max 1440–1600px
```

TV is **not** simply a stretched desktop. Keep readable line lengths,
larger controls and generous safe margins.

------------------------------------------------------------------------

# 4. Universal Public Layout

``` text
┌──────────────────────────────────────────────┐
│ HEADER / NAVIGATION                          │
├──────────────────────────────────────────────┤
│ BREADCRUMB / PAGE HERO                       │
├──────────────────────────────────────────────┤
│ MAIN CONTENT                                 │
│                                              │
├──────────────────────────────────────────────┤
│ RELATED CONTENT / FINAL CTA                  │
├──────────────────────────────────────────────┤
│ FOOTER                                       │
└──────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 5. Header

### Desktop / Large Desktop

``` text
┌───────────────────────────────────────────────────────────────────┐
│ LOGO │ Home │ Courses │ Webinars │ Ebooks │ Blog │ About │       │
│      │                                              Contact │ EN │☾│
│                                                           Login  │
└───────────────────────────────────────────────────────────────────┘
```

### Laptop

``` text
┌──────────────────────────────────────────────────────────┐
│ LOGO │ Home │ Courses │ Webinar │ Ebook │ Blog │ EN │☾│☰│
└──────────────────────────────────────────────────────────┘
```

### Tablet / Mobile

``` text
┌──────────────────────────────────┐
│ LOGO                    ☾   ☰    │
└──────────────────────────────────┘
```

Mobile drawer:

``` text
LOGO                                      ✕
────────────────────────────────────────────
হোম
কোর্সসমূহ
ওয়েবিনার / সেমিনার
ইবুক
ব্লগ
আমাদের সম্পর্কে
যোগাযোগ
────────────────────────────────────────────
বাংলা / English
Dark / Light
[ Login ]
```

### TV

Use the full navigation with larger spacing but keep a centered
max-width. If TV is remote-controlled, every interactive item must have
a strong focus state and logical directional order.

------------------------------------------------------------------------

# 6. Footer

### Desktop

``` text
┌──────────────────────────────────────────────────────────────────┐
│ LOGO + Academy Description                                      │
│                                                                  │
│ Academy       Learning       Support       Contact               │
│ Courses       Webinars       FAQ           Phone                 │
│ Ebooks        Free Classes   Contact       Email                 │
│ Blogs         Packages                      Address              │
│                                                                  │
│ Other Emisha Initiatives                                         │
│ [Logo] [Logo] [Logo] [Logo] [Logo]                              │
│                                                                  │
│ Social Icons                                                     │
│                                                                  │
│ © Emisha Academy                              Policies           │
└──────────────────────────────────────────────────────────────────┘
```

### Mobile

``` text
LOGO
Description

Academy ▼
Learning ▼
Support ▼
Contact ▼

Social
Copyright / Policies
```

### TV

5-column footer with larger spacing; do not stretch text across the
whole screen.

------------------------------------------------------------------------

# 7. Homepage

Global order:

``` text
HEADER
↓
HERO
↓
OUR COURSES
↓
OUR EBOOKS
↓
WHY CHOOSE US
↓
COURSE PACKAGES
↓
FREE CLASSES
↓
STUDENT REVIEWS
↓
FAQ
↓
OUR ECOSYSTEM
↓
FINAL CTA
↓
FOOTER
```

## Hero --- Desktop

``` text
┌───────────────────────────────────────────────────────────────┐
│ Small trust label                                             │
│                                                               │
│ আপনার শেখার যাত্রা                                           │
│ শুরু হোক এখান থেকেই                                         │
│                                                               │
│ Supporting description                                        │
│                                                               │
│ [কোর্স দেখুন]       [ফ্রি ক্লাস দেখুন]                        │
│                                                               │
│ Students · Courses · Instructors       ┌───────────────────┐ │
│                                        │                   │ │
│                                        │   HERO VISUAL     │ │
│                                        │                   │ │
│                                        └───────────────────┘ │
└───────────────────────────────────────────────────────────────┘
```

Desktop: roughly 50/50 content and visual.

## Hero --- Tablet

``` text
Heading
Description
[Primary CTA] [Secondary CTA]

┌───────────────────────────────────────┐
│              HERO VISUAL              │
└───────────────────────────────────────┘
```

## Hero --- Mobile

``` text
Trust label
Main heading
Description

[কোর্স দেখুন]
[ফ্রি ক্লাস দেখুন]

┌──────────────────────────┐
│       HERO VISUAL        │
└──────────────────────────┘

Trust metrics
```

## Hero --- TV

``` text
                  TRUST LABEL

       আপনার শেখার যাত্রা শুরু হোক এখান থেকেই

                 Supporting text

       [কোর্স দেখুন]   [ফ্রি ক্লাস দেখুন]

                  LARGE VISUAL
```

Keep text centered within a readable max-width.

------------------------------------------------------------------------

# 8. Homepage --- Courses

### Desktop

``` text
┌─────────────────────────────────────────────────────────────┐
│ আমাদের কোর্সসমূহ                         [সব কোর্স দেখুন]  │
│ আপনার লক্ষ্য অনুযায়ী শেখার সুযোগ                         │
│                                                             │
│ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐               │
│ │ Image  │ │ Image  │ │ Image  │ │ Image  │               │
│ │ Title  │ │ Title  │ │ Title  │ │ Title  │               │
│ │ Meta   │ │ Meta   │ │ Meta   │ │ Meta   │               │
│ │ Price  │ │ Price  │ │ Price  │ │ Price  │               │
│ └────────┘ └────────┘ └────────┘ └────────┘               │
└─────────────────────────────────────────────────────────────┘
```

Mobile: one card per row or controlled horizontal carousel.

Tablet: 2 columns.

Laptop: 3 columns.

Desktop/Large: 3--4 columns.

TV: 4--5 reasonably large cards.

------------------------------------------------------------------------

# 9. Homepage --- Ebooks

``` text
Heading + [সব ইবুক দেখুন]

┌───────┐ ┌───────┐ ┌───────┐ ┌───────┐
│ COVER │ │ COVER │ │ COVER │ │ COVER │
│ Title │ │ Title │ │ Title │ │ Title │
│Author │ │Author │ │Author │ │Author │
│Version│ │Version│ │Version│ │Version│
└───────┘ └───────┘ └───────┘ └───────┘
```

Mobile: 1--2 compact cards depending on width.

------------------------------------------------------------------------

# 10. Homepage --- Why Choose Us

``` text
কেন আমাদের বেছে নেবেন?

┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ Icon        │ │ Icon        │ │ Icon        │
│ Limited     │ │ Expert      │ │ Practical   │
│ Batch       │ │ Instructor  │ │ Learning    │
└─────────────┘ └─────────────┘ └─────────────┘

┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ Support     │ │ Quality     │ │ Community   │
└─────────────┘ └─────────────┘ └─────────────┘
```

Mobile: 1 column. Tablet: 2. Desktop: 3.

------------------------------------------------------------------------

# 11. Homepage --- Packages

``` text
কোর্স প্যাকেজসমূহ

┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│ Package Name     │ │ Package Name     │ │ Package Name     │
│ Course A         │ │ Course A         │ │ Course A         │
│ Course B         │ │ Course B         │ │ Course B         │
│ Course C         │ │ Course C         │ │ Course C         │
│ Regular Price    │ │ Regular Price    │ │ Regular Price    │
│ Package Price    │ │ Package Price    │ │ Package Price    │
│ [View Package]   │ │ [View Package]   │ │ [View Package]   │
└──────────────────┘ └──────────────────┘ └──────────────────┘
```

Mobile: vertical cards. Featured package may appear first.

------------------------------------------------------------------------

# 12. Homepage --- Free Classes

``` text
ফ্রি ক্লাসসমূহ                         [সব ফ্রি ক্লাস]

┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│ Video Thumbnail │ │ Video Thumbnail │ │ Video Thumbnail │
│       ▶         │ │       ▶         │ │       ▶         │
│ Title           │ │ Title           │ │ Title           │
│ Instructor      │ │ Instructor      │ │ Instructor      │
└─────────────────┘ └─────────────────┘ └─────────────────┘
```

Mobile: horizontal carousel or stack.

------------------------------------------------------------------------

# 13. Homepage --- Reviews

``` text
শিক্ষার্থীদের মতামত

[ভিডিও রিভিউ] [ইমেজ রিভিউ] [টেক্সট রিভিউ]

┌───────────────────────┐ ┌───────────────────────────┐
│ Featured Review       │ │ Supporting Review 1       │
│ Video / Image         │ │ Supporting Review 2       │
│                       │ │ Supporting Review 3       │
└───────────────────────┘ └───────────────────────────┘
```

Mobile: tabs + featured review + horizontal carousel.

------------------------------------------------------------------------

# 14. Homepage --- FAQ

``` text
Frequently Asked Questions

┌─────────────────────────────────────────────────┐
│ প্রশ্ন ১                                  +     │
├─────────────────────────────────────────────────┤
│ প্রশ্ন ২                                  +     │
├─────────────────────────────────────────────────┤
│ প্রশ্ন ৩                                  +     │
├─────────────────────────────────────────────────┤
│ প্রশ্ন ৪                                  +     │
└─────────────────────────────────────────────────┘
```

Mobile: full-width accordion. TV: max-width around 1000--1100px.

------------------------------------------------------------------------

# 15. Homepage --- Ecosystem

``` text
আমাদের অন্যান্য উদ্যোগ

[Emisha Tours] [Harmain] [Safar] [Hajj & Umrah] [Visa Processing]
```

Mobile: horizontal logo rail.

Desktop: centered row.

TV: larger evenly spaced logo grid.

------------------------------------------------------------------------

# 16. Homepage --- Final CTA

``` text
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│       আপনার শেখার যাত্রা আজ থেকেই শুরু করুন                │
│       Supporting message                                    │
│                                                             │
│       [কোর্স দেখুন]        [যোগাযোগ করুন]                  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 17. Courses Listing

### Desktop

``` text
PAGE HEADER
কোর্সসমূহ
Description

┌──────────────────────────────────────────────────────────────┐
│ Search courses...                                            │
└──────────────────────────────────────────────────────────────┘

┌───────────────┬──────────────────────────────────────────────┐
│ FILTERS       │ Result count                  [Sort]         │
│ Category      │                                              │
│ Level         │ ┌────────┐ ┌────────┐ ┌────────┐           │
│ Language      │ │ Course │ │ Course │ │ Course │           │
│ Price         │ └────────┘ └────────┘ └────────┘           │
│ Duration      │                                              │
│ Availability  │ ┌────────┐ ┌────────┐ ┌────────┐           │
│               │ │ Course │ │ Course │ │ Course │           │
└───────────────┴──────────────────────────────────────────────┘

Pagination
```

### Mobile

``` text
PAGE HEADER
Search
[Filter] [Sort]

Course Card
Course Card
Course Card
...
```

Filter becomes a bottom sheet.

------------------------------------------------------------------------

# 18. Course Filter Drawer

``` text
┌──────────────────────────────┐
│ Filters                 ✕    │
├──────────────────────────────┤
│ Category                     │
│ □ Design                     │
│ □ Development                │
│ □ Marketing                  │
│                              │
│ Level                        │
│ □ Beginner                   │
│ □ Intermediate               │
│ □ Advanced                   │
│                              │
│ Price [Min] [Max]            │
│ Language                     │
│ □ বাংলা  □ English          │
├──────────────────────────────┤
│ [Clear All] [Apply Filters]  │
└──────────────────────────────┘
```

------------------------------------------------------------------------

# 19. Course Detail

### Desktop

``` text
BREADCRUMB

┌──────────────────────────────────────────────────────────────┐
│ ┌─────────────────────────┐ ┌──────────────────────────────┐ │
│ │ Course Image / Preview  │ │ Course Title                 │ │
│ │                         │ │ Short Description            │ │
│ │                         │ │ Level · Duration · Lessons   │ │
│ │                         │ │ Capacity                     │ │
│ │                         │ │                              │ │
│ │                         │ │ Price                        │ │
│ └─────────────────────────┘ │ [Buy Now] [Add to Cart]      │ │
│                             └──────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘

ABOUT COURSE
CURRICULUM
INSTRUCTOR
REVIEWS
RELATED COURSES
```

### Mobile

``` text
Breadcrumb
Image
Title
Description
Level / Duration / Lessons / Language / Capacity
Price
[Buy Now]
[Add to Cart]

About
Curriculum
Instructor
Reviews
Related Courses
```

Optional sticky bottom:

``` text
┌──────────────────────────────┐
│ ৳8,500             [Buy Now] │
└──────────────────────────────┘
```

------------------------------------------------------------------------

# 20. Course Curriculum

``` text
Course Curriculum

┌─────────────────────────────────────────────┐
│ Module 01                         6 Lessons │
│   ✓ Lesson 01                   12 min      │
│   ✓ Lesson 02                   20 min      │
│   ● Lesson 03                   15 min      │
│   ○ Lesson 04                   18 min      │
│                                             │
│ Module 02                         5 Lessons │
│   ○ Lesson 01                   20 min      │
└─────────────────────────────────────────────┘
```

Desktop may keep module navigation open. Mobile uses accordion.

------------------------------------------------------------------------

# 21. Course Instructor

``` text
┌────────────────────────────────────────────────────┐
│ [PHOTO]  Instructor Name                            │
│          Designation                                │
│          Experience                                 │
│                                                     │
│          Short biography                            │
└────────────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 22. Course Reviews

``` text
[ভিডিও রিভিউ] [টেক্সট রিভিউ]

Video:
┌──────────────┐ ┌──────────────┐
│ ▶ Thumbnail  │ │ ▶ Thumbnail  │
└──────────────┘ └──────────────┘

Text:
Avatar
Student Name
★★★★★
Review
```

------------------------------------------------------------------------

# 23. Related Courses

``` text
এই কোর্সগুলোও আপনার ভালো লাগতে পারে

┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐
│ Card   │ │ Card   │ │ Card   │ │ Card   │
└────────┘ └────────┘ └────────┘ └────────┘
```

Mobile: horizontal carousel or 1-column stack.

------------------------------------------------------------------------

# 24. Webinar Listing

``` text
PAGE HEADER
Webinars & Seminars

Search
[Filter] [Sort]

┌──────────┐ ┌──────────┐ ┌──────────┐
│ Image    │ │ Image    │ │ Image    │
│ Upcoming │ │ Upcoming │ │ Completed│
│ Title    │ │ Title    │ │ Title    │
│ Date     │ │ Date     │ │ Date     │
│ Speaker  │ │ Speaker  │ │ Speaker  │
└──────────┘ └──────────┘ └──────────┘
```

------------------------------------------------------------------------

# 25. Webinar Detail

``` text
BREADCRUMB

┌─────────────────────────────────────────────────────┐
│ Webinar Title                                       │
│ Date · Time · Language · Level                      │
│ Short Description                                   │
│ [Register Now]                                      │
└─────────────────────────────────────────────────────┘

ABOUT
SCHEDULE
SPEAKER
PREREQUISITES
WHO SHOULD ATTEND
RELATED WEBINARS
```

Mobile: image → title → metadata → CTA → content.

------------------------------------------------------------------------

# 26. About Page

``` text
HEADER
↓
HERO
↓
OUR STORY
↓
MISSION / VISION
↓
OUR JOURNEY
↓
GALLERY
↓
OUR ORGANIZATIONS
↓
FINAL CTA
↓
FOOTER
```

Journey desktop:

``` text
2023 ─────● Story
           │
2024 ─────● Growth
           │
2025 ─────● Expansion
           │
2026 ─────● Vision
```

Mobile: vertical timeline.

Gallery: controlled grid, 2 columns mobile.

------------------------------------------------------------------------

# 27. Contact Page

### Desktop

``` text
PAGE HEADER

┌────────────────────────────┬──────────────────────────────┐
│ Contact Information        │ Contact Form                 │
│                            │ Name                         │
│ Address                    │ Email                        │
│ Phone                      │ Phone                        │
│ Email                      │ Subject                      │
│                            │ Message                      │
│ Google Map                 │ [Send Message]               │
└────────────────────────────┴──────────────────────────────┘
```

### Mobile

``` text
Contact Info
Google Map
Contact Form
```

------------------------------------------------------------------------

# 28. Blog Listing

``` text
PAGE HEADER
Featured Blog

┌────────────────────────────────────────────────────┐
│ Large Image │ Title                                │
│             │ Excerpt                              │
│             │ Author · Date · Read Time             │
└────────────────────────────────────────────────────┘

Search
[Category] [Tag] [Date]

┌──────────┐ ┌──────────┐ ┌──────────┐
│ Image    │ │ Image    │ │ Image    │
│ Category │ │ Category │ │ Category │
│ Title    │ │ Title    │ │ Title    │
│ Excerpt  │ │ Excerpt  │ │ Excerpt  │
└──────────┘ └──────────┘ └──────────┘
```

Mobile: featured article then 1-column cards.

------------------------------------------------------------------------

# 29. Blog Detail

``` text
Breadcrumb
Category
BLOG TITLE
Author · Date · Reading Time

┌──────────────────────────────────────────┐
│ HERO IMAGE                               │
└──────────────────────────────────────────┘

Article body
H2
Paragraph
Image
Quote
List
...

Related Articles
Final CTA
```

Reading width:

``` text
Desktop: 820–900px
Tablet: 720–800px
Mobile: viewport - 32px
TV: 900–1100px
```

------------------------------------------------------------------------

# 30. Ebook Listing

``` text
PAGE HEADER
Search

[Category] [Language] [Author] [Country] [Version]

┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐
│ Cover  │ │ Cover  │ │ Cover  │ │ Cover  │
│ Title  │ │ Title  │ │ Title  │ │ Title  │
│ Author │ │ Author │ │ Author │ │ Author │
│ Pages  │ │ Pages  │ │ Pages  │ │ Pages  │
│ Price  │ │ Price  │ │ Price  │ │ Price  │
└────────┘ └────────┘ └────────┘ └────────┘
```

------------------------------------------------------------------------

# 31. Ebook Detail

``` text
┌───────────────┬──────────────────────────────────────┐
│ Book Cover    │ Ebook Title                          │
│               │ Author                               │
│               │ Version                              │
│               │ Pages                                │
│               │ Language                             │
│               │ Target Countries                     │
│               │ Price                                │
│               │ [Buy / Download]                     │
└───────────────┴──────────────────────────────────────┘

DESCRIPTION
TABLE OF CONTENTS
PREVIEW
AUTHOR
RELATED EBOOKS
```

Mobile: cover first, then metadata and CTA.

------------------------------------------------------------------------

# 32. Authentication

## Login

``` text
LOGO

Welcome Back

Email / Phone
[........................]

Password
[........................]

[ ] Remember me      Forgot password?

[ Login ]

Don't have an account? Register
```

## Register

``` text
Name
Email
Phone
Password
Confirm Password

[Create Account]

Already registered? Login
```

## Forgot Password

``` text
Forgot Password?
Email / Phone
[....................]

[Send Reset Link]
Back to Login
```

------------------------------------------------------------------------

# 33. Student Dashboard

### Desktop

``` text
┌──────────────┬──────────────────────────────────────────────┐
│ SIDEBAR      │ TOPBAR                                      │
│ Overview     ├──────────────────────────────────────────────┤
│ My Courses   │ Welcome back                                │
│ Webinars     │                                              │
│ Ebooks       │ ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐│
│ Orders       │ │Courses │ │Progress│ │Webinar │ │Ebooks ││
│ Reviews      │ └────────┘ └────────┘ └────────┘ └────────┘│
│ Notifications│                                              │
│ Profile      │ Continue Learning                            │
│ Settings     │ ┌──────────────────────────────────────────┐ │
│              │ │ Course + Progress + Continue              │ │
│              │ └──────────────────────────────────────────┘ │
│              │ Upcoming Webinars / Recent Activity          │
└──────────────┴──────────────────────────────────────────────┘
```

### Mobile

``` text
TOPBAR
Welcome

┌────────┐ ┌────────┐
│Courses │ │Progress│
└────────┘ └────────┘

Continue Learning
┌──────────────────────────┐
│ Course                   │
│ Progress                 │
│ [Continue]               │
└──────────────────────────┘

Upcoming Webinars

BOTTOM NAV
Home | Courses | Webinars | Notifications | Profile
```

Tablet: collapsible sidebar.

------------------------------------------------------------------------

# 34. Student --- My Courses

``` text
My Courses

[All] [In Progress] [Completed]
Search

┌───────────────────────────────┐
│ Course                        │
│ Progress ███████░░░ 72%      │
│ 18 / 25 lessons              │
│ [Continue Learning]          │
└───────────────────────────────┘
```

Desktop: 3--4 cards.

------------------------------------------------------------------------

# 35. Student --- Learning View

### Desktop

``` text
┌───────────────┬────────────────────────────────────────────┐
│ Curriculum    │ Video / Lesson                             │
│ Module 01     │ ┌────────────────────────────────────────┐ │
│ ✓ Lesson 01   │ │              VIDEO PLAYER              │ │
│ ✓ Lesson 02   │ └────────────────────────────────────────┘ │
│ ● Lesson 03   │ Lesson Title                               │
│ ○ Lesson 04   │ Description                                │
│ Module 02     │                                            │
│ ○ Lesson 01   │ [Previous]                 [Next Lesson]   │
└───────────────┴────────────────────────────────────────────┘
```

Mobile:

``` text
Video
Lesson Title
Description
[Previous] [Next]
Curriculum Accordion
```

TV learning mode:

``` text
FULL SCREEN VIDEO
↓
Lesson title
↓
Minimal controls
```

------------------------------------------------------------------------

# 36. Student --- Orders

Desktop:

``` text
Orders

┌────────────┬────────────┬────────┬──────────────┐
│ Order      │ Date       │ Amount │ Status       │
├────────────┼────────────┼────────┼──────────────┤
│ #EM1001    │ 21 Sep     │ ৳8500  │ Paid         │
│ #EM1002    │ 19 Sep     │ ৳3500  │ Pending      │
└────────────┴────────────┴────────┴──────────────┘
```

Mobile: order cards.

------------------------------------------------------------------------

# 37. Student --- Ebooks

``` text
My Ebooks

┌──────────┐ ┌──────────┐
│ Cover    │ │ Cover    │
│ Title    │ │ Title    │
│ [Read]   │ │ [Read]   │
└──────────┘ └──────────┘
```

------------------------------------------------------------------------

# 38. Worker Dashboard

``` text
┌──────────────┬──────────────────────────────────────────────┐
│ SIDEBAR      │ TOPBAR                                      │
│ Dashboard    ├──────────────────────────────────────────────┤
│ Assigned     │ Assigned Tasks                              │
│ Courses      │ ┌────────┐ ┌────────┐ ┌────────┐            │
│ Webinars     │ │Pending │ │Review  │ │Overdue │            │
│ Blogs        │ └────────┘ └────────┘ └────────┘            │
│ Ebooks       │                                              │
│ Reviews      │ Assigned Content                            │
│ Media        │ ┌──────────────────────────────────────────┐ │
│ Leads        │ │ Content / Status / Deadline / Action     │ │
└──────────────┴──────────────────────────────────────────────┘
```

Worker sees only authorized/assigned resources.

------------------------------------------------------------------------

# 39. Worker --- Assigned Content

``` text
Assigned Content
[Search]
[Type] [Status] [Due Date]

┌──────────────────────────────────────────────┐
│ Content Title                                │
│ Type · Status · Assigned · Deadline          │
│ [Open] [Edit]                                │
└──────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 40. Worker --- Review Moderation

``` text
Reviews
[Video] [Text] [Image]

┌───────────────────────────────────────────────┐
│ Student Name                                  │
│ Course                                        │
│ Review                                         │
│ [Approve] [Reject] [View]                     │
└───────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 41. Manager Dashboard

``` text
┌──────────────┬──────────────────────────────────────────────┐
│ Sidebar      │ Overview                                     │
│ Dashboard    │                                              │
│ Courses      │ Students | Courses | Revenue | Leads         │
│ Batches      │                                              │
│ Instructors  │ Enrollment Analytics                         │
│ Webinars     │ ┌──────────────────────────────────────────┐ │
│ Ebooks       │ │ CHART                                    │ │
│ Blogs        │ └──────────────────────────────────────────┘ │
│ Reviews      │ Course Capacity                              │
│ Students     │ Staff Workload                               │
│ Orders       │ Recent Orders                                │
│ Reports      │                                              │
│ Staff        │                                              │
└──────────────┴──────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 42. Manager --- Course Management

``` text
Courses                         [Create Course]

[Search] [Filter]

┌─────────────────────────────────────────────────────────────┐
│ Course | Category | Instructor | Batch | Capacity | Status │
├─────────────────────────────────────────────────────────────┤
│ ...                                                         │
└─────────────────────────────────────────────────────────────┘
```

Actions:

``` text
View
Edit
Duplicate
Publish
Archive
```

------------------------------------------------------------------------

# 43. Manager --- Course Editor

``` text
┌──────────────────────────────────────┬─────────────────────┐
│ MAIN                                │ SIDEBAR             │
│ Course Title                         │ Status              │
│ Short Description                    │ Category            │
│ Description Editor                   │ Instructor          │
│ Curriculum                           │ Batch               │
│ Modules / Lessons                    │ Price               │
│                                      │ Capacity            │
│                                      │ SEO                 │
│                                      │                     │
│                                      │ [Save Draft]        │
│                                      │ [Publish]           │
└──────────────────────────────────────┴─────────────────────┘
```

Mobile: one column + sticky save bar.

------------------------------------------------------------------------

# 44. Manager --- Batch Management

``` text
Course → Batches

┌──────────────────────────────────────────────────────────────┐
│ Batch | Start | End | Capacity | Filled | Status | Actions  │
├──────────────────────────────────────────────────────────────┤
│ Batch 01 | ... | ... | 25 | 21 | Open | Edit               │
└──────────────────────────────────────────────────────────────┘

[Create Batch]
```

------------------------------------------------------------------------

# 45. Admin Dashboard

``` text
┌───────────────┬────────────────────────────────────────────┐
│ ADMIN SIDEBAR │ TOPBAR                                    │
│ Overview      ├────────────────────────────────────────────┤
│ Users         │ System Overview                            │
│ Roles         │ Students / Managers / Workers / Admins     │
│ Permissions   │ Revenue / Orders / Enrollment              │
│ Courses       │                                            │
│ Webinars      │ ┌────────────────────────────────────────┐ │
│ Ebooks        │ │ Analytics                              │ │
│ Blogs         │ └────────────────────────────────────────┘ │
│ Homepage      │ Recent Activity                            │
│ Pages         │ Audit Logs                                 │
│ Menus         │                                            │
│ Media         │                                            │
│ Orders        │                                            │
│ Payments      │                                            │
│ Reviews       │                                            │
│ Notifications │                                            │
│ SEO           │                                            │
│ Settings      │                                            │
│ Audit Logs    │                                            │
└───────────────┴────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 46. Admin --- User Management

``` text
Users
[Search] [Role] [Status] [Create User]

┌──────────────────────────────────────────────────────────┐
│ Name | Email | Role | Status | Last Login | Actions     │
├──────────────────────────────────────────────────────────┤
│ ...                                                      │
└──────────────────────────────────────────────────────────┘
```

Mobile:

``` text
Name
Role
Status
Last Login
[View] [Edit] [More]
```

------------------------------------------------------------------------

# 47. Admin --- Roles & Permissions

``` text
Roles
┌───────────────────────────────────────┐
│ Student                               │
│ Worker                                │
│ Manager                               │
│ Admin                                 │
│ [Create Role]                         │
└───────────────────────────────────────┘
```

Permission editor:

``` text
Role: Worker

Courses
☑ View   ☑ Create   ☑ Update   ☐ Delete   ☐ Publish

Webinars
☑ View   ☑ Create   ☑ Update   ☐ Delete

Blogs
☑ View   ☑ Create   ☑ Update   ☐ Delete

[Select All] [Clear All]
[Save Permissions]
```

------------------------------------------------------------------------

# 48. Admin --- Homepage Builder

``` text
Homepage

☰ Hero            [Edit] [On]
☰ Courses         [Edit] [On]
☰ Ebooks          [Edit] [On]
☰ Why Choose Us   [Edit] [On]
☰ Packages        [Edit] [On]
☰ Free Classes    [Edit] [On]
☰ Reviews         [Edit] [On]
☰ FAQ             [Edit] [On]
☰ Ecosystem       [Edit] [On]
☰ Final CTA       [Edit] [On]
```

------------------------------------------------------------------------

# 49. Admin --- Media Library

``` text
Media Library                  [Upload] [New Folder]

Search

┌────┐ ┌────┐ ┌────┐ ┌────┐ ┌────┐
│IMG │ │IMG │ │IMG │ │IMG │ │PDF │
└────┘ └────┘ └────┘ └────┘ └────┘
```

------------------------------------------------------------------------

# 50. Admin --- Audit Log

``` text
Audit Logs

[Actor] [Action] [Resource] [Date]

┌─────────────────────────────────────────────────────────┐
│ 21 Sep · Admin · Updated Course #32                    │
│ Capacity 20 → 25                                       │
│ [View Details]                                         │
├─────────────────────────────────────────────────────────┤
│ 21 Sep · Manager · Published Blog #42                  │
└─────────────────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 51. Admin --- Settings

Use categorized navigation:

``` text
General
Brand
Contact
Localization
SEO
Email
Payment
Storage
Security
Notifications
```

Avoid one extremely long settings page.

------------------------------------------------------------------------

# 52. Universal CRUD Pattern

List:

``` text
Page Header
↓
Search / Filters / Create
↓
Table / Cards
↓
Pagination
```

Editor:

``` text
Breadcrumb
↓
Page Title
↓
Main Form + Metadata Sidebar
↓
Save / Publish
```

------------------------------------------------------------------------

# 53. Create/Edit --- Mobile

``` text
← Back

Course Title
[................]

Short Description
[................]

Description
[................]

Category
[Select]

Instructor
[Select]

Price
[........]

Capacity
[........]

Status
[........]

[Save Draft]
[Publish]
```

------------------------------------------------------------------------

# 54. Delete / Archive Modal

``` text
┌──────────────────────────────────┐
│ Delete / Archive?           ✕    │
│                                  │
│ Related records may exist.       │
│ Archiving is recommended.        │
│                                  │
│ [Cancel] [Archive]               │
└──────────────────────────────────┘
```

Hard deletion should require stronger confirmation.

------------------------------------------------------------------------

# 55. Publish Confirmation

``` text
┌──────────────────────────────────┐
│ Publish Course?                  │
│                                  │
│ Confirm required information     │
│ is complete before publishing.   │
│                                  │
│ [Cancel] [Publish]               │
└──────────────────────────────────┘
```

------------------------------------------------------------------------

# 56. Loading State

Use skeletons:

``` text
┌───────────┐ ┌───────────┐ ┌───────────┐
│ ████████  │ │ ████████  │ │ ████████  │
│ █████     │ │ █████     │ │ █████     │
│ ████      │ │ ████      │ │ ████      │
└───────────┘ └───────────┘ └───────────┘
```

Reserve image dimensions to reduce layout shift.

------------------------------------------------------------------------

# 57. Empty State

``` text
┌─────────────────────────────┐
│       No courses found      │
│ Try changing your filters.  │
│ [Clear Filters]             │
└─────────────────────────────┘
```

------------------------------------------------------------------------

# 58. Error State

``` text
কিছু একটা সমস্যা হয়েছে।

অনুগ্রহ করে আবার চেষ্টা করুন।

[আবার চেষ্টা করুন]
```

------------------------------------------------------------------------

# 59. 404 / 403 / Offline

### 404

``` text
404
এই পেজটি খুঁজে পাওয়া যায়নি।
[হোমে ফিরে যান]
```

### 403

``` text
403
এই পেজটি দেখার অনুমতি আপনার নেই।
[Dashboard-এ ফিরে যান]
```

### Offline

``` text
ইন্টারনেট সংযোগ পাওয়া যাচ্ছে না।
আপনার সংযোগ পরীক্ষা করে আবার চেষ্টা করুন।
```

------------------------------------------------------------------------

# 60. Checkout

### Desktop

``` text
┌──────────────────────────────┬───────────────────────────┐
│ Customer Information         │ Order Summary             │
│ Name                         │ Course                    │
│ Email                        │ Price                     │
│ Phone                        │ Discount                  │
│ Payment Method               │ Total                     │
│ ○ bKash                      │                           │
│ ○ Nagad                      │ [Pay Now]                 │
│ ○ Card                       │                           │
└──────────────────────────────┴───────────────────────────┘
```

### Mobile

``` text
Course
Price

Customer Information
Name
Email
Phone

Payment Method

[Pay Now]
```

Hide unnecessary navigation during checkout.

------------------------------------------------------------------------

# 61. Payment Result

Success:

``` text
✓ Payment Successful

Order #EM10001
Enrollment has been created.

[Go to My Course]
[View Order]
```

Failure:

``` text
Payment could not be completed.

[Try Again]
[Contact Support]
```

------------------------------------------------------------------------

# 62. Enrollment Success

``` text
🎉 Enrollment Complete

Course: Laravel Web Development
Batch: Batch 03
Start: 01 October 2026

[Start Learning]
```

------------------------------------------------------------------------

# 63. Webinar Registration Success

``` text
Registration Confirmed

Webinar: ...
Date: ...
Time: ...

[Add to Calendar]
[View Webinar]
```

------------------------------------------------------------------------

# 64. Ebook Purchase Success

``` text
Purchase Complete

Ebook: ...
Version: ...

[Read Ebook]
[Download]
```

Private file authorization remains server-side.

------------------------------------------------------------------------

# 65. Notification Center

Desktop:

``` text
┌────────────────────────────────────────────┐
│ Notifications              Mark all read  │
├────────────────────────────────────────────┤
│ ● Course enrollment confirmed              │
│   5 minutes ago                            │
├────────────────────────────────────────────┤
│ ○ Webinar reminder                         │
│   1 hour ago                               │
└────────────────────────────────────────────┘
```

Mobile: full-page notification list.

------------------------------------------------------------------------

# 66. Profile

``` text
Profile

Avatar
Name
Email
Phone
Joined Date

[Edit Profile]

Security
Change Password
Sessions
```

------------------------------------------------------------------------

# 67. Responsive Tables

Desktop:

``` text
Full table
```

Tablet:

``` text
Horizontal scroll only if necessary
```

Mobile:

``` text
Convert row to card
```

Never force a 12-column table into 320px.

------------------------------------------------------------------------

# 68. Responsive Forms

Desktop:

``` text
2 columns
```

Tablet:

``` text
1–2 columns
```

Mobile:

``` text
1 column
```

TV:

``` text
2 columns with large controls
```

------------------------------------------------------------------------

# 69. Responsive Modals

Desktop:

``` text
520–720px centered
```

Mobile:

``` text
near-full-screen / bottom sheet
```

Tablet:

``` text
center modal
```

TV:

``` text
larger modal, larger type
```

------------------------------------------------------------------------

# 70. Responsive Drawers

Mobile:

``` text
85–100% width
```

Tablet:

``` text
420–520px
```

Desktop: only where useful.

------------------------------------------------------------------------

# 71. Responsive Tabs

Desktop:

``` text
[ভিডিও রিভিউ] [টেক্সট রিভিউ]
```

Mobile:

``` text
[ভিডিও] [টেক্সট]
```

If tabs exceed width, allow horizontal scrolling.

------------------------------------------------------------------------

# 72. Responsive Pagination

Desktop:

``` text
← Previous 1 2 3 ... 10 Next →
```

Mobile:

``` text
← Previous
Page 2 of 10
Next →
```

------------------------------------------------------------------------

# 73. Responsive Breadcrumbs

Desktop:

``` text
Home / Courses / Web Development
```

Mobile:

``` text
← Courses
```

------------------------------------------------------------------------

# 74. Card Grid Matrix

  Device            Courses   Blogs   Ebooks
  --------------- --------- ------- --------
  Mobile                  1       1     1--2
  Tablet                  2       2        2
  Laptop                  3       3        3
  Desktop              3--4       3        4
  Large Desktop           4    3--4        4
  4K                      4       4        5
  TV                   4--5       4        5

Cards must retain a useful minimum width; never create tiny cards simply
to fill the screen.

------------------------------------------------------------------------

# 75. Dashboard Navigation Matrix

  Device          Student               Worker/Manager/Admin
  --------------- --------------------- ----------------------
  Mobile          Bottom nav + drawer   Drawer
  Tablet          Collapsible sidebar   Collapsible sidebar
  Laptop          Sidebar               Sidebar
  Desktop         Sidebar               Sidebar
  Large Desktop   Sidebar               Sidebar
  4K              Sidebar               Sidebar
  TV              Focused nav           Optional compact nav

------------------------------------------------------------------------

# 76. Dashboard Mobile Navigation

Student bottom navigation:

``` text
Home
Courses
Webinars
Notifications
Profile
```

Staff/admin use a drawer because their navigation tree is much larger.

------------------------------------------------------------------------

# 77. TV / Large Display Wireframes

TV should prioritize **visibility and focus**, not dense CRUD.

## TV Homepage

``` text
┌───────────────────────────────────────────────────────────────┐
│ LOGO     Home  Courses  Webinars  Ebooks  Blog  About       │
│                                                               │
│          আপনার শেখার যাত্রা শুরু হোক এখান থেকেই             │
│                                                               │
│          [কোর্স দেখুন]   [ফ্রি ক্লাস দেখুন]                  │
│                                                               │
│                     LARGE HERO VISUAL                         │
└───────────────────────────────────────────────────────────────┘
```

## TV Course Listing

``` text
Search
[____________________________________________]

[All] [Beginner] [Intermediate] [Advanced]

┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐
│ Course  │ │ Course  │ │ Course  │ │ Course  │
└─────────┘ └─────────┘ └─────────┘ └─────────┘
```

## TV Learning Mode

``` text
┌───────────────────────────────────────────────────────────────┐
│ Course Name                                    Lesson 03      │
├───────────────────────────────────────────────────────────────┤
│                                                               │
│                         VIDEO PLAYER                          │
│                                                               │
├───────────────────────────────────────────────────────────────┤
│ Lesson title                                                  │
│ Description                                                   │
│ [Previous]                              [Next Lesson]         │
└───────────────────────────────────────────────────────────────┘
```

## TV Admin Monitoring

``` text
┌───────────────────────────────────────────────────────────────┐
│ EMISHA ACADEMY — SYSTEM OVERVIEW                              │
├───────────────────────────────────────────────────────────────┤
│ Students    Courses    Enrollments    Revenue                │
│ 1,250       32         890            ৳12.5L                 │
│                                                               │
│ ┌──────────────────────────┐ ┌─────────────────────────────┐ │
│ │ Enrollment Trend         │ │ Course Occupancy            │ │
│ │          CHART           │ │          CHART              │ │
│ └──────────────────────────┘ └─────────────────────────────┘ │
│                                                               │
│ Recent Activity                                               │
└───────────────────────────────────────────────────────────────┘
```

------------------------------------------------------------------------

# 78. TV Safe Area

For very large screens:

``` text
5–8% horizontal safe area
```

where practical.

Never place critical CTA or labels against the screen edge.

------------------------------------------------------------------------

# 79. TV Typography

Approximate presentation starting points:

``` text
Body: 24–28px+
H2: 40–56px
H1: 56–72px+
```

Final size depends on viewing distance and target TV.

------------------------------------------------------------------------

# 80. TV Interaction

If display-only:

-   no hover-dependent content;
-   no tiny controls.

If interactive:

-   visible focus ring;
-   directional navigation;
-   large controls;
-   predictable focus order.

------------------------------------------------------------------------

# 81. Mobile Learning

Video should be 16:9 and support fullscreen landscape.

Mobile lesson:

``` text
Video
↓
Lesson title
↓
Description
↓
Previous / Next
↓
Curriculum accordion
```

When fullscreen, hide non-essential application chrome.

------------------------------------------------------------------------

# 82. Orientation

Support:

``` text
Portrait
Landscape
```

especially on tablets and learning video.

Tablet landscape may increase grids from 2 to 3 columns when space
allows.

------------------------------------------------------------------------

# 83. Very Small Mobile --- 320px

At 320px:

-   stack CTA buttons;
-   reduce non-essential metadata;
-   maintain 44px touch targets;
-   prevent horizontal overflow;
-   allow long titles to wrap;
-   keep 16px minimum page gutter where possible.

------------------------------------------------------------------------

# 84. Accessibility Wireframe Rules

Every screen must have:

``` text
Logical heading hierarchy
Keyboard order
Visible focus
Accessible labels
Error association
44px+ touch targets
Reduced-motion behavior
No color-only status
```

Tabs use semantic tab patterns. Accordions use buttons with
`aria-expanded` and `aria-controls`. Modals trap and restore focus.

------------------------------------------------------------------------

# 85. Responsive Content Priority

When width decreases:

### Keep

``` text
Title
Core information
Primary CTA
Critical status
Navigation
```

### Collapse / Move

``` text
Secondary metadata
Long descriptions
Secondary actions
Related content
Large decorative imagery
```

Never hide critical purchase or learning information simply to save
space.

------------------------------------------------------------------------

# 86. Search / Filter Behavior by Device

  Device    Search        Filters
  --------- ------------- ------------------
  Mobile    Full width    Bottom sheet
  Tablet    Full width    Drawer
  Laptop    Full width    Sidebar
  Desktop   Full width    Sidebar
  Large     Constrained   Sidebar
  TV        Large field   Horizontal chips

------------------------------------------------------------------------

# 87. Public Page State Matrix

Every public content page must define:

``` text
Loading
Loaded
Empty
404
Error
```

Protected detail pages additionally:

``` text
401
403
```

------------------------------------------------------------------------

# 88. Dashboard State Matrix

Every dashboard page must define:

``` text
Loading
Loaded
Empty
Error
Unauthorized
Forbidden
Offline
```

CMS editors additionally:

``` text
Unsaved changes
Saving
Saved
Validation errors
Publish confirmation
```

------------------------------------------------------------------------

# 89. Universal Component Inventory

``` text
Header
Footer
Hero
SectionHeader
Breadcrumb
CourseCard
CourseMeta
PackageCard
EbookCard
WebinarCard
BlogCard
ReviewCard
InstructorCard
FAQAccordion
SearchInput
FilterDrawer
SortDropdown
Pagination
Tabs
Badge
Button
Input
Select
Textarea
Modal
Drawer
Toast
Table
DataCard
StatCard
ChartCard
ProgressBar
Timeline
Gallery
NotificationList
```

------------------------------------------------------------------------

# 90. Component Mapping --- Course Detail

``` text
Breadcrumb
CourseHero
CourseMeta
PurchaseCard
CourseOverview
CurriculumAccordion
InstructorCard
ReviewTabs
RelatedCourses
```

------------------------------------------------------------------------

# 91. Component Mapping --- Dashboard

``` text
DashboardLayout
Sidebar
Topbar
StatCard
ChartCard
DataTable
ActivityFeed
StatusBadge
NotificationList
PageHeader
FilterBar
```

------------------------------------------------------------------------

# 92. API/Data Dependencies

Examples:

``` text
Courses Listing
GET /api/v1/public/courses

Course Detail
GET /api/v1/public/courses/{slug}

Webinars
GET /api/v1/public/webinars

Blogs
GET /api/v1/public/blogs

Ebooks
GET /api/v1/public/ebooks

Student Dashboard
GET /api/v1/student/dashboard

Admin Courses
GET /api/v1/admin/courses
POST /api/v1/admin/courses
PATCH /api/v1/admin/courses/{id}
```

The complete API contract belongs in `ARCHITECTURE.md`.

------------------------------------------------------------------------

# 93. Device QA Matrix

## 320--480px

Check:

``` text
Header
Hero
Buttons
Cards
Forms
Drawer
Modal
Footer
```

## 600--1023px

Check:

``` text
Tablet navigation
2-column grids
Dashboard sidebar
Course split layout
Forms
```

## 1024--1439px

Check:

``` text
Laptop header
3-column cards
Desktop dashboard
Course detail
```

## 1440--1920px

Check:

``` text
Content max width
4-card grids
Whitespace
Dashboard density
```

## 2560--3840px

Check:

``` text
No stretched content
No microscopic UI
Large-screen typography
TV-safe margins
Chart readability
```

------------------------------------------------------------------------

# 94. Definition of Done

The wireframe implementation is complete when:

-   [ ] Every public page is wireframed
-   [ ] Every auth page is wireframed
-   [ ] Student dashboard is wireframed
-   [ ] Worker dashboard is wireframed
-   [ ] Manager dashboard is wireframed
-   [ ] Admin dashboard is wireframed
-   [ ] CRUD workflows are wireframed
-   [ ] Search/filter are wireframed
-   [ ] Checkout/enrollment are wireframed
-   [ ] Review system is wireframed
-   [ ] CMS workflow is wireframed
-   [ ] Permission editor is wireframed
-   [ ] Audit logs are wireframed
-   [ ] Mobile behavior is defined
-   [ ] Tablet behavior is defined
-   [ ] Laptop behavior is defined
-   [ ] Desktop behavior is defined
-   [ ] Large desktop behavior is defined
-   [ ] 4K behavior is defined
-   [ ] TV behavior is defined
-   [ ] Portrait/landscape behavior is considered
-   [ ] Loading states are defined
-   [ ] Empty states are defined
-   [ ] Error states are defined
-   [ ] Unauthorized/forbidden states are defined
-   [ ] Accessibility behavior is defined
-   [ ] Component mapping is defined
-   [ ] API/data dependencies are identified

------------------------------------------------------------------------

# 95. Final Wireframe Principle

> **Every screen should make the user's next action obvious without
> making the interface visually noisy.**

Emisha Academy should therefore feel like the same product across:

``` text
Phone
Tablet
Laptop
Desktop
4K Monitor
TV
```

The system should adapt rather than merely shrink.
