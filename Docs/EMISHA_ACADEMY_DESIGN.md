# Emisha Academy --- DESIGN.md

**Project:** Emisha Academy\
**Product Type:** EdTech Platform\
**Primary Language:** বাংলা\
**Secondary Language:** English\
**Primary Theme:** Dark\
**Secondary Theme:** Light\
**Brand Accent:** Golden\
**Primary UI Family:** Bluish / Blue-Slate\
**Bangla Typeface:** Hind Siliguri\
**English Typeface:** Poppins\
**Design Direction:** Clean, eye-soothing, minimal, soft, premium,
rounded-rectangle component system\
**Primary Inspiration:** Computer School 24 ---
component/information-architecture inspiration only\
**Design Rule:** Do not clone the reference or any competitor/demo
website.

------------------------------------------------------------------------

# 1. Design Vision

Emisha Academy should look like a **modern premium learning platform**,
not a generic coaching website and not a conventional e-commerce store.

The visual language should communicate:

-   Trust
-   Quality
-   Calmness
-   Professionalism
-   Structured learning
-   Human guidance
-   Practical education
-   Modern technology

The interface should feel:

> **Soft + Premium + Educational + Trustworthy + Focused**

The visual system should avoid excessive decoration. Every visual
element must have a purpose.

------------------------------------------------------------------------

# 2. Core Design Principles

## 2.1 Content First

Typography and spacing must make educational content easy to consume.

Do not let:

-   gradients;
-   illustrations;
-   badges;
-   shadows;
-   animations;
-   decorative backgrounds

compete with the actual course or learning information.

## 2.2 Dark First

The dark theme is the primary visual identity.

The dark interface should not be pure black.

Use layered bluish surfaces:

``` text
Deep Background
→ Elevated Surface
→ Card Surface
→ Hover Surface
→ Border
→ Content
→ Accent
```

## 2.3 Gold as an Accent

Gold is the Emisha Academy brand signature.

Gold should primarily appear on:

-   logo;
-   primary CTA;
-   active navigation state;
-   important numbers;
-   selected tabs;
-   course highlights;
-   premium badges;
-   key icons;
-   subtle decorative details.

Do NOT make the whole interface golden.

## 2.4 Soft Geometry

Primary component shape:

``` text
Rounded Rectangle
```

Use consistent corner radii.

Avoid mixing:

-   sharp cards;
-   excessive pills;
-   random rounded corners;
-   overly circular containers.

------------------------------------------------------------------------

# 3. Design Tokens

The application must use semantic design tokens instead of hard-coded
colors.

Recommended structure:

``` text
--color-background
--color-surface
--color-surface-elevated
--color-surface-hover
--color-border
--color-text-primary
--color-text-secondary
--color-text-muted
--color-primary
--color-primary-hover
--color-brand
--color-success
--color-warning
--color-danger
--color-info
```

------------------------------------------------------------------------

# 4. Dark Theme Color System

Recommended starting palette. Exact values may be refined during
implementation after contrast testing.

## 4.1 Background

``` text
Background 950: #07111F
Background 900: #0A1628
Background 850: #0D1B30
```

Use:

-   `#07111F` for the main application background;
-   `#0A1628` for large page surfaces;
-   `#0D1B30` for elevated areas.

## 4.2 Bluish Surface

``` text
Surface 900: #10213A
Surface 800: #142A46
Surface 700: #193352
Surface Hover: #1D3B5E
```

Cards should generally use a surface lighter than the page background.

## 4.3 Text

``` text
Primary: #F5F8FC
Secondary: #C5D0DF
Muted: #8E9CAF
Disabled: #657287
```

## 4.4 Borders

``` text
Border: rgba(170, 195, 220, 0.12)
Border Strong: rgba(170, 195, 220, 0.20)
```

## 4.5 Brand Gold

Recommended semantic range:

``` text
Gold 300: #F8D77A
Gold 400: #F3C95B
Gold 500: #DFAE32
Gold 600: #C99620
Gold 700: #A97812
```

Use `Gold 400/500` as the primary accent.

Avoid overly saturated yellow.

------------------------------------------------------------------------

# 5. Light Theme Color System

Light mode must feel like the same brand, not a completely different
website.

## Background

``` text
Background: #F6F8FC
Surface: #FFFFFF
Surface Soft: #F0F4FA
Surface Blue: #EAF1F9
```

## Text

``` text
Primary: #10213A
Secondary: #40536D
Muted: #718096
```

## Borders

``` text
Border: #DCE5EF
Border Strong: #C7D4E3
```

## Brand

Use the same gold scale while ensuring sufficient contrast.

------------------------------------------------------------------------

# 6. Semantic Status Colors

Status colors must not depend on the theme.

``` text
Success:
#36B37E

Warning:
#E6A23C

Danger:
#E05D5D

Info:
#4C8DFF
```

Use status colors sparingly.

Examples:

-   available;
-   pending;
-   published;
-   suspended;
-   rejected;
-   payment failed.

------------------------------------------------------------------------

# 7. Typography System

## 7.1 Font Families

### Bangla

``` text
"Hind Siliguri", sans-serif
```

### English

``` text
"Poppins", sans-serif
```

Use appropriate fallback fonts.

## 7.2 Font Weight

Recommended:

``` text
Regular: 400
Medium: 500
SemiBold: 600
Bold: 700
```

Avoid using 800/900 throughout the UI.

## 7.3 Display Typography

Desktop:

``` text
Display XL: 56px / 1.12 / 700
Display LG: 48px / 1.15 / 700
Display MD: 40px / 1.18 / 700
```

Tablet:

``` text
44px
38px
34px
```

Mobile:

``` text
36px
32px
28px
```

Hero headings should generally remain within 2--3 lines.

## 7.4 Heading Scale

``` text
H1: 48–56px
H2: 36–42px
H3: 28–32px
H4: 22–24px
H5: 18–20px
H6: 16–18px
```

## 7.5 Body

``` text
Body XL: 18px
Body LG: 16px
Body MD: 15px
Body SM: 14px
Caption: 12–13px
```

Bangla line-height should generally be slightly more generous than
English.

------------------------------------------------------------------------

# 8. Typography Rules

## Bangla

Use Hind Siliguri for:

-   navigation;
-   headings;
-   descriptions;
-   forms;
-   buttons;
-   dashboard;
-   labels.

Do not use decorative Bangla fonts.

## English

Use Poppins consistently.

Avoid mixing more than two primary typefaces.

## Mixed Content

For content such as:

``` text
Laravel দিয়ে Web Development শিখুন
```

Hind Siliguri should remain the primary text context while Latin glyphs
render through Poppins-compatible fallback behavior.

------------------------------------------------------------------------

# 9. Spacing System

Use an 8px-based spacing scale with 4px micro increments.

``` text
4
8
12
16
20
24
32
40
48
56
64
80
96
120
```

Primary section spacing:

``` text
Desktop: 96–120px
Tablet: 72–96px
Mobile: 56–72px
```

Cards:

``` text
Small: 16px
Medium: 20px
Large: 24px
```

------------------------------------------------------------------------

# 10. Layout Container

Recommended maximum widths:

``` text
Mobile: 100%
Tablet: 100%
Desktop: 1280px
Wide: 1440px
```

Main content:

``` text
max-width: 1280px
margin-inline: auto
padding-inline: 24px
```

Large desktop:

``` text
padding-inline: 40px
```

Mobile:

``` text
padding-inline: 16px
```

------------------------------------------------------------------------

# 11. Responsive Breakpoints

Recommended:

``` text
xs: 480px
sm: 640px
md: 768px
lg: 1024px
xl: 1280px
2xl: 1440px
```

Design must not depend only on these exact breakpoints; components
should remain fluid between them.

------------------------------------------------------------------------

# 12. Grid System

## Desktop

12-column grid.

Recommended:

``` text
12 columns
24px gutter
```

## Tablet

8-column conceptual grid.

## Mobile

4-column conceptual grid.

Course listing:

``` text
Desktop: 3–4 cards
Tablet: 2 cards
Mobile: 1 card
```

Blog:

``` text
Desktop: 3
Tablet: 2
Mobile: 1
```

Ebook:

``` text
Desktop: 4
Tablet: 2–3
Mobile: 1–2
```

------------------------------------------------------------------------

# 13. Border Radius System

``` text
Radius XS: 6px
Radius SM: 10px
Radius MD: 14px
Radius LG: 18px
Radius XL: 24px
Radius 2XL: 32px
```

Recommended:

-   input: 12--14px;
-   button: 12--14px;
-   cards: 18--24px;
-   hero container: 24--32px;
-   modal: 20--24px.

------------------------------------------------------------------------

# 14. Shadows

Dark theme should use subtle shadows.

Avoid large black drop shadows.

Recommended conceptual levels:

``` text
Shadow SM
Shadow MD
Shadow LG
```

Use shadows primarily for:

-   floating navigation;
-   dropdowns;
-   modal;
-   elevated cards;
-   sticky controls.

Borders should carry more visual separation than shadows.

------------------------------------------------------------------------

# 15. Header / Navigation

## Desktop

Structure:

``` text
[Logo] [Nav Links................] [Language] [Theme] [Login]
```

Navigation should remain visually lightweight.

Active state:

-   gold text;
-   subtle gold underline or background;
-   no oversized pill.

## Mobile

``` text
[Logo]                [Theme] [Menu]
```

Menu opens a full-height or large drawer.

Drawer includes:

-   navigation;
-   language;
-   account;
-   primary CTA.

------------------------------------------------------------------------

# 16. Header Behavior

Desktop:

-   static initially;
-   optional sticky after scroll;
-   slightly translucent surface;
-   subtle bottom border.

Scrolled state:

``` text
backdrop blur
surface elevation
border
```

Avoid excessive glassmorphism.

------------------------------------------------------------------------

# 17. Logo Treatment

Dark:

-   light/white logo text;
-   gold symbol/accent.

Light:

-   deep-blue logo text;
-   gold accent.

Do not place the logo inside an unnecessary card.

------------------------------------------------------------------------

# 18. Buttons

## Primary Button

Visual:

-   golden background;
-   dark-blue text;
-   medium/bold weight;
-   rounded rectangle.

Example:

``` text
[ কোর্স দেখুন → ]
```

## Secondary Button

Dark:

-   bluish surface;
-   soft border;
-   white text.

Light:

-   white/blue surface;
-   blue text.

## Tertiary

Text-only with arrow/icon.

## Danger

Use red only for destructive actions.

------------------------------------------------------------------------

# 19. Button Sizes

``` text
Small: 36px height
Medium: 44px
Large: 52px
XL: 56px
```

Minimum mobile touch target:

``` text
44 × 44px
```

Buttons should not contain excessive icon decoration.

------------------------------------------------------------------------

# 20. Cards

Card language:

``` text
rounded
soft border
subtle surface contrast
controlled shadow
```

Course cards should feel like product discovery cards but retain an
educational character.

Avoid excessive badges.

------------------------------------------------------------------------

# 21. Course Card

Recommended structure:

``` text
┌─────────────────────────────┐
│                             │
│        Course Image         │
│                             │
├─────────────────────────────┤
│ Category                    │
│ Course Title                │
│ Short Description           │
│                             │
│ Level · Duration · Lessons  │
│                             │
│ ৳ Price        [Details →]  │
└─────────────────────────────┘
```

Optional:

``` text
● 8 seats available
```

Use seat information only when relevant.

------------------------------------------------------------------------

# 22. Course Card Visual Priority

Priority:

1.  Course image
2.  Course title
3.  Short value proposition
4.  Metadata
5.  Price
6.  CTA

Do not make discount badges larger than the title.

------------------------------------------------------------------------

# 23. Course Detail Hero

Desktop:

``` text
┌──────────────────────────────────────────────────────────┐
│ Breadcrumb                                                │
│                                                          │
│ Course title                         Course image         │
│ Short description                    / preview             │
│                                                          │
│ Rating · Level · Duration                                │
│                                                          │
│ Price                         [Buy Now] [Add to Cart]     │
└──────────────────────────────────────────────────────────┘
```

Mobile:

``` text
Breadcrumb
Image
Title
Description
Metadata
Price
CTA
```

On mobile, CTA may become sticky near the bottom when appropriate.

------------------------------------------------------------------------

# 24. Course Information Panel

Use a grid of small information blocks:

``` text
Level
Intermediate

Duration
8 Weeks

Lessons
24 Classes

Language
বাংলা

Capacity
25 Students
```

Use icons sparingly.

------------------------------------------------------------------------

# 25. Course Curriculum

Desktop:

``` text
Module 01
  Lesson 01
  Lesson 02
  Lesson 03

Module 02
  Lesson 01
  Lesson 02
```

Each module is an accordion.

Visual hierarchy:

-   module title;
-   lesson count;
-   duration;
-   preview badge where applicable.

------------------------------------------------------------------------

# 26. Course Instructor Card

Structure:

``` text
[Portrait]

Instructor Name
Designation

Experience / Expertise

Short bio

[View Profile]
```

Avoid overly decorative profile cards.

------------------------------------------------------------------------

# 27. Course Reviews Tabs

Tabs:

``` text
ভিডিও রিভিউ     টেক্সট রিভিউ
```

Active tab:

-   gold indicator;
-   strong text.

Video review:

-   thumbnail;
-   play button;
-   student name;
-   course/batch;
-   short caption.

Text review:

-   quote;
-   avatar;
-   name;
-   verified status;
-   rating.

------------------------------------------------------------------------

# 28. Related Course Section

Heading:

> এই কোর্সগুলোও আপনার ভালো লাগতে পারে

Cards should reuse the standard CourseCard.

Desktop:

-   4 cards where space permits.

Mobile:

-   horizontal scroll or stacked list.

------------------------------------------------------------------------

# 29. Homepage Hero Design

The hero should be calm and premium rather than visually loud.

Recommended structure:

``` text
┌───────────────────────────────────────────────────────┐
│                                                       │
│ Small trust label                                     │
│                                                       │
│ Learn. Grow. Build.                                   │
│ বাংলা primary headline                                │
│                                                       │
│ Supporting text                                      │
│                                                       │
│ [কোর্স দেখুন] [ফ্রি ক্লাস দেখুন]                     │
│                                                       │
│ Trust metrics                  Visual / student image │
│                                                       │
└───────────────────────────────────────────────────────┘
```

Use a soft radial bluish glow behind the hero visual if desired.

Gold should highlight one phrase or CTA, not the entire hero.

------------------------------------------------------------------------

# 30. Homepage Course Section

Section header:

``` text
আমাদের কোর্সসমূহ
আপনার লক্ষ্য অনুযায়ী শেখার সুযোগ
                         [সব কোর্স দেখুন]
```

Cards:

-   3 or 4 desktop;
-   2 tablet;
-   1 mobile.

------------------------------------------------------------------------

# 31. Ebook Showcase

Use slightly more editorial styling than course cards.

Recommended:

``` text
Cover
Title
Author
Version
Pages
Language
CTA
```

Book cover should visually dominate the card.

------------------------------------------------------------------------

# 32. Why Choose Us

Use 3--6 value cards.

Example:

``` text
[Icon]
সীমিত ব্যাচ
কম শিক্ষার্থী, বেশি মনোযোগ
```

Cards should have:

-   small icon;
-   heading;
-   2--3 line description.

Avoid huge icons.

------------------------------------------------------------------------

# 33. Course Packages

Packages can use a slightly elevated visual style.

Recommended:

``` text
Featured Package

Package Name

Course A
Course B
Course C

Regular Price
Package Price

[প্যাকেজ দেখুন]
```

Gold border should be subtle.

------------------------------------------------------------------------

# 34. Free Classes

Free class cards:

``` text
[Video Thumbnail]
▶
Free Class

Title
Instructor
Duration

[Watch Free Class]
```

Video thumbnail must maintain consistent aspect ratio.

------------------------------------------------------------------------

# 35. Student Reviews Homepage

Use a two-stage layout:

``` text
Section heading

[Video Reviews] [Student Stories]

Large featured testimonial
Small supporting testimonials
```

For image reviews:

-   masonry-like visual grid can be used carefully;
-   avoid Pinterest-style chaos.

------------------------------------------------------------------------

# 36. FAQ

Accordion:

``` text
01  প্রশ্নটি কী?                         +
02  কীভাবে কোর্সে ভর্তি হব?              +
03  পেমেন্ট কীভাবে করব?                  +
```

Expanded state:

-   gold accent;
-   slightly elevated surface;
-   smooth height transition.

------------------------------------------------------------------------

# 37. Ecosystem Section

The ecosystem section must not visually overpower the Academy.

Recommended:

``` text
আমাদের অন্যান্য উদ্যোগ

[Logo] Emisha Tours & Travels
[Logo] Harmain
[Logo] Safar
[Logo] Hajj & Umrah
[Logo] Visa Processing
```

Use muted visual treatment.

------------------------------------------------------------------------

# 38. Final CTA

Large rounded container:

``` text
┌─────────────────────────────────────────────────┐
│ আপনার শেখার যাত্রা আজ থেকেই শুরু করুন            │
│                                                 │
│ [কোর্স দেখুন]      [যোগাযোগ করুন]              │
└─────────────────────────────────────────────────┘
```

Use a subtle blue gradient/glow with restrained gold detail.

------------------------------------------------------------------------

# 39. Footer Design

Footer should be darker than the main page even in light mode.

Structure:

``` text
Logo + short description

Academy
Courses
Webinars
Ebooks
Blog

Support
FAQ
Contact
Policies

Ecosystem
...

Contact
Phone
Email
Address

Social icons

Copyright
```

Keep it structured and readable.

------------------------------------------------------------------------

# 40. Course Listing Page Design

Top:

``` text
Page title
Description

[Search.....................................]
```

Below:

``` text
[Filter] [Sort]
```

Desktop:

``` text
┌───────────────┬─────────────────────────────┐
│ Filters       │ Course Grid                 │
│               │                             │
│ Category      │ Card Card Card              │
│ Level         │ Card Card Card              │
│ Price         │                             │
│ Duration      │ Pagination                  │
└───────────────┴─────────────────────────────┘
```

Mobile:

``` text
Search
[Filter] [Sort]

Course cards
```

Filter opens a bottom sheet/drawer.

------------------------------------------------------------------------

# 41. Search Input

Search should have:

-   search icon;
-   placeholder;
-   clear button;
-   keyboard support.

Example:

``` text
কোর্স খুঁজুন...
```

Focused state:

-   gold or blue border;
-   soft glow;
-   no heavy shadow.

------------------------------------------------------------------------

# 42. Filter Design

Desktop:

Persistent sidebar.

Mobile:

Drawer.

Filter groups:

``` text
Category
Level
Language
Price
Duration
Availability
Instructor
```

Include:

``` text
Clear All
Apply Filters
```

------------------------------------------------------------------------

# 43. Webinar Card

Structure:

``` text
[Image]

Upcoming
Webinar Title

Date · Time
Level · Language

Speaker

[Details]
```

Upcoming events may use a subtle gold date accent.

------------------------------------------------------------------------

# 44. Webinar Detail Design

Top hero:

``` text
Category
Title
Description

Date
Time
Language
Level

[Register Now]
```

Below:

``` text
About
Schedule
Speaker
Prerequisites
Who should attend
Related Webinars
```

------------------------------------------------------------------------

# 45. About Page Design

Use storytelling.

Order:

``` text
Hero
Our Story
Mission / Vision
Journey
Gallery
Values
Institutions
Final CTA
```

Use alternating content blocks sparingly.

Do not create excessive zig-zag layouts.

------------------------------------------------------------------------

# 46. Journey / Milestone Timeline

Desktop:

``` text
2023 ─────●
          │
2024 ─────●
          │
2025 ─────●
          │
2026 ─────●
```

Mobile:

Vertical timeline.

Gold is used only for milestone markers.

------------------------------------------------------------------------

# 47. Gallery

Use a controlled grid:

Desktop:

``` text
Large  Large  Small
Small  Large  Small
```

Mobile:

``` text
2-column grid
```

Images should have consistent radius.

------------------------------------------------------------------------

# 48. Contact Page

Desktop:

``` text
Contact headline

┌─────────────────────┬───────────────────────┐
│ Contact information │ Contact form          │
│                     │                       │
│ Address             │ Name                  │
│ Phone               │ Email                 │
│ Email               │ Phone                 │
│ Map                 │ Message               │
│                     │ [Send Message]        │
└─────────────────────┴───────────────────────┘
```

Mobile:

-   information;
-   map;
-   form.

------------------------------------------------------------------------

# 49. Blog Listing Design

Use editorial card hierarchy.

Card:

``` text
[Cover Image]
Category
Title
Excerpt
Author · Date · Read time
```

Featured blog may occupy larger width.

------------------------------------------------------------------------

# 50. Blog Detail Design

Desktop content width should be intentionally narrower than the site
container.

Recommended:

``` text
max-width: 820–900px
```

Structure:

``` text
Category
Title
Author / Date / Read Time
Hero Image

Article Body

Related Articles
```

Long-form reading should prioritize line length and vertical rhythm.

------------------------------------------------------------------------

# 51. Ebook Listing Design

Ebooks should visually feel like a library.

Recommended:

``` text
Cover
Title
Author
Version
Pages
Language
Price
```

Use a consistent cover ratio.

------------------------------------------------------------------------

# 52. Ebook Detail Design

Desktop:

``` text
┌──────────────┬─────────────────────────────┐
│              │ Title                       │
│    Cover     │ Author                      │
│              │ Version                     │
│              │ Pages                       │
│              │ Language                    │
│              │ Target Country              │
│              │ Price                       │
│              │ [Buy / Download]            │
└──────────────┴─────────────────────────────┘
```

Below:

-   description;
-   table of contents;
-   preview;
-   author;
-   related ebooks.

------------------------------------------------------------------------

# 53. Student Dashboard Design

Dashboard should be visually calmer than the public marketing site.

Layout:

``` text
┌──────────────┬─────────────────────────────────────┐
│ Sidebar      │ Topbar                              │
│              ├─────────────────────────────────────┤
│ Overview     │                                     │
│ My Courses   │ Dashboard Content                  │
│ Webinars     │                                     │
│ Ebooks       │                                     │
│ Orders       │                                     │
│ Reviews      │                                     │
│ Profile      │                                     │
└──────────────┴─────────────────────────────────────┘
```

------------------------------------------------------------------------

# 54. Dashboard Sidebar

Dark mode:

-   slightly darker than content;
-   active item uses subtle gold background;
-   icon + label.

Mobile:

-   drawer.

Do not use excessive nested navigation.

------------------------------------------------------------------------

# 55. Dashboard Topbar

Contains:

-   page title;
-   breadcrumbs where needed;
-   notification;
-   profile;
-   language;
-   theme.

Optional:

-   global search.

------------------------------------------------------------------------

# 56. Student Dashboard Cards

Metrics:

``` text
Active Courses
Completed
Upcoming Webinars
Purchased Ebooks
```

Cards should be compact.

Use icons + number + supporting label.

------------------------------------------------------------------------

# 57. Learning Progress Card

Example:

``` text
Graphic Design Masterclass

████████████░░░░ 72%

18 / 25 lessons completed

[Continue Learning]
```

Progress bar:

-   blue/gold accent;
-   accessible text percentage.

------------------------------------------------------------------------

# 58. Worker Dashboard

Worker dashboard should emphasize assigned work.

Top:

``` text
Assigned Tasks
Pending Reviews
Pending Content
Open Leads
```

Main:

``` text
My Assigned Content
Recent Activity
Pending Moderation
```

------------------------------------------------------------------------

# 59. Manager Dashboard

Manager dashboard should emphasize operational overview.

Metrics:

``` text
Students
Enrollments
Courses
Revenue
Leads
Pending Reviews
```

Sections:

-   enrollment trends;
-   course occupancy;
-   upcoming webinars;
-   recent orders;
-   staff workload.

------------------------------------------------------------------------

# 60. Admin Dashboard

Admin UI can be more information-dense.

Primary navigation remains clean.

Dashboard:

``` text
System Overview

Users
Students
Managers
Workers

Content
Courses
Webinars
Blogs
Ebooks

Commerce
Orders
Payments
Revenue

Operations
Leads
Reviews
Notifications

System
Roles
Permissions
Settings
Audit Logs
```

------------------------------------------------------------------------

# 61. Dashboard Tables

Desktop:

-   dense but breathable.

Mobile:

Do not force a huge horizontal table where avoidable.

Convert row information into cards or responsive stacked records.

Example mobile course row:

``` text
Course Name
Status
Instructor
Price

[Edit] [More]
```

------------------------------------------------------------------------

# 62. Admin Forms

Use a consistent two-column desktop layout:

``` text
Main Content
Sidebar
```

Example:

``` text
┌───────────────────────────────┬──────────────────┐
│ Course title                  │ Publish Status   │
│ Description                   │ Category         │
│ Curriculum                   │ Instructor       │
│                               │ Price            │
│                               │ SEO              │
└───────────────────────────────┴──────────────────┘
```

Mobile becomes one column.

------------------------------------------------------------------------

# 63. Rich Text Editor

The editor must feel like a modern document editor.

Toolbar should remain limited:

-   heading;
-   bold;
-   italic;
-   underline;
-   list;
-   link;
-   quote;
-   image;
-   video/embed where allowed.

Do not expose raw HTML editing to normal workers.

------------------------------------------------------------------------

# 64. Media Uploader

Design:

``` text
┌─────────────────────────────┐
│ Drag & Drop                  │
│                              │
│ Upload image                 │
│ PNG / JPG / WEBP             │
└─────────────────────────────┘
```

After upload:

-   preview;
-   file name;
-   dimensions;
-   alt text;
-   replace;
-   delete.

------------------------------------------------------------------------

# 65. Modal Design

Modal:

-   20--24px radius;
-   dark elevated surface;
-   subtle border;
-   backdrop;
-   clear title;
-   close icon;
-   primary/secondary action.

Do not use modal for long forms if a dedicated page/drawer is better.

------------------------------------------------------------------------

# 66. Drawer Design

Use drawer for:

-   mobile filters;
-   quick edit;
-   mobile navigation;
-   compact forms.

Drawer must:

-   trap focus;
-   close via Escape;
-   have visible close action;
-   preserve scroll context.

------------------------------------------------------------------------

# 67. Toasts

Use for:

-   saved;
-   updated;
-   deleted;
-   copied;
-   submitted.

Toast should not replace important inline validation.

------------------------------------------------------------------------

# 68. Confirmation Dialog

For destructive action:

``` text
আপনি কি নিশ্চিতভাবে এই কোর্সটি আর্কাইভ করতে চান?

[Cancel] [Archive]
```

Destructive action must be visually clear.

------------------------------------------------------------------------

# 69. Loading States

Never leave blank screens.

Use:

-   skeleton cards;
-   skeleton text;
-   button spinner;
-   table skeleton.

Avoid generic full-screen spinner for normal navigation.

------------------------------------------------------------------------

# 70. Empty States

Illustration should be minimal.

Example:

``` text
কোনো কোর্স পাওয়া যায়নি

ফিল্টার পরিবর্তন করে আবার চেষ্টা করুন।

[সব ফিল্টার পরিষ্কার করুন]
```

------------------------------------------------------------------------

# 71. Error States

Example:

``` text
কিছু একটা সমস্যা হয়েছে।

অনুগ্রহ করে আবার চেষ্টা করুন।

[Retry]
```

For admin API errors:

-   human message;
-   technical reference ID if useful.

------------------------------------------------------------------------

# 72. Badges

Badge types:

``` text
Featured
New
Free
Upcoming
Popular
Sold Out
Draft
Published
Pending
```

Use badge colors semantically.

Do not use more than 1--2 badges per card unless necessary.

------------------------------------------------------------------------

# 73. Icons

Recommended style:

-   outline;
-   rounded;
-   simple;
-   consistent stroke.

Do not mix multiple icon libraries visually.

Icon sizes:

``` text
16px
18px
20px
24px
32px
```

Large decorative icons should be rare.

------------------------------------------------------------------------

# 74. Illustrations

Illustrations should use:

-   soft blue;
-   muted neutral;
-   controlled gold.

Avoid cartoon-heavy visuals unless intentionally used for a specific
educational campaign.

------------------------------------------------------------------------

# 75. Photography

Photography should feel:

-   authentic;
-   professional;
-   warm;
-   educational.

Preferred subjects:

-   instructors;
-   students;
-   classroom moments;
-   learning;
-   technology;
-   discussion.

Avoid generic over-staged stock photos wherever real academy photography
is available.

------------------------------------------------------------------------

# 76. Image Treatment

Images:

-   rounded;
-   consistent aspect ratios;
-   optimized;
-   no excessive borders.

Hero images may use:

-   soft gradient overlay;
-   subtle glow;
-   controlled shadow.

Do not overuse masks.

------------------------------------------------------------------------

# 77. Motion Design

Motion should feel:

-   smooth;
-   subtle;
-   fast enough to feel responsive;
-   slow enough to feel premium.

Recommended:

``` text
Fast: 150ms
Normal: 200ms
Medium: 300ms
Slow: 450ms
```

Use easing:

``` text
ease-out
cubic-bezier(...)
```

------------------------------------------------------------------------

# 78. Animation Rules

Good:

-   button hover;
-   card lift 1--3px;
-   accordion expansion;
-   tab indicator;
-   drawer;
-   modal;
-   subtle hero entrance.

Avoid:

-   constant floating;
-   bouncing;
-   excessive parallax;
-   auto-rotating text;
-   distracting scroll animations.

Respect:

``` text
prefers-reduced-motion
```

------------------------------------------------------------------------

# 79. Hover States

Desktop hover:

-   slightly lighter surface;
-   subtle border;
-   1--2px elevation;
-   icon movement;
-   gold accent.

Do not dramatically enlarge cards.

Mobile must have an equivalent non-hover interaction.

------------------------------------------------------------------------

# 80. Focus States

Keyboard focus must be obvious.

Use:

``` text
2px focus ring
```

Gold or bright blue depending on background.

Do not remove browser accessibility focus without replacement.

------------------------------------------------------------------------

# 81. Accessibility Color Rules

Never use color alone to communicate:

-   status;
-   error;
-   success;
-   selected state.

Pair color with:

-   icon;
-   text;
-   pattern;
-   label.

------------------------------------------------------------------------

# 82. Forms

Inputs:

-   44--48px minimum height;
-   rounded;
-   soft border;
-   clear label;
-   helpful placeholder;
-   validation message.

Do not rely only on placeholder as label.

------------------------------------------------------------------------

# 83. Input States

Required states:

``` text
Default
Hover
Focus
Filled
Error
Success
Disabled
Readonly
```

Error:

-   border;
-   icon;
-   message.

------------------------------------------------------------------------

# 84. Pricing UI

Primary price:

-   large;
-   bold;
-   high contrast.

Discount:

-   secondary.

Example:

``` text
৳ 8,500
৳ 10,000
20% OFF
```

Avoid making the discount badge visually louder than the final price.

------------------------------------------------------------------------

# 85. Capacity UI

Use:

``` text
18 / 25 seats filled
```

Progress:

``` text
██████████████░░░
```

For low availability:

-   subtle warning state.

For full:

``` text
সিট পূর্ণ
```

Do not use fake countdown timers.

------------------------------------------------------------------------

# 86. Course Comparison

If comparison is implemented later:

-   title;
-   price;
-   level;
-   duration;
-   lessons;
-   instructor;
-   certificate;
-   support;
-   format.

Keep comparison simple.

------------------------------------------------------------------------

# 87. Breadcrumbs

Use on:

-   course detail;
-   webinar detail;
-   blog detail;
-   ebook detail;
-   dashboard subpages.

Example:

``` text
হোম / কোর্সসমূহ / Web Development
```

Mobile:

-   truncate middle;
-   preserve current page.

------------------------------------------------------------------------

# 88. Pagination

Desktop:

``` text
← Previous  1  2  3  ...  10  Next →
```

Mobile:

``` text
← Previous
Page 2 of 10
Next →
```

Infinite scroll is not recommended for primary SEO content pages.

------------------------------------------------------------------------

# 89. Tabs

Tabs should be:

-   clearly grouped;
-   horizontally scrollable on mobile;
-   keyboard accessible.

Active state:

-   gold text;
-   gold indicator.

------------------------------------------------------------------------

# 90. Dark/Light Theme Component Mapping

Every component must define both themes.

Example:

``` text
Card:
Dark → Surface 900
Light → White

Text:
Dark → White
Light → Deep Blue

Border:
Dark → Soft White/Blue
Light → Blue Gray

Accent:
Both → Gold
```

No component should use a hard-coded dark-only color.

------------------------------------------------------------------------

# 91. Theme Switcher

Use compact icon control:

``` text
☾
```

or:

``` text
Dark / Light
```

Tooltip:

-   Dark mode
-   Light mode

Respect system preference on first visit.

------------------------------------------------------------------------

# 92. Language Switcher

Desktop:

``` text
বাংলা | EN
```

Mobile:

``` text
বাংলা
English
```

Do not use flags as the only language indicator.

------------------------------------------------------------------------

# 93. Mobile Navigation

Mobile menu:

``` text
Logo
Close

হোম
কোর্সসমূহ
ওয়েবিনার
ইবুক
ব্লগ
আমাদের সম্পর্কে
যোগাযোগ

বাংলা / English
Dark / Light

[Login]
```

Menu should scroll independently if necessary.

------------------------------------------------------------------------

# 94. Mobile Course Purchase

On course detail:

A sticky bottom action can contain:

``` text
৳ 8,500
[Buy Now]
```

Only use if it does not cover important content.

------------------------------------------------------------------------

# 95. Mobile Dashboard

Structure:

``` text
Topbar
Page content
Bottom/Drawer navigation
```

Avoid permanently consuming 30--35% of the viewport with a sidebar.

------------------------------------------------------------------------

# 96. Desktop Dashboard Density

Desktop dashboards can use:

-   4 metric cards;
-   2-column analytics;
-   tables;
-   activity timeline.

Keep enough spacing between functional areas.

------------------------------------------------------------------------

# 97. Tablet Strategy

Tablet must not simply receive desktop UI squeezed into a smaller width.

Changes:

-   2-column course grid;
-   collapsible sidebar;
-   simplified topbar;
-   2-column forms where practical;
-   larger touch targets.

------------------------------------------------------------------------

# 98. Large Desktop Strategy

At 1440px+:

-   increase whitespace;
-   preserve max-width;
-   do not stretch text to extreme widths;
-   use larger imagery;
-   optionally show additional secondary content.

Do not create giant 1600px-wide text blocks.

------------------------------------------------------------------------

# 99. Design System Naming

Components should follow consistent names:

``` text
ButtonPrimary
ButtonSecondary
CourseCard
CourseMeta
ReviewCard
InstructorCard
SectionHeader
PageHeader
SearchInput
FilterDrawer
DataTable
StatusBadge
```

Avoid vague names like:

``` text
Box1
Card2
NewCard
FinalCard
```

------------------------------------------------------------------------

# 100. Design Tokens in Code

Use CSS variables or equivalent theme tokens.

Conceptual:

``` css
:root {
  --color-bg: ...;
  --color-surface: ...;
  --color-text: ...;
  --color-brand: ...;
  --radius-card: ...;
  --space-section: ...;
}

[data-theme="light"] {
  ...
}

[data-theme="dark"] {
  ...
}
```

Component styles should consume tokens.

------------------------------------------------------------------------

# 101. Public Page Visual Hierarchy

Every page should follow:

``` text
Page Context
↓
Primary Heading
↓
Primary Information
↓
Action
↓
Supporting Details
↓
Related Content
↓
CTA
↓
Footer
```

Avoid presenting every piece of information at equal visual weight.

------------------------------------------------------------------------

# 102. Homepage Visual Hierarchy

``` text
Hero
★★★★★

Featured Courses
★★★★

Ebooks
★★★

Why Choose Us
★★★

Packages
★★★★

Free Classes
★★★

Reviews
★★★★

FAQ
★★

Ecosystem
★★

Final CTA
★★★★★
```

This is a hierarchy guideline, not a literal scoring system.

------------------------------------------------------------------------

# 103. Course Page Visual Hierarchy

Highest priority:

1.  Course name
2.  Value proposition
3.  Price/action
4.  Capacity
5.  Curriculum
6.  Instructor
7.  Reviews
8.  Related courses

------------------------------------------------------------------------

# 104. Webinar Page Hierarchy

1.  Event title
2.  Date/time
3.  Registration
4.  Value proposition
5.  Speaker
6.  Prerequisites
7.  Details
8.  Related events

------------------------------------------------------------------------

# 105. Ebook Page Hierarchy

1.  Cover
2.  Title
3.  Author
4.  Price/download
5.  Metadata
6.  Description
7.  Preview
8.  Related ebooks

------------------------------------------------------------------------

# 106. Blog Reading Hierarchy

1.  Title
2.  Metadata
3.  Hero image
4.  Intro
5.  Article body
6.  Related content

The article body must have strong readability.

------------------------------------------------------------------------

# 107. SEO Visual Requirements

SEO should not distort the visual design.

Use:

-   one H1 per primary page;
-   logical H2/H3;
-   descriptive link text;
-   meaningful image alt;
-   visible content matching metadata.

------------------------------------------------------------------------

# 108. Component State Documentation

Every reusable component should document:

``` text
Default
Hover
Focus
Active
Disabled
Loading
Error
Empty
Dark
Light
Mobile
Desktop
```

------------------------------------------------------------------------

# 109. Design QA Checklist

Before release:

## Visual

-   [ ] Dark theme checked
-   [ ] Light theme checked
-   [ ] Bangla typography checked
-   [ ] English typography checked
-   [ ] Mixed-language rendering checked
-   [ ] No layout overflow
-   [ ] Consistent spacing
-   [ ] Consistent radius
-   [ ] Consistent icons

## Responsive

-   [ ] 360px
-   [ ] 390px
-   [ ] 430px
-   [ ] 768px
-   [ ] 1024px
-   [ ] 1280px
-   [ ] 1440px
-   [ ] 1920px

## Accessibility

-   [ ] keyboard
-   [ ] focus
-   [ ] contrast
-   [ ] labels
-   [ ] alt text
-   [ ] reduced motion

------------------------------------------------------------------------

# 110. Design QA for Bangla

Specifically verify:

-   line wrapping;
-   punctuation;
-   Bengali numerals where intentionally used;
-   mixed Bangla/English spacing;
-   heading height;
-   button text fit;
-   table readability;
-   mobile navigation;
-   form labels;
-   card descriptions.

Hind Siliguri should be loaded consistently to prevent layout shifts.

------------------------------------------------------------------------

# 111. Design QA for Long Content

The CMS must allow long:

-   course titles;
-   blog titles;
-   ebook titles;
-   instructor names;
-   descriptions.

Cards must remain stable when content length changes.

Use:

-   controlled line clamps where appropriate;
-   flexible height where readability matters;
-   fixed image aspect ratios.

Do not hard-truncate important information.

------------------------------------------------------------------------

# 112. Empty/Loading/Error Visual Language

The three states should visually belong to the same design system.

``` text
Loading → Skeleton
Empty → Soft illustration + message
Error → Status icon + message + retry
```

------------------------------------------------------------------------

# 113. Notification Design

In-app notifications:

``` text
[Icon] Course enrollment confirmed
       আপনার কোর্সে ভর্তি সম্পন্ন হয়েছে
       5 min ago
```

Unread:

-   subtle blue/gold indicator.

Avoid bright red unless notification is genuinely critical.

------------------------------------------------------------------------

# 114. Profile Design

Student profile:

``` text
Avatar
Name
Email
Phone
Joined Date

Edit Profile
Change Password
```

Staff profile can additionally show:

-   role;
-   permissions summary;
-   last login.

Never expose sensitive authentication data.

------------------------------------------------------------------------

# 115. Security UI

Security-sensitive actions should have clear confirmation.

Examples:

``` text
Delete User
Change Role
Reset Password
Publish Course
Change Payment Setting
```

Use stronger confirmation for high-impact actions.

------------------------------------------------------------------------

# 116. Admin Role Editor

Recommended visual:

``` text
Role Name
Role Description

Permissions

Courses
☑ View
☑ Create
☑ Update
☐ Delete
☑ Publish

Webinars
☑ View
☑ Create
☑ Update
☐ Delete
```

Include:

``` text
Select All
Clear All
```

but avoid accidental mass privilege assignment.

------------------------------------------------------------------------

# 117. Audit Log UI

Timeline/table:

``` text
21 Sep 2026 · 10:22

Admin
Updated Course #32

Changed:
Capacity 20 → 25

[View Details]
```

Use muted styling.

------------------------------------------------------------------------

# 118. Design for Trust

Trust indicators may include:

-   number of students;
-   years/experience;
-   instructor experience;
-   completed batches;
-   verified reviews;
-   partner/organization logos.

Only display factual metrics that the academy can substantiate.

------------------------------------------------------------------------

# 119. Conversion Design

Every public page should have one clear primary action.

Examples:

Course:

``` text
Buy Now
```

Webinar:

``` text
Register
```

Ebook:

``` text
Buy / Download
```

Blog:

``` text
Explore Courses
```

About:

``` text
Explore Courses
```

Contact:

``` text
Send Message
```

------------------------------------------------------------------------

# 120. CTA Consistency

Do not randomly alternate:

``` text
Learn More
Explore
View
See More
Discover
Check
```

for the same action.

Define a controlled copy vocabulary.

Recommended:

``` text
বিস্তারিত দেখুন
সব দেখুন
কোর্স দেখুন
এখনই ভর্তি হোন
কার্টে যোগ করুন
ফ্রি ক্লাস দেখুন
রেজিস্টার করুন
যোগাযোগ করুন
```

------------------------------------------------------------------------

# 121. Design Copy Tone

Bangla copy should be:

-   natural;
-   confident;
-   respectful;
-   concise;
-   educational;
-   human.

Avoid:

-   exaggerated promises;
-   aggressive sales language;
-   unrealistic career guarantees;
-   excessive English jargon.

------------------------------------------------------------------------

# 122. Visual Content Ratio

For public marketing pages:

``` text
Text + UI: ~55–65%
Imagery: ~35–45%
```

This is a flexible guideline, not a strict mathematical rule.

Course detail pages may be more information-heavy.

------------------------------------------------------------------------

# 123. Visual Consistency Rules

All public pages must share:

-   same header;
-   same footer;
-   same page container;
-   same typography;
-   same button system;
-   same card system;
-   same theme tokens;
-   same spacing.

Dashboards may have a separate layout but must share the same brand
system.

------------------------------------------------------------------------

# 124. Design System Documentation

The design system should eventually be represented in Storybook or an
equivalent internal component catalog.

Document:

``` text
Colors
Typography
Buttons
Inputs
Cards
Tables
Modals
Drawers
Tabs
Badges
Alerts
Navigation
Pagination
```

------------------------------------------------------------------------

# 125. Final Design Direction

The final Emisha Academy experience should visually read as:

> **A calm, premium Bangla-first digital academy where the learner
> immediately understands what to learn, why it matters, who teaches it,
> what it costs, and how to begin.**

The interface should not attempt to impress through visual complexity.

It should impress through:

-   clarity;
-   consistency;
-   typography;
-   spacing;
-   polish;
-   information hierarchy;
-   responsive behavior;
-   trust.

------------------------------------------------------------------------

# 126. Design Implementation Priority

Implement the visual system in this order:

``` text
1. Theme tokens
2. Typography
3. Spacing
4. Container/Grid
5. Buttons
6. Inputs
7. Cards
8. Header/Footer
9. Course components
10. Review components
11. Public page layouts
12. Dashboard shell
13. Dashboard components
14. Tables/forms
15. Motion
16. Accessibility refinement
17. Responsive refinement
```

Do not build individual pages first and create the design system
afterward.

The design system must come first.

------------------------------------------------------------------------

# 127. Design Definition of Done

The design system is ready when:

-   [ ] Dark theme is complete
-   [ ] Light theme is complete
-   [ ] Typography is consistent
-   [ ] Hind Siliguri is correctly applied to Bangla
-   [ ] Poppins is correctly applied to English
-   [ ] Responsive breakpoints are documented
-   [ ] Component states are defined
-   [ ] Public page layouts are defined
-   [ ] Dashboard layouts are defined
-   [ ] Mobile behavior is defined
-   [ ] Accessibility states are defined
-   [ ] Loading/empty/error states are defined
-   [ ] CMS-driven content does not break layouts
-   [ ] Course capacity UI is defined
-   [ ] Review tabs are defined
-   [ ] Search/filter UI is defined
-   [ ] CTA hierarchy is defined
-   [ ] Design tokens are ready for Vue/CSS implementation

------------------------------------------------------------------------

# 128. Handoff to ARCHITECTURE.md

The next document must convert this visual system into a technical
architecture.

`ARCHITECTURE.md` should define:

-   Laravel application structure;
-   Vue 3 application structure;
-   MySQL schema;
-   all entities and relationships;
-   migrations;
-   authentication;
-   Sanctum;
-   RBAC;
-   Policies/Gates;
-   scoped permissions;
-   API endpoints;
-   services;
-   actions;
-   events;
-   queues;
-   caching;
-   media storage;
-   search;
-   localization;
-   SEO;
-   payment abstraction;
-   enrollment transactions;
-   capacity locking;
-   notifications;
-   audit logging;
-   testing;
-   CI/CD;
-   deployment.

------------------------------------------------------------------------

# 129. Design Handoff Rule

The architecture implementation must preserve this design contract.

Do not introduce backend-driven UI patterns that cause:

-   inconsistent spacing;
-   hard-coded colors;
-   arbitrary component styles;
-   duplicated UI;
-   inaccessible forms;
-   dashboard/public visual inconsistency.

All Vue components should consume the design tokens and reusable
components defined by this document.

------------------------------------------------------------------------

# 130. Master Visual Rule

**Emisha Academy should look premium because it is organized, not
because it is overloaded.**

The design should always prefer:

``` text
Clarity > Decoration
Consistency > Novelty
Readability > Density
Trust > Hype
Useful Motion > Constant Motion
Content > Ornament
System > One-off Styling
```
