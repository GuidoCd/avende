<template>
  <ProfileLayout>
    <Head title="Leads & Contactos" />

    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Leads & Contactos') }}</h1>
          <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
            {{ __('Gestiona los mensajes y clientes potenciales de tus propiedades publicadas.') }}
          </p>
        </div>
      </div>

      <!-- Search & Filters -->
      <div class="bg-white dark:bg-zinc-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700">
        <form @submit.prevent="handleSearch" class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          </div>
          <input 
            v-model="search"
            type="search" 
            :placeholder="__('Buscar por nombre, correo, teléfono o propiedad...')"
            class="block w-full pl-10 pr-3 py-3 border border-gray-200 dark:border-zinc-700 rounded-xl leading-5 bg-gray-50 dark:bg-zinc-900 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:bg-white dark:focus:bg-zinc-800 focus:ring-2 focus:ring-[#008f39]/50 focus:border-[#008f39] sm:text-sm transition-colors text-gray-900 dark:text-white"
          >
          <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <span class="text-sm font-semibold text-[#008f39] hover:text-emerald-700">{{ __('Buscar') }}</span>
          </button>
        </form>
      </div>

      <!-- Content (Cards Grid) -->
      <div v-if="leads.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="lead in leads.data" 
          :key="lead.id" 
          class="bg-white dark:bg-zinc-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-zinc-700 hover:shadow-md transition-shadow flex flex-col h-full"
        >
          <!-- Card Header (Property & Date) -->
          <div class="flex justify-between items-start mb-4 gap-2 border-b border-gray-100 dark:border-zinc-700 pb-4">
            <div class="min-w-0">
              <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ __('Propiedad') }}</span>
              <Link :href="`/properties/${lead.property?.slug}`" class="block mt-0.5 text-sm font-bold text-[#008f39] hover:underline truncate" :title="lead.property?.title">
                {{ lead.property?.title || 'Propiedad Eliminada' }}
              </Link>
            </div>
            <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap bg-gray-50 dark:bg-zinc-700/50 px-2 py-1 rounded-md">
              {{ formatDate(lead.created_at) }}
            </span>
          </div>

          <!-- Contact Info -->
          <div class="space-y-3 flex-1">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-[#008f39] dark:text-emerald-400 flex items-center justify-center font-bold text-lg shrink-0">
                {{ lead.name.charAt(0).toUpperCase() }}
              </div>
              <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate" :title="lead.name">{{ lead.name }}</p>
                <div class="flex flex-col text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                  <a v-if="lead.email && lead.email !== 'sin@correo.com'" :href="`mailto:${lead.email}`" class="hover:text-[#008f39] truncate transition-colors">{{ lead.email }}</a>
                  <a v-if="lead.phone" :href="`tel:${lead.phone}`" class="hover:text-[#008f39] transition-colors mt-0.5">{{ lead.phone }}</a>
                </div>
              </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-zinc-900/50 p-3.5 rounded-xl border border-gray-100 dark:border-zinc-700/50 mt-4 relative">
              <svg class="absolute top-3 right-3 w-4 h-4 text-gray-300 dark:text-zinc-600" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/></svg>
              <p class="text-sm text-gray-700 dark:text-gray-300 italic pr-6 line-clamp-4">{{ lead.message }}</p>
            </div>
          </div>
          
          <!-- Actions footer -->
          <div class="mt-5 pt-4 border-t border-gray-100 dark:border-zinc-700 flex gap-2">
            <a v-if="lead.phone" :href="`https://wa.me/${lead.phone.replace(/[^0-9]/g, '')}`" target="_blank" class="flex-1 flex justify-center items-center gap-1.5 py-2 px-3 rounded-lg bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366]/20 transition-colors text-xs font-bold">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
            </a>
            <a v-if="lead.email && lead.email !== 'sin@correo.com'" :href="`mailto:${lead.email}`" class="flex-1 flex justify-center items-center gap-1.5 py-2 px-3 rounded-lg bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-zinc-600 transition-colors text-xs font-bold">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-white dark:bg-zinc-800 rounded-2xl p-10 shadow-sm border border-gray-100 dark:border-zinc-700 flex flex-col items-center justify-center text-center">
        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-zinc-700 flex items-center justify-center mb-4">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ search ? __('No se encontraron resultados') : __('No hay mensajes') }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm">
          {{ search ? __('Intenta buscar con otros términos.') : __('Cuando alguien se interese en tus propiedades y te escriba, los mensajes aparecerán aquí.') }}
        </p>
        <button v-if="search" @click="search = ''; handleSearch()" class="mt-4 px-4 py-2 bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-xl text-sm font-semibold transition-colors">
          {{ __('Limpiar búsqueda') }}
        </button>
      </div>

      <!-- Pagination -->
      <div v-if="leads.links && leads.data.length > 0" class="flex justify-center mt-8">
        <div class="inline-flex items-center gap-1 bg-white dark:bg-zinc-800 p-1.5 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm">
          <template v-for="(link, index) in leads.links" :key="index">
            <Link 
              v-if="link.url"
              :href="link.url" 
              class="px-3 py-1.5 text-sm rounded-lg transition-colors font-medium border"
              :class="link.active ? 'bg-[#008f39] text-white border-[#008f39]' : 'bg-transparent text-gray-700 dark:text-gray-300 border-transparent hover:bg-gray-100 dark:hover:bg-zinc-700'"
              v-html="link.label"
            />
            <span 
              v-else 
              class="px-3 py-1.5 text-sm rounded-lg text-gray-400 dark:text-gray-500 font-medium"
              v-html="link.label"
            ></span>
          </template>
        </div>
      </div>
      
    </div>
  </ProfileLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

declare const __: any;

const props = defineProps<{
  leads: any;
  filters: any;
}>();

const search = ref(props.filters?.search || '');

const handleSearch = () => {
  router.get('/publisher/leads', { search: search.value }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  });
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('es-BO', { 
    day: 'numeric', 
    month: 'short', 
    year: 'numeric' 
  }).format(date);
};
</script>
