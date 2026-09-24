import { defineStore } from 'pinia';
import apiClient from '../api/client';
import { getAvatarUriById } from '../utils/avatars';

export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  avatar?: string;
  roles: string[];
  permissions: string[];
  profile?: {
    headline?: string;
    bio?: string;
    city?: string;
    country?: string;
    education?: string;
    occupation?: string;
  };
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    token: (typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null) as string | null,
    isAuthenticated: Boolean(typeof window !== 'undefined' && localStorage.getItem('auth_token')),
    isLoading: false,
    initialized: false,
  }),
  getters: {
    roles: (state) => state.user?.roles || [],
    isAdmin: (state) => Boolean(state.user?.roles?.some((r) => ['SuperAdmin', 'Admin'].includes(r))),
    isManager: (state) => Boolean(state.user?.roles?.includes('Manager')),
    isWorker: (state) => Boolean(state.user?.roles?.some((r) => ['Worker', 'Counselor', 'Moderator'].includes(r))),
    isInstructor: (state) => Boolean(state.user?.roles?.includes('Instructor')),
    isStudent: (state) => Boolean(state.user?.roles?.includes('Student')),
    userName: (state) => state.user?.name || '',
    userAvatar: (state) => getAvatarUriById(state.user?.avatar, state.user?.name),
    primaryRole: (state): string => {
      if (state.user?.roles?.includes('SuperAdmin')) return 'SuperAdmin';
      if (state.user?.roles?.includes('Admin')) return 'Admin';
      if (state.user?.roles?.includes('Manager')) return 'Manager';
      if (state.user?.roles?.includes('Moderator')) return 'Moderator';
      if (state.user?.roles?.includes('Worker') || state.user?.roles?.includes('Counselor')) return 'Worker';
      if (state.user?.roles?.includes('Instructor')) return 'Instructor';
      return state.user?.roles?.[0] || 'Student';
    },
  },
  actions: {
    async fetchUser() {
      const storedToken = localStorage.getItem('auth_token');
      if (!storedToken) {
        this.user = null;
        this.token = null;
        this.isAuthenticated = false;
        this.initialized = true;
        return;
      }

      this.isLoading = true;
      try {
        const response = await apiClient.get('/auth/me');
        this.user = response.data.data;
        this.token = storedToken;
        this.isAuthenticated = true;
      } catch (error: any) {
        if (error.response?.status === 401) {
          localStorage.removeItem('auth_token');
          this.user = null;
          this.token = null;
          this.isAuthenticated = false;
        }
      } finally {
        this.isLoading = false;
        this.initialized = true;
      }
    },

    async login(email: string, password: string) {
      this.isLoading = true;
      try {
        const response = await apiClient.post('/auth/login', { email, password });
        const { user, token } = response.data.data;
        
        localStorage.setItem('auth_token', token);
        this.token = token;
        this.user = user;
        this.isAuthenticated = true;
        this.initialized = true;
        return response.data;
      } finally {
        this.isLoading = false;
      }
    },

    async register(payload: { name: string; email: string; phone?: string; password: string; password_confirmation?: string }) {
      this.isLoading = true;
      try {
        const response = await apiClient.post('/auth/register', payload);
        const { user, token } = response.data.data;
        
        localStorage.setItem('auth_token', token);
        this.token = token;
        this.user = user;
        this.isAuthenticated = true;
        this.initialized = true;
        return response.data;
      } finally {
        this.isLoading = false;
      }
    },

    async logout() {
      try {
        await apiClient.post('/auth/logout');
      } catch (e) {
        // Continue cleanup even if server request fails
      } finally {
        localStorage.removeItem('auth_token');
        this.user = null;
        this.token = null;
        this.isAuthenticated = false;
      }
    },
  },
});

export default useAuthStore;
