<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#FFFFFF">
    
    <title>{{ config('app.name', 'Emisha Academy') }} - আধুনিক ও মানসম্মত স্কিল প্ল্যাটফর্ম</title>
    
    <!-- PWA & Mobile Web App Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Emisha Academy">
    <meta name="application-name" content="Emisha Academy">
    <meta name="msapplication-TileColor" content="#D4AF37">
    <meta name="msapplication-TileImage" content="{{ asset('android-chrome-192x192.png') }}">
    
    <!-- Favicons & App Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Anti-FOIT: Early Theme Initializer Script -->
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('emisha_theme');
                var isDark = savedTheme === 'dark';
                var root = document.documentElement;
                if (isDark) {
                    root.classList.add('dark');
                    root.classList.remove('light');
                    root.setAttribute('data-theme', 'dark');
                } else {
                    root.classList.remove('dark');
                    root.classList.add('light');
                    root.setAttribute('data-theme', 'light');
                }
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Primary SEO Meta Tags -->
    <meta name="title" content="Emisha Academy - এভিয়েশন, এয়ার টিকেটিং ও ভিসা প্রসেসিং ট্রেনিং">
    <meta name="description" content="বাংলাদেশ ও আন্তর্জাতিক এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়তে Sabre/Galileo GDS এয়ার টিকেটিং ও ভিসা প্রসেসিং প্র্যাকটিক্যাল ট্রেনিং নিন ইমিশা একাডেমিতে।">
    <meta name="keywords" content="air ticketing course bangladesh, sabre gds training dhaka, galileo gds course mirpur, visa processing course, travel agency business training, aviation academy bangladesh, emisha academy">
    <meta name="author" content="Emisha Academy">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" id="canonical-url" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook / LinkedIn -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Emisha Academy">
    <meta property="og:url" id="og-url" content="{{ url()->current() }}">
    <meta property="og:title" id="og-title" content="Emisha Academy - এভিয়েশন, এয়ার টিকেটিং ও ভিসা প্রসেসিং ট্রেনিং">
    <meta property="og:description" id="og-desc" content="বাংলাদেশ ও আন্তর্জাতিক এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়তে Sabre/Galileo GDS এয়ার টিকেটিং ও ভিসা প্রসেসিং প্র্যাকটিক্যাল ট্রেনিং নিন ইমিশা একাডেমিতে।">
    <meta property="og:image" id="og-image" content="{{ asset('android-chrome-512x512.png') }}">
    <meta property="og:locale" content="bn_BD">
    <meta property="og:locale:alternate" content="en_US">

    <!-- Twitter / X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@EmishaAcademy">
    <meta name="twitter:creator" content="@EmishaAcademy">
    <meta name="twitter:url" id="tw-url" content="{{ url()->current() }}">
    <meta name="twitter:title" id="tw-title" content="Emisha Academy - এভিয়েশন, এয়ার টিকেটিং ও ভিসা প্রসেসিং ট্রেনিং">
    <meta name="twitter:description" id="tw-desc" content="বাংলাদেশ ও আন্তর্জাতিক এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়তে Sabre/Galileo GDS এয়ার টিকেটিং ও ভিসা প্রসেসিং প্র্যাকটিক্যাল ট্রেনিং নিন।">
    <meta name="twitter:image" id="tw-image" content="{{ asset('android-chrome-512x512.png') }}">

    <!-- Structured Data (Schema.org JSON-LD): EducationalOrganization & WebSite -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@graph": [
        {
          "@@type": "EducationalOrganization",
          "@@id": "{{ url('/') }}#organization",
          "name": "Emisha Academy",
          "alternateName": "ইমিশা একাডেমি",
          "url": "{{ url('/') }}",
          "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('android-chrome-512x512.png') }}",
            "width": 512,
            "height": 512
          },
          "description": "Leading Aviation, Sabre & Galileo GDS Air Ticketing, Visa Processing & Global Tourism Skill Development Institute in Bangladesh.",
          "telephone": "+8801805464293",
          "email": "info@emisha.academy",
          "address": {
            "@@type": "PostalAddress",
            "streetAddress": "Mirpur-10, Dhaka",
            "addressLocality": "Dhaka",
            "postalCode": "1216",
            "addressCountry": "BD"
          },
          "geo": {
            "@@type": "GeoCoordinates",
            "latitude": "23.8069",
            "longitude": "90.3687"
          },
          "sameAs": [
            "https://www.facebook.com/profile.php?id=61590103572746",
            "https://maps.app.goo.gl/S8HazBnbGhM6H2xo7"
          ],
          "openingHoursSpecification": [
            {
              "@@type": "OpeningHoursSpecification",
              "dayOfWeek": [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
                "Sunday"
              ],
              "opens": "10:00",
              "closes": "18:30"
            }
          ]
        },
        {
          "@@type": "WebSite",
          "@@id": "{{ url('/') }}#website",
          "url": "{{ url('/') }}",
          "name": "Emisha Academy",
          "publisher": {
            "@@id": "{{ url('/') }}#organization"
          },
          "inLanguage": ["bn-BD", "en-US"],
          "potentialAction": {
            "@@type": "SearchAction",
            "target": {
              "@@type": "EntryPoint",
              "urlTemplate": "{{ url('/courses') }}?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }
        }
      ]
    }
    </script>
    
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1038263312547629');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1038263312547629&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
</head>
<body class="bg-[var(--bg-deep)] text-[var(--text-primary)] font-bangla antialiased selection:bg-[#D4AF37] selection:text-slate-950 min-h-screen transition-colors duration-200">
    <div id="app"></div>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</body>
</html>
