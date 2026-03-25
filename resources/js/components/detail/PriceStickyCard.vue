<script setup lang="ts">
import { ref } from 'vue';
import { Heart, Share2, CalendarDays, Star } from 'lucide-vue-next';

declare const __: any;

const props = defineProps<{
  property: {
    price: number;
    isForRent: boolean;
    maintenanceFee?: number;
    title: string;
    tags?: string[];
  };
}>();

const saved = ref(false);

const handleShare = async () => {
  if (navigator.share) {
    await navigator.share({ title: props.property.title, url: window.location.href });
  } else {
    await navigator.clipboard.writeText(window.location.href);
    // You could add a toast here
  }
};

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(value);
};
</script>

<template>
  <div class="rounded-2xl border border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
    <!-- Price block -->
    <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
      <div class="flex items-start justify-between gap-3 mb-1">
        <div>
          <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">
            {{ property.isForRent ? __('Renta mensual') : __('Precio') }}
          </p>
          <p class="text-2xl font-extrabold text-gray-900 dark:text-white leading-none">
            {{ formatCurrency(property.price) }}<span v-if="property.isForRent">/{{ __('mes') }}</span>
          </p>
          <p v-if="property.maintenanceFee" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            + {{ formatCurrency(property.maintenanceFee) }}/{{ __('mes') }} {{ __('mantenimiento') }}
          </p>
        </div>
        <!-- Actions -->
        <div class="flex items-center gap-1.5 flex-shrink-0 pt-0.5">
          <button
            @click="saved = !saved"
            :aria-label="saved ? 'Quitar de guardados' : 'Guardar propiedad'"
            :class="[
              'w-8 h-8 rounded-xl flex items-center justify-center border transition-all',
              saved
                ? 'bg-red-50 border-red-200 text-red-500 dark:bg-red-950/20 dark:border-red-900'
                : 'bg-gray-50 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
            ]"
          >
            <Heart :size="14" :fill="saved ? 'currentColor' : 'none'" />
          </button>
          <button
            @click="handleShare"
            aria-label="Compartir propiedad"
            class="w-8 h-8 rounded-xl flex items-center justify-center bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all"
          >
            <Share2 :size="14" />
          </button>
        </div>
      </div>

      <!-- Rating (static for now as in the original mockup) -->
      <div class="flex items-center gap-1.5 mt-3">
        <div class="flex items-center gap-0.5">
          <Star v-for="s in 5" :key="s" :size="11" class="text-amber-400 fill-amber-400" />
        </div>
        <span class="text-xs font-semibold text-gray-900 dark:text-white">4.9</span>
        <span class="text-xs text-gray-500 dark:text-gray-400">(12 reseñas)</span>
      </div>
    </div>

    <!-- CTA -->
    <!-- <div class="p-5 space-y-2.5">
      <button class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-[#008f39] text-white font-semibold text-sm hover:bg-[#008f39]/90 active:scale-[0.98] transition-all shadow-sm">
        <CalendarDays :size="15" />
        Agendar visita
      </button>
      <button class="w-full py-2.5 rounded-xl border border-[#008f39] text-[#008f39] dark:text-[#008f39] font-semibold text-sm hover:bg-[#008f39]/10 transition-all">
        Solicitar información
      </button>
    </div> -->

    <!-- Tags -->
    <div v-if="property.tags && property.tags.length > 0" class="px-5 pb-5 flex flex-wrap gap-1.5">
      <span
        v-for="tag in property.tags" :key="tag"
        class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-[#008f39]/10 text-[#008f39] border border-[#008f39]/20"
      >
        {{ tag }}
      </span>
    </div>
  </div>
</template>
