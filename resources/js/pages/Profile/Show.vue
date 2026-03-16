<template>
  <div class="space-y-6">
    
    <!-- Personal Info Block -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50">
        <h3 class="text-lg font-bold">{{ __('Personal Information') }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Update your information and email address.') }}</p>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Name') }}</label>
            <input type="text" :value="$page.props.auth.user.name" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" disabled>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Email') }}</label>
            <input type="email" :value="$page.props.auth.user.email" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" disabled>
          </div>
          <div class="space-y-1 sm:col-span-2">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Phone') }}</label>
            <input type="text" placeholder="+591 60000000" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
          </div>
        </div>
        <div class="mt-6 flex justify-end">
          <button class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 font-semibold py-2 px-6 rounded-xl shadow-sm transition-colors text-sm">
            {{ __('Save Changes') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Preferences / Alerts Placeholder -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('My Zone Alerts') }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('We will notify you when there are new properties here.') }}</p>
        </div>
        <div class="bg-emerald-100 text-[#008f39] dark:bg-emerald-900/30 dark:text-emerald-400 p-2 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </div>
      </div>
      <div class="p-6 flex flex-col items-center justify-center text-center py-12">
        <svg class="w-16 h-16 text-gray-300 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-1">{{ __('You have no alerts yet') }}</h4>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Search on the map and create your first geolocated alert.') }}</p>
        <Link href="/" class="text-[#008f39] font-medium hover:underline text-sm flex items-center gap-1">
          {{ __('Explore the Map') }}
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </Link>
      </div>
    </div>

  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

defineOptions({ layout: ProfileLayout })
</script>
