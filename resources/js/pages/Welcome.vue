<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthModal from '../components/AuthModal.vue';
import MapboxMap from '../components/MapboxMap.vue';
import PropertySidebar from '../components/PropertySidebar.vue';
import SearchBar from '../components/SearchBar.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

// Mock Data representing properties in Mexico City (Polanco/Condesa area)
const mockProperties = ref([
  {
    id: 1,
    title: 'Moderno Apartamento Polanco',
    address: 'Av. Presidente Masaryk 234, Polanco',
    price: '28,500',
    type: 'rent',
    beds: 2,
    baths: 2,
    sqm: 95,
    has360: true,
    lat: 19.4314,
    lng: -99.1917,
    image: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 2,
    title: 'Casa con Alberca Santa Fe',
    address: 'Prol. Paseo de la Reforma 1200, Santa Fe',
    price: '6,800,000',
    type: 'sale',
    beds: 4,
    baths: 3,
    sqm: 320,
    has360: false,
    lat: 19.3621,
    lng: -99.2559,
    image: 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 3,
    title: 'Penthouse Vista Panorámica',
    address: 'Anatole France 515, Polanco',
    price: '85,000',
    type: 'rent',
    beds: 3,
    baths: 3,
    sqm: 210,
    has360: true,
    lat: 19.4350,
    lng: -99.1950,
    image: 'https://images.unsplash.com/photo-1600607687931-cebf559ba7ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 4,
    title: 'Townhouse Roma Norte',
    address: 'Orizaba 142, Roma Norte',
    price: '3,200,000',
    type: 'sale',
    beds: 3,
    baths: 2,
    sqm: 180,
    has360: false,
    lat: 19.4184,
    lng: -99.1585,
    image: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 5,
    title: 'Studio Luminoso Condesa',
    address: 'Amsterdam 78, Condesa',
    price: '14,500',
    type: 'rent',
    beds: 1,
    baths: 1,
    sqm: 52,
    has360: true,
    lat: 19.4124,
    lng: -99.1711,
    image: 'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 6,
    title: 'Departamento Lomas Chapultepec',
    address: 'Monte Líbano 1100, Lomas',
    price: '42,000',
    type: 'rent',
    beds: 3,
    baths: 2,
    sqm: 145,
    has360: true,
    lat: 19.4230,
    lng: -99.2140,
    image: 'https://images.unsplash.com/photo-1502672260266-1c1de2d9d0cb?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  }
]);

const mapRef = ref<InstanceType<typeof MapboxMap> | null>(null);
const isAuthModalOpen = ref(false);
const showMobileMap = ref(true); // default to map on mobile

const handlePropertySelect = (property: any) => {
  // Logic when a property is selected from the sidebar
  console.log('Selected property:', property.id);
  if (mapRef.value) {
    mapRef.value.flyTo(property.lng, property.lat);
  }
};
</script>

<template>
    <Head>
        <title>Vive Aquí - Encuentra tu próximo hogar</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    </Head>

    <div class="relative w-screen h-screen overflow-hidden bg-gray-50 dark:bg-zinc-950 font-['Inter',sans-serif]">
        <!-- Full-screen Mapbox Background (hidden on mobile if list view is active) -->
        <div :class="['absolute inset-0', showMobileMap ? 'block' : 'hidden md:block']">
            <MapboxMap ref="mapRef" :markers="mockProperties" />
        </div>

        <!-- Floating Search & Filter Bar Overlay -->
        <div :class="['transition-opacity duration-300', showMobileMap ? 'opacity-100 z-10' : 'opacity-0 -z-10 md:opacity-100 md:z-10']">
            <SearchBar @open-auth="isAuthModalOpen = true" />
        </div>

        <!-- Collapsible Side Panel for Properties -->
        <div :class="['absolute top-0 right-0 h-full w-full md:w-[400px] lg:w-[440px] transition-transform duration-300 z-20', showMobileMap ? 'translate-x-full md:translate-x-0' : 'translate-x-0']">
            <PropertySidebar :properties="mockProperties" @select-property="handlePropertySelect" />
        </div>

        <!-- Mobile Toggle Button (Map / List) -->
        <button 
            @click="showMobileMap = !showMobileMap"
            class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-[#008f39] text-white px-6 py-3 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.2)] font-semibold flex items-center gap-2 hover:scale-105 transition-transform"
        >
            <svg v-if="showMobileMap" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            {{ showMobileMap ? 'Lista' : 'Mapa' }}
        </button>

        <!-- Inertia Auth Navigation Links (Hidden or positioned appropriately if needed) -->
        <div class="absolute top-6 right-6 z-30 hidden lg:flex gap-4 items-center">
            <a v-if="$page.props.auth?.user" href="/dashboard" class="px-4 py-2 font-semibold text-white bg-emerald-600 rounded-full shadow-lg hover:bg-emerald-700 transition-colors">
                Dashboard
            </a>
        </div>
        
        <AuthModal :is-open="isAuthModalOpen" @close="isAuthModalOpen = false" />
    </div>
</template>

<style>
/* Global resets or specific app-level overrides */
body {
    overflow: hidden;
    margin: 0;
    padding: 0;
}
</style>
