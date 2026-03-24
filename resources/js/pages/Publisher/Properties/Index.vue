<template>
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

       <!-- Populated List Grid -->
       <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">
           <div v-for="property in properties.data" :key="property.id" class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden flex flex-col group hover:border-[#008f39] dark:hover:border-[#008f39] transition-colors">
               <!-- Card Image -->
               <div class="relative h-48 bg-gray-100 dark:bg-zinc-700 w-full overflow-hidden">
                   <img v-if="property.thumbnail" :src="property.thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                   <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                   </div>
                   <!-- Status Badge -->
                   <div class="absolute top-3 right-3">
                       <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold shadow-sm backdrop-blur-md" 
                             :class="property.is_active ? 'bg-emerald-500/90 text-white' : 'bg-gray-800/80 text-gray-100'">
                          {{ property.is_active ? __('Active') : __('Inactive') }}
                       </span>
                   </div>
               </div>

               <!-- Card Content -->
               <div class="p-5 flex-1 flex flex-col">
                   <div class="flex justify-between items-start gap-4 mb-2">
                       <h4 class="font-bold text-lg text-gray-900 dark:text-white line-clamp-1" :title="property.title">{{ property.title || __('Untitled Property') }}</h4>
                       <span class="font-bold text-[#008f39] whitespace-nowrap">{{ property.currency }} {{ (property.price / 100).toLocaleString() }}</span>
                   </div>
                   
                   <p class="text-sm text-gray-500 dark:text-zinc-400 line-clamp-1 mb-6 flex items-center gap-1.5">
                       <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                       {{ property.address || __('Pending address') }}
                   </p>

                   <div class="mt-auto pt-4 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between">
                       <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                           {{ property.views_count || 0 }} {{ __('Visits') }}
                       </div>
                       
                       <div class="flex items-center gap-4">
                           <label v-if="property.publishing_status !== 'draft'" class="relative inline-flex items-center cursor-pointer" :title="__('Toggle Visibility')">
                               <input type="checkbox" :checked="property.is_active" @change="toggleActive(property)" class="sr-only peer">
                               <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-600 peer-checked:after:translate-x-[16px] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-500 peer-checked:bg-[#008f39]"></div>
                           </label>
                           <span v-else class="text-xs text-amber-600 dark:text-amber-400 font-bold tracking-wide">{{ __('DRAFT') }}</span>

                          <Link :href="`/publisher/properties/${property.uuid}/build`" class="bg-gray-100 hover:bg-gray-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-gray-900 dark:text-white px-3 py-1.5 rounded-lg font-medium text-xs transition-colors">{{ __('Edit') }}</Link>
                       </div>
                   </div>
               </div>
           </div>
       </div>
     </div>
   </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

defineOptions({ layout: ProfileLayout });

defineProps({
  properties: {
    type: Object as () => { data: Record<string, any>[] },
    required: false,
    default: () => ({ data: [] })
  }
});

declare const __: any;

const toggleActive = (property: Record<string, any>) => {
    if (property.publishing_status === 'draft') return;
    
    router.patch(`/publisher/properties/${property.uuid}/toggle`, {
        is_active: !property.is_active
    }, { preserveScroll: true });
};
</script>
