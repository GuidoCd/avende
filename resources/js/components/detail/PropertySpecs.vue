<script setup lang="ts">
import { ref, computed } from 'vue';
import {
  BedDouble, Bath, Ruler, Car, Building2, Clock, DollarSign,
  Layers, ChevronDown, ChevronUp, Wifi, Dumbbell, Shield,
  TreePine, Package, Dog, Waves, MapPin
} from 'lucide-vue-next';

declare const __: any;

const props = defineProps<{
  property: {
    beds?: number;
    baths?: number;
    sqm?: number;
    sqmCovered?: number;
    parkingSpots?: number;
    floors?: number;
    totalFloors?: number;
    halfBaths?: number;
    age?: number;
    maintenanceFee?: number;
    description?: string;
    neighborhood?: string;
    amenities?: string[];
  };
}>();

const AMENITY_ICONS: Record<string, any> = {
  "Gimnasio equipado": Dumbbell,
  "Rooftop lounge": Building2,
  "Concierge 24h": Shield,
  "Seguridad": Shield,
  "Elevador": Building2,
  "Estacionamiento cubierto": Car,
  "Bodega": Package,
  "Pet friendly": Dog,
  "Internet de fibra": Wifi,
  "Área de juegos": TreePine,
  "Alberca": Waves,
};

const showAll = ref(false);
const descExpanded = ref(false);

const descLines = computed(() => props.property.description?.split("\n\n") || []);
const shortDesc = computed(() => descLines.value.slice(0, 1).join("\n\n"));
const hasMoreDesc = computed(() => descLines.value.length > 1);

const amenities = computed(() => props.property.amenities || []);
const visibleAmenities = computed(() => showAll.value ? amenities.value : amenities.value.slice(0, 6));

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(value);
};
</script>

<template>
  <div class="space-y-6">
    <!-- Quick stats chips -->
    <div class="flex flex-wrap gap-2">
      <div v-if="property.beds" class="flex items-center gap-1.5 px-3 py-1.5 bg-[#008f39]/10 rounded-full text-sm font-medium text-[#008f39]">
        <BedDouble :size="13" />
        {{ property.beds }} rec.
      </div>
      <div v-if="property.baths" class="flex items-center gap-1.5 px-3 py-1.5 bg-[#008f39]/10 rounded-full text-sm font-medium text-[#008f39]">
        <Bath :size="13" />
        {{ property.baths }} baños
      </div>
      <div v-if="property.halfBaths" class="flex items-center gap-1.5 px-3 py-1.5 bg-[#008f39]/10 rounded-full text-sm font-medium text-[#008f39]">
        <Bath :size="13" />
        {{ property.halfBaths }} medios baños
      </div>
      <div v-if="property.sqm" class="flex items-center gap-1.5 px-3 py-1.5 bg-[#008f39]/10 rounded-full text-sm font-medium text-[#008f39]">
        <Ruler :size="13" />
        {{ property.sqm }} m²
      </div>
      <div v-if="property.parkingSpots" class="flex items-center gap-1.5 px-3 py-1.5 bg-[#008f39]/10 rounded-full text-sm font-medium text-[#008f39]">
        <Car :size="13" />
        {{ property.parkingSpots }} cajón{{ property.parkingSpots > 1 ? 'es' : '' }}
      </div>
      <div v-if="property.floors" class="flex items-center gap-1.5 px-3 py-1.5 bg-[#008f39]/10 rounded-full text-sm font-medium text-[#008f39]">
        <Building2 :size="13" />
        Piso {{ property.floors }}
      </div>
    </div>

    <!-- Description -->
    <section v-if="property.description">
      <h2 class="text-base font-bold text-gray-900 dark:text-white mb-3">Descripción</h2>
      <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 border border-gray-200 dark:border-zinc-800">
        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-line">
          {{ descExpanded ? property.description : shortDesc }}
        </p>
        <button
          v-if="hasMoreDesc"
          @click="descExpanded = !descExpanded"
          class="mt-3 flex items-center gap-1 text-sm font-semibold text-[#008f39] hover:text-[#008f39]/80 transition-colors"
        >
          <template v-if="descExpanded">
            <ChevronUp :size="14" /> Ver menos
          </template>
          <template v-else>
            <ChevronDown :size="14" /> Ver más
          </template>
        </button>
      </div>
    </section>

    <!-- Specs grid -->
    <section>
      <h2 class="text-base font-bold text-gray-900 dark:text-white mb-3">Características</h2>
      <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 overflow-hidden p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-0">
          
            <div v-if="property.sqm" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Ruler :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Superficie total</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.sqm }} m²</span>
            </div>
            
            <div v-if="property.sqmCovered" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Ruler :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Superficie cubierta</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.sqmCovered }} m²</span>
            </div>
            
            <div v-if="property.beds" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <BedDouble :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Recámaras</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.beds }}</span>
            </div>
            
            <div v-if="property.baths" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Bath :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Baños</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.baths }}</span>
            </div>
            
            <div v-if="property.halfBaths" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Bath :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Medios Baños</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.halfBaths }}</span>
            </div>
            
            <div v-if="property.parkingSpots" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Car :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Cajones</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.parkingSpots }}</span>
            </div>
            
            <div v-if="property.floors" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Layers :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Piso</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.floors }}</span>
            </div>

            <div v-if="property.totalFloors" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Layers :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Pisos en total</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.totalFloors }}</span>
            </div>
            
            <div v-if="property.neighborhood" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <MapPin :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Barrio / Colonia</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.neighborhood }}</span>
            </div>
            
            <div v-if="property.age !== null && property.age !== undefined" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <Clock :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Antigüedad</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ property.age }} años</span>
            </div>
            
            <div v-if="property.maintenanceFee" class="flex items-center gap-3 py-3 border-b border-gray-100 dark:border-zinc-800/50">
              <div class="w-8 h-8 rounded-lg bg-[#008f39]/10 flex items-center justify-center shrink-0">
                <DollarSign :size="14" class="text-[#008f39]" />
              </div>
              <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Mantenimiento</span>
              <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ formatCurrency(property.maintenanceFee) }}/{{ __('mes') }}</span>
            </div>
            
        </div>
      </div>
    </section>

    <!-- Amenities -->
    <section v-if="amenities.length > 0">
      <h2 class="text-base font-bold text-gray-900 dark:text-white mb-3">Amenidades</h2>
      <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 p-5">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
          <div
            v-for="amenity in visibleAmenities" :key="amenity"
            class="flex items-center gap-2.5 p-2.5 rounded-xl bg-gray-50 dark:bg-zinc-800/50 border border-gray-100 dark:border-zinc-700/50"
          >
            <div class="w-7 h-7 rounded-lg bg-[#008f39]/10 flex items-center justify-center flex-shrink-0">
              <component :is="AMENITY_ICONS[amenity] || Package" :size="13" class="text-[#008f39]" />
            </div>
            <span class="text-xs font-medium text-gray-900 dark:text-gray-200 leading-tight">{{ amenity }}</span>
          </div>
        </div>
        <button
          v-if="amenities.length > 6"
          @click="showAll = !showAll"
          class="mt-4 flex items-center gap-1 text-sm font-semibold text-[#008f39] hover:text-[#008f39]/80 transition-colors"
        >
          <template v-if="showAll">
            <ChevronUp :size="14" /> Mostrar menos
          </template>
          <template v-else>
            <ChevronDown :size="14" /> Ver las {{ amenities.length - 6 }} amenidades restantes
          </template>
        </button>
      </div>
    </section>
  </div>
</template>
