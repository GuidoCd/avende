<template>
  <div class="space-y-6">
    
    <!-- List Header Block -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('My Favorite Properties') }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('List of properties you have saved to view later.') }}</p>
        </div>
        <div class="bg-red-50 text-red-500 dark:bg-red-900/30 dark:text-red-400 p-2 rounded-xl">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </div>
      </div>

      <div class="p-6">
        <template v-if="favorites.length > 0">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <PropertyCard 
              v-for="property in favorites" 
              :key="property.id" 
              :property="property" 
            />
          </div>
        </template>
        
        <template v-else>
          <!-- Empty State -->
          <div class="flex flex-col items-center justify-center text-center py-12">
            <svg class="w-16 h-16 text-gray-300 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            <h4 class="text-base font-medium text-gray-900 dark:text-white mb-1">{{ __('You have no favorites yet') }}</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Explore the map and click the heart to save properties and track them.') }}</p>
            <Link href="/" class="text-[#008f39] font-medium hover:underline text-sm flex items-center gap-1">
              {{ __('Explore the Map') }}
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </Link>
          </div>
        </template>
      </div>
    </div>

  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import PropertyCard from '@/components/PropertyCard.vue';

defineOptions({ layout: ProfileLayout });

defineProps({
  favorites: {
    type: Array,
    default: () => []
  }
});
</script>
