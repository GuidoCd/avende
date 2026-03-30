<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { Loader2, Plus, X, Star, UploadCloud } from 'lucide-vue-next';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import { ref, computed, watch, nextTick, onUnmounted } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

declare const __: any;

interface PropertyType {
    id: number;
    name: string;
    slug: string;
    icon: string | null;
}

interface PropertyStatus {
    id: number;
    name: string;
    color: string | null;
}

interface Feature {
    id: number;
    name: string;
    icon: string | null;
}

const props = defineProps<{
    property: any;
    propertyTypes: PropertyType[];
    propertyStatuses: PropertyStatus[];
    features: Record<string, Feature[]>;
    currencies: { code: string; name: string; symbol: string }[];
}>();

const steps = [
    { id: 'basics', title: 'The Basics' },
    { id: 'location', title: 'Location' },
    { id: 'layout', title: 'Layout' },
    { id: 'amenities', title: 'Amenities' },
    { id: 'images', title: 'Photos' },
    { id: 'pricing', title: 'Pricing' },
    { id: 'review', title: 'Review' },
];

const currentStepIndex = ref(0);
const isSaving = ref(false);

const form = useForm({
    title: props.property.title || '',
    property_type_id: props.property.property_type_id || null,
    property_status_id: props.property.property_status_id || null,
    address: props.property.address || '',
    city: props.property.city || '',
    state: props.property.state || '',
    country: props.property.country || '',
    zip_code: props.property.zip_code || '',
    latitude: props.property.latitude || 0,
    longitude: props.property.longitude || 0,
    surface_total: props.property.surface_total || null,
    surface_covered: props.property.surface_covered || null,
    rooms: props.property.rooms || null,
    bathrooms: props.property.bathrooms || null,
    half_bathrooms: props.property.half_bathrooms || null,
    garage: props.property.garage || null,
    floor_number: props.property.floor_number || null,
    total_floors: props.property.total_floors || null,
    year_built: props.property.year_built || null,
    features: props.property.features?.map((f: any) => f.id) || [],
    price: props.property.price ? props.property.price / 100 : null,
    currency: props.property.currency || 'USD',
    description: props.property.description || '',
    neighborhood: props.property.neighborhood || '',
    common_expenses: props.property.common_expenses || null,
    publishing_status: props.property.publishing_status || 'draft',
    images: [] as File[], // for new uploads
    main_image_uuid: (props.property.images?.find((img: any) => img.is_main)?.uuid || null) as string | null,
    deleted_image_uuids: [] as string[], // track deletions
});

// Mapbox Integration
const mapContainer = ref<HTMLElement | null>(null);
let map: mapboxgl.Map | null = null;
let marker: mapboxgl.Marker | null = null;
let themeObserver: MutationObserver | null = null;

const initMap = () => {
    if (!mapContainer.value) return;
    mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN as string;
    const isDarkGlobal = document.documentElement.classList.contains('dark');
    
    map = new mapboxgl.Map({
        container: mapContainer.value,
        style: isDarkGlobal ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11',
        center: [form.longitude || -63.1812, form.latitude || -17.7833], // Santa Cruz de la Sierra default
        zoom: form.longitude ? 15 : 12,
    });

    marker = new mapboxgl.Marker({ draggable: true, color: '#008f39' })
        .setLngLat([form.longitude || -63.1812, form.latitude || -17.7833])
        .addTo(map);

    marker.on('dragend', onMarkerDragEnd);

    // Geolocation centering if permission is already given or user accepts
    if (!form.longitude && !form.latitude) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lng = position.coords.longitude;
                    const lat = position.coords.latitude;
                    form.longitude = lng;
                    form.latitude = lat;
                    // Auto-fill address backwards
                    onMarkerDragEnd();
                    map?.flyTo({ center: [lng, lat], zoom: 15 });
                    marker?.setLngLat([lng, lat]);
                },
                () => { /* Ignored if denied */ }
            );
        }
    }

    // Watch for dark mode class changes to update map
    themeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && map) {
                const isNowDark = document.documentElement.classList.contains('dark');
                map.setStyle(isNowDark ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11');
            }
        });
    });

    themeObserver.observe(document.documentElement, { attributes: true });
};

const onMarkerDragEnd = async () => {
    if (!marker) return;
    const lngLat = marker.getLngLat();
    form.longitude = lngLat.lng;
    form.latitude = lngLat.lat;
    
    try {
        const res = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${lngLat.lng},${lngLat.lat}.json?access_token=${mapboxgl.accessToken}&types=address,poi`);
        const data = await res.json();
        if (data.features && data.features.length > 0) {
            const context = data.features[0].context || [];
            form.address = data.features[0].text || '';
            const city = context.find((c: any) => c.id.startsWith('place'))?.text;
            const state = context.find((c: any) => c.id.startsWith('region'))?.text;
            const country = context.find((c: any) => c.id.startsWith('country'))?.text;
            if (city) form.city = city;
            if (state) form.state = state;
            if (country) form.country = country;
        }
    } catch (e) {
        console.error('Reverse Geocoding failed', e);
    }
};

const searchLocationText = async () => {
    const query = `${form.address} ${form.city} ${form.state} ${form.country}`.trim();
    if (!query) return;
    try {
        const res = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${mapboxgl.accessToken}`);
        const data = await res.json();
        if (data.features && data.features.length > 0) {
            const [lng, lat] = data.features[0].center;
            form.longitude = lng;
            form.latitude = lat;
            // Add slight offset so marker is not exactly in center if popup was there, but it's fine
            map?.flyTo({ center: [lng, lat], zoom: 15 });
            marker?.setLngLat([lng, lat]);
        }
    } catch (e) {
        console.error('Forward Geocoding failed', e);
    }
};

const selectedTypeSlug = computed(() => {
    const type = props.propertyTypes.find(t => t.id === form.property_type_id);
    return type ? type.slug : '';
});

watch(currentStepIndex, async (newVal) => {
    if (steps[newVal].id === 'location') {
        // Wait for Vue to render the v-show container
        await nextTick();
        setTimeout(() => {
            if (!map) initMap();
            else map.resize(); // Fix map size issues on visibility toggle
        }, 100);
    }
});

onUnmounted(() => {
    if (themeObserver) {
        themeObserver.disconnect();
    }
    if (map) map.remove();
});

// Theme and Appearance (Header)
const { appearance, updateAppearance } = useAppearance();

const isDark = computed(() => 
    appearance.value === 'dark' || 
    (appearance.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)
);

const toggleDarkMode = () => {
    updateAppearance(isDark.value ? 'light' : 'dark');
};

const isLangMenuOpen = ref(false);

const changeLanguage = (locale: string) => {
    isLangMenuOpen.value = false;
    if (locale === currentLocale.value) return;
    changeLocale(locale);
};

const getObjectURL = (file: File) => {
    return URL.createObjectURL(file);
};

const existingImages = computed(() => {
    // Filter out logically deleted images until saved
    return (props.property.images || []).filter((img: any) => !form.deleted_image_uuids.includes(img.uuid));
});

const handleImageUpload = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files) {
        // Enforce appending new files to form.images
        Array.from(input.files).forEach(file => {
            form.images.push(file);
        });
    }
};

const removeNewImage = (index: number) => {
    form.images.splice(index, 1);
};

const removeExistingImage = (uuid: string) => {
    form.deleted_image_uuids.push(uuid);
    if (form.main_image_uuid === uuid) {
        form.main_image_uuid = null;
    }
};

const setMainImage = (uuid: string, isNew: boolean = false) => {
    if (!isNew) {
        form.main_image_uuid = uuid;
    }
};

const getCurrencySymbol = (code: string) => {
    const c = props.currencies.find(x => x.code === code);
    return c ? c.symbol : '$';
};

const currentStep = computed(() => steps[currentStepIndex.value]);
const progressPercentage = computed(() => ((currentStepIndex.value + 1) / steps.length) * 100);

const canGoNext = computed(() => {
    switch (currentStep.value.id) {
        case 'basics':
            return form.property_type_id !== null && form.property_status_id !== null && form.title.trim() !== '';
        case 'location':
            return form.address.trim() !== '' && form.city.trim() !== '';
        case 'images':
            return existingImages.value.length > 0 || form.images.length > 0;
        case 'pricing':
            return form.price !== null && form.price > 0;
        default:
            return true;
    }
});

const saveAndNext = () => {
    if (!canGoNext.value) return;

    isSaving.value = true;
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(`/publisher/properties/${props.property.uuid}`, {
        forceFormData: true,
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            // Reset files after successful upload
            form.images = [];
            form.deleted_image_uuids = [];
            
            if (currentStepIndex.value < steps.length - 1) {
                currentStepIndex.value++;
            }
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

const goBack = () => {
    if (currentStepIndex.value > 0) {
        currentStepIndex.value--;
    } else {
        router.visit('/publisher/properties');
    }
};

const saveAndExit = () => {
    isSaving.value = true;
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(`/publisher/properties/${props.property.uuid}`, {
        forceFormData: true,
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
             router.visit('/publisher/properties');
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

const publish = () => {
    form.publishing_status = 'published';
    isSaving.value = true;
    form.put(`/publisher/properties/${props.property.uuid}`, {
        onFinish: () => {
            isSaving.value = false;
        }
    });
};

const toggleFeature = (id: number) => {
    const index = form.features.indexOf(id);
    if (index === -1) {
        form.features.push(id);
    } else {
        form.features.splice(index, 1);
    }
};

const page = usePage();
const currentLocale = computed(() => page.props.locale || 'es');

const changeLocale = (locale: string) => {
    if (locale === currentLocale.value) return;
    
    isSaving.value = true;
    form.transform((data) => ({
        ...data,
        _method: 'put',
    })).post(`/publisher/properties/${props.property.uuid}`, {
        forceFormData: true,
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            router.post('/language', { locale: locale });
        },
        onFinish: () => {
            isSaving.value = false;
        }
    });
};
</script>

<template>
    <Head :title="__('Create Property')" />

    <div class="min-h-screen bg-transparent flex flex-col font-sans">
        <!-- Header -->
        <header class="bg-white dark:bg-zinc-900 border-b border-gray-100 dark:border-zinc-800 px-6 py-4 flex items-center justify-between sticky top-0 z-10 transition-colors">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Create Property') }}</h1>
            
            <div class="flex items-center gap-2">
                
                <!-- Language Selector -->
                <div class="relative">
                    <button @click="isLangMenuOpen = !isLangMenuOpen" class="flex items-center gap-1 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                        <span class="text-xs font-semibold uppercase hidden sm:block">{{ currentLocale }}</span>
                    </button>

                    <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                    >
                        <div v-if="isLangMenuOpen" class="absolute right-0 mt-2 w-32 rounded-xl shadow-lg bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5 dark:ring-white/10 overflow-hidden text-sm z-50">
                        <div class="py-1">
                            <button @click="changeLanguage('es')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'es', 'text-gray-700 dark:text-gray-300': currentLocale !== 'es'}">
                                Español <span v-if="currentLocale === 'es'">✓</span>
                            </button>
                            <button @click="changeLanguage('en')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'en', 'text-gray-700 dark:text-gray-300': currentLocale !== 'en'}">
                                English <span v-if="currentLocale === 'en'">✓</span>
                            </button>
                            <button @click="changeLanguage('pt')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'pt', 'text-gray-700 dark:text-gray-300': currentLocale !== 'pt'}">
                                Português <span v-if="currentLocale === 'pt'">✓</span>
                            </button>
                        </div>
                        </div>
                    </transition>
                </div>

                <!-- Theme Toggle -->
                <button @click="toggleDarkMode" class="p-2 ml-1 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
                    <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg v-else class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>

                <div class="w-px h-6 bg-gray-200 dark:bg-zinc-700 mx-1"></div>

                <button @click="saveAndExit" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white font-medium transition-colors text-sm sm:text-base">
                    {{ __('Save & Exit') }}
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col pb-24 relative overflow-hidden bg-gray-50 dark:bg-zinc-900 transition-colors">
            
            <div class="flex-1 max-w-3xl mx-auto w-full px-4 py-12">
                <!-- Transition could go here -->
                <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    
                    <!-- STEP: BASICS -->
                    <div v-if="currentStep.id === 'basics'" class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">{{ __("Let's start with the basics") }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Give your listing a catchy title and select its category.') }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Listing Title') }}</label>
                            <input v-model="form.title" type="text" :placeholder="__('e.g. Modern Apartment with Ocean View')" 
                                class="w-full text-lg p-4 border rounded-xl shadow-sm focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] transition-all bg-white dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500" />
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                            <textarea v-model="form.description" rows="4" :placeholder="__('Describe the best features of your property...')"
                                class="w-full text-md p-4 border rounded-xl shadow-sm focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] transition-all bg-white dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                            <div class="space-y-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Property Type') }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button v-for="type in propertyTypes" :key="type.id" type="button"
                                        @click="form.property_type_id = type.id"
                                        :class="[
                                            'p-4 border rounded-xl text-left transition-all hover:border-[#008f39] dark:hover:border-[#008f39]',
                                            form.property_type_id === type.id 
                                                ? 'border-[#008f39] ring-2 ring-[#008f39] bg-emerald-50 dark:bg-emerald-900/20' 
                                                : 'border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800'
                                        ]">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ type.name }}</div>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Listing Type') }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button v-for="status in propertyStatuses" :key="status.id" type="button"
                                        @click="form.property_status_id = status.id"
                                        :class="[
                                            'p-4 border rounded-xl text-left transition-all hover:border-[#008f39] dark:hover:border-[#008f39]',
                                            form.property_status_id === status.id 
                                                ? 'border-[#008f39] ring-2 ring-[#008f39] bg-emerald-50 dark:bg-emerald-900/20' 
                                                : 'border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800'
                                        ]">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ status.name }}</div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP: LOCATION -->
                    <div v-show="currentStep.id === 'location'" class="space-y-8 h-full flex flex-col">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">{{ __('Where is your property located?') }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Buyers will only see your exact address after they book a viewing.') }}</p>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-8 flex-1">
                            <!-- Input section -->
                            <div class="space-y-5 bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 h-fit transition-colors flex flex-col justify-between">
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Street Address') }}</label>
                                        <input v-model="form.address" type="text" placeholder="Av. Principal 123" class="w-full p-3 border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('City') }}</label>
                                            <input v-model="form.city" type="text" class="w-full p-3 border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('State / Region') }}</label>
                                            <input v-model="form.state" type="text" class="w-full p-3 border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Country') }}</label>
                                        <input v-model="form.country" type="text" class="w-full p-3 border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Neighborhood') }}</label>
                                        <input v-model="form.neighborhood" type="text" placeholder="e.g. Centro Histórico" class="w-full p-3 border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                                    </div>
                                </div>
                                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between">
                                    <p class="text-xs text-gray-400 dark:text-zinc-500 max-w-[60%]">{{ __('Move the marker on the map to automatically fill these details, or click search.') }}</p>
                                    <button @click.prevent="searchLocationText" class="px-5 py-2.5 bg-[#008f39] hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                                        {{ __('Search on Map') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Map section -->
                            <div class="min-h-[400px] h-full w-full rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-zinc-700 relative flex-1">
                                <div ref="mapContainer" class="absolute inset-0 z-0 bg-gray-100 dark:bg-zinc-800" style="min-height: 400px; width: 100%;"></div>
                                <div class="absolute top-4 left-4 right-4 z-10 bg-white/90 dark:bg-zinc-900/90 backdrop-blur pointer-events-none p-3 rounded-xl shadow border border-gray-200 dark:border-zinc-700 text-sm font-medium text-center text-gray-700 dark:text-gray-200 flex flex-col items-center justify-center">
                                   {{ __('Drag the pin to adjust the exact location.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP: LAYOUT -->
                    <div v-if="currentStep.id === 'layout'" class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">{{ __('Share some basic details') }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('How many rooms and bathrooms does it have?') }}</p>
                        </div>

                        <div class="space-y-6 max-w-md bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 transition-colors">
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Bedrooms') }}</span>
                                <div class="flex items-center gap-4">
                                    <button @click="form.rooms = Math.max(0, (form.rooms || 0) - 1)" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">-</button>
                                    <span class="w-6 text-center text-lg text-gray-900 dark:text-white">{{ form.rooms || 0 }}</span>
                                    <button @click="form.rooms = (form.rooms || 0) + 1" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Bathrooms') }}</span>
                                <div class="flex items-center gap-4">
                                    <button @click="form.bathrooms = Math.max(0, (form.bathrooms || 0) - 1)" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">-</button>
                                    <span class="w-6 text-center text-lg text-gray-900 dark:text-white">{{ form.bathrooms || 0 }}</span>
                                    <button @click="form.bathrooms = (form.bathrooms || 0) + 1" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Total Surface (m²)') }}</span>
                                <input v-model="form.surface_total" type="number" class="w-24 p-2 text-center border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Covered Surface (m²)') }}</span>
                                <input v-model="form.surface_covered" type="number" class="w-24 p-2 text-center border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Half Bathrooms') }}</span>
                                <div class="flex items-center gap-4">
                                    <button @click="form.half_bathrooms = Math.max(0, (form.half_bathrooms || 0) - 1)" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">-</button>
                                    <span class="w-6 text-center text-lg text-gray-900 dark:text-white">{{ form.half_bathrooms || 0 }}</span>
                                    <button @click="form.half_bathrooms = (form.half_bathrooms || 0) + 1" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Garage (Optional)') }}</span>
                                <div class="flex items-center gap-4">
                                    <button @click="form.garage = Math.max(0, (form.garage || 0) - 1)" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">-</button>
                                    <span class="w-6 text-center text-lg text-gray-900 dark:text-white">{{ form.garage || 0 }}</span>
                                    <button @click="form.garage = (form.garage || 0) + 1" class="w-10 h-10 rounded-full border border-gray-200 dark:border-zinc-600 flex items-center justify-center hover:border-[#008f39] dark:hover:border-[#008f39] text-gray-900 dark:text-white transition-colors">+</button>
                                </div>
                            </div>
                            <div v-if="selectedTypeSlug === 'apartment'" class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Floor Number') }}</span>
                                <input v-model="form.floor_number" type="number" class="w-24 p-2 text-center border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                            </div>
                            <div v-else-if="selectedTypeSlug === 'house'" class="flex items-center justify-between py-4 border-b border-gray-100 dark:border-zinc-700">
                                <span class="text-lg text-gray-800 dark:text-gray-200">{{ __('Total Floors') }}</span>
                                <input v-model="form.total_floors" type="number" class="w-24 p-2 text-center border rounded-lg focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white" />
                            </div>
                        </div>
                    </div>

                    <!-- STEP: AMENITIES -->
                    <div v-if="currentStep.id === 'amenities'" class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">{{ __('What amenities do you offer?') }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Select all that apply to your property.') }}</p>
                        </div>

                        <div v-for="(categoryFeatures, type) in features" :key="type" class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white capitalize">{{ __(type) }}</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <button v-for="feature in categoryFeatures" :key="feature.id" type="button"
                                    @click="toggleFeature(feature.id)"
                                    :class="[
                                        'p-4 border rounded-xl text-left transition-all hover:border-[#008f39] dark:hover:border-[#008f39]',
                                        form.features.includes(feature.id) 
                                            ? 'border-[#008f39] ring-2 ring-[#008f39] bg-emerald-50 dark:bg-emerald-900/20' 
                                            : 'border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800'
                                    ]">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ feature.name }}</div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP: IMAGES -->
                    <div v-if="currentStep.id === 'images'" class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">{{ __('Showcase your property') }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('Upload high-quality images. You can select the main cover photo.') }}</p>
                        </div>

                        <!-- Dropzone / Upload button -->
                        <div class="bg-white dark:bg-zinc-800 p-8 rounded-2xl shadow-sm border-2 border-dashed border-gray-300 dark:border-zinc-600 flex flex-col items-center justify-center text-center transition-colors hover:border-[#008f39] dark:hover:border-[#008f39]">
                            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/20 text-[#008f39] rounded-full flex items-center justify-center mb-4">
                                <UploadCloud class="w-8 h-8"/>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('Drag & Drop your photos here') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-zinc-400 mb-6">{{ __('Supported formats: JPG, PNG. Max size: 5MB per photo.') }}</p>
                            
                            <label class="cursor-pointer bg-[#008f39] hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm inline-flex items-center gap-2">
                                <Plus class="w-5 h-5" />
                                <span>{{ __('Browse Files') }}</span>
                                <input type="file" multiple accept="image/png, image/jpeg, image/webp" class="hidden" @change="handleImageUpload" />
                            </label>
                        </div>

                        <!-- Image Grid -->
                        <div v-if="existingImages.length > 0 || form.images.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-6">
                            
                            <!-- Existing Images (Spatie MediaLibrary) -->
                            <div v-for="img in existingImages" :key="img.uuid" class="relative group rounded-xl overflow-hidden aspect-square bg-gray-100 dark:bg-zinc-800 border-2 transition-all"
                                 :class="form.main_image_uuid === img.uuid ? 'border-[#008f39] shadow-md ring-2 ring-[#008f39]/20' : 'border-transparent'">
                                <img :src="img.url" class="w-full h-full object-cover" />
                                <!-- Hover Actions -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-2">
                                    <div class="flex justify-between w-full">
                                        <button @click.prevent="setMainImage(img.uuid)" :class="form.main_image_uuid === img.uuid ? 'bg-[#008f39] text-white' : 'bg-white/80 text-gray-800 hover:bg-white'" class="p-1.5 rounded-md backdrop-blur-sm transition-colors text-xs font-semibold flex items-center gap-1 shadow-sm">
                                            <Star class="w-4 h-4" :class="form.main_image_uuid === img.uuid ? 'fill-current' : ''"/> {{ __('Main') }}
                                        </button>
                                        <button @click.prevent="removeExistingImage(img.uuid)" class="bg-red-500/80 hover:bg-red-600 text-white p-1.5 rounded-md backdrop-blur-sm transition-colors shadow-sm">
                                            <X class="w-4 h-4"/>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="form.main_image_uuid === img.uuid" class="absolute bottom-2 left-2 bg-[#008f39] text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                                    {{ __('Main Cover') }}
                                </div>
                            </div>
                            
                            <!-- New Uploads Preview (Local Blob) -->
                            <div v-for="(file, index) in form.images" :key="`new-${index}`" class="relative group rounded-xl overflow-hidden aspect-square border-2 border-dashed border-gray-300 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900/50">
                                <img :src="getObjectURL(file)" class="w-full h-full object-cover opacity-70" />
                                <div class="absolute top-2 right-2 flex gap-2">
                                    <button @click.prevent="removeNewImage(index)" class="bg-red-500/80 hover:bg-red-600 text-white p-1.5 rounded-md backdrop-blur-sm transition-colors shadow-sm">
                                        <X class="w-4 h-4"/>
                                    </button>
                                </div>
                                <div class="absolute bottom-0 inset-x-0 bg-linear-to-t from-black/60 to-transparent p-2">
                                    <div class="text-white text-xs font-medium">{{ __('New Upload') }}</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- STEP: PRICING -->
                    <div v-if="currentStep.id === 'pricing'" class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">{{ __('Now, set your price') }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __('You can change this at any time later.') }}</p>
                        </div>
                        
                        <div class="max-w-md space-y-8 bg-white dark:bg-zinc-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 transition-colors">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Currency') }}</label>
                                <select v-model="form.currency" class="w-full px-4 py-4 text-lg border rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] bg-white dark:bg-zinc-900 border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white transition-colors mb-4">
                                    <option v-for="c in currencies" :key="c.code" :value="c.code">{{ c.code }} - {{ c.name }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Price') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-xl font-medium">{{ getCurrencySymbol(form.currency) }}</span>
                                    </div>
                                    <input v-model="form.price" type="number" 
                                        class="w-full pl-10 pr-4 py-4 text-3xl font-bold border rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white transition-colors" 
                                        placeholder="0" />
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Common Expenses (Optional)') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-md">{{ getCurrencySymbol(form.currency) }}</span>
                                    </div>
                                    <input v-model="form.common_expenses" type="number" 
                                        class="w-full pl-10 pr-4 py-3 text-lg border rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] bg-transparent border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white transition-colors" 
                                        placeholder="0" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP: REVIEW -->
                    <div v-if="currentStep.id === 'review'" class="space-y-8">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">{{ __('Review your listing') }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">{{ __("Here's what we have so far. You're ready to publish!") }}</p>
                        </div>
                        
                        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-6 flex flex-col gap-4 transition-colors">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ form.title }}</h3>
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                <span>{{ form.address || __('Address pending') }}, {{ form.city || __('City pending') }}</span>
                            </div>
                            <div class="text-xl font-semibold text-[#008f39] mt-2">
                                {{ form.currency }} ${{ form.price || 0 }}
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 dark:bg-sky-900/30 border border-blue-100 dark:border-sky-800 text-blue-800 dark:text-sky-300 rounded-xl p-4 flex gap-3 transition-colors">
                            <p class="text-sm">{{ __('By publishing, your property will be visible to all users. Make sure your details are accurate.') }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- Footer Bar -->
        <footer class="fixed bottom-0 left-0 right-0 bg-white dark:bg-zinc-800 border-t border-gray-100 dark:border-zinc-700 p-4 z-20 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] dark:shadow-none transition-colors">
            <div class="max-w-3xl mx-auto flex items-center justify-between relative">
                
                <!-- Progress bar background -->
                <div class="absolute top-0 left-0 right-0 -mt-4 h-1 bg-gray-200 dark:bg-zinc-700 transition-colors">
                    <div class="h-full bg-[#008f39] transition-all duration-300 ease-out" :style="{ width: `${progressPercentage}%` }"></div>
                </div>

                <button @click="goBack" 
                    class="px-6 py-3 font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-lg transition-colors underline decoration-2 underline-offset-4">
                    {{ __('Back') }}
                </button>
                
                <button v-if="currentStepIndex < steps.length - 1" 
                    @click="saveAndNext"
                    :disabled="!canGoNext || isSaving"
                    :class="[
                        'px-8 py-3 rounded-lg font-medium text-white transition-all flex items-center gap-2',
                        canGoNext ? 'bg-[#008f39] hover:bg-emerald-700' : 'bg-gray-300 dark:bg-zinc-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'
                    ]">
                    <Loader2 v-if="isSaving" class="w-5 h-5 animate-spin" />
                    <span v-else>{{ __('Next') }}</span>
                </button>
                
                <button v-else
                    @click="publish"
                    :disabled="isSaving"
                    class="px-8 py-3 rounded-lg font-medium text-white bg-green-600 hover:bg-green-700 transition-all flex items-center gap-2">
                    <Loader2 v-if="isSaving" class="w-5 h-5 animate-spin" />
                    <span v-else>{{ __('Publish Property') }}</span>
                </button>

            </div>
        </footer>
    </div>
</template>
