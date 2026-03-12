<template>
  <div 
    class="fixed right-0 top-0 h-full w-full md:w-[400px] lg:w-[440px] bg-white dark:bg-zinc-900 shadow-[-10px_0_30px_rgb(0,0,0,0.05)] transition-transform duration-500 flex flex-col z-20 border-l border-gray-100 dark:border-zinc-800"
    :class="{ 'translate-x-full': !isOpen }"
  >
    <!-- Toggle Button for Desktop -->
    <button 
      class="hidden md:flex absolute -left-12 top-24 bg-white dark:bg-zinc-800 w-12 h-12 rounded-l-xl shadow-[0_4px_20px_rgb(0,0,0,0.08)] border border-r-0 border-gray-100 dark:border-zinc-700 items-center justify-center hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors"
      @click="isOpen = !isOpen"
    >
      <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isOpen ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7'"></path>
      </svg>
    </button>

    <!-- Header -->
    <div class="p-5 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md sticky top-0 z-30">
      <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
          {{ __('Properties') }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ properties.length }} {{ __('results found') }}</p>
      </div>
      <!-- Sort/Filter minimal toggle -->
      <button class="text-sky-500 hover:text-sky-600 dark:text-sky-400 dark:hover:text-sky-300 font-medium text-sm flex items-center gap-1 transition-colors bg-sky-50 dark:bg-sky-500/10 px-3 py-1.5 rounded-full">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        {{ __('Nearest') }}
      </button>
    </div>

    <!-- Scrollable list -->
    <div class="flex-1 overflow-y-auto p-5 pb-24 space-y-4 custom-scrollbar bg-gray-50/50 dark:bg-zinc-900/50">
      <PropertyCard 
        v-for="prop in properties" 
        :key="prop.id" 
        :property="prop" 
        @click="$emit('select-property', prop)"
        class="transform transition-transform duration-300"
      />
    </div>

    <!-- Mobile drag handle (visual only for now) -->
    <div class="md:hidden absolute top-0 left-0 w-full h-6 flex justify-center items-center">
      <div class="w-12 h-1.5 bg-gray-300 dark:bg-zinc-600 rounded-full"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import PropertyCard from './PropertyCard.vue';

defineProps({
  properties: {
    type: Array,
    required: true
  }
});

defineEmits(['select-property']);

const isOpen = ref(true); // Default open on desktop
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
