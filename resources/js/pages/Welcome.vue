<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthModal from '../components/AuthModal.vue';
import MapboxMap from '../components/MapboxMap.vue';
import PropertySidebar from '../components/PropertySidebar.vue';
import SearchBar from '../components/SearchBar.vue';

declare const __: any;

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    initialProperties: Array<any>;
    ipLocation: { lat: number; lng: number };
    filters: any;
}>();

console.log(props.ipLocation)
const mapRef = ref<InstanceType<typeof MapboxMap> | null>(null);
const isAuthModalOpen = ref(false);
const initialAuthTab = ref('login');
const showMobileMap = ref(true);
const isInitialLoading = ref(true);
const isMapUpdating = ref(false);
const userLocation = ref({ lat: props.ipLocation.lat, lng: props.ipLocation.lng });

const requestUserLocation = () => {
    if (!navigator.geolocation) {
        console.warn('Geolocalización no soportada. Usando IP fallback.');
        finalizeLoading(props.ipLocation.lat, props.ipLocation.lng);
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            userLocation.value = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            finalizeLoading(userLocation.value.lat, userLocation.value.lng);
        },
        (error) => {
            console.warn('Permiso denegado o error. Usando IP fallback.', error);
            finalizeLoading(props.ipLocation.lat, props.ipLocation.lng);
        },
        { timeout: 5000 }
    );
};

const finalizeLoading = (lat: number, lng: number) => {
    isInitialLoading.value = false;
    if (mapRef.value) {
        // Volvemos a enviar los números separados
        mapRef.value.flyTo(lng, lat, 13, false); 
    }
};

// 4. Buscar propiedades por límites geográficos automáticamente
const fetchPropertiesByBounds = (bounds: any) => {
    isMapUpdating.value = true;
    
    // Usamos partial reload de Inertia para pedir sólo la caja actual
    router.get('/', { 
        sw_lng: bounds.sw_lng, 
        sw_lat: bounds.sw_lat, 
        ne_lng: bounds.ne_lng, 
        ne_lat: bounds.ne_lat,
        lat: bounds.center_lat,
        lng: bounds.center_lng // opcional para tener un punto central
    }, {
        preserveState: true,
        preserveScroll: true,
        only: ['initialProperties'],
        onFinish: () => {
            isMapUpdating.value = false;
        }
    });
};

const handlePropertySelect = (property: any) => {
    if (mapRef.value) {
        mapRef.value.flyTo(property.lng, property.lat, 16, true);
    }
};

// Ejecutar al montar el componente
onMounted(() => {
    requestUserLocation();

    // Catch query parameters for opening auth modal from About Us
    const urlParams = new URLSearchParams(window.location.search);
    const authAction = urlParams.get('auth');
    if (authAction === 'register' || authAction === 'login') {
        initialAuthTab.value = authAction;
        isAuthModalOpen.value = true;
        // Clean URL to prevent re-opening modal on refresh
        window.history.replaceState({}, '', window.location.pathname);
    }
});
</script>

<template>
    <Head>
        <title>Vive Aquí - Encuentra tu próximo hogar</title>
        </Head>

    <div class="relative w-screen h-screen overflow-hidden bg-gray-50 dark:bg-zinc-950 font-['Inter',sans-serif]">
        
        <!-- Pantalla de carga inicial (bloqueante, sólo ocurre 1 vez) -->
        <div v-if="isInitialLoading" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-white/80 dark:bg-zinc-950/80 backdrop-blur-sm transition-opacity duration-300">
            <div class="w-12 h-12 border-4 border-[#008f39]/30 border-t-[#008f39] rounded-full animate-spin"></div>
            <p class="mt-4 font-medium text-gray-700 dark:text-gray-300 animate-pulse">{{ __('Locating best properties...') }}</p>
        </div>
        
        <!-- Indicador sutil de carga al moverse por el mapa (Cero bloqueante) -->
        <div v-show="isMapUpdating && !isInitialLoading" class="absolute top-24 left-1/2 -translate-x-1/2 z-40 bg-white/90 dark:bg-zinc-800/90 shadow-lg px-3 py-1.5 rounded-full flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 pointer-events-none transition-opacity duration-200">
            <div class="w-3 h-3 border-2 border-[#008f39]/30 border-t-[#008f39] rounded-full animate-spin"></div>
            {{ __('Refreshing map...') }}
        </div>

        <div :class="['absolute inset-0', showMobileMap ? 'block' : 'hidden md:block']">
            <MapboxMap ref="mapRef" :markers="initialProperties" @bounds-change="fetchPropertiesByBounds" />
        </div>

        <div :class="['transition-opacity duration-300', showMobileMap ? 'opacity-100 z-10' : 'opacity-0 -z-10 md:opacity-100 md:z-10']">
            <SearchBar 
                @open-auth="isAuthModalOpen = true" 
                @location-searched="(coords: any) => { if(mapRef) mapRef.flyTo(coords.lng, coords.lat, 14, false) }" 
            />
        </div>

        <div :class="['absolute top-0 right-0 h-full w-full md:w-[400px] lg:w-[440px] transition-transform duration-300 z-20', showMobileMap ? 'translate-x-full md:translate-x-0' : 'translate-x-0']">
            <PropertySidebar :properties="initialProperties" @select-property="handlePropertySelect" />
        </div>

        <button 
            @click="showMobileMap = !showMobileMap"
            class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-[#008f39] text-white px-6 py-3 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.2)] font-semibold flex items-center gap-2 hover:scale-105 transition-transform"
        >
            <svg v-if="showMobileMap" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            {{ showMobileMap ? 'Lista' : 'Mapa' }}
        </button>
        
        <AuthModal :is-open="isAuthModalOpen" :initial-tab="initialAuthTab" @close="isAuthModalOpen = false" />
    </div>
</template>