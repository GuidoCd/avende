<template>
  <ProfileLayout>
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden mb-8">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('Create New Property') }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Fill in the details to publish your property.') }}</p>
        </div>
        <Link href="/publisher/properties" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors text-sm font-medium flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          {{ __('Back to List') }}
        </Link>
      </div>
      
      <div class="p-6">
         <form @submit.prevent="submit" class="space-y-8">
            
            <!-- Basic Information -->
            <section class="space-y-4">
              <h4 class="text-sm font-bold text-[#008f39] uppercase tracking-wider border-b border-gray-100 dark:border-zinc-700 pb-2">
                {{ __('Basic Information') }}
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-1 md:col-span-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Property Title') }} <span class="text-red-500">*</span></label>
                    <input v-model="form.title" type="text" :placeholder="__('Ej. Hermoso Apartamento en el Centro')" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" :class="{'border-red-500': form.errors.title}">
                    <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                  </div>

                  <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Operation Type') }} <span class="text-red-500">*</span></label>
                    <select v-model="form.status" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" :class="{'border-red-500': form.errors.status}">
                       <option value="" disabled>{{ __('Select type') }}</option>
                       <option value="venta">{{ __('Sale') }}</option>
                       <option value="alquiler">{{ __('Rent') }}</option>
                       <option value="anticretico">{{ __('Anticretico') }}</option>
                    </select>
                    <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
                  </div>

                  <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Property Type') }} <span class="text-red-500">*</span></label>
                    <select v-model="form.type" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" :class="{'border-red-500': form.errors.type}">
                       <option value="" disabled>{{ __('Select category') }}</option>
                       <option value="casa">{{ __('House') }}</option>
                       <option value="departamento">{{ __('Apartment') }}</option>
                       <option value="terreno">{{ __('Land') }}</option>
                       <option value="oficina">{{ __('Office') }}</option>
                       <option value="local_comercial">{{ __('Commercial') }}</option>
                       <option value="galpon">{{ __('Warehouse') }}</option>
                    </select>
                    <div v-if="form.errors.type" class="text-red-500 text-xs mt-1">{{ form.errors.type }}</div>
                  </div>

                  <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Price') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                       <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                          $
                       </div>
                       <input v-model="form.price" type="number" min="0" placeholder="0" class="w-full pl-8 pr-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" :class="{'border-red-500': form.errors.price}">
                    </div>
                    <div v-if="form.errors.price" class="text-red-500 text-xs mt-1">{{ form.errors.price }}</div>
                  </div>

                  <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Currency') }}</label>
                    <select v-model="form.currency" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
                       <option value="USD">USD ($)</option>
                       <option value="BOB">BOB (Bs.)</option>
                    </select>
                  </div>
              </div>
            </section>

            <!-- Location with Map -->
            <section class="space-y-4">
              <h4 class="text-sm font-bold text-[#008f39] uppercase tracking-wider border-b border-gray-100 dark:border-zinc-700 pb-2">
                {{ __('Location') }}
              </h4>
              
              <div class="space-y-4">
                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Address') }} <span class="text-red-500">*</span></label>
                  <div class="flex gap-2">
                    <input v-model="form.address" type="text" :placeholder="__('Street name and number')" class="flex-1 px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" :class="{'border-red-500': form.errors.address}">
                    <button type="button" @click="searchAddress" class="px-4 py-2 bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-xl transition-colors text-sm font-bold flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                      <span class="hidden sm:inline">{{ __('Search') }}</span>
                    </button>
                  </div>
                  <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                </div>

                <div class="bg-gray-100 dark:bg-zinc-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-zinc-700 relative h-72 md:h-96">
                   <div ref="mapContainer" class="w-full h-full"></div>
                   <div class="absolute bottom-4 left-4 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-md px-3 py-2 rounded-lg shadow-lg border border-gray-200 dark:border-zinc-700 z-10 text-[10px] uppercase font-bold text-gray-500">
                      {{ __('Drag the marker to pinpoint the exact location') }}
                   </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                   <div class="space-y-1">
                      <label class="text-[10px] font-bold text-gray-400 uppercase">{{ __('Latitude') }}</label>
                      <input v-model="form.latitude" type="text" readonly class="w-full px-3 py-2 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-500 cursor-not-allowed">
                   </div>
                   <div class="space-y-1">
                      <label class="text-[10px] font-bold text-gray-400 uppercase">{{ __('Longitude') }}</label>
                      <input v-model="form.longitude" type="text" readonly class="w-full px-3 py-2 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200 dark:border-zinc-700 rounded-lg text-sm text-gray-500 cursor-not-allowed">
                   </div>
                </div>
              </div>
            </section>

            <!-- Detailed Specs -->
            <section class="space-y-4">
              <h4 class="text-sm font-bold text-[#008f39] uppercase tracking-wider border-b border-gray-100 dark:border-zinc-700 pb-2">
                {{ __('Property Details') }}
              </h4>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Rooms') }}</label>
                  <input v-model="form.rooms" type="number" min="0" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
                </div>
                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Bathrooms') }}</label>
                  <input v-model="form.bathrooms" type="number" min="0" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
                </div>
                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Garage') }}</label>
                  <input v-model="form.garage" type="number" min="0" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
                </div>
                <div class="hidden md:block"></div>

                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Total Area') }} (m²)</label>
                  <input v-model="form.surface_total" type="number" min="0" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
                </div>
                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Built Area') }} (m²)</label>
                  <input v-model="form.surface_covered" type="number" min="0" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
                </div>
              </div>
            </section>

            <!-- Media Upload -->
            <section class="space-y-4">
              <h4 class="text-sm font-bold text-[#008f39] uppercase tracking-wider border-b border-gray-100 dark:border-zinc-700 pb-2">
                {{ __('Images') }}
              </h4>
              <div class="space-y-4">
                <div 
                  @dragover.prevent="isDragging = true" 
                  @dragleave.prevent="isDragging = false" 
                  @drop.prevent="handleDrop"
                  class="border-2 border-dashed rounded-2xl p-8 flex flex-col items-center justify-center transition-colors cursor-pointer"
                  :class="isDragging ? 'border-[#008f39] bg-emerald-50 dark:bg-emerald-900/10' : 'border-gray-200 dark:border-zinc-700 hover:border-gray-300 dark:hover:border-zinc-600'"
                  @click="$refs.fileInput.click()"
                >
                  <input type="file" ref="fileInput" multiple accept="image/*" class="hidden" @change="handleFileSelect">
                  <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                  <p class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ __('Drag and drop images or click to browse') }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ __('Max 5MB per image. Support JPEG, PNG, WEBP.') }}</p>
                </div>

                <!-- Preview Area -->
                <div v-if="previews.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                  <div v-for="(preview, index) in previews" :key="index" class="relative aspect-square rounded-xl overflow-hidden border border-gray-100 dark:border-zinc-700 group shadow-sm transition-transform hover:scale-105">
                    <img :src="preview" class="w-full h-full object-cover">
                    <button type="button" @click.stop="removeImage(index)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                  </div>
                </div>
                <div v-if="form.errors.images" class="text-red-500 text-xs">{{ form.errors.images }}</div>
              </div>
            </section>
            
            <div class="space-y-1">
               <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Description') }}</label>
               <textarea v-model="form.description" rows="4" :placeholder="__('Describe los detalles principales de la propiedad...')" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="border-t border-gray-100 dark:border-zinc-700 pt-6 flex justify-end">
               <button type="submit" :disabled="form.processing" class="bg-[#008f39] hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-colors flex items-center gap-2">
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                  {{ __('Submit Property') }}
               </button>
            </div>
         </form>
      </div>
    </div>
  </ProfileLayout>
</template>

<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';

const mapContainer = ref(null);
let map = null;
let marker = null;

const isDragging = ref(false);
const previews = ref([]);
const fileInput = ref(null);

const form = useForm({
   title: '',
   status: '',
   type: '',
   price: '',
   currency: 'USD',
   address: '',
   latitude: -17.7833, // Default Santa Cruz, Boliva
   longitude: -63.1821,
   description: '',
   rooms: null,
   bathrooms: null,
   surface_total: null,
   surface_covered: null,
   garage: null,
   images: [],
});

const handleFileSelect = (e) => {
  const files = Array.from(e.target.files);
  addFiles(files);
};

const handleDrop = (e) => {
  isDragging.value = false;
  const files = Array.from(e.dataTransfer.files);
  addFiles(files);
};

const addFiles = (files) => {
  files.forEach(file => {
    if (file.type.startsWith('image/')) {
      form.images.push(file);
      const reader = new FileReader();
      reader.onload = (e) => {
        previews.value.push(e.target.result);
      };
      reader.readAsDataURL(file);
    }
  });
};

const removeImage = (index) => {
  form.images.splice(index, 1);
  previews.value.splice(index, 1);
};

onMounted(() => {
  mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN || '';
  
  const isDark = document.documentElement.classList.contains('dark');

  map = new mapboxgl.Map({
    container: mapContainer.value,
    style: isDark ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11',
    center: [form.longitude, form.latitude],
    zoom: 13
  });

  marker = new mapboxgl.Marker({
    draggable: true,
    color: '#008f39'
  })
  .setLngLat([form.longitude, form.latitude])
  .addTo(map);

  marker.on('dragend', () => {
    const lngLat = marker.getLngLat();
    form.latitude = lngLat.lat.toFixed(6);
    form.longitude = lngLat.lng.toFixed(6);
  });

  map.on('click', (e) => {
    marker.setLngLat(e.lngLat);
    form.latitude = e.lngLat.lat.toFixed(6);
    form.longitude = e.lngLat.lng.toFixed(6);
  });
});

const searchAddress = async () => {
  if (!form.address || form.address.length < 5) return;
  
  try {
    const token = import.meta.env.VITE_MAPBOX_TOKEN;
    const response = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(form.address)}.json?access_token=${token}&limit=1&country=BO`);
    const data = await response.json();
    
    if (data.features && data.features.length > 0) {
      const [lng, lat] = data.features[0].center;
      form.latitude = lat.toFixed(6);
      form.longitude = lng.toFixed(6);
      marker.setLngLat([lng, lat]);
      map.flyTo({ center: [lng, lat], zoom: 15 });
    }
  } catch (error) {
    console.error('Geocoding error:', error);
  }
};

onUnmounted(() => {
  if (map) map.remove();
});

const submit = () => {
   form.post('/publisher/properties');
};
</script>
