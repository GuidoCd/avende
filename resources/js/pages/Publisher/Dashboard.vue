<template>
  <div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('Publisher Dashboard') }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Overview of your properties and audience reach.') }}</p>
        </div>
        <Link href="/publisher/properties/create" class="bg-[#008f39] hover:bg-emerald-700 text-white px-4 py-2 rounded-xl shadow-sm font-medium transition-colors text-sm flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          {{ __('New Property') }}
        </Link>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      
      <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-5">
         <div class="flex items-center justify-between mb-4">
           <div class="bg-indigo-50 dark:bg-indigo-900/30 p-2 rounded-lg">
             <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
           </div>
           <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Total Properties') }}</span>
         </div>
         <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_properties }}</p>
      </div>

      <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-5">
         <div class="flex items-center justify-between mb-4">
           <div class="bg-emerald-50 dark:bg-emerald-900/30 p-2 rounded-lg">
             <svg class="w-6 h-6 text-[#008f39] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
           </div>
           <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Active') }}</span>
         </div>
         <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.active_properties }}</p>
      </div>

      <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-5">
         <div class="flex items-center justify-between mb-4">
           <div class="bg-sky-50 dark:bg-sky-900/30 p-2 rounded-lg">
             <svg class="w-6 h-6 text-sky-500 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
           </div>
           <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Profile Views') }}</span>
         </div>
         <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_visits.toLocaleString() }}</p>
         <p class="text-sm text-emerald-500 mt-1 flex items-center gap-1 font-medium"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> +12.5% {{ __('this week') }}</p>
      </div>

      <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-5">
         <div class="flex items-center justify-between mb-4">
           <div class="bg-rose-50 dark:bg-rose-900/30 p-2 rounded-lg">
             <svg class="w-6 h-6 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
           </div>
           <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Total Leads') }}</span>
         </div>
         <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_leads }}</p>
      </div>

    </div>

    <!-- Charts Placeholder -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700">
        <h3 class="text-base font-bold">{{ __('Visits Overview') }} (Últimos 7 días)</h3>
      </div>
      <div class="p-6 h-72 flex items-end gap-2 relative">
         <!-- Simple HTML CSS Bar Chart Demo (Replace with real ChartJS later) -->
         <div class="absolute inset-x-6 top-6 bottom-8 border-b border-l border-gray-100 dark:border-zinc-700">
            <!-- Y-axis lines -->
            <div class="absolute w-full border-t border-gray-100 dark:border-zinc-700/50 bottom-[25%] left-0"></div>
            <div class="absolute w-full border-t border-gray-100 dark:border-zinc-700/50 bottom-[50%] left-0"></div>
            <div class="absolute w-full border-t border-gray-100 dark:border-zinc-700/50 bottom-[75%] left-0"></div>
            <div class="absolute w-full border-t border-gray-100 dark:border-zinc-700/50 top-0 left-0"></div>
         </div>
         
         <div v-for="(val, index) in visitsChart.data" :key="index" class="flex-1 flex flex-col items-center justify-end h-full z-10 gap-2 group cursor-crosshair">
            <!-- Tooltip placeholder -->
            <div class="opacity-0 group-hover:opacity-100 bg-gray-900 text-white text-xs py-1 px-2 rounded absolute -top-4 transition-opacity whitespace-nowrap z-20 pointer-events-none">
              {{ val }} {{ __('Visits') }}
            </div>
            <div 
              class="w-full max-w-[40px] bg-sky-400 hover:bg-sky-500 dark:bg-sky-500 dark:hover:bg-sky-400 rounded-t-md transition-all duration-500"
              :style="`height: ${(val / Math.max(...visitsChart.data)) * 100}%`">
            </div>
            <span class="text-xs text-gray-500 font-medium">{{ visitsChart.labels[index] }}</span>
         </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

declare const __: any;


defineOptions({ layout: ProfileLayout });
defineProps({
  stats: {
    type: Object,
    required: true
  },
  visitsChart: {
    type: Object,
    required: true
  }
});
</script>
