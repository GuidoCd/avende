<script setup lang="ts">
import { ref, computed } from 'vue';
import { X, ChevronLeft, ChevronRight, Grid2x2, Camera, View, MapPin } from 'lucide-vue-next';

const props = defineProps<{
  images: string[];
  title: string;
}>();

const MEDIA_TABS = [
  { id: "photos", label: "Fotos", icon: Camera },
  { id: "360", label: "Vista 360°", icon: View },
  { id: "street", label: "Street View", icon: MapPin },
];

const lightboxOpen = ref(false);
const activeIndex = ref(0);
const activeTab = ref("photos");

const allImages = computed(() => props.images.length > 0 ? props.images : ["/images/placeholder.jpg"]);
const displayImages = computed(() => allImages.value.slice(0, 5));

const openLightbox = (index: number) => {
  activeIndex.value = index;
  lightboxOpen.value = true;
};

const prev = () => {
  activeIndex.value = activeIndex.value === 0 ? allImages.value.length - 1 : activeIndex.value - 1;
};

const next = () => {
  activeIndex.value = activeIndex.value === allImages.value.length - 1 ? 0 : activeIndex.value + 1;
};
</script>

<template>
  <div>
    <!-- Media tabs -->
    <div class="flex items-center gap-1 mb-3">
      <button
        v-for="tab in MEDIA_TABS" :key="tab.id"
        @click="tab.id === 'photos' ? activeTab = tab.id : null"
        :class="[
          'flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all',
          activeTab === tab.id
            ? 'bg-[#008f39] text-white border-[#008f39] shadow-sm'
            : tab.id !== 'photos'
              ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-zinc-800/50 dark:text-gray-500'
              : 'bg-white text-gray-800 border-gray-200 hover:border-[#008f39]/40 hover:text-[#008f39] dark:bg-zinc-900 dark:text-gray-200 dark:border-zinc-800'
        ]"
      >
        <component :is="tab.icon" :size="13" />
        {{ tab.label }}
        <span v-if="tab.id !== 'photos'" class="text-[10px] font-normal opacity-60 ml-0.5">(próximamente)</span>
      </button>
    </div>

    <!-- Photo grid -->
    <div class="relative rounded-2xl overflow-hidden grid grid-cols-4 grid-rows-2 gap-1.5 h-[420px] md:h-[500px]">
      <!-- Main large image -->
      <button
        class="col-span-2 row-span-2 relative overflow-hidden group w-full h-full"
        @click="openLightbox(0)"
        aria-label="Ver foto principal"
      >
        <img
          :src="displayImages[0]"
          :alt="title + ' - foto principal'"
          class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300" />
      </button>

      <!-- Thumbnail grid — top right 2 -->
      <button
        v-for="i in [1, 2]" :key="'top-'+i"
        class="relative overflow-hidden group w-full h-full"
        @click="openLightbox(i)"
        :aria-label="'Ver foto ' + (i + 1)"
      >
        <img v-if="displayImages[i]"
          :src="displayImages[i]"
          :alt="title + ' - foto ' + (i + 1)"
          class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <div v-else class="w-full h-full bg-gray-100 dark:bg-zinc-800" />
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300" />
      </button>

      <!-- Bottom right 2 -->
      <button
        v-for="i in [3, 4]" :key="'bottom-'+i"
        class="relative overflow-hidden group w-full h-full"
        @click="openLightbox(i)"
        :aria-label="'Ver foto ' + (i + 1)"
      >
        <img v-if="displayImages[i]"
          :src="displayImages[i]"
          :alt="title + ' - foto ' + (i + 1)"
          class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <div v-else class="w-full h-full bg-gray-100 dark:bg-zinc-800" />
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300" />
        <!-- Show all button on last thumbnail -->
        <div v-if="i === 4 && allImages.length > 5" class="absolute inset-0 bg-black/40 flex items-center justify-center">
          <div class="flex items-center gap-2 bg-white dark:bg-zinc-900 text-gray-900 dark:text-white text-xs font-semibold px-3 py-2 rounded-xl shadow-lg">
            <Grid2x2 :size="13" />
            Ver todas ({{ allImages.length }})
          </div>
        </div>
      </button>

      <!-- Photo count badge -->
      <div class="absolute bottom-3 left-3 flex items-center gap-1.5 bg-black/60 backdrop-blur-sm text-white text-xs font-medium px-2.5 py-1 rounded-lg pointer-events-none">
        <Camera :size="12" />
        {{ allImages.length }} fotos
      </div>
    </div>

    <!-- Lightbox -->
    <div v-if="lightboxOpen"
      class="fixed inset-0 z-[100] bg-white/95 dark:bg-zinc-950/95 backdrop-blur-md flex flex-col"
      role="dialog"
      aria-modal="true"
      aria-label="Galería de fotos"
    >
      <!-- Lightbox header -->
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-zinc-800">
        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ title }}</span>
        <div class="flex items-center gap-3">
          <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ activeIndex + 1 }} / {{ allImages.length }}
          </span>
          <button
            @click="lightboxOpen = false"
            class="w-8 h-8 rounded-xl flex items-center justify-center bg-gray-100 dark:bg-zinc-900 hover:bg-gray-200 dark:hover:bg-zinc-800 transition-colors text-gray-700 dark:text-gray-300"
            aria-label="Cerrar galería"
          >
            <X :size="16" />
          </button>
        </div>
      </div>

      <!-- Main image -->
      <div class="flex-1 relative flex items-center justify-center p-4 min-h-0">
        <button
          @click="prev"
        class="absolute left-4 z-10 w-10 h-10 rounded-xl bg-white/50 backdrop-blur dark:bg-zinc-900/50 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-800 dark:text-white"
          aria-label="Foto anterior"
        >
          <ChevronLeft :size="20" />
        </button>
        <div class="relative w-full max-w-4xl h-full flex items-center justify-center">
          <img
            :src="allImages[activeIndex]"
            :alt="title + ' - foto ' + (activeIndex + 1)"
            class="max-w-full max-h-full object-contain"
          />
        </div>
        <button
          @click="next"
        class="absolute right-4 z-10 w-10 h-10 rounded-xl bg-white/50 backdrop-blur dark:bg-zinc-900/50 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-800 dark:text-white"
          aria-label="Siguiente foto"
        >
          <ChevronRight :size="20" />
        </button>
      </div>

      <!-- Thumbnail strip -->
      <div class="flex items-center gap-2 px-5 py-4 overflow-x-auto border-t border-gray-200 dark:border-zinc-800">
        <button
          v-for="(src, i) in allImages" :key="'thumb-'+i"
          @click="activeIndex = i"
          :class="[
            'relative w-16 h-12 rounded-lg overflow-hidden flex-shrink-0 border-2 transition-all',
            i === activeIndex ? 'border-[#008f39] scale-105' : 'border-transparent opacity-60 hover:opacity-100'
          ]"
          :aria-label="'Ir a foto ' + (i + 1)"
        >
          <img :src="src" alt="" class="absolute inset-0 w-full h-full object-cover" />
        </button>
      </div>
    </div>
  </div>
</template>
