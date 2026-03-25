<template>
  <div class="absolute inset-0 z-0">
    <div ref="mapContainer" class="w-full h-full bg-gray-100 dark:bg-zinc-900"></div>
  </div>
</template>

<script setup lang="ts">
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import { ref, onMounted, onUnmounted, watch, shallowRef, getCurrentInstance } from 'vue';

const instance = getCurrentInstance();
const trans = (key: string) => {
  if (instance && instance.appContext.config.globalProperties.__) {
    return instance.appContext.config.globalProperties.__(key);
  }
  return typeof (window as any).__ === 'function' ? (window as any).__(key) : key;
};

// 1. Definimos la interfaz para que TS conozca la estructura de las propiedades
interface PropertyMarker {
  id?: number | string;
  title?: string;
  address?: string;
  price?: string | number;
  beds?: number;
  baths?: number;
  sqm?: number;
  lat: number | string;
  lng: number | string;
  image?: string;
  [key: string]: any; // Permite otros campos dinámicos
}

// 2. Tipado de Props usando la sintaxis de TypeScript
const props = withDefaults(defineProps<{
  markers?: PropertyMarker[]
}>(), {
  markers: () => []
});

// 3. Tipado de Emits
const emit = defineEmits<{
  (e: 'marker-click', property: PropertyMarker): void,
  (e: 'bounds-change', bounds: { sw_lng: number, sw_lat: number, ne_lng: number, ne_lat: number, center_lng: number, center_lat: number }): void
}>();

// 4. Tipado de variables reactivas y referencias
const mapContainer = ref<HTMLElement | null>(null);
let map: mapboxgl.Map | null = null;
const markerInstances = shallowRef<mapboxgl.Marker[]>([]);
let themeObserver: MutationObserver | null = null;
let isProgrammaticMove = false;

onMounted(() => {
  mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN || '';

  const isDark = document.documentElement.classList.contains('dark');

  if (mapContainer.value) {
    map = new mapboxgl.Map({
      container: mapContainer.value,
      style: isDark ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11',
      center: [-99.1917, 19.4314], // Central zone focus
      zoom: 12.5,
      pitch: 45,
      bearing: -17.6,
      antialias: true
    });

    map.on('load', () => {
      updateMarkers();
    });

    map.on('moveend', () => {
      if (!map) return;
      
      // Si el movimiento fue causado por un flyTo() programático (click en marker o sidebar), 
      // ignoramos este evento para no recargar las propiedades y destrozar el Popup abierto.
      if (isProgrammaticMove) {
          isProgrammaticMove = false;
          return;
      }

      const bounds = map.getBounds();
      const center = map.getCenter();
      if (!bounds) return;
      
      emit('bounds-change', {
        sw_lng: bounds.getWest(),
        sw_lat: bounds.getSouth(),
        ne_lng: bounds.getEast(),
        ne_lat: bounds.getNorth(),
        center_lng: center.lng,
        center_lat: center.lat
      });
    });
  }

  // Watch for dark mode class changes
  themeObserver = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.attributeName === 'class' && map) {
        const isNowDark = document.documentElement.classList.contains('dark');
        map.setStyle(isNowDark ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11');
      }
    });
  });

  themeObserver.observe(document.documentElement, {
    attributes: true
  });
});

// 5. SOLUCIÓN AL ERROR NaN: Convertimos a Float y validamos
const flyTo = (lng: number | string, lat: number | string, zoom: number = 15, isProgrammatic: boolean = true) => {
  if (!map) return;
  
  const parsedLng = typeof lng === 'string' ? parseFloat(lng) : lng;
  const parsedLat = typeof lat === 'string' ? parseFloat(lat) : lat;

  if (!isNaN(parsedLng) && !isNaN(parsedLat)) {
    isProgrammaticMove = isProgrammatic;
    map.flyTo({
      center: [parsedLng, parsedLat],
      zoom: zoom,
      essential: true,
      duration: 1200
    });
  } else {
    console.warn('Mapbox flyTo error: Coordenadas inválidas detectadas.', { lng, lat });
  }
};

defineExpose({
  flyTo
});

const updateMarkers = () => {
  if (!map) return;
  
  // Limpiar marcadores existentes
  markerInstances.value.forEach(m => m.remove());
  markerInstances.value = [];

  props.markers.forEach(property => {
    // SOLUCIÓN AL ERROR NaN: Parseo seguro de cada marcador
    const parsedLng = parseFloat(property.lng as string);
    const parsedLat = parseFloat(property.lat as string);

    // Si las coordenadas son inválidas, saltamos esta propiedad para no romper el mapa
    if (isNaN(parsedLng) || isNaN(parsedLat)) return;

    const el = document.createElement('div');
    el.className = 'custom-marker group relative flex flex-col items-center justify-center cursor-pointer';
    el.innerHTML = `
      <div class="relative z-10 bg-[#00a676] hover:bg-[#008f65] transition-all transform group-hover:scale-105 duration-300 text-white font-bold px-3 py-1.5 rounded-full shadow-[0_4px_12px_rgba(0,166,118,0.5)] border-2 border-white/20 text-xs whitespace-nowrap">
        $${property.price}${property.isForRent ? `/${trans('mes')}` : ''}
      </div>
      <div class="absolute -bottom-2 w-3 h-3 bg-[#00a676] transform rotate-45 group-hover:bg-[#008f65] transition-colors z-0"></div>
      <div class="absolute -bottom-4 w-6 h-6 bg-[#00a676]/20 rounded-full blur-sm scale-y-50 z-[-1]"></div>
      <div class="absolute inset-0 rounded-full bg-black/20 dark:bg-white/20 animate-ping opacity-0 group-hover:opacity-100 z-[-2] scale-150 pointer-events-none"></div>
    `;

    const popupContent = document.createElement('div');
    popupContent.className = 'w-72 bg-white dark:bg-zinc-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-100 dark:border-zinc-700 relative';
    
    // Fallbacks agregados a las propiedades por si alguna viene null de la BD
    popupContent.innerHTML = `
        <button class="absolute top-2 right-2 z-20 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full backdrop-blur-sm transition-colors close-popup-btn">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="h-32 w-full relative bg-gray-200 dark:bg-zinc-700">
          ${property.image ? `<img src="${property.image}" class="w-full h-full object-cover" />` : ''}
          <div class="absolute top-2 left-2 bg-[#00a676] text-white text-xs font-bold px-2 py-0.5 rounded-md shadow-sm">
            Destacado
          </div>
          <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-3 pt-8">
            <div class="font-bold text-xl text-white outline-none" style="-webkit-text-stroke: 0.5px black;">$${property.price || 0}<span class="text-sm font-normal">${property.isForRent ? `/${trans('mes')}` : ''}</span></div>
          </div>
        </div>
        <div class="p-3">
          <div class="font-bold text-gray-900 dark:text-white mb-1 truncate">${property.title || 'Sin título'}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 truncate mb-3">${property.address || 'Ubicación no especificada'}</div>
          <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-300 font-medium mb-4">
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>${property.beds || 0} hab.</span>
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>${property.baths || 0} baños</span>
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>${property.sqm || 0} m²</span>
          </div>
          
          <div class="flex items-center gap-2">
            <a href="/properties/${property.slug || '#'}" class="flex-1 py-1.5 px-2 bg-[#008f39] text-white rounded-full text-xs font-bold hover:bg-emerald-700 transition shadow-sm flex items-center justify-center gap-1">
              <span>Ver Propiedad</span>
            </a>
          </div>
        </div>
    `;

    const popup = new mapboxgl.Popup({ 
      offset: 35, 
      closeButton: false,
      closeOnClick: true,
      className: 'custom-popup z-50',
      maxWidth: '300px'
    }).setDOMContent(popupContent);

    const closeBtn = popupContent.querySelector('.close-popup-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        popup.remove();
      });
    }

    el.addEventListener('click', () => {
      // Validación segura al cerrar popups
      markerInstances.value.forEach(m => { 
        const currentPopup = m.getPopup();
        if(currentPopup && currentPopup.isOpen()) currentPopup.remove(); 
      });
      emit('marker-click', property);
      flyTo(parsedLng, parsedLat); // Llamamos al flyTo con los números limpios
    });

    const marker = new mapboxgl.Marker({ element: el })
      .setLngLat([parsedLng, parsedLat]) // Coordenadas limpias
      .setPopup(popup)
      .addTo(map!);

    markerInstances.value.push(marker);
  });
};

watch(() => props.markers, () => {
  updateMarkers();
}, { deep: true });

onUnmounted(() => {
  if (themeObserver) {
    themeObserver.disconnect();
  }
  if (map) map.remove();
});
</script>

<style>
.custom-marker {
  transform-origin: center bottom;
}
.mapboxgl-popup-content {
  padding: 0 !important;
  background: transparent !important;
  box-shadow: none !important;
}
.mapboxgl-popup-tip {
  border-top-color: white !important;
}
.dark .mapboxgl-popup-tip {
  border-top-color: #27272a !important;
}
</style>