import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import { useSeo } from '../composables/useSeo';
import { useAuthStore } from '../stores/auth';

const routes: Array<RouteRecordRaw> = [
  // Public Routes
  {
    path: '/',
    component: () => import('../layouts/PublicLayout.vue'),
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('../views/public/HomeView.vue'),
        meta: {
          title: 'এভিয়েশন, এয়ার টিকেটিং ও ভিসা প্রসেসিং ট্রেনিং',
          description: 'ইমিশা একাডেমিতে Sabre/Galileo GDS ও ভিসা প্রসেসিংয়ের লাইভ হ্যান্ডস-অন ট্রেনিং নিয়ে এভিয়েশন ও ট্রাভেল এজেন্সিতে প্রফেশনাল ক্যারিয়ার শুরু করুন।',
          keywords: 'air ticketing course, sabre training bangladesh, galileo gds course, visa processing training dhaka, emisha academy',
        },
      },
      {
        path: 'courses',
        name: 'courses',
        component: () => import('../views/public/CoursesView.vue'),
        meta: {
          title: 'প্রফেশনাল ক্যারিয়ার কোর্সসমূহ (Courses)',
          description: 'এয়ার টিকেটিং (Sabre/Galileo), ভিসা প্রসেসিং ও গ্লোবাল ট্যুরিজম ক্যারিয়ার কোর্সসমূহ দেখুন এবং বিশেষ ছাড়ে ভর্তি হোন।',
          keywords: 'air ticketing course fee, sabre gds training, visa consultancy course, emisha academy courses',
        },
      },
      {
        path: 'courses/:slug',
        name: 'course-detail',
        component: () => import('../views/public/CourseDetailView.vue'),
        meta: {
          title: 'কোর্স বিবরণী ও সিলেবাস',
          type: 'course',
        },
      },
      {
        path: 'webinars',
        name: 'webinars',
        component: () => import('../views/public/WebinarsView.vue'),
        meta: {
          title: 'ফ্রি লাইভ মাস্টারক্লাস ও ওয়েবিনার (Live Webinars)',
          description: 'এভিয়েশন ক্যারিয়ার, এয়ার টিকেটিং বিজনেস এবং ভিসা কনসালটেন্সি বিষয়ক ফ্রি লাইভ সেমিনার ও মাস্টারক্লাসে অংশ নিন।',
          keywords: 'free aviation webinar, travel agency career workshop, visa consultancy seminar',
        },
      },
      {
        path: 'webinars/:slug',
        name: 'webinar-detail',
        component: () => import('../views/public/WebinarDetailView.vue'),
        meta: {
          title: 'মাস্টারক্লাস রেজিস্ট্রেশন ও রেকর্ডিং',
          type: 'event',
        },
      },
      {
        path: 'ebooks',
        name: 'ebooks',
        component: () => import('../views/public/EbooksView.vue'),
        meta: {
          title: 'ফ্রি প্রফেশনাল ই-বুক ও রিসোর্স (Free E-books)',
          description: 'Sabre GDS শর্টকাট শিট, ভিসা চেকলিস্ট ও এভিয়েশন ক্যারিয়ার ফ্রি গাইডবুক ডাউনলোড করুন।',
          keywords: 'sabre gds cheat sheet pdf, visa checklist bangladesh, aviation career guidebook pdf',
        },
      },
      {
        path: 'ebooks/:slug',
        name: 'ebook-detail',
        component: () => import('../views/public/EbookDetailView.vue'),
        meta: {
          title: 'ই-বুক ডাউনলোড',
        },
      },
      {
        path: 'blog',
        name: 'blog',
        component: () => import('../views/public/BlogView.vue'),
        meta: {
          title: 'এভিয়েশন ও ট্রাভেল ব্লগ (Aviation & Career Blog)',
          description: 'এভিয়েশন ইন্ডাস্ট্রি আপডেট, ভিসা প্রসেসিং নিয়মাবলী ও ট্রাভেল এজেন্সি ব্যবসা সংক্রান্ত প্র্যাকটিক্যাল আর্টিকেল।',
          keywords: 'aviation industry news bangladesh, air ticketing tips, travel agency business guide',
        },
      },
      {
        path: 'blog/:slug',
        name: 'blog-detail',
        component: () => import('../views/public/BlogDetailView.vue'),
        meta: {
          title: 'ব্লগ আর্টিকেল',
          type: 'article',
        },
      },
      {
        path: 'resources',
        name: 'resources',
        redirect: '/webinars',
      },
      {
        path: 'about',
        name: 'about',
        component: () => import('../views/public/AboutView.vue'),
        meta: {
          title: 'আমাদের সম্পর্কে (About Us)',
          description: 'ইমিশা একাডেমির লক্ষ্য, অত্যাধুনিক কম্পিউটার ল্যাব ও অভিজ্ঞ এভিয়েশন ট্রেইনারদের পরিচিতি।',
        },
      },
      {
        path: 'contact',
        name: 'contact',
        component: () => import('../views/public/ContactView.vue'),
        meta: {
          title: 'যোগাযোগ ও ক্যাম্পাস লোকেশন (Contact & Location)',
          description: 'মিরপুর-১০ ক্যাম্পাসে সরাসরি আসুন অথবা হটলাইনে যোগাযোগ করুন: ০১৮০৫৪৬৪২৯৩।',
        },
      },
      {
        path: ':pathMatch(.*)*',
        name: 'not-found',
        component: () => import('../views/public/NotFoundView.vue'),
        meta: {
          title: 'পেজটি পাওয়া যায়নি (404 - Not Found)',
        },
      },
    ],
  },
  // Auth Routes
  {
    path: '/',
    component: () => import('../layouts/AuthLayout.vue'),
    children: [
      {
        path: 'login',
        name: 'login',
        component: () => import('../views/auth/LoginView.vue'),
        meta: { title: 'লগইন (Login)', guestOnly: true },
      },
      {
        path: 'register',
        name: 'register',
        component: () => import('../views/auth/RegisterView.vue'),
        meta: { title: 'রেজিস্ট্রেশন (Register)', guestOnly: true },
      },
      {
        path: 'forgot-password',
        name: 'forgot-password',
        component: () => import('../views/auth/ForgotPasswordView.vue'),
        meta: { title: 'পাসওয়ার্ড রিসেট (Reset Password)', guestOnly: true },
      },
    ],
  },
  // Student Portal
  {
    path: '/student',
    component: () => import('../layouts/StudentLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: '/student/dashboard',
      },
      {
        path: 'dashboard',
        name: 'student-dashboard',
        component: () => import('../views/student/DashboardView.vue'),
        meta: { title: 'শিক্ষার্থী ড্যাশবোর্ড' },
      },
      {
        path: 'courses',
        name: 'student-courses',
        component: () => import('../views/student/MyCoursesView.vue'),
        meta: { title: 'আমার কোর্সসমূহ' },
      },
      {
        path: 'courses/:id/learn',
        name: 'student-classroom',
        component: () => import('../views/student/ClassroomView.vue'),
        meta: { title: 'ক্লাসরুম ও লেসন' },
      },
      {
        path: 'notes',
        name: 'student-notes',
        component: () => import('../views/student/StudentNotesView.vue'),
        meta: { title: 'আমার নোটস' },
      },
      {
        path: 'notifications',
        name: 'student-notifications',
        component: () => import('../views/student/StudentNotificationsView.vue'),
        meta: { title: 'নোটিফিকেশন' },
      },
      {
        path: 'orders',
        name: 'student-orders',
        component: () => import('../views/student/StudentOrdersView.vue'),
        meta: { title: 'অর্ডার ও ইনভয়েস' },
      },
      {
        path: 'settings',
        name: 'student-settings',
        component: () => import('../views/student/StudentSettingsView.vue'),
        meta: { title: 'প্রোফাইল সেটিংস' },
      },
    ],
  },
  // Admin & Staff Management Portal
  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, role: 'Admin' },
    children: [
      {
        path: '',
        redirect: '/admin/dashboard',
      },
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: () => import('../views/admin/DashboardView.vue'),
        meta: { title: 'অ্যাডমিন ড্যাশবোর্ড' },
      },
      {
        path: 'courses',
        name: 'admin-courses',
        component: () => import('../views/admin/AdminCoursesView.vue'),
        meta: { title: 'কোর্স ম্যানেজমেন্ট' },
      },
      {
        path: 'enrollments',
        name: 'admin-enrollments',
        component: () => import('../views/admin/AdminEnrollmentsView.vue'),
        meta: { title: 'শিক্ষার্থী এনরোলমেন্ট ও ব্যাচ রেজিস্টার' },
      },
      {
        path: 'categories',
        name: 'admin-categories',
        component: () => import('../views/admin/AdminCategoriesView.vue'),
        meta: { title: 'কোর্স ক্যাটাগরি ও ক্যারিয়ার ট্র্যাক' },
      },
      {
        path: 'mentors',
        name: 'admin-mentors',
        component: () => import('../views/admin/AdminMentorsView.vue'),
        meta: { title: 'মেন্টর ও ফ্যাকাল্টি প্যানেল' },
      },
      {
        path: 'instructors',
        redirect: '/admin/mentors',
      },
      {
        path: 'webinars',
        name: 'admin-webinars',
        component: () => import('../views/admin/AdminWebinarsView.vue'),
        meta: { title: 'ওয়েবিনার ও মাস্টারক্লাস ম্যানেজমেন্ট' },
      },
      {
        path: 'blogs',
        name: 'admin-blogs',
        component: () => import('../views/admin/AdminBlogsView.vue'),
        meta: { title: 'ব্লগ ও নলেজ হাব ম্যানেজমেন্ট' },
      },
      {
        path: 'ebooks',
        name: 'admin-ebooks',
        component: () => import('../views/admin/AdminEbooksView.vue'),
        meta: { title: 'ই-বুক ও রিসোর্স ম্যানেজমেন্ট' },
      },
      {
        path: 'orders',
        name: 'admin-orders',
        component: () => import('../views/admin/AdminOrdersView.vue'),
        meta: { title: 'অর্ডার ম্যানেজমেন্ট' },
      },
      {
        path: 'leads',
        name: 'admin-leads',
        component: () => import('../views/admin/AdminLeadsView.vue'),
        meta: { title: 'লিড সিআরএম' },
      },
      {
        path: 'employees',
        name: 'admin-employees',
        component: () => import('../views/admin/AdminEmployeesView.vue'),
        meta: { title: 'কর্মী তালিকা', adminOnly: true },
      },
      {
        path: 'notices',
        name: 'admin-notices',
        component: () => import('../views/admin/AdminNoticesView.vue'),
        meta: { title: 'নোটিশ বোর্ড ও অ্যানাউন্সমেন্ট' },
      },
      {
        path: 'audit-logs',
        name: 'admin-audit-logs',
        component: () => import('../views/admin/AdminAuditLogsView.vue'),
        meta: { title: 'অডিট লগ' },
      },
    ],
  },
  // Worker / Counselor Shortcut Route
  {
    path: '/worker',
    redirect: '/admin/leads',
  },
  {
    path: '/manager',
    redirect: '/admin/dashboard',
  },
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, _from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
        top: 80,
      };
    }
    return { top: 0, left: 0, behavior: 'smooth' };
  },
});

// Authentication Navigation Guard
router.beforeEach(async (to, _from, next) => {
  const authStore = useAuthStore();

  // Lazy-load user profile if token exists but user is not fetched yet
  if (authStore.token && !authStore.user && !authStore.initialized) {
    await authStore.fetchUser();
  }

  // 2. Protected routes requiring authentication
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!authStore.isAuthenticated) {
      return next({
        path: '/login',
        query: { redirect: to.fullPath },
      });
    }

    // Role checks: if route requires Admin role
    if (to.matched.some((record) => record.meta.role === 'Admin')) {
      const canAccessAdmin = authStore.isAdmin || authStore.isManager || authStore.isWorker;
      if (!canAccessAdmin) {
        return next('/student/dashboard');
      }
    }

    // Admin-only screens (e.g. employee directory) are hidden from Manager / Moderator accounts
    if (to.matched.some((record) => record.meta.adminOnly) && !authStore.isAdmin) {
      return next('/admin/dashboard');
    }
  }

  next();
});

// Automatic SEO Meta Tag Updater & Meta Pixel PageView Tracking Guard
const { setMeta } = useSeo();
router.afterEach((to) => {
  if (to.meta && to.meta.title) {
    setMeta({
      title: to.meta.title as string,
      description: to.meta.description as string | undefined,
      keywords: to.meta.keywords as string | undefined,
      type: (to.meta.type as any) || 'website',
    });
  }

  // Meta Pixel SPA Navigation PageView Tracking
  if (typeof window !== 'undefined' && (window as any).fbq) {
    (window as any).fbq('track', 'PageView');
  }
});

export default router;
