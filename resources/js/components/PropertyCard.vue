<template>
  <div class="bg-white dark:bg-zinc-800 p-3 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 hover:shadow-md transition-shadow cursor-pointer group">
    <div class="relative rounded-xl overflow-hidden aspect-4/3 mb-3 bg-gray-100 dark:bg-zinc-900">
      <img :src="property.image || 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'" alt="Property thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
      <div class="absolute top-2 left-2 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-sm px-2 py-1 rounded-md text-xs font-bold text-gray-900 dark:text-white shadow-sm">
        {{ property.isForRent ? __('For Rent') : __('For Sale') }}
      </div>
      <!-- 360 View Button Placeholder -->
      <button v-if="property.has360" class="absolute bottom-2 right-2 bg-black/60 hover:bg-black/80 backdrop-blur-sm text-white p-2 rounded-full transition-colors flex items-center justify-center">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
      </button>
    </div>
    
    <div>
      <div class="font-bold text-lg text-emerald-600 dark:text-emerald-400 mb-1">
        ${{ property.price ? (property.price).toLocaleString() : '0' }}<span v-if="property.isForRent" class="text-sm font-normal text-gray-500">/{{ __('mes') }}</span>
      </div>
      <h3 class="font-semibold text-gray-900 dark:text-white truncate mb-1" :title="property.title">{{ property.title }}</h3>
      <p class="text-xs text-gray-500 dark:text-gray-400 truncate flex items-center gap-1 mb-3">
        <svg class="w-3.5 h-3.5 shrink-0 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        {{ property.address }}
      </p>

      <div class="flex items-center gap-4 text-xs text-gray-600 dark:text-gray-300 font-medium">
        <div class="flex items-center gap-1">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          {{ property.beds }}
        </div>
        <div class="flex items-center gap-1">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
          {{ property.baths }}
        </div>
        <div class="flex items-center gap-1">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
          {{ property.sqm }} m²
        </div>
      </div>
      
      <!-- Action buttons -->
      <div class="mt-3 flex gap-2">
        <Link :href="property.slug ? `/properties/${property.slug}` : '#'" @click.stop class="flex-1 py-1.5 px-2 bg-[#008f39] text-white rounded-full text-xs font-bold hover:bg-emerald-700 transition shadow-sm flex items-center justify-center gap-1">
          <span>Ver Propiedad</span>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

declare const __: any;

defineProps({
  property: {
    type: Object,
    required: true
  }
});
</script>
