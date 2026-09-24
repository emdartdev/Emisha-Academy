/**
 * Emisha Academy - Comprehensive SEO & Structured Data (JSON-LD) Management Composable
 */

export interface SeoOptions {
  title?: string;
  description?: string;
  keywords?: string;
  image?: string;
  url?: string;
  type?: 'website' | 'article' | 'course' | 'event';
  schema?: Record<string, any> | Record<string, any>[];
}

const DEFAULT_TITLE = 'Emisha Academy - এভিয়েশন, এয়ার টিকেটিং ও ভিসা প্রসেসিং ট্রেনিং';
const DEFAULT_DESC = 'বাংলাদেশ ও আন্তর্জাতিক এভিয়েশন ও ট্রাভেল এজেন্সিতে ক্যারিয়ার গড়তে Sabre/Galileo GDS এয়ার টিকেটিং ও ভিসা প্রসেসিং প্র্যাকটিক্যাল ট্রেনিং নিন ইমিশা একাডেমিতে।';
const DEFAULT_IMAGE = '/android-chrome-512x512.png';
const BASE_URL = typeof window !== 'undefined' ? window.location.origin : 'https://emisha.academy';

export function useSeo() {
  const setMeta = (options: SeoOptions) => {
    if (typeof document === 'undefined') return;

    const fullTitle = options.title
      ? `${options.title} | Emisha Academy`
      : DEFAULT_TITLE;
    const description = options.description || DEFAULT_DESC;
    const currentUrl = options.url || (typeof window !== 'undefined' ? window.location.href : BASE_URL);
    const imageUrl = options.image
      ? (options.image.startsWith('http') ? options.image : `${BASE_URL}${options.image}`)
      : `${BASE_URL}${DEFAULT_IMAGE}`;

    // 1. Document Title
    document.title = fullTitle;

    // 2. Meta Tags Helper
    const setMetaTag = (selector: string, attr: string, value: string) => {
      let el = document.querySelector(selector);
      if (!el) {
        el = document.createElement('meta');
        if (selector.includes('name=')) {
          const name = selector.match(/name="([^"]+)"/)?.[1];
          if (name) el.setAttribute('name', name);
        } else if (selector.includes('property=')) {
          const prop = selector.match(/property="([^"]+)"/)?.[1];
          if (prop) el.setAttribute('property', prop);
        }
        document.head.appendChild(el);
      }
      el.setAttribute(attr, value);
    };

    // Primary Meta
    setMetaTag('meta[name="title"]', 'content', fullTitle);
    setMetaTag('meta[name="description"]', 'content', description);
    if (options.keywords) {
      setMetaTag('meta[name="keywords"]', 'content', options.keywords);
    }

    // Canonical
    let canonical = document.querySelector('link[rel="canonical"]');
    if (!canonical) {
      canonical = document.createElement('link');
      canonical.setAttribute('rel', 'canonical');
      document.head.appendChild(canonical);
    }
    canonical.setAttribute('href', currentUrl);

    // OpenGraph
    setMetaTag('meta[property="og:title"]', 'content', fullTitle);
    setMetaTag('meta[property="og:description"]', 'content', description);
    setMetaTag('meta[property="og:url"]', 'content', currentUrl);
    setMetaTag('meta[property="og:image"]', 'content', imageUrl);
    setMetaTag('meta[property="og:type"]', 'content', options.type || 'website');

    // Twitter
    setMetaTag('meta[name="twitter:title"]', 'content', fullTitle);
    setMetaTag('meta[name="twitter:description"]', 'content', description);
    setMetaTag('meta[name="twitter:url"]', 'content', currentUrl);
    setMetaTag('meta[name="twitter:image"]', 'content', imageUrl);

    // 3. Dynamic JSON-LD Structured Data
    const existingScript = document.getElementById('dynamic-schema-ld');
    if (existingScript) {
      existingScript.remove();
    }

    if (options.schema) {
      const script = document.createElement('script');
      script.id = 'dynamic-schema-ld';
      script.type = 'application/ld+json';
      const schemaData = Array.isArray(options.schema)
        ? {
            '@context': 'https://schema.org',
            '@graph': options.schema,
          }
        : {
            '@context': 'https://schema.org',
            ...options.schema,
          };
      script.textContent = JSON.stringify(schemaData, null, 2);
      document.head.appendChild(script);
    }
  };

  /**
   * Helper to build Course JSON-LD Schema
   */
  const buildCourseSchema = (course: any) => {
    return {
      '@type': 'Course',
      'name': course.title_bn || course.title_en,
      'description': course.subtitle_bn || course.description_bn || course.title_en,
      'provider': {
        '@type': 'EducationalOrganization',
        'name': 'Emisha Academy',
        'sameAs': 'https://emisha.academy',
      },
      'offers': {
        '@type': 'Offer',
        'category': 'Paid',
        'priceCurrency': 'BDT',
        'price': course.sale_price || course.regular_price || 0,
        'availability': 'https://schema.org/InStock',
      },
      'hasCourseInstance': {
        '@type': 'CourseInstance',
        'courseMode': course.format === 'live' ? 'Blended' : 'Online',
        'courseWorkload': `PT${course.total_hours || 40}H`,
        'instructor': {
          '@type': 'Person',
          'name': course.instructor?.name || 'Senior Aviation Specialist, Emisha Academy',
        },
      },
    };
  };

  /**
   * Helper to build Webinar / Event JSON-LD Schema
   */
  const buildWebinarSchema = (webinar: any) => {
    return {
      '@type': 'Event',
      'name': webinar.title_bn || webinar.title_en,
      'description': webinar.subtitle_bn || webinar.description_bn,
      'startDate': webinar.start_time || new Date().toISOString(),
      'eventStatus': 'https://schema.org/EventScheduled',
      'eventAttendanceMode': 'https://schema.org/OnlineEventAttendanceMode',
      'location': {
        '@type': 'VirtualLocation',
        'url': `${BASE_URL}/webinars/${webinar.slug}`,
      },
      'organizer': {
        '@type': 'Organization',
        'name': 'Emisha Academy',
        'url': BASE_URL,
      },
      'offers': {
        '@type': 'Offer',
        'price': webinar.is_free ? '0' : (webinar.fee || '0'),
        'priceCurrency': 'BDT',
        'availability': 'https://schema.org/InStock',
      },
    };
  };

  /**
   * Helper to build Blog / Article JSON-LD Schema
   */
  const buildArticleSchema = (post: any) => {
    return {
      '@type': 'BlogPosting',
      'headline': post.title_bn || post.title_en,
      'description': post.excerpt_bn || post.excerpt_en || post.title_bn,
      'image': post.thumbnail || `${BASE_URL}${DEFAULT_IMAGE}`,
      'datePublished': post.published_at || post.created_at,
      'dateModified': post.updated_at || post.created_at,
      'author': {
        '@type': 'Person',
        'name': post.author_name || 'Emisha Academy Editorial Team',
      },
      'publisher': {
        '@type': 'Organization',
        'name': 'Emisha Academy',
        'logo': {
          '@type': 'ImageObject',
          'url': `${BASE_URL}${DEFAULT_IMAGE}`,
        },
      },
    };
  };

  /**
   * Helper to build Ebook / Digital Document JSON-LD Schema
   */
  const buildEbookSchema = (ebook: any) => {
    return {
      '@type': 'Book',
      'name': ebook.title_bn || ebook.title_en,
      'description': ebook.description_bn || ebook.summary_bn || ebook.title_en,
      'image': ebook.cover_image || `${BASE_URL}${DEFAULT_IMAGE}`,
      'bookFormat': 'https://schema.org/EBook',
      'numberOfPages': ebook.pages_count || 95,
      'inLanguage': ['bn-BD', 'en-US'],
      'author': {
        '@type': 'Person',
        'name': ebook.author_name_bn || ebook.author_name_en || 'ইমিশা একাডেমি রিসার্চ টিম',
      },
      'publisher': {
        '@type': 'Organization',
        'name': 'Emisha Academy',
        'url': BASE_URL,
      },
      'aggregateRating': {
        '@type': 'AggregateRating',
        'ratingValue': ebook.rating || '4.95',
        'reviewCount': ebook.reviews_count || 84,
        'bestRating': '5',
        'worstRating': '1',
      },
      'offers': {
        '@type': 'Offer',
        'price': ebook.is_free ? '0' : (ebook.sale_price || ebook.regular_price || '0'),
        'priceCurrency': 'BDT',
        'availability': 'https://schema.org/InStock',
      },
    };
  };

  /**
   * Helper to build BreadcrumbList JSON-LD Schema
   */
  const buildBreadcrumbSchema = (items: { name: string; url: string }[]) => {
    return {
      '@type': 'BreadcrumbList',
      'itemListElement': items.map((item, index) => ({
        '@type': 'ListItem',
        'position': index + 1,
        'name': item.name,
        'item': item.url.startsWith('http') ? item.url : `${BASE_URL}${item.url}`,
      })),
    };
  };

  /**
   * Helper to build FAQPage JSON-LD Schema
   */
  const buildFAQSchema = (faqs: { question: string; answer: string }[]) => {
    return {
      '@type': 'FAQPage',
      'mainEntity': faqs.map((faq) => ({
        '@type': 'Question',
        'name': faq.question,
        'acceptedAnswer': {
          '@type': 'Answer',
          'text': faq.answer,
        },
      })),
    };
  };

  /**
   * Helper to build Educational Organization Schema
   */
  const buildOrganizationSchema = () => {
    return {
      '@type': 'EducationalOrganization',
      'name': 'Emisha Academy',
      'alternateName': 'ইমিশা একাডেমি',
      'url': BASE_URL,
      'logo': `${BASE_URL}${DEFAULT_IMAGE}`,
      'telephone': '+8801805464293',
      'email': 'info@emisha.academy',
      'address': {
        '@type': 'PostalAddress',
        'streetAddress': 'Mirpur-10, Dhaka',
        'addressLocality': 'Dhaka',
        'postalCode': '1216',
        'addressCountry': 'BD',
      },
      'sameAs': [
        'https://www.facebook.com/profile.php?id=61590103572746',
        'https://maps.app.goo.gl/S8HazBnbGhM6H2xo7',
      ],
    };
  };

  return {
    setMeta,
    buildCourseSchema,
    buildWebinarSchema,
    buildArticleSchema,
    buildEbookSchema,
    buildBreadcrumbSchema,
    buildFAQSchema,
    buildOrganizationSchema,
  };
}

