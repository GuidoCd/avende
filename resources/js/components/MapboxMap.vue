<template>
  <div class="absolute inset-0 z-0">
    <div ref="mapContainer" class="w-full h-full bg-gray-100 dark:bg-zinc-900"></div>
  </div>
</template>

<script setup>
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import { ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
  markers: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['marker-click']);

const mapContainer = ref(null);
let map = null;
const markerInstances = ref([]);
let themeObserver = null;

onMounted(() => {
  mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN || '';

  const isDark = document.documentElement.classList.contains('dark');

  map = new mapboxgl.Map({
    container: mapContainer.value,
    style: isDark ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11',
    center: [-99.1917, 19.4314], // Central zone (Polanco/Condesa) focus
    zoom: 12.5,
    pitch: 45,
    bearing: -17.6,
    antialias: true
  });

  map.on('load', () => {
    updateMarkers();
  });

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

const flyTo = (lng, lat) => {
  if (!map) return;
  map.flyTo({
    center: [lng, lat],
    zoom: 15,
    essential: true,
    duration: 1200
  });
};

defineExpose({
  flyTo
});

const updateMarkers = () => {
  if (!map) return;
  
  // Clear existing
  markerInstances.value.forEach(m => m.remove());
  markerInstances.value = [];

  props.markers.forEach(property => {
    const el = document.createElement('div');
    el.className = 'custom-marker group relative flex flex-col items-center justify-center cursor-pointer';
    el.innerHTML = `
      <div class="relative z-10 bg-[#00a676] hover:bg-[#008f65] transition-all transform group-hover:scale-105 duration-300 text-white font-bold px-3 py-1.5 rounded-full shadow-[0_4px_12px_rgba(0,166,118,0.5)] border-2 border-white/20 text-xs whitespace-nowrap">
        $${property.price}/m
      </div>
      <div class="absolute -bottom-2 w-3 h-3 bg-[#00a676] transform rotate-45 group-hover:bg-[#008f65] transition-colors z-0"></div>
      <div class="absolute -bottom-4 w-6 h-6 bg-[#00a676]/20 rounded-full blur-sm scale-y-50 z-[-1]"></div>
      <div class="absolute inset-0 rounded-full bg-white/20 animate-ping opacity-0 group-hover:opacity-100 z-[-2] scale-150 pointer-events-none"></div>
    `;

    // We manually create the popup HTML so we can attach custom event listeners
    const popupContent = document.createElement('div');
    popupContent.className = 'w-72 bg-white dark:bg-zinc-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-100 dark:border-zinc-700 relative';
    popupContent.innerHTML = `
        <button class="absolute top-2 right-2 z-20 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full backdrop-blur-sm transition-colors close-popup-btn">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="h-32 w-full relative">
          <img src="${property.image}" class="w-full h-full object-cover" />
          <div class="absolute top-2 left-2 bg-[#00a676] text-white text-xs font-bold px-2 py-0.5 rounded-md shadow-sm">
            Destacado
          </div>
          <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-3 pt-8">
            <div class="font-bold text-xl text-white outline-none" style="-webkit-text-stroke: 0.5px black;">$${property.price}<span class="text-sm font-normal">/mes</span></div>
          </div>
        </div>
        <div class="p-3">
          <div class="font-bold text-gray-900 dark:text-white mb-1 truncate">${property.title}</div>
          <div class="text-xs text-gray-500 dark:text-gray-400 truncate mb-3">${property.address}</div>
          <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-300 font-medium mb-4">
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>${property.beds} hab.</span>
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>${property.baths} baños</span>
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>${property.sqm} m²</span>
          </div>
          
          <div class="flex items-center gap-2">
            <button class="flex-1 py-1.5 px-2 border border-gray-200 dark:border-zinc-700 rounded-full text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition flex items-center justify-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              <span>360°</span>
            </button>
            <button class="flex-1 py-1.5 px-2 border border-gray-200 dark:border-zinc-700 rounded-full text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition flex items-center justify-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              <span>Street</span>
            </button>
            <button class="flex-1 py-1.5 px-2 bg-[#008f39] text-white rounded-full text-xs font-bold hover:bg-emerald-700 transition shadow-sm flex items-center justify-center gap-1">
              <span>Ver</span>
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
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

    // Attach listener to the manual close button
    const closeBtn = popupContent.querySelector('.close-popup-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        popup.remove();
      });
    }

    el.addEventListener('click', () => {
      // Close other popups
      markerInstances.value.forEach(m => { if(m.getPopup().isOpen()) m.togglePopup(); });
      emit('marker-click', property);
      flyTo(property.lng, property.lat);
    });

    const marker = new mapboxgl.Marker({ element: el })
      .setLngLat([property.lng, property.lat])
      .setPopup(popup)
      .addTo(map);

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
  border-top-color: #27272a !important; /* zinc-800 */
}
</style>
