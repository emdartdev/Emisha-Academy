<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h1 class="text-lg sm:text-xl font-black text-[var(--text-primary)]">
          {{ t('Employee Directory', 'কর্মী তালিকা (Employee Names)') }}
        </h1>
        <p class="text-xs text-[var(--text-secondary)] mt-1 max-w-2xl">
          {{ t(
            'Only Admins can add, edit or remove names here. Moderators pick their name from this list when they accept a lead, so every lead is tracked to a real person.',
            'শুধুমাত্র অ্যাডমিন এখানে নাম যুক্ত, এডিট বা মুছে ফেলতে পারবেন। মডারেটররা লিড গ্রহণ করার সময় এই তালিকা থেকে নিজের নাম নির্বাচন করবেন — ফলে প্রতিটি লিড কে হ্যান্ডেল করছে তা ট্র্যাক থাকবে।'
          ) }}
        </p>
      </div>
      <button
        type="button"
        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#D4AF37] to-[#F7E7A9] text-slate-950 font-bold text-xs hover:shadow-lg transition-all cursor-pointer shrink-0"
        @click="openCreate"
      >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span>{{ t('Add employee', 'নতুন কর্মী যুক্ত করুন') }}</span>
      </button>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
        <p class="text-[10px] font-bold uppercase text-[var(--text-muted)]">{{ t('Employees', 'মোট কর্মী') }}</p>
        <p class="text-xl font-black text-[var(--text-primary)] mt-1">{{ formatNumber(employees.length, themeStore.locale) }}</p>
      </div>
      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
        <p class="text-[10px] font-bold uppercase text-[var(--text-muted)]">{{ t('Active', 'সক্রিয়') }}</p>
        <p class="text-xl font-black text-emerald-500 mt-1">{{ formatNumber(activeCount, themeStore.locale) }}</p>
      </div>
      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
        <p class="text-[10px] font-bold uppercase text-[var(--text-muted)]">{{ t('Open leads', 'চলমান লিড') }}</p>
        <p class="text-xl font-black text-[#D4AF37] mt-1">{{ formatNumber(totals.open, themeStore.locale) }}</p>
      </div>
      <div class="p-4 rounded-2xl bg-[var(--bg-surface)] border border-[var(--border-subtle)]">
        <p class="text-[10px] font-bold uppercase text-[var(--text-muted)]">{{ t('Converted', 'ভর্তি সম্পন্ন') }}</p>
        <p class="text-xl font-black text-sky-500 mt-1">{{ formatNumber(totals.converted, themeStore.locale) }}</p>
      </div>
    </div>

    <!-- Search -->
    <input
      v-model="search"
      type="search"
      :placeholder="t('Search by name, phone or designation…', 'নাম, ফোন বা পদবি দিয়ে খুঁজুন…')"
      class="w-full sm:max-w-sm px-3.5 py-2.5 rounded-xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"
    />

    <!-- Table -->
    <div v-if="loading" class="space-y-2">
      <div v-for="i in 4" :key="i" class="h-16 rounded-2xl bg-[var(--bg-surface)] animate-pulse"></div>
    </div>

    <div v-else-if="filtered.length === 0" class="p-12 rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-center text-xs text-[var(--text-muted)]">
      {{ t('No employees yet. Add the names of the people who handle leads.', 'এখনও কোনো কর্মী যুক্ত করা হয়নি। যারা লিড হ্যান্ডেল করবেন তাদের নাম যুক্ত করুন।') }}
    </div>

    <div v-else class="rounded-3xl bg-[var(--bg-surface)] border border-[var(--border-subtle)] overflow-x-auto">
      <table class="w-full text-left text-xs min-w-[720px]">
        <thead class="bg-[var(--bg-elevated)] border-b border-[var(--border-subtle)] text-[var(--text-secondary)] uppercase tracking-wider font-extrabold text-[10px]">
          <tr>
            <th class="py-3.5 px-5">{{ t('Name', 'নাম') }}</th>
            <th class="py-3.5 px-5">{{ t('Contact', 'যোগাযোগ') }}</th>
            <th class="py-3.5 px-5">{{ t('Leads (open / total)', 'লিড (চলমান / মোট)') }}</th>
            <th class="py-3.5 px-5">{{ t('Converted', 'ভর্তি') }}</th>
            <th class="py-3.5 px-5">{{ t('Status', 'স্ট্যাটাস') }}</th>
            <th class="py-3.5 px-5 text-right">{{ t('Actions', 'অ্যাকশন') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[var(--border-subtle)]">
          <tr v-for="emp in filtered" :key="emp.id" class="hover:bg-[var(--bg-elevated)]/50">
            <td class="py-3.5 px-5">
              <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-full bg-[#D4AF37]/15 text-[#D4AF37] flex items-center justify-center font-black">{{ emp.name.charAt(0) }}</span>
                <div>
                  <p class="font-bold text-[var(--text-primary)]">{{ emp.name }}</p>
                  <p v-if="emp.designation" class="text-[10px] text-[var(--text-muted)]">{{ emp.designation }}</p>
                </div>
              </div>
            </td>
            <td class="py-3.5 px-5 text-[var(--text-secondary)]">
              <p v-if="emp.phone">{{ emp.phone }}</p>
              <p v-if="emp.email" class="text-[10px]">{{ emp.email }}</p>
              <span v-if="!emp.phone && !emp.email" class="text-[var(--text-muted)]">—</span>
            </td>
            <td class="py-3.5 px-5">
              <router-link :to="{ path: '/admin/leads', query: { employee_id: emp.id } }" class="font-bold text-[var(--text-primary)] hover:text-[#D4AF37]">
                {{ formatNumber(emp.open_leads || 0, themeStore.locale) }} / {{ formatNumber(emp.total_leads || 0, themeStore.locale) }}
              </router-link>
            </td>
            <td class="py-3.5 px-5 font-bold text-emerald-500">{{ formatNumber(emp.converted_leads || 0, themeStore.locale) }}</td>
            <td class="py-3.5 px-5">
              <button
                type="button"
                class="px-2.5 py-1 rounded-lg text-[10px] font-bold border cursor-pointer"
                :class="emp.is_active ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/30' : 'bg-[var(--bg-elevated)] text-[var(--text-muted)] border-[var(--border-subtle)]'"
                :title="t('Inactive names are hidden from the accept dropdown', 'নিষ্ক্রিয় নাম লিড গ্রহণের তালিকায় দেখাবে না')"
                @click="toggleActive(emp)"
              >{{ emp.is_active ? t('Active', 'সক্রিয়') : t('Inactive', 'নিষ্ক্রিয়') }}</button>
            </td>
            <td class="py-3.5 px-5 text-right whitespace-nowrap">
              <button type="button" class="px-2.5 py-1.5 rounded-lg bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-[11px] font-bold text-[var(--text-primary)] hover:border-[#D4AF37] cursor-pointer mr-1.5" @click="openEdit(emp)">
                {{ t('Edit', 'এডিট') }}
              </button>
              <button type="button" class="px-2.5 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-[11px] font-bold text-rose-500 cursor-pointer" @click="remove(emp)">
                {{ t('Remove', 'মুছুন') }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create / Edit modal -->
    <AppModal v-model="showModal" :title="editingId ? t('Edit employee', 'কর্মীর তথ্য এডিট') : t('Add employee', 'নতুন কর্মী যুক্ত করুন')" size="md">
      <form id="employee-form" class="space-y-3" @submit.prevent="submit">
        <div>
          <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">{{ t('Full name', 'পূর্ণ নাম') }} *</label>
          <input v-model="form.name" required maxlength="255" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">{{ t('Designation', 'পদবি') }}</label>
          <input v-model="form.designation" type="text" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" :placeholder="t('e.g. Admission Counselor', 'যেমন: অ্যাডমিশন কাউন্সেলর')" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">{{ t('Phone', 'ফোন') }}</label>
            <input v-model="form.phone" type="tel" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">{{ t('Email', 'ইমেইল') }}</label>
            <input v-model="form.email" type="email" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]" />
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">{{ t('Internal note', 'অভ্যন্তরীণ নোট') }}</label>
          <textarea v-model="form.notes" rows="2" class="w-full px-3.5 py-2 rounded-xl bg-[var(--bg-elevated)] border border-[var(--border-subtle)] text-xs text-[var(--text-primary)] focus:outline-none focus:border-[#D4AF37]"></textarea>
        </div>
        <label class="flex items-center gap-2 text-xs text-[var(--text-primary)] cursor-pointer">
          <input v-model="form.is_active" type="checkbox" class="w-4 h-4 accent-[#D4AF37]" />
          {{ t('Active (shown to moderators when accepting leads)', 'সক্রিয় (লিড গ্রহণের সময় মডারেটররা নামটি দেখতে পাবেন)') }}
        </label>
      </form>
      <template #footer>
        <button type="button" class="px-4 py-2 rounded-xl text-xs text-[var(--text-secondary)] cursor-pointer" @click="showModal = false">{{ t('Cancel', 'বাতিল') }}</button>
        <button type="submit" form="employee-form" :disabled="saving" class="px-5 py-2 rounded-xl bg-[#D4AF37] text-slate-950 font-bold text-xs cursor-pointer disabled:opacity-50">
          {{ saving ? t('Saving…', 'সংরক্ষণ হচ্ছে…') : t('Save', 'সংরক্ষণ করুন') }}
        </button>
      </template>
    </AppModal>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue';
import apiClient from '../../api/client';
import { useThemeStore } from '../../stores/theme';
import { useToastStore } from '../../stores/toast';
import { formatNumber } from '../../utils/locale';
import AppModal from '../../components/ui/AppModal.vue';

const themeStore = useThemeStore();
const toast = useToastStore();
const t = (en: string, bn: string) => (themeStore.locale === 'bn' ? bn : en);

const employees = ref<any[]>([]);
const loading = ref(true);
const search = ref('');
const showModal = ref(false);
const saving = ref(false);
const editingId = ref<number | null>(null);
const form = reactive({
  name: '',
  designation: '',
  phone: '',
  email: '',
  notes: '',
  is_active: true,
});

const filtered = computed(() => {
  const q = search.value.trim().toLowerCase();
  if (!q) return employees.value;
  return employees.value.filter((e) =>
    [e.name, e.phone, e.designation, e.email].some((v) => (v || '').toLowerCase().includes(q))
  );
});
const activeCount = computed(() => employees.value.filter((e) => e.is_active).length);
const totals = computed(() => ({
  open: employees.value.reduce((s, e) => s + (e.open_leads || 0), 0),
  converted: employees.value.reduce((s, e) => s + (e.converted_leads || 0), 0),
}));

async function fetchEmployees() {
  loading.value = true;
  try {
    const res = await apiClient.get('/admin/employees');
    employees.value = res.data.data || [];
  } catch (err: any) {
    toast.error(err.response?.data?.message || t('Could not load employees.', 'কর্মী তালিকা লোড করা যায়নি।'));
  } finally {
    loading.value = false;
  }
}

function openCreate() {
  editingId.value = null;
  Object.assign(form, { name: '', designation: '', phone: '', email: '', notes: '', is_active: true });
  showModal.value = true;
}

function openEdit(emp: any) {
  editingId.value = emp.id;
  Object.assign(form, {
    name: emp.name,
    designation: emp.designation || '',
    phone: emp.phone || '',
    email: emp.email || '',
    notes: emp.notes || '',
    is_active: !!emp.is_active,
  });
  showModal.value = true;
}

async function submit() {
  saving.value = true;
  const payload = {
    ...form,
    designation: form.designation || null,
    phone: form.phone || null,
    email: form.email || null,
    notes: form.notes || null,
  };
  try {
    if (editingId.value) {
      await apiClient.put(`/admin/employees/${editingId.value}`, payload);
    } else {
      await apiClient.post('/admin/employees', payload);
    }
    toast.success(t('Saved.', 'সংরক্ষিত হয়েছে।'));
    showModal.value = false;
    fetchEmployees();
  } catch (err: any) {
    const errors = err.response?.data?.errors;
    const first = errors ? (Object.values(errors)[0] as string[])?.[0] : null;
    toast.error(first || err.response?.data?.message || t('Save failed.', 'সংরক্ষণ ব্যর্থ হয়েছে।'));
  } finally {
    saving.value = false;
  }
}

async function toggleActive(emp: any) {
  try {
    await apiClient.put(`/admin/employees/${emp.id}`, { name: emp.name, is_active: !emp.is_active });
    emp.is_active = !emp.is_active;
  } catch (err: any) {
    toast.error(err.response?.data?.message || t('Update failed.', 'আপডেট ব্যর্থ হয়েছে।'));
  }
}

async function remove(emp: any) {
  const msg = t(
    `Remove "${emp.name}"? Their ${emp.total_leads || 0} lead(s) will go back to the unassigned pool.`,
    `"${emp.name}" নামটি মুছে ফেলবেন? তার ${emp.total_leads || 0}টি লিড আবার অনির্ধারিত তালিকায় ফিরে যাবে।`
  );
  if (!window.confirm(msg)) return;
  try {
    await apiClient.delete(`/admin/employees/${emp.id}`);
    employees.value = employees.value.filter((e) => e.id !== emp.id);
    toast.success(t('Employee removed.', 'কর্মীর নাম মুছে ফেলা হয়েছে।'));
  } catch (err: any) {
    toast.error(err.response?.data?.message || t('Remove failed.', 'মুছে ফেলা যায়নি।'));
  }
}

onMounted(fetchEmployees);
</script>
