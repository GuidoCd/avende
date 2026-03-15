<template>
  <ProfileLayout>
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('My Properties') }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your listings and track their status.') }}</p>
        </div>
        <Link href="/publisher/properties/create" class="bg-[#008f39] hover:bg-emerald-700 text-white px-4 py-2 rounded-xl shadow-sm font-medium transition-colors text-sm flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          {{ __('New Property') }}
        </Link>
      </div>

      <div class="p-0">
        <!-- Empty State (Placeholder logic since we have fake stats but no seeded properties likely) -->
        <div v-if="!properties || !properties.data || properties.data.length === 0" class="flex flex-col items-center justify-center text-center py-16 px-6">
          <div class="bg-gray-50 dark:bg-zinc-900/50 p-6 rounded-full mb-4">
             <svg class="w-16 h-16 text-gray-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          </div>
          <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('You haven\'t posted any properties yet') }}</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-sm">{{ __('Start reaching thousands of potential buyers or renters today by publishing your first property.') }}</p>
          <Link href="/publisher/properties/create" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 font-semibold py-3 px-6 rounded-xl shadow-sm transition-colors text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('Publish First Property') }}
          </Link>
        </div>

        <!-- Populated List Table/Grid Placeholder -->
        <div v-else class="overflow-x-auto">
           <table class="w-full text-left border-collapse">
              <thead>
                 <tr class="bg-gray-50/50 dark:bg-zinc-800/50 border-b border-gray-100 dark:border-zinc-700 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="p-4 font-bold">{{ __('Property') }}</th>
                    <th class="p-4 font-bold">{{ __('Status') }}</th>
                    <th class="p-4 font-bold">{{ __('Price') }}</th>
                    <th class="p-4 font-bold">{{ __('Visits') }}</th>
                    <th class="p-4 font-bold text-right">{{ __('Actions') }}</th>
                 </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 dark:divide-zinc-700 text-sm">
                 <tr v-for="property in properties.data" :key="property.id" class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                     <td class="p-4">
                        <div class="flex items-center gap-3">
                           <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-zinc-700 overflow-hidden flex-shrink-0 border border-gray-100 dark:border-zinc-700">
                              <img v-if="property.thumbnail" :src="property.thumbnail" class="w-full h-full object-cover">
                              <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                              </div>
                           </div>
                           <div class="flex flex-col min-w-0">
                              <span class="font-bold text-gray-900 dark:text-white truncate">{{ property.title }}</span>
                              <span class="text-xs text-gray-500 truncate">{{ property.address }}</span>
                           </div>
                        </div>
                     </td>
                    <td class="p-4">
                       <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" 
                             :class="property.is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-gray-300'">
                          {{ property.is_active ? __('Active') : __('Inactive') }}
                       </span>
                    </td>
                     <td class="p-4 font-medium">{{ property.currency }} {{ (property.price / 100).toLocaleString() }}</td>
                    <td class="p-4 text-gray-500">{{ property.views_count || 0 }}</td>
                    <td class="p-4 text-right">
                       <button class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 font-medium text-xs">{{ __('Edit') }}</button>
                    </td>
                 </tr>
              </tbody>
           </table>
        </div>
      </div>
    </div>
  </ProfileLayout>
</template>

<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  properties: {
    type: Object,
    required: false,
    default: () => ({ data: [] })
  }
});
</script>
