# Emisha Academy --- Complete Website Product Requirements Document (PRD)

**Document Type:** Product Requirements Document\
**Project:** Emisha Academy\
**Product Category:** EdTech / Education & Skills Development Platform\
**Primary Language:** বাংলা\
**Secondary Language:** English\
**Document Status:** Master Product Specification --- Version 1.0\
**Prepared For:** Product, UI/UX, Laravel, Vue.js, QA, DevOps and
Content Teams\
**Target Stack:** PHP + Laravel + Vue.js + MySQL, with supporting
production technologies as required

------------------------------------------------------------------------

## 1. Executive Summary

Emisha Academy is a bilingual, content-driven EdTech platform designed
to present and sell professional courses, webinars/seminars and ebooks
while also providing a structured authenticated learning and management
ecosystem.

The public-facing website must feel **premium, calm, modern, trustworthy
and educational**, rather than looking like a generic e-commerce store.
The visual direction should use a **dark-first bluish interface**, a
refined **golden brand accent**, soft surfaces, generous whitespace,
rounded rectangular components, subtle depth and smooth
micro-interactions.

The primary audience is Bangla-speaking learners. Therefore:

-   Bangla is the default UI/content language.
-   Hind Siliguri is the primary Bangla font.
-   Poppins is the primary English font.
-   Every important public-facing content entity should support
    bilingual content.
-   Admin/Manager/Worker users must be able to create and manage
    localized content without developer intervention.
-   The architecture must keep content, permissions, media and business
    rules separate enough that the platform can grow without requiring a
    rewrite.

The public website will contain:

1.  Home
2.  Courses
3.  Course Details / Slug
4.  Webinars / Seminars
5.  Webinar / Seminar Details / Slug
6.  About Us
7.  Contact Us
8.  Blog
9.  Blog Details / Slug
10. Ebooks
11. Ebook Details / Slug

The authenticated system will contain:

1.  Student Dashboard
2.  Manager Dashboard
3.  Worker / Moderator Dashboard
4.  Admin Dashboard

The platform should be treated as a **content-management + commerce +
learning-management foundation**, even if the first release does not
expose every LMS capability publicly.

------------------------------------------------------------------------

# 2. Product Vision

## 2.1 Vision

Build a trusted Bangla-first learning platform where a learner can:

-   discover a course;
-   understand exactly what the course offers;
-   evaluate its level, duration, capacity, instructor and reviews;
-   inspect free classes before purchasing;
-   purchase or reserve a seat;
-   access learning resources through an authenticated account;
-   attend webinars/seminars;
-   discover ebooks;
-   read educational articles;
-   receive support;
-   and remain connected with the broader Emisha ecosystem.

## 2.2 Product Promise

The website should communicate:

> **Quality over quantity, guided learning over random content, and
> practical education over superficial course selling.**

Because Emisha Academy intentionally limits batch capacity, the product
should make **limited seats, instructor quality, structured learning and
learner support** visible throughout the experience.

## 2.3 Business Goals

### Primary goals

-   Generate qualified course leads.
-   Convert visitors into enrolled students.
-   Showcase educational credibility.
-   Build long-term learner trust.
-   Sell courses and ebooks.
-   Promote webinars and seminars.
-   Capture contact and admission inquiries.
-   Build a reusable content-management system.
-   Reduce dependency on developers for routine content changes.
-   Create an operational dashboard for academy staff.

### Secondary goals

-   Build organic traffic through SEO-friendly course, blog, webinar and
    ebook pages.
-   Build a reusable learner database.
-   Enable staff to manage content, leads, enrollments, reviews and
    media.
-   Establish a foundation for future LMS functionality.
-   Cross-promote related Emisha businesses and initiatives.

------------------------------------------------------------------------

# 3. Design Inspiration Boundary

## 3.1 Primary Design Reference

The provided primary inspiration website is:

**Computer School 24 --- computerschool24.com**

Only the **general component language, information architecture patterns
and interaction ideas** should be studied from this reference.

The design must NOT become a clone.

Relevant inspiration patterns include:

-   clear educational hero section;
-   course discovery;
-   course cards;
-   structured course detail presentation;
-   feature/value proposition section;
-   free/demo class presentation;
-   student feedback;
-   FAQ;
-   related initiatives;
-   conversion-focused CTAs;
-   consistent footer;
-   clear course filtering.

The reference demonstrates how an EdTech site can combine educational
content, course discovery, social proof and conversion elements in one
coherent page.

## 3.2 Explicitly Excluded Sources

The competitor/demo URLs supplied in the brief are **not design
inspiration sources** and must not be copied or visually imitated.

They may exist in the project brief as contextual references only. No
layout, copy, visual style, component structure, branding, illustration
style or content should be copied from them.

## 3.3 Originality Requirement

The final UI must have its own:

-   component styling;
-   spacing system;
-   color tokens;
-   card geometry;
-   icon treatment;
-   typography hierarchy;
-   navigation behavior;
-   imagery treatment;
-   motion language;
-   dashboard architecture.

------------------------------------------------------------------------

# 4. Target Users

## 4.1 Public Visitor

A visitor who has not created an account.

Goals:

-   understand Emisha Academy;
-   browse courses;
-   compare offerings;
-   inspect course details;
-   watch free classes;
-   inspect reviews;
-   browse webinars;
-   browse ebooks;
-   read blogs;
-   contact the academy;
-   initiate enrollment.

Permissions:

-   read published public content;
-   submit contact/inquiry forms;
-   optionally register;
-   optionally add public products/courses to cart where applicable.

------------------------------------------------------------------------

## 4.2 Student

An authenticated learner.

Goals:

-   manage profile;
-   see enrolled courses;
-   access permitted course content;
-   track learning progress;
-   view purchases/enrollments;
-   access receipts/order history;
-   manage webinar registrations;
-   access purchased/downloadable ebooks;
-   submit reviews where permitted;
-   receive notifications;
-   communicate with support.

Student must never receive staff-level content-management permissions.

------------------------------------------------------------------------

## 4.3 Worker / Moderator

Operational content/support staff.

Typical responsibilities:

-   manage assigned content;
-   moderate reviews;
-   manage leads;
-   update selected course content;
-   manage blog posts;
-   manage webinars;
-   manage ebooks;
-   manage media;
-   respond to contact inquiries;
-   moderate learner-generated content.

Workers must operate within explicit permission scopes.

------------------------------------------------------------------------

## 4.4 Manager

A senior operational role.

Typical responsibilities:

-   manage most academy content;
-   manage courses;
-   manage batches/capacity;
-   manage instructors;
-   manage webinars;
-   manage ebooks;
-   manage blogs;
-   manage reviews;
-   manage packages;
-   manage leads;
-   manage staff assignments;
-   view reports.

Manager should not automatically receive unrestricted
system/security/configuration privileges.

------------------------------------------------------------------------

## 4.5 Admin

System owner / highest application role.

Responsibilities:

-   complete content management;
-   user management;
-   role and permission management;
-   system settings;
-   audit logs;
-   payment configuration;
-   media governance;
-   SEO defaults;
-   navigation/footer configuration;
-   localization;
-   security configuration;
-   backups/integrations where exposed through the application.

------------------------------------------------------------------------

# 5. Core Product Principles

1.  **Bangla-first**
2.  **Mobile-first**
3.  **Accessible by default**
4.  **SEO-friendly**
5.  **Content editable without code**
6.  **Permission-driven**
7.  **Secure by default**
8.  **Fast page rendering**
9.  **Consistent components**
10. **No unnecessary visual noise**
11. **Clear conversion paths**
12. **Limited-capacity courses must expose seat availability
    accurately**
13. **No fake scarcity**
14. **No hard-coded business content**
15. **Every destructive staff action requires appropriate
    authorization**

------------------------------------------------------------------------

# 6. Brand & Visual Product Requirements

## 6.1 Brand Color

Primary brand accent:

-   Golden / warm metallic-inspired gold.

Recommended token direction:

-   `brand-50`
-   `brand-100`
-   `brand-200`
-   `brand-300`
-   `brand-400`
-   `brand-500`
-   `brand-600`
-   `brand-700`
-   `brand-800`
-   `brand-900`

The exact production hex values should be finalized during the Design MD
phase.

## 6.2 Primary Color Family

Bluish colors should form the primary interface family.

Dark mode:

-   deep blue;
-   blue-black;
-   slate-blue;
-   soft bluish surfaces.

Light mode:

-   very light blue/gray;
-   off-white;
-   cool neutral surfaces.

## 6.3 Typography

### Bangla

**Hind Siliguri**

Use for:

-   headings;
-   body text;
-   buttons;
-   labels;
-   cards;
-   forms;
-   navigation;
-   dashboard UI.

### English

**Poppins**

Use for:

-   English headings;
-   English body text;
-   numbers where appropriate;
-   navigation;
-   technical labels;
-   metadata.

The application must support mixed Bangla/English strings without broken
baselines or inconsistent line heights.

## 6.4 Shape Language

Primary shape:

-   rounded rectangles.

Avoid:

-   excessive pills;
-   excessive circles;
-   sharp dense boxes;
-   overly decorative cards.

Use:

-   medium-to-large border radius;
-   soft borders;
-   subtle shadows;
-   layered surfaces;
-   clean spacing.

## 6.5 Visual Density

The site should feel:

-   spacious;
-   calm;
-   premium;
-   readable;
-   breathable.

Avoid:

-   excessive gradients;
-   aggressive neon colors;
-   excessive animation;
-   too many badges;
-   cluttered dashboards;
-   excessive drop shadows.

------------------------------------------------------------------------

# 7. Theme Requirements

## 7.1 Dark Theme

Dark theme is the primary theme.

Characteristics:

-   deep bluish background;
-   slightly lighter blue surface;
-   golden highlights;
-   soft white text;
-   muted blue-gray secondary text;
-   visible but subtle borders;
-   carefully controlled contrast.

## 7.2 Light Theme

Light theme should use the same design tokens semantically.

Characteristics:

-   warm/cool off-white background;
-   pale bluish surfaces;
-   dark blue text;
-   gold accent;
-   subtle borders;
-   restrained shadows.

## 7.3 Theme Persistence

Theme selection should persist using:

-   authenticated user preference when logged in;
-   local storage/cookie for guests;
-   system preference as initial fallback.

------------------------------------------------------------------------

# 8. Localization Requirements

## 8.1 Languages

-   `bn` --- primary
-   `en` --- secondary

## 8.2 Localization Architecture

Static interface strings must not be hard-coded directly into Vue
components.

Use translation keys for:

-   navigation;
-   buttons;
-   validation messages;
-   empty states;
-   system messages;
-   dashboard UI;
-   form labels;
-   accessibility labels.

Dynamic content should support bilingual fields where required.

Example conceptual structure:

``` text
course.title.bn
course.title.en

course.short_description.bn
course.short_description.en

course.description.bn
course.description.en
```

## 8.3 Language Toggle

Global toggle:

``` text
বাংলা | English
```

Behavior:

-   change current locale;
-   preserve current route where possible;
-   preserve content context;
-   update document language;
-   update metadata where appropriate;
-   persist preference.

------------------------------------------------------------------------

# 9. Public Website Information Architecture

## Primary Navigation

Recommended structure:

``` text
Logo
হোম
কোর্সসমূহ
ওয়েবিনার
ইবুক
ব্লগ
আমাদের সম্পর্কে
যোগাযোগ
[Language]
[Theme]
[Login / Dashboard]
```

Optional future navigation:

-   Packages
-   Free Classes
-   Student Reviews

These may be represented as homepage sections rather than top-level
pages initially.

------------------------------------------------------------------------

# 10. Homepage PRD

## 10.1 Homepage Objective

The homepage must answer within the first viewport:

1.  What is Emisha Academy?
2.  What can I learn here?
3.  Why should I trust it?
4.  How do I start?

## 10.2 Section Order

Recommended canonical order:

1.  Header
2.  Hero
3.  Trust / quick statistics
4.  Our Courses
5.  Our Ebooks
6.  Why Choose Emisha Academy
7.  Course Packages
8.  Free Classes
9.  Student Video Reviews
10. Student Image/Text Reviews
11. FAQ
12. Emisha Ecosystem / Capabilities
13. Final CTA
14. Universal Footer

------------------------------------------------------------------------

## 10.3 Hero Section

### Content

-   headline;
-   supporting description;
-   primary CTA;
-   secondary CTA;
-   visual/illustration;
-   optional floating trust indicators.

Example content direction:

> **শিখুন দক্ষতার সাথে, এগিয়ে যান আত্মবিশ্বাসের সাথে।**

The exact copy should be finalized separately.

### CTAs

Primary:

-   কোর্স দেখুন

Secondary:

-   ফ্রি ক্লাস দেখুন

Optional:

-   পরামর্শ নিন

### Functional Requirements

-   CMS editable;
-   bilingual;
-   optional hero image/video;
-   CTA URL editable;
-   visibility toggle;
-   ordering;
-   SEO-safe heading hierarchy.

------------------------------------------------------------------------

# 11. Our Courses Section

## Requirements

Display:

-   featured courses;
-   category;
-   title;
-   short description;
-   price;
-   previous price if discounted;
-   course type;
-   level;
-   duration;
-   capacity/seat state;
-   CTA.

Actions:

-   বিস্তারিত দেখুন
-   ভর্তি / Buy
-   কার্টে যোগ করুন where applicable

Course cards must be reusable components.

------------------------------------------------------------------------

# 12. Our Ebooks Section

Display:

-   cover image;
-   title;
-   author;
-   short description;
-   language;
-   version;
-   page count;
-   price/free state;
-   CTA.

Actions:

-   বিস্তারিত
-   কিনুন / Download depending on business model.

------------------------------------------------------------------------

# 13. Why Choose Emisha Academy

This section communicates value propositions.

Potential value propositions:

-   সীমিত ব্যাচ সাইজ;
-   অভিজ্ঞ ইনস্ট্রাক্টর;
-   প্র্যাকটিক্যাল লার্নিং;
-   structured curriculum;
-   learner support;
-   career guidance;
-   quality-focused learning;
-   Bengali-first teaching;
-   real-world examples.

Every item must be CMS editable.

Fields:

-   icon;
-   title BN;
-   title EN;
-   description BN;
-   description EN;
-   sort order;
-   active status.

------------------------------------------------------------------------

# 14. Course Packages

A package combines multiple courses.

## Package Fields

-   title;
-   slug;
-   short description;
-   long description;
-   package image;
-   included courses;
-   regular combined price;
-   package price;
-   discount;
-   validity;
-   seat/capacity rule;
-   featured status;
-   active status;
-   published_at;
-   SEO metadata.

## Package Behavior

A package should be treated as a first-class product entity rather than
simply hard-coded homepage content.

------------------------------------------------------------------------

# 15. Free Classes

Purpose:

-   demonstrate teaching quality;
-   build trust;
-   generate leads;
-   help users evaluate course fit.

Each free class can include:

-   title;
-   thumbnail;
-   video URL/embed;
-   duration;
-   category;
-   related course;
-   instructor;
-   description;
-   publication date;
-   visibility;
-   language.

Optional:

-   gated viewing;
-   lead capture;
-   CTA after video.

------------------------------------------------------------------------

# 16. Student Reviews

Review types:

1.  Video review
2.  Text review
3.  Image review

Fields:

-   student name;
-   student avatar;
-   course;
-   review type;
-   rating;
-   title;
-   review text;
-   video URL;
-   image;
-   batch;
-   verified flag;
-   publication status;
-   moderation status.

Homepage should provide tabs/toggles:

``` text
ভিডিও রিভিউ | টেক্সট রিভিউ | ইমেজ রিভিউ
```

------------------------------------------------------------------------

# 17. FAQ

FAQ fields:

-   question BN;
-   question EN;
-   answer BN;
-   answer EN;
-   category;
-   sort order;
-   active status.

Support:

-   accordion;
-   search later as optional enhancement;
-   schema markup for eligible public FAQs.

------------------------------------------------------------------------

# 18. Emisha Ecosystem Section

The academy should show its wider organizational capabilities without
confusing users.

Possible entities:

-   Emisha Tours & Travels;
-   Harmain;
-   Safar;
-   Hajj & Umrah;
-   Visa Processing.

Each organization/initiative should be represented as a CMS entity:

-   name;
-   logo;
-   description;
-   website;
-   category;
-   image;
-   CTA;
-   active status.

Important:

This section must communicate ecosystem breadth, not distract from the
academy's educational purpose.

------------------------------------------------------------------------

# 19. Final CTA

Purpose:

Convert undecided visitors.

Possible actions:

-   Explore Courses
-   Talk to an Advisor
-   Join a Free Class

Admin-editable:

-   title;
-   description;
-   CTA;
-   image/background;
-   visibility.

------------------------------------------------------------------------

# 20. Universal Footer

The footer appears on every public page.

Suggested groups:

### Academy

-   About
-   Courses
-   Webinars
-   Ebooks
-   Blog

### Support

-   Contact
-   FAQ
-   Student Login
-   Help

### Legal

-   Privacy Policy
-   Terms
-   Refund Policy
-   Student Policy

### Ecosystem

-   Emisha Tours & Travels
-   Harmain
-   Safar
-   Hajj & Umrah
-   Visa Processing

### Contact

-   address;
-   phone;
-   email;
-   social links;
-   map link.

Footer must be CMS editable.

------------------------------------------------------------------------

# 21. Courses Listing Page

## Objectives

Allow users to:

-   discover;
-   search;
-   filter;
-   sort;
-   compare;
-   open course details.

## Search

Search fields:

-   title;
-   short description;
-   instructor;
-   category;
-   tag.

Search should support Bangla and English.

## Filters

Recommended:

-   category;
-   course type;
-   level;
-   language;
-   price range;
-   availability;
-   instructor;
-   duration;
-   featured;
-   new/popular.

## Sorting

-   newest;
-   price low-to-high;
-   price high-to-low;
-   popularity;
-   recommended;
-   starting soon.

## Course Card

Minimum:

-   image;
-   category;
-   title;
-   short description;
-   level;
-   duration;
-   lessons;
-   instructor;
-   price;
-   discount;
-   availability;
-   CTA.

------------------------------------------------------------------------

# 22. Course Detail Page

## 22.1 Course Header

Display:

-   course title;
-   subtitle;
-   category;
-   rating;
-   review count;
-   instructor;
-   course type;
-   language;
-   level;
-   duration;
-   total lessons;
-   capacity;
-   seats remaining;
-   price;
-   discount;
-   enrollment state.

## 22.2 Business Rule: Limited Capacity

The system must support real seat capacity.

Example:

``` text
capacity = 25
enrolled = 21
remaining = 4
```

The public site should display:

> সীমিত আসন --- ৪টি সিট বাকি

Only if the data is accurate.

Do not expose misleading artificial scarcity.

## 22.3 Purchase Actions

Depending on course configuration:

-   Add to Cart
-   Buy Now
-   Enroll
-   Register Interest

If the course is full:

-   disable purchase;
-   show full message;
-   allow waitlist if enabled.

------------------------------------------------------------------------

# 23. Course Content

Course details can contain:

-   overview;
-   learning outcomes;
-   curriculum;
-   modules;
-   lessons;
-   requirements;
-   resources;
-   certification;
-   support;
-   FAQs;
-   policies.

## Module

Fields:

-   title;
-   description;
-   sequence;
-   estimated duration.

## Lesson

Fields:

-   title;
-   type;
-   duration;
-   video;
-   text;
-   attachment;
-   preview;
-   sequence;
-   published status.

Lesson types can include:

-   video;
-   text;
-   PDF;
-   external resource;
-   quiz --- future;
-   assignment --- future.

------------------------------------------------------------------------

# 24. Instructor Section

Each instructor entity:

-   name;
-   profile image;
-   designation;
-   short bio;
-   long bio;
-   experience;
-   expertise;
-   social links;
-   courses;
-   active status.

Course pages should display instructor information.

------------------------------------------------------------------------

# 25. Course Reviews

Course-specific reviews must be filtered by the current course.

Tabs:

``` text
ভিডিও রিভিউ
টেক্সট রিভিউ
```

Optional:

``` text
সব রিভিউ
```

Review moderation:

``` text
Pending → Approved → Published
             ↓
          Rejected
```

Workers may moderate if permission is granted.

------------------------------------------------------------------------

# 26. Related Courses

Related courses should be algorithmic or manually curated.

Matching signals:

-   same category;
-   same tags;
-   same level;
-   shared instructor;
-   related skills;
-   manual admin selection.

Admin should be able to override automatic suggestions.

------------------------------------------------------------------------

# 27. Webinar / Seminar Listing

Display all published webinars/seminars.

## Search

Search:

-   title;
-   speaker;
-   topic;
-   category;
-   keyword.

## Filters

-   upcoming;
-   past;
-   category;
-   level;
-   language;
-   format;
-   free/paid.

## Card

-   image;
-   title;
-   short description;
-   speaker;
-   date;
-   time;
-   duration;
-   language;
-   level;
-   registration state.

------------------------------------------------------------------------

# 28. Webinar / Seminar Detail Page

Fields:

-   title;
-   slug;
-   overview;
-   description;
-   date;
-   start time;
-   end time;
-   timezone;
-   language;
-   level;
-   prerequisites;
-   instructor/speaker;
-   format;
-   meeting location/link;
-   registration deadline;
-   capacity;
-   remaining seats;
-   price;
-   related courses;
-   related webinars.

Actions:

-   Register
-   Buy/Reserve
-   Add to Calendar
-   Contact Support

Private meeting links must never be publicly exposed before
authorization if the event requires authenticated access.

------------------------------------------------------------------------

# 29. About Page

Required sections:

1.  Our Story
2.  Our Mission
3.  Our Vision
4.  Our Values
5.  Our Journey / Milestones
6.  Academy Moments / Gallery
7.  Team / Instructors
8.  Related Institutions
9.  Final CTA

## Milestones

Fields:

-   year/date;
-   title;
-   description;
-   image;
-   sequence.

## Gallery

Fields:

-   image;
-   title;
-   description;
-   category;
-   date;
-   active status.

------------------------------------------------------------------------

# 30. Contact Page

## Contact Form

Fields:

-   name;
-   email;
-   phone;
-   subject;
-   message;
-   preferred contact method;
-   optional course;
-   optional consent checkbox.

Security:

-   validation;
-   rate limiting;
-   spam protection;
-   server-side validation;
-   CSRF protection where session-based;
-   sanitization/output escaping.

## Contact Information

Editable:

-   office address;
-   phone;
-   email;
-   hotline;
-   WhatsApp;
-   map coordinates;
-   map URL;
-   business hours;
-   social links.

------------------------------------------------------------------------

# 31. Blog Listing Page

Features:

-   article cards;
-   search;
-   category filter;
-   tag filter;
-   author filter;
-   date filter;
-   pagination.

Card:

-   image;
-   category;
-   title;
-   excerpt;
-   author;
-   date;
-   read time;
-   CTA.

------------------------------------------------------------------------

# 32. Blog Detail Page

Display:

-   title;
-   cover image;
-   author;
-   publication date;
-   updated date;
-   category;
-   reading time;
-   body;
-   related articles;
-   tags;
-   share actions.

Content should support rich text but must be sanitized.

Recommended:

-   controlled rich text editor;
-   no arbitrary executable HTML;
-   media whitelist;
-   sanitized embeds.

------------------------------------------------------------------------

# 33. Ebook Listing Page

Features:

-   ebook cards;
-   search;
-   category;
-   language;
-   country/target market;
-   author;
-   version;
-   price/free filter.

------------------------------------------------------------------------

# 34. Ebook Detail Page

Required fields:

-   ebook title;
-   subtitle;
-   cover;
-   author;
-   version;
-   page count;
-   language;
-   target country/countries;
-   description;
-   table of contents;
-   publication date;
-   file size;
-   format;
-   price;
-   preview;
-   related ebooks.

Potential formats:

-   PDF;
-   EPUB;
-   other supported formats.

Downloaded files must be protected and served only to authorized users
where the ebook is paid/private.

------------------------------------------------------------------------

# 35. Student Dashboard PRD

## Dashboard Home

Widgets:

-   enrolled courses;
-   active courses;
-   completed courses;
-   upcoming webinars;
-   recent purchases;
-   learning progress;
-   notifications.

## My Courses

-   course list;
-   progress;
-   last lesson;
-   continue button;
-   completion status.

## Course Learning View

-   curriculum sidebar;
-   lesson player;
-   lesson navigation;
-   progress;
-   resources;
-   notes --- future;
-   quiz --- future;
-   assignment --- future.

## My Orders

-   order ID;
-   date;
-   amount;
-   status;
-   invoice;
-   items.

## My Ebooks

-   purchased ebooks;
-   download;
-   version;
-   access status.

## My Webinars

-   registered webinars;
-   date/time;
-   joining information;
-   calendar action.

## Reviews

Student can review eligible completed/enrolled courses according to
business rules.

------------------------------------------------------------------------

# 36. Worker / Moderator Dashboard

## Core Modules

-   dashboard;
-   assigned courses;
-   assigned webinars;
-   blog management;
-   ebook management;
-   review moderation;
-   free classes;
-   student inquiries;
-   contact submissions;
-   media;
-   notifications.

Workers should see only modules permitted by their role/permission set.

## Assignment Model

Managers/Admins may assign content ownership to workers.

Example:

``` text
Worker A → Blog + Review moderation
Worker B → Course content
Worker C → Webinar + Leads
```

------------------------------------------------------------------------

# 37. Manager Dashboard

Manager can manage operational academy resources.

Modules:

-   dashboard;
-   courses;
-   course categories;
-   batches;
-   packages;
-   instructors;
-   webinars;
-   seminars;
-   ebooks;
-   blogs;
-   free classes;
-   reviews;
-   FAQs;
-   homepage sections;
-   leads;
-   students;
-   orders;
-   media;
-   reports;
-   staff assignments.

Manager should not automatically control:

-   system root settings;
-   role creation;
-   permission architecture;
-   security settings;
-   audit deletion;
-   administrator account management.

------------------------------------------------------------------------

# 38. Admin Dashboard

Admin has full application governance.

Modules:

-   overview;
-   users;
-   roles;
-   permissions;
-   students;
-   managers;
-   workers;
-   courses;
-   modules;
-   lessons;
-   batches;
-   packages;
-   instructors;
-   webinars;
-   seminars;
-   ebooks;
-   blogs;
-   reviews;
-   FAQs;
-   homepage;
-   pages;
-   menus;
-   footer;
-   media;
-   leads;
-   orders;
-   payments;
-   notifications;
-   translations;
-   SEO;
-   settings;
-   audit logs;
-   integrations;
-   system health.

------------------------------------------------------------------------

# 39. Role-Based Access Control

Use permission-based authorization rather than relying only on role
names.

## Core roles

``` text
admin
manager
worker
student
```

## Permission naming convention

``` text
courses.view
courses.create
courses.update
courses.delete
courses.publish
courses.archive

course_modules.view
course_modules.create
course_modules.update
course_modules.delete

lessons.view
lessons.create
lessons.update
lessons.delete
lessons.publish

webinars.view
webinars.create
webinars.update
webinars.delete
webinars.publish

ebooks.view
ebooks.create
ebooks.update
ebooks.delete
ebooks.publish

blogs.view
blogs.create
blogs.update
blogs.delete
blogs.publish

reviews.view
reviews.moderate
reviews.publish
reviews.delete

users.view
users.create
users.update
users.delete

roles.view
roles.create
roles.update
roles.delete

settings.view
settings.update

audit_logs.view
```

------------------------------------------------------------------------

# 40. Access Control Matrix

  Capability                             Student          Worker   Manager   Admin
  ------------------------ --------------------- --------------- --------- -------
  Public content view                        Yes             Yes       Yes     Yes
  Own profile edit                           Yes             Yes       Yes     Yes
  Own course access                          Yes              No        No     Yes
  Create course                               No   Assigned only       Yes     Yes
  Edit course                                 No   Assigned only       Yes     Yes
  Delete course                               No   No by default   Limited     Yes
  Publish course                              No        Optional       Yes     Yes
  Manage modules                              No        Assigned       Yes     Yes
  Manage lessons                              No        Assigned       Yes     Yes
  Manage instructors                          No        Optional       Yes     Yes
  Manage packages                             No        Optional       Yes     Yes
  Manage webinars                             No        Assigned       Yes     Yes
  Manage ebooks                               No        Assigned       Yes     Yes
  Manage blogs                                No        Assigned       Yes     Yes
  Moderate reviews           Own submission only             Yes       Yes     Yes
  Manage homepage                             No        Optional       Yes     Yes
  Manage FAQ                                  No        Assigned       Yes     Yes
  Manage leads                                No        Assigned       Yes     Yes
  View students                         Own data         Limited       Yes     Yes
  Manage users                                No              No   Limited     Yes
  Manage roles                                No              No        No     Yes
  Manage permissions                          No              No        No     Yes
  Manage system settings                      No              No        No     Yes
  View audit logs                             No         Limited   Limited     Yes
  Payment configuration                       No              No        No     Yes

------------------------------------------------------------------------

# 41. Authorization Principles

Authorization must be enforced on the server.

Never trust:

-   hidden Vue buttons;
-   route visibility;
-   frontend role checks;
-   request payload role fields.

Every protected API endpoint must independently authorize the action.

Use:

-   Laravel authentication;
-   policies/gates;
-   middleware;
-   permission checks;
-   resource ownership checks;
-   server-side validation.

Laravel provides Gates and Policies for authorization, and these should
be used as the application's authorization primitives rather than
implementing scattered role checks.

------------------------------------------------------------------------

# 42. Authentication Requirements

Authentication should support:

-   registration;
-   login;
-   logout;
-   email verification;
-   password reset;
-   password change;
-   remember-me where appropriate;
-   optional 2FA for staff;
-   session invalidation;
-   account status.

For a Vue SPA communicating with Laravel, Laravel Sanctum is a suitable
authentication foundation. Sanctum supports SPA authentication using
cookie-based session authentication for first-party SPAs and provides
CSRF/session protections.

Staff accounts should have stronger controls than ordinary student
accounts.

------------------------------------------------------------------------

# 43. Account Status

Users can have:

``` text
active
pending
suspended
blocked
deactivated
```

Blocked/suspended users must not be able to authenticate or perform
protected operations according to policy.

------------------------------------------------------------------------

# 44. Content Publishing Workflow

Recommended workflow:

``` text
Draft
  ↓
Review
  ↓
Approved
  ↓
Published
  ↓
Archived
```

Workers may create/edit drafts.

Managers may approve/publish depending on permission.

Admins have final override.

This workflow is especially useful for:

-   courses;
-   webinars;
-   blogs;
-   ebooks;
-   homepage content;
-   reviews.

------------------------------------------------------------------------

# 45. Audit Logging

Every sensitive administrative mutation should create an audit record.

Log:

-   actor;
-   actor role;
-   action;
-   entity;
-   entity ID;
-   before state summary;
-   after state summary;
-   IP address;
-   user agent;
-   timestamp;
-   request/correlation ID.

Examples:

``` text
Admin updated course #32
Manager published blog #91
Worker approved review #120
Admin changed payment settings
Manager changed course capacity
```

Audit logs should be append-oriented and protected from normal staff
deletion.

------------------------------------------------------------------------

# 46. Course Capacity & Enrollment Integrity

Capacity is a business-critical field.

The system must avoid:

``` text
capacity = 25
two simultaneous users both see 1 remaining seat
both complete enrollment
actual enrollment = 26
```

Enrollment allocation must use transactional database logic.

Recommended approach:

1.  begin transaction;
2.  lock/read the relevant batch or seat allocation record;
3.  verify available capacity;
4.  create enrollment/order;
5.  increment reserved/enrolled count;
6.  commit;
7.  release lock.

MySQL/InnoDB transactional behavior and foreign-key constraints should
be used to protect relational consistency.

------------------------------------------------------------------------

# 47. Cart & Order Requirements

The platform may support:

-   add to cart;
-   remove from cart;
-   quantity where relevant;
-   coupon later;
-   checkout;
-   payment;
-   order confirmation;
-   invoice;
-   order history.

Cart item types:

-   course;
-   package;
-   ebook;
-   webinar.

A product must be revalidated during checkout.

Do not trust client-submitted:

-   price;
-   discount;
-   capacity;
-   product status.

The server must calculate the final payable amount.

------------------------------------------------------------------------

# 48. Payment Architecture

Payment provider is configurable.

Potential Bangladesh-focused integrations can be added later through a
payment abstraction layer.

The application should define:

``` text
PaymentGatewayInterface
```

with operations such as:

``` text
createPayment()
verifyPayment()
refundPayment()
handleCallback()
```

This prevents the business domain from becoming tightly coupled to one
provider.

------------------------------------------------------------------------

# 49. Notification System

Notification channels:

-   in-app;
-   email;
-   optional SMS;
-   optional WhatsApp integration.

Notification events:

-   registration;
-   email verification;
-   password reset;
-   enrollment;
-   payment success;
-   payment failure;
-   course update;
-   webinar reminder;
-   seat availability;
-   review approval;
-   admin announcements.

Laravel supports queued notifications, which is useful for external
delivery operations that should not block a user's request.

------------------------------------------------------------------------

# 50. Media Management

Central media library should support:

-   image upload;
-   image replacement;
-   video thumbnail;
-   document upload;
-   PDF;
-   ebook file;
-   course resource;
-   profile image.

Metadata:

-   original name;
-   file name;
-   MIME type;
-   size;
-   dimensions;
-   alt text;
-   caption;
-   uploader;
-   folder/category;
-   visibility.

Images should be optimized and delivered in modern formats where
practical.

------------------------------------------------------------------------

# 51. SEO Requirements

Every public content entity should support:

-   SEO title;
-   meta description;
-   canonical URL;
-   OG title;
-   OG description;
-   OG image;
-   Twitter/X card metadata where relevant;
-   robots configuration;
-   structured data.

Dynamic page types:

-   Course
-   Webinar
-   Blog
-   Ebook
-   Organization/About
-   FAQ

Use semantic URLs:

``` text
/courses
/courses/{slug}

/webinars
/webinars/{slug}

/blogs
/blogs/{slug}

/ebooks
/ebooks/{slug}
```

Slug requirements:

-   unique;
-   immutable by default;
-   redirect support when changed;
-   lowercase;
-   normalized;
-   collision-safe.

------------------------------------------------------------------------

# 52. Search Requirements

Search should support:

-   Bangla;
-   English;
-   partial matching;
-   title;
-   description;
-   tags;
-   category;
-   instructor.

Initial implementation can use MySQL indexing/full-text capabilities
where appropriate.

As content volume grows, the architecture should permit migration to a
dedicated search engine without changing the public UX.

------------------------------------------------------------------------

# 53. Database Domain Model

Core entities:

``` text
User
Role
Permission
RolePermission
UserRole

Course
CourseCategory
CourseTag
CourseCourseTag
CourseModule
Lesson
CourseInstructor
Instructor

Batch
Enrollment
CourseReview

Package
PackageCourse

Webinar
WebinarSpeaker
WebinarRegistration

Ebook
EbookCategory
EbookAuthor
EbookFile

Blog
BlogCategory
BlogTag
BlogAuthor

FreeClass

FAQ

HomepageSection
HomepageItem

Organization

Media
MediaFolder

Cart
CartItem
Order
OrderItem
Payment
Refund

ContactInquiry
Lead

Notification
AuditLog

Translation / localized content structures
SEO metadata
SiteSetting
```

------------------------------------------------------------------------

# 54. Database Relationship Principles

Examples:

``` text
Course
 ├── Category
 ├── Instructor(s)
 ├── Batch(es)
 ├── Module(s)
 │    └── Lesson(s)
 ├── Review(s)
 ├── Tag(s)
 └── Related Course(s)
```

``` text
Package
 └── Course(s)
```

``` text
Webinar
 ├── Speaker(s)
 ├── Registration(s)
 └── Related Webinar(s)
```

``` text
Ebook
 ├── Author
 ├── Category
 ├── File(s)
 └── Related Ebook(s)
```

------------------------------------------------------------------------

# 55. Localization Data Strategy

Two approaches are acceptable:

### Approach A --- JSON translation columns

Example:

``` text
title = {
  "bn": "...",
  "en": "..."
}
```

Advantages:

-   simple;
-   convenient for content-heavy fields.

### Approach B --- separate translation tables

Example:

``` text
courses
course_translations
```

Recommended when:

-   content volume becomes large;
-   translation metadata is needed;
-   future languages are expected;
-   editorial workflow needs per-language status.

For Emisha Academy, the architecture should be designed so that
bilingual fields do not require code changes when additional languages
are introduced later.

------------------------------------------------------------------------

# 56. API Requirements

Recommended API grouping:

``` text
/api/v1/public/*
/api/v1/auth/*
/api/v1/student/*
/api/v1/worker/*
/api/v1/manager/*
/api/v1/admin/*
```

Public endpoints should expose only published content.

Protected endpoints must use authentication and authorization.

API responses should use consistent structure:

``` json
{
  "success": true,
  "message": "...",
  "data": {},
  "meta": {}
}
```

Error:

``` json
{
  "success": false,
  "message": "...",
  "errors": {}
}
```

------------------------------------------------------------------------

# 57. Frontend Architecture

Recommended:

-   Vue 3;
-   Composition API;
-   TypeScript;
-   Vue Router;
-   Pinia;
-   Vite;
-   reusable component system;
-   Tailwind CSS or an equivalent token-driven CSS architecture.

Vue 3's Composition API is appropriate for reusable reactive logic, and
Vue Router provides composables for route/navigation access.

Recommended frontend layers:

``` text
components/
layouts/
pages/
composables/
stores/
services/
types/
utils/
router/
i18n/
```

------------------------------------------------------------------------

# 58. Component Architecture

Core components:

``` text
AppHeader
AppFooter
LanguageSwitcher
ThemeSwitcher
SearchBar
FilterPanel
CourseCard
CourseGrid
CourseMeta
PriceDisplay
CapacityBadge
InstructorCard
ReviewCard
VideoReviewCard
TextReviewCard
EbookCard
WebinarCard
BlogCard
FAQAccordion
HeroSection
SectionHeader
CTASection
Pagination
EmptyState
LoadingState
ErrorState
Modal
Drawer
Toast
ConfirmDialog
```

Dashboard components:

``` text
DashboardShell
Sidebar
Topbar
StatCard
DataTable
DataTableToolbar
FormDrawer
FormModal
StatusBadge
PermissionGate
AuditTimeline
```

------------------------------------------------------------------------

# 59. Responsive Requirements

The entire application must work across:

-   mobile;
-   tablet;
-   laptop;
-   desktop;
-   large desktop.

Recommended breakpoints will be finalized in Design MD.

No feature should depend on hover only.

Mobile requirements:

-   collapsible navigation;
-   touch-friendly controls;
-   filter drawer;
-   horizontally scrollable tabs where necessary;
-   sticky bottom CTA only where useful;
-   readable tables with responsive alternatives;
-   dashboard sidebar becomes drawer.

------------------------------------------------------------------------

# 60. Accessibility

Minimum requirements:

-   semantic HTML;
-   keyboard navigation;
-   visible focus states;
-   sufficient contrast;
-   form labels;
-   meaningful error messages;
-   alt text;
-   accessible modal behavior;
-   accessible tabs;
-   accessible accordions;
-   screen-reader-friendly status updates.

Video content should support captions/subtitles where available.

------------------------------------------------------------------------

# 61. Performance Requirements

Target principles:

-   optimized images;
-   lazy loading;
-   code splitting;
-   route-level chunking;
-   caching;
-   compressed assets;
-   minimized API payloads;
-   pagination;
-   database indexing;
-   queueing for expensive jobs.

MySQL indexing should be intentional: indexes improve lookup performance
but add storage and write/update cost, so indexes should be chosen
around real query patterns rather than added indiscriminately.

------------------------------------------------------------------------

# 62. Caching

Potential cache targets:

-   homepage sections;
-   published course listings;
-   categories;
-   FAQs;
-   footer/navigation;
-   public settings;
-   related content.

Cache invalidation should occur when relevant content is:

-   published;
-   updated;
-   unpublished;
-   deleted.

------------------------------------------------------------------------

# 63. Security Requirements

Follow a secure-development baseline informed by OWASP ASVS.

Required controls:

-   password hashing;
-   CSRF protection;
-   authorization;
-   input validation;
-   output escaping;
-   rate limiting;
-   secure session handling;
-   file upload validation;
-   MIME validation;
-   upload size limits;
-   private file access controls;
-   SQL injection prevention through ORM/query binding;
-   XSS prevention;
-   secure headers;
-   HTTPS in production;
-   secret management;
-   audit logging;
-   least privilege;
-   account lock/suspension controls.

OWASP ASVS is specifically intended as a basis for verifying web
application security controls and secure-development requirements.

------------------------------------------------------------------------

# 64. File Security

Never trust file extension alone.

Validate:

-   MIME;
-   extension;
-   actual file signature where relevant;
-   size;
-   dimensions;
-   upload destination.

Private course resources and paid ebook files should not be exposed
through guessable public URLs.

Use authorized download endpoints or signed temporary URLs where
applicable.

------------------------------------------------------------------------

# 65. Admin Content Editing

The main principle:

> If a business/content team should reasonably be able to change it, it
> should not be hard-coded.

Editable items include:

-   homepage headings;
-   hero;
-   CTA;
-   course details;
-   course pricing;
-   capacity;
-   instructors;
-   reviews;
-   webinar details;
-   ebook details;
-   blog;
-   FAQ;
-   footer;
-   contact info;
-   ecosystem institutions;
-   navigation;
-   SEO fields;
-   translations.

------------------------------------------------------------------------

# 66. Content Versioning

Important entities should support revision history where practical.

At minimum:

-   updated_by;
-   updated_at;
-   published_by;
-   published_at.

Advanced future capability:

``` text
Draft v1
Draft v2
Published v2
Archived v1
```

------------------------------------------------------------------------

# 67. Soft Delete Policy

Use soft deletion for entities where historical relationships matter.

Recommended:

-   courses;
-   webinars;
-   ebooks;
-   blogs;
-   instructors;
-   users;
-   reviews;
-   orders.

Do not physically delete transactional data casually.

Orders/payments should remain auditable.

------------------------------------------------------------------------

# 68. Empty States

Every listing must have meaningful empty states.

Examples:

``` text
কোনো কোর্স পাওয়া যায়নি।
আপনার ফিল্টার পরিবর্তন করে আবার চেষ্টা করুন।
```

Actions:

-   clear filters;
-   browse all;
-   contact support.

------------------------------------------------------------------------

# 69. Error Handling

User-facing errors should be human-readable.

Avoid exposing:

-   SQL errors;
-   stack traces;
-   internal file paths;
-   secrets;
-   server configuration.

Admin logs can contain technical details subject to access control.

------------------------------------------------------------------------

# 70. Admin Dashboard Analytics

Initial metrics:

-   total students;
-   active students;
-   published courses;
-   upcoming webinars;
-   total ebooks;
-   pending reviews;
-   pending leads;
-   orders;
-   revenue;
-   enrollment count;
-   course occupancy.

Future analytics:

-   conversion rate;
-   course funnel;
-   abandoned cart;
-   traffic;
-   learner engagement;
-   content performance.

------------------------------------------------------------------------

# 71. Lead Management

Lead statuses:

``` text
new
contacted
qualified
follow_up
converted
closed
lost
```

Fields:

-   name;
-   phone;
-   email;
-   source;
-   course;
-   message;
-   assigned staff;
-   status;
-   notes;
-   next follow-up;
-   created_at.

------------------------------------------------------------------------

# 72. Contact Inquiry Management

Every contact submission becomes a record.

Workers/managers can:

-   view;
-   assign;
-   reply status;
-   add notes;
-   mark resolved.

Students should never see internal staff notes.

------------------------------------------------------------------------

# 73. Course Enrollment Lifecycle

Recommended:

``` text
Interested
↓
Cart
↓
Checkout
↓
Payment Pending
↓
Payment Confirmed
↓
Enrollment Active
↓
Course In Progress
↓
Completed
↓
Certificate Eligible
```

Certificate functionality can be implemented in a later release.

------------------------------------------------------------------------

# 74. Review Eligibility

Review rules should be configurable.

Recommended initial rule:

-   student must be enrolled;
-   optionally course must be completed;
-   one review per course per student unless edited;
-   review can enter moderation;
-   staff can approve/reject.

------------------------------------------------------------------------

# 75. Review Trust

Where technically possible, display:

``` text
Verified Student
```

only when the system can verify the enrollment relationship.

Do not label manually entered testimonials as verified unless there is a
verification mechanism.

------------------------------------------------------------------------

# 76. Course Package Business Rules

Package may:

-   include multiple courses;
-   have independent price;
-   have start/end date;
-   have limited availability;
-   optionally inherit course capacity;
-   optionally create multiple enrollments after purchase.

Package purchase must be atomic enough that partial enrollment does not
occur after a successful transaction.

------------------------------------------------------------------------

# 77. Webinar Capacity

Like courses, webinars can have:

``` text
capacity
registered
remaining
waitlist_enabled
```

Registration must be concurrency-safe.

------------------------------------------------------------------------

# 78. Ebook Access

Free ebook:

-   public download or account-gated download.

Paid ebook:

-   payment required;
-   authenticated access;
-   download permission tied to order;
-   optional download limit;
-   access revocation policy configurable.

------------------------------------------------------------------------

# 79. Blog Governance

Blog status:

``` text
draft
review
scheduled
published
archived
```

Optional:

-   scheduled publication;
-   author;
-   editor;
-   SEO preview;
-   social image.

------------------------------------------------------------------------

# 80. Scheduled Publishing

Future-ready support for:

-   blog publishing;
-   webinar visibility;
-   course launch;
-   package promotion;
-   homepage campaign.

Laravel queue/scheduler infrastructure can support background jobs and
scheduled operations.

------------------------------------------------------------------------

# 81. Admin Content Builder

Homepage should use configurable sections rather than one giant
hard-coded Vue page.

Concept:

``` text
Homepage
 ├── Hero
 ├── Courses
 ├── Ebooks
 ├── Why Choose Us
 ├── Packages
 ├── Free Classes
 ├── Reviews
 ├── FAQ
 ├── Ecosystem
 └── Final CTA
```

Each section:

-   enabled/disabled;
-   orderable;
-   editable;
-   localized.

------------------------------------------------------------------------

# 82. Navigation Management

Admin should be able to:

-   add menu item;
-   edit label;
-   set URL;
-   choose internal route;
-   choose external URL;
-   reorder;
-   enable/disable;
-   choose visibility.

Do not allow untrusted workers to inject arbitrary JavaScript into URLs.

------------------------------------------------------------------------

# 83. Settings

Settings categories:

### General

-   academy name;
-   logo;
-   favicon;
-   default language;
-   default theme.

### Contact

-   address;
-   phone;
-   email;
-   social.

### SEO

-   default title;
-   default description;
-   OG image.

### Business

-   currency;
-   enrollment settings;
-   tax configuration if needed.

### Notifications

-   email settings;
-   SMS settings;
-   templates.

### Security

-   session duration;
-   login attempt rules;
-   2FA requirements.

------------------------------------------------------------------------

# 84. Currency

Primary business currency:

``` text
BDT / ৳
```

Architecture should allow future currencies.

Money must be stored as integer minor units where practical rather than
floating-point.

Example:

``` text
amount = 499000
currency = BDT
```

representing ৳4,990.00 where the system uses two minor units.

------------------------------------------------------------------------

# 85. Date & Time

Store timestamps in a consistent backend timezone strategy, preferably
UTC at the persistence layer where practical.

Display localized time for the user.

For Bangladesh-facing operations:

``` text
Asia/Dhaka
```

should be supported.

Webinar schedules must explicitly handle timezone.

------------------------------------------------------------------------

# 86. URL & Slug Governance

Slug uniqueness should be enforced at the database/business layer.

If:

``` text
graphic-design
```

already exists:

``` text
graphic-design-2
```

or another deterministic strategy may be used.

When changing a published slug:

-   create redirect;
-   preserve SEO;
-   avoid broken links.

------------------------------------------------------------------------

# 87. Sitemap

Generate sitemap entries for:

-   homepage;
-   courses;
-   webinars;
-   blogs;
-   ebooks;
-   about;
-   contact;
-   other indexable pages.

Exclude:

-   dashboards;
-   private lessons;
-   private downloads;
-   cart;
-   checkout;
-   account pages.

------------------------------------------------------------------------

# 88. Robots Rules

Disallow private/application routes.

Examples conceptually:

``` text
/dashboard
/admin
/manager
/worker
/student
/cart
/checkout
```

Exact implementation should be validated during deployment.

------------------------------------------------------------------------

# 89. Analytics

Architecture should support:

-   GA4;
-   Meta Pixel;
-   future analytics tools.

Track events such as:

``` text
view_course
click_course_cta
add_to_cart
begin_checkout
purchase
view_webinar
register_webinar
download_ebook
submit_contact
watch_free_class
```

Consent/privacy requirements must be respected.

------------------------------------------------------------------------

# 90. SEO Structured Data

Potential schemas:

### Organization

For academy organization information.

### Course

For eligible course pages.

### Article

For blog pages.

### FAQPage

Only where the content genuinely qualifies.

### Event

For eligible webinars/seminars.

Structured data must reflect visible page content.

------------------------------------------------------------------------

# 91. Email Templates

System emails:

-   welcome;
-   verification;
-   password reset;
-   enrollment confirmation;
-   payment receipt;
-   webinar registration;
-   webinar reminder;
-   review moderation status;
-   admin notification.

All email templates should support localization.

------------------------------------------------------------------------

# 92. Dashboard Notification Center

Each authenticated user has:

-   unread count;
-   notifications;
-   mark read;
-   mark all read.

Notification fields:

-   type;
-   title;
-   body;
-   action URL;
-   read state;
-   created_at.

------------------------------------------------------------------------

# 93. Permission Management UX

Admin should be able to:

1.  create role;
2.  assign permissions;
3.  assign users;
4.  revoke permissions;
5.  view effective permissions.

Avoid giving users arbitrary permission combinations without clear
labels.

Recommended role editor:

``` text
Courses
  [ ] View
  [ ] Create
  [ ] Update
  [ ] Delete
  [ ] Publish

Blogs
  [ ] View
  [ ] Create
  [ ] Update
  [ ] Delete
  [ ] Publish
```

------------------------------------------------------------------------

# 94. Scoped Permissions

For workers, permissions may be scoped by ownership/assignment.

Example:

``` text
worker can update blogs = yes
worker can update all blogs = no
worker can update assigned blogs = yes
```

This is stronger than a simple role-only system.

------------------------------------------------------------------------

# 95. Admin Impersonation

Optional future feature:

Admin can impersonate a user for support.

If implemented:

-   require explicit permission;
-   create audit log;
-   display impersonation banner;
-   provide exit action;
-   never allow privilege escalation through impersonation.

------------------------------------------------------------------------

# 96. Testing Requirements

## Unit Tests

Test:

-   pricing;
-   discount;
-   capacity;
-   enrollment;
-   package logic;
-   permission checks;
-   slug generation;
-   localization;
-   review eligibility.

## Feature Tests

Test:

-   registration;
-   login;
-   checkout;
-   enrollment;
-   content publishing;
-   role permissions;
-   course filtering;
-   blog filtering;
-   webinar registration.

## Browser/E2E Tests

Test:

-   mobile navigation;
-   language switching;
-   theme switching;
-   course purchase journey;
-   dashboard access;
-   staff content editing.

------------------------------------------------------------------------

# 97. Security Test Cases

Must test:

-   unauthorized API access;
-   IDOR;
-   privilege escalation;
-   CSRF;
-   XSS;
-   SQL injection;
-   malicious file upload;
-   brute-force login;
-   session fixation;
-   expired session;
-   broken access to private ebooks;
-   private lesson URL access;
-   role manipulation;
-   forged payment callbacks.

------------------------------------------------------------------------

# 98. Accessibility Test Cases

Check:

-   keyboard navigation;
-   focus order;
-   color contrast;
-   form labels;
-   screen reader labels;
-   modal focus trapping;
-   tab semantics;
-   accordion semantics;
-   image alt text;
-   reduced-motion preference.

------------------------------------------------------------------------

# 99. Performance Acceptance Targets

Initial targets:

-   fast first contentful rendering;
-   minimal blocking JavaScript;
-   optimized hero assets;
-   responsive images;
-   paginated lists;
-   no unnecessary API calls.

The exact performance budget should be finalized after real content and
hosting constraints are known.

------------------------------------------------------------------------

# 100. Deployment Requirements

Recommended production stack:

``` text
Browser
   ↓
CDN / Reverse Proxy
   ↓
Nginx
   ↓
PHP-FPM
   ↓
Laravel
   ↓
MySQL
   ↓
Redis
```

Optional:

``` text
Object Storage
Queue Worker
Scheduler
Mail Provider
Payment Provider
Analytics
Monitoring
```

------------------------------------------------------------------------

# 101. Environment Separation

Environments:

``` text
local
development
staging
production
```

Never use production payment credentials in development.

Never commit:

``` text
.env
private keys
API secrets
database passwords
SMTP credentials
payment secrets
```

------------------------------------------------------------------------

# 102. Backup Strategy

Database:

-   daily full backup;
-   retention policy;
-   off-server copy;
-   periodic restore test.

Media:

-   redundant storage;
-   backup;
-   versioning where practical.

A backup that has never been restored is not a proven backup.

------------------------------------------------------------------------

# 103. Observability

Monitor:

-   application errors;
-   failed jobs;
-   slow queries;
-   payment failures;
-   authentication anomalies;
-   queue backlog;
-   storage;
-   database health.

Log correlation IDs for debugging.

------------------------------------------------------------------------

# 104. Queue Jobs

Potential queued jobs:

``` text
SendEmail
SendNotification
ProcessImage
GenerateThumbnail
GenerateSitemap
PublishScheduledContent
SendWebinarReminder
GenerateInvoice
ProcessPaymentCallback
```

Long-running operations should not block normal web requests.

------------------------------------------------------------------------

# 105. Scheduled Tasks

Potential scheduler tasks:

``` text
Publish scheduled blogs
Send webinar reminders
Expire old offers
Refresh sitemap
Clean temporary files
Generate reports
Check pending payments
Archive stale notifications
```

------------------------------------------------------------------------

# 106. API Rate Limiting

Apply stricter limits to:

-   login;
-   registration;
-   password reset;
-   contact forms;
-   review submission;
-   search endpoints;
-   public APIs;
-   payment callbacks where appropriate.

------------------------------------------------------------------------

# 107. Admin Data Tables

All major dashboard lists should support:

-   search;
-   filtering;
-   sorting;
-   pagination;
-   column visibility;
-   bulk actions where safe;
-   export where authorized.

Bulk delete should not be the default for transactional records.

------------------------------------------------------------------------

# 108. Bulk Actions

Potential bulk actions:

-   publish;
-   unpublish;
-   archive;
-   assign;
-   change category;
-   mark reviewed.

Destructive bulk operations require:

-   permission;
-   confirmation;
-   clear count;
-   audit record.

------------------------------------------------------------------------

# 109. Content Draft Preview

Staff should be able to preview unpublished content before publishing.

Preview should show:

-   public layout;
-   mobile layout;
-   desktop layout;
-   metadata preview where possible.

------------------------------------------------------------------------

# 110. Content Scheduling

Content can optionally have:

``` text
publish_at
unpublish_at
```

This is useful for:

-   course launches;
-   webinars;
-   campaigns;
-   blog posts;
-   offers.

------------------------------------------------------------------------

# 111. Homepage Personalization --- Future

Future possibility:

-   logged-in student sees continue-learning CTA;
-   visitor sees course discovery CTA;
-   returning user sees relevant course suggestions.

This should not be required for V1.

------------------------------------------------------------------------

# 112. Recommendation Engine --- Initial

Do not over-engineer AI recommendations at launch.

Use deterministic signals:

``` text
same category
same tag
same level
same instructor
manual selection
```

Later:

-   engagement;
-   purchase history;
-   collaborative filtering;
-   AI recommendations.

------------------------------------------------------------------------

# 113. Content Moderation

Moderatable content:

-   reviews;
-   student testimonials;
-   comments if added;
-   uploaded profile media.

Statuses:

``` text
pending
approved
rejected
hidden
```

------------------------------------------------------------------------

# 114. Data Privacy

Collect only necessary personal data.

Students should be able to:

-   view profile;
-   update profile;
-   request account-related support;
-   understand how their data is used.

Sensitive operational data should not be visible to ordinary workers
unless required.

------------------------------------------------------------------------

# 115. Terms & Policies

Public legal/policy pages should eventually include:

-   Terms & Conditions;
-   Privacy Policy;
-   Refund Policy;
-   Student Policy;
-   Course Policy;
-   Webinar Policy;
-   Ebook License/Usage Policy.

These should be CMS-managed but access-controlled.

------------------------------------------------------------------------

# 116. Non-Functional Requirements Summary

  Area            Requirement
  --------------- ----------------------------------------------
  Availability    Production-ready architecture
  Security        OWASP-informed secure development
  Performance     Optimized images, caching, pagination
  Accessibility   Keyboard and semantic accessibility
  Localization    Bangla + English
  Theme           Dark-first + Light
  Responsive      Mobile, tablet, laptop, desktop
  SEO             Dynamic metadata + sitemap + structured data
  Authorization   Role + permission + ownership
  Audit           Sensitive administrative actions
  Scalability     Modular Laravel architecture
  Database        MySQL/InnoDB
  Frontend        Vue 3
  Backend         Laravel
  Fonts           Hind Siliguri + Poppins
  Currency        BDT
  Content         CMS editable

------------------------------------------------------------------------

# 117. Suggested Project Structure

Conceptual Laravel structure:

``` text
app/
├── Actions/
├── Console/
├── Enums/
├── Events/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Jobs/
├── Models/
├── Notifications/
├── Policies/
├── Services/
└── Support/

resources/
├── js/
│   ├── components/
│   ├── layouts/
│   ├── pages/
│   ├── stores/
│   ├── composables/
│   ├── services/
│   ├── router/
│   ├── i18n/
│   └── types/
├── css/
└── views/

routes/
├── web.php
├── api.php
└── console.php

database/
├── migrations/
├── seeders/
└── factories/

storage/
├── app/
├── framework/
└── logs/
```

------------------------------------------------------------------------

# 118. Suggested Domain Services

Recommended services:

``` text
CourseService
EnrollmentService
CapacityService
PackageService
WebinarService
EbookService
BlogService
ReviewService
MediaService
SearchService
SeoService
NotificationService
PaymentService
OrderService
PermissionService
AuditService
LocalizationService
```

Avoid putting all business logic directly inside controllers.

------------------------------------------------------------------------

# 119. Recommended Laravel Patterns

Use:

-   Form Requests for validation;
-   Policies for resource authorization;
-   API Resources for response transformation;
-   Services/Actions for business operations;
-   Events for domain notifications;
-   Jobs for asynchronous tasks;
-   Eloquent relationships;
-   database transactions for financial/enrollment operations.

Laravel's current documentation supports API-backend use with JavaScript
SPAs, Vite-based frontend bundling and Sanctum for SPA authentication.

------------------------------------------------------------------------

# 120. Frontend State Management

Use Pinia stores for:

``` text
auth
theme
locale
cart
notifications
user
```

Avoid storing every API response globally.

Page-specific data should remain page-scoped where practical.

------------------------------------------------------------------------

# 121. Form Architecture

Reusable form primitives:

``` text
TextInput
Textarea
Select
MultiSelect
RichTextEditor
DatePicker
TimePicker
FileUploader
ImageUploader
Toggle
Checkbox
Radio
CurrencyInput
SlugInput
SeoFields
```

Forms must show:

-   validation state;
-   loading state;
-   success state;
-   server errors;
-   unsaved changes warning where needed.

------------------------------------------------------------------------

# 122. Dashboard Navigation Model

Student:

``` text
Overview
My Courses
My Webinars
My Ebooks
Orders
Reviews
Notifications
Profile
Settings
Logout
```

Worker:

``` text
Overview
Assigned Content
Courses
Webinars
Blogs
Ebooks
Reviews
Leads
Media
Notifications
Profile
Logout
```

Manager:

``` text
Overview
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
Media
Reports
Staff
Settings — limited
Logout
```

Admin:

``` text
Overview
Users
Roles & Permissions
Courses
Batches
Packages
Instructors
Webinars
Ebooks
Blogs
Reviews
Homepage
Pages
Menus
Footer
Media
Students
Leads
Orders
Payments
Notifications
Translations
SEO
Settings
Audit Logs
System
Logout
```

------------------------------------------------------------------------

# 123. V1 Scope

## Must Have

-   public homepage;
-   course listing;
-   course details;
-   webinar listing/detail;
-   about;
-   contact;
-   blog listing/detail;
-   ebook listing/detail;
-   authentication;
-   student dashboard;
-   worker dashboard;
-   manager dashboard;
-   admin dashboard;
-   RBAC;
-   course management;
-   webinar management;
-   ebook management;
-   blog management;
-   review management;
-   FAQ management;
-   homepage management;
-   media management;
-   bilingual UI;
-   dark/light theme;
-   responsive design;
-   SEO basics;
-   audit logs;
-   search/filter;
-   basic order/enrollment foundation.

## Should Have

-   package management;
-   free class management;
-   notifications;
-   scheduled publishing;
-   lead management;
-   advanced SEO;
-   analytics events;
-   webinar reminders;
-   invoice generation.

## Could Have

-   quizzes;
-   assignments;
-   certificates;
-   course notes;
-   student discussion;
-   advanced recommendation engine;
-   AI-powered search.

## Not Required for Initial Launch

-   full social network;
-   complex AI tutor;
-   marketplace for third-party instructors;
-   multi-tenant academy system;
-   cryptocurrency payment;
-   advanced affiliate network.

------------------------------------------------------------------------

# 124. Acceptance Criteria --- Public Website

## Homepage

-   All configured sections render correctly.
-   Content can be changed from dashboard.
-   Bangla is default.
-   English toggle works.
-   Dark theme is default.
-   Light theme works.
-   Mobile layout is usable.
-   Footer appears globally.

## Courses

-   Search works.
-   Filters work.
-   Pagination works.
-   Published courses only are public.
-   Course details load by slug.
-   Capacity displays correctly.
-   Related courses appear.

## Webinars

-   Search/filter works.
-   Details display schedule and prerequisites.
-   Capacity is accurate.
-   Related webinars appear.

## Blogs

-   Search/filter works.
-   Slug works.
-   SEO metadata works.
-   Related posts appear.

## Ebooks

-   Search/filter works.
-   Metadata displays.
-   Authorized downloads work.
-   Related ebooks appear.

------------------------------------------------------------------------

# 125. Acceptance Criteria --- Dashboard

### Student

-   Can log in.
-   Can view own enrollments.
-   Cannot access staff routes.
-   Can view eligible content.
-   Can manage own profile.

### Worker

-   Can access only assigned/permitted modules.
-   Cannot manage roles.
-   Cannot modify protected system settings.
-   Moderation actions are audited.

### Manager

-   Can manage permitted academy content.
-   Cannot manage administrator privileges.
-   Changes are audited.

### Admin

-   Can manage roles and permissions.
-   Can manage all content.
-   Can view audit logs.
-   Can change system settings.

------------------------------------------------------------------------

# 126. Development Phases

## Phase 1 --- Foundation

-   Laravel setup;
-   Vue setup;
-   MySQL;
-   authentication;
-   RBAC;
-   localization;
-   theme system;
-   base layout;
-   design tokens.

## Phase 2 --- Public Content

-   homepage;
-   courses;
-   webinars;
-   blogs;
-   ebooks;
-   about;
-   contact;
-   footer.

## Phase 3 --- Content Management

-   admin;
-   manager;
-   worker;
-   CMS modules;
-   media;
-   publishing.

## Phase 4 --- Commerce & Enrollment

-   cart;
-   checkout;
-   payment abstraction;
-   orders;
-   enrollment;
-   capacity.

## Phase 5 --- Student Experience

-   dashboard;
-   course access;
-   webinar registration;
-   ebook access;
-   reviews;
-   notifications.

## Phase 6 --- Quality

-   testing;
-   security;
-   SEO;
-   accessibility;
-   performance;
-   analytics.

## Phase 7 --- Production

-   staging;
-   deployment;
-   backup;
-   monitoring;
-   launch checklist.

------------------------------------------------------------------------

# 127. Product Risks

  Risk                    Mitigation
  ----------------------- ------------------------------------
  Role escalation         Server-side permission checks
  Overselling seats       DB transaction + locking
  Inconsistent content    CMS + publishing workflow
  Slow homepage           caching + optimized assets
  Poor Bangla UX          Hind Siliguri + content QA
  Broken translations     translation-key governance
  SEO duplication         canonical/slugs/metadata
  Private file leakage    authorized download layer
  Payment inconsistency   gateway abstraction + verification
  Staff mistakes          audit logs + confirmation
  Content clutter         controlled CMS structure
  Excessive scope         phased implementation

------------------------------------------------------------------------

# 128. Definition of Done

A feature is considered complete only when:

-   backend logic exists;
-   frontend UI exists;
-   validation exists;
-   authorization exists;
-   mobile UI exists;
-   desktop UI exists;
-   localization exists where applicable;
-   loading state exists;
-   empty state exists;
-   error state exists;
-   audit behavior exists where relevant;
-   tests exist;
-   SEO is implemented where public;
-   accessibility is considered;
-   documentation is updated.

------------------------------------------------------------------------

# 129. Product Quality Bar

The final product should not feel like:

-   a generic template;
-   a simple Laravel CRUD application;
-   a crowded coaching website;
-   an e-commerce-only storefront;
-   a dashboard-first application.

It should feel like:

> **A premium, trustworthy, Bangla-first learning ecosystem with a
> polished public website and a serious operational backend.**

------------------------------------------------------------------------

# 130. Final Product Blueprint

``` text
                         EMISHA ACADEMY
                               │
             ┌─────────────────┴─────────────────┐
             │                                   │
       PUBLIC WEBSITE                     AUTHENTICATED APP
             │                                   │
   ┌─────────┼──────────┐            ┌───────────┼───────────┐
   │         │          │            │           │           │
 Courses   Webinars   Resources   Student     Staff       Admin
   │         │          │          │           │           │
   │         │       Blog/Ebook    │      Worker/Manager   │
   │         │          │          │           │           │
   └─────────┴──────────┴──────────┴───────────┴───────────┘
                               │
                         Laravel API
                               │
                    ┌──────────┴──────────┐
                    │                     │
                  MySQL                 Redis
                    │                     │
                    └──────────┬──────────┘
                               │
                     Storage / Queue / Mail
```

------------------------------------------------------------------------

# 131. Research & Technical Basis

This PRD uses the supplied Emisha Academy requirements as the primary
product source and uses current official technical documentation to
validate architectural direction.

### Laravel

Laravel's current documentation supports using Laravel as an API backend
for JavaScript SPAs, using Vite for asset compilation, and using Sanctum
for SPA authentication. Laravel 12 also provides Vue-oriented
starter-kit options and built-in authentication scaffolding.

Laravel authorization provides Gates and Policies suitable for enforcing
permission/resource rules on the backend.

Laravel notifications can be queued, which is appropriate for
email/SMS/external notification work that should not delay a normal web
request.

### Vue

Vue 3 Composition API is suitable for reusable reactive logic, while Vue
Router provides route/navigation integration for Vue applications.

### MySQL

MySQL 8.4 documentation supports InnoDB foreign keys and indexed
relationships for referential integrity. MySQL documentation also
emphasizes intentional index selection because indexes improve read
performance while adding write/storage overhead.

### Security

OWASP ASVS is used as the security baseline for secure web application
requirements and verification.

------------------------------------------------------------------------

# 132. Official Technical References

-   Laravel Documentation --- current framework documentation
-   Laravel Release Notes --- current supported Laravel release
    information
-   Laravel Sanctum --- SPA authentication
-   Vue.js Documentation --- Vue 3 and Composition API
-   Vue Router Documentation --- routing and Composition API
-   MySQL 8.4 Reference Manual --- constraints, indexes and transactions
-   OWASP Application Security Verification Standard (ASVS)

------------------------------------------------------------------------

# 133. Next Documents in This Product Specification Series

This PRD is intentionally the **first document** in a four-document
implementation specification.

The next documents should be produced separately:

## Document 2 --- DESIGN.md

Will define:

-   exact color system;
-   dark/light theme tokens;
-   typography scale;
-   spacing system;
-   grid;
-   breakpoints;
-   component design;
-   cards;
-   buttons;
-   forms;
-   dashboard UI;
-   iconography;
-   imagery;
-   animations;
-   hover/focus states;
-   page-by-page visual design rules.

## Document 3 --- ARCHITECTURE.md

Will define:

-   Laravel architecture;
-   Vue architecture;
-   folder structure;
-   database schema;
-   relationships;
-   migrations;
-   API architecture;
-   RBAC implementation;
-   policies;
-   services;
-   repositories where useful;
-   queues;
-   events;
-   caching;
-   deployment;
-   security architecture;
-   testing architecture.

## Document 4 --- WIREFRAME.md

Will define responsive wireframes for:

-   mobile;
-   tablet;
-   laptop;
-   desktop;
-   large desktop;

for every public page and every dashboard.

------------------------------------------------------------------------

# 134. Final Instruction to Implementation Team

Build Emisha Academy as a **content-managed, permission-controlled,
bilingual EdTech platform**, not merely as a collection of static pages.

The public website must prioritize:

``` text
Trust
→ Discovery
→ Understanding
→ Evaluation
→ Conversion
→ Learning
→ Retention
```

The management platform must prioritize:

``` text
Control
→ Safety
→ Workflow
→ Auditability
→ Ease of Content Management
→ Operational Visibility
```

The most important architectural rule is:

> **Every public content element that the academy may reasonably need to
> change should be editable through the appropriate dashboard, while
> every sensitive operation must remain protected by server-side
> authorization and audit logging.**

The most important UX rule is:

> **A learner should never have to fight the interface to understand
> what a course is, who it is for, what it costs, what it includes, how
> much capacity is available, and what to do next.**

The most important visual rule is:

> **Keep the interface calm, premium, soft and highly readable; use gold
> as a controlled brand accent rather than allowing it to dominate the
> entire UI.**

The most important technical rule is:

> **Keep business logic on the Laravel backend, keep the Vue frontend
> modular, keep permissions explicit, and keep content/data separate
> from presentation.**
