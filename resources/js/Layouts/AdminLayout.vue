<template>
  <div class="min-h-screen bg-gray-50 dark:bg-zinc-900 text-gray-900 dark:text-gray-100 flex font-sans">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex-col hidden md:flex shrink-0 shadow-xl z-20">
      <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950">
        <span class="font-bold text-lg text-emerald-400 tracking-wide">{{ __('Admin Portal') }}</span>
      </div>
      <nav class="flex-1 py-6 px-4 space-y-2">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-2 block">{{ __('Menu') }}</span>
        
        <Link href="/admin-portal/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm border border-transparent" :class="$page.url === '/admin-portal/dashboard' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white'">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
          {{ __('Dashboard') }}
        </Link>
        <Link href="/admin-portal/users" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm border border-transparent" :class="$page.url.startsWith('/admin-portal/users') ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white'">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          {{ __('Users') }}
        </Link>
        <Link href="/admin-portal/properties" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm border border-transparent" :class="$page.url.startsWith('/admin-portal/properties') ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white'">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          {{ __('Properties') }}
        </Link>
      </nav>
      <div class="p-4 border-t border-slate-800 bg-slate-950">
        <Link href="/logout" method="post" as="button" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm w-full text-slate-400 hover:bg-red-500/10 hover:text-red-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
          {{ __('Log Out') }}
        </Link>
        <Link href="/" class="flex items-center gap-3 px-4 py-3 mt-2 rounded-xl transition-colors font-medium text-sm w-full text-slate-400 hover:bg-slate-800 hover:text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          {{ __('Back to Main Site') }}
        </Link>
      </div>
    </aside>

    <!-- Mobile Menu / Overlay would go here -->

    <!-- Content -->
    <div class="flex-1 flex flex-col min-w-0">
      <header class="h-16 bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700 flex items-center justify-between px-6 shrink-0 z-10 sticky top-0 shadow-sm transition-colors">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">{{ title || __('System Administration') }}</h2>
        <div class="flex items-center gap-4">
            
            <button @click="toggleDarkMode" class="p-2 ml-1 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors text-gray-500 dark:text-gray-400 border border-transparent dark:border-zinc-700">
               <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
               <svg v-else class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>

            <span class="text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400 px-3 py-1.5 rounded-full border border-emerald-200 dark:border-emerald-800/60 uppercase tracking-widest">{{ __('Admin') }}</span>
        </div>
      </header>
      <main class="flex-1 overflow-y-auto p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps<{
    title?: string;
}>();

declare const __: any;
const page = usePage();
const isDark = ref(false);

onMounted(() => {
  isDark.value = document.documentElement.classList.contains('dark');
});

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};
</script>
