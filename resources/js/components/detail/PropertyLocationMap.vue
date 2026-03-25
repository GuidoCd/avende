<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { MapPin } from 'lucide-vue-next';

const props = defineProps<{
  lat: number;
  lng: number;
  address: string;
  neighborhood: string;
}>();

const containerRef = ref<HTMLElement | null>(null);
let mapInstance: any = null;
let markerInstance: any = null;

onMounted(async () => {
  // Inject Mapbox CSS
  const cssId = "mapbox-gl-css";
  if (!document.getElementById(cssId)) {
    const link = document.createElement("link");
    link.id = cssId;
    link.rel = "stylesheet";
    link.href = "https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css";
    document.head.appendChild(link);
  }

  const token = import.meta.env.VITE_MAPBOX_TOKEN;
  if (!containerRef.value || !token) return;

  try {
    const mapboxgl = (await import('mapbox-gl')).default;
    mapboxgl.accessToken = token;

    const map = new mapboxgl.Map({
      container: containerRef.value,
      style: "mapbox://styles/mapbox/light-v11",
      center: [props.lng, props.lat],
      zoom: 15,
      interactive: true,
      attributionControl: false,
    });

    mapInstance = map;

    // Custom marker
    const el = document.createElement("div");
    el.style.cssText = `
      display: flex; flex-direction: column; align-items: center;
      cursor: pointer; user-select: none;
      filter: drop-shadow(0 4px 8px rgba(16,185,129,0.4));
    `;
    const head = document.createElement("div");
    head.style.cssText = `
      width: 36px; height: 36px; border-radius: 50% 50% 50% 0;
      transform: rotate(-45deg);
      background: #008f39;
      border: 3px solid #fff;
      display: flex; align-items: center; justify-content: center;
    `;
    const inner = document.createElement("div");
    inner.style.cssText = `
      transform: rotate(45deg);
      color: white;
      display: flex; align-items: center; justify-content: center;
    `;
    inner.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>`;
    head.appendChild(inner);
    el.appendChild(head);

    markerInstance = new mapboxgl.Marker({ element: el })
      .setLngLat([props.lng, props.lat])
      .addTo(map);
  } catch (err) {
    console.error('Failed to load mapbox-gl', err);
  }
});

onUnmounted(() => {
  if (markerInstance) markerInstance.remove();
  if (mapInstance) mapInstance.remove();
});
</script>

<template>
  <section v-if="lat && lng">
    <h2 class="text-base font-bold text-gray-900 dark:text-white mb-3">Ubicación</h2>
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 overflow-hidden">
      <div class="px-5 pt-4 pb-3 flex items-center gap-2">
        <MapPin :size="14" class="text-[#008f39]" />
        <div>
          <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ neighborhood }}</p>
          <p class="text-xs text-gray-500 dark:text-gray-400">{{ address }}</p>
        </div>
      </div>
      <div ref="containerRef" class="w-full h-56 md:h-72" aria-label="Mapa de ubicación de la propiedad"></div>
      <div class="px-5 py-3 border-t border-gray-200 dark:border-zinc-800 flex items-center justify-between">
        <p class="text-xs text-gray-500 dark:text-gray-400">La ubicación exacta se revela al agendar visita</p>
        <a
          :href="`https://maps.google.com/?q=${lat},${lng}`"
          target="_blank"
          rel="noopener noreferrer"
          class="text-xs font-semibold text-[#008f39] hover:underline"
        >
          Ver en Google Maps
        </a>
      </div>
    </div>
  </section>
</template>
