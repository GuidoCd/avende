<template>
  <div class="min-h-screen bg-[#f5f5f5] dark:bg-zinc-900 text-gray-900 dark:text-white font-sans">
    
    <!-- Top Navigation Bar -->
    <header class="bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700 shadow-sm sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          
          <!-- Logo & Back to Home -->
          <div class="flex items-center gap-4">
            <Link href="/" class="hover:opacity-80 transition-opacity">
              <div class="flex items-center gap-2">
                <div class="bg-[#008f39] p-1.5 rounded-lg">
                  <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <span class="font-bold text-lg hidden sm:block text-[#008f39]">Vive Aquí</span>
              </div>
            </Link>
          </div>

          <!-- Right side actions -->
          <div class="flex items-center gap-4">
            <!-- Role Toggle Placeholder -->
            <button class="flex bg-gray-100 dark:bg-zinc-700 rounded-full p-1 border border-gray-200 dark:border-zinc-600 transition-colors">
              <div class="px-3 py-1 text-sm font-medium rounded-full bg-white dark:bg-zinc-800 text-gray-900 dark:text-white shadow-sm">
                {{ __('Visitor View') }}
              </div>
              <div class="px-3 py-1 text-sm font-medium rounded-full text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors cursor-pointer">
                {{ __('Publisher View') }}
              </div>
            </button>

            <!-- Logout -->
            <Link href="/logout" method="post" as="button" class="text-sm font-medium text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              <span class="hidden sm:inline">{{ __('Log Out') }}</span>
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Profile Content -->
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left Sidebar / User Card -->
        <div class="md:col-span-1">
          <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-6 flex flex-col items-center text-center">
            
            <div class="relative mb-4">
              <!-- Avatar -->
              <div class="w-24 h-24 rounded-full bg-linear-to-br from-[#008f39] to-emerald-300 flex items-center justify-center text-white text-3xl font-bold uppercase shadow-lg border-4 border-white dark:border-zinc-800">
                {{ $page.props.auth.user.name.charAt(0) }}
              </div>
              <button class="absolute bottom-0 right-0 bg-white dark:bg-zinc-700 text-gray-600 dark:text-gray-300 p-1.5 rounded-full shadow border border-gray-200 dark:border-zinc-600 hover:text-[#008f39] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              </button>
            </div>
            
            <h2 class="text-xl font-bold">{{ $page.props.auth.user.name }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $page.props.auth.user.email }}</p>
            
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              Cuenta Activa
            </span>

          </div>
        </div>

        <!-- Right Content Area (Forms, settings, activity) -->
        <div class="md:col-span-2 space-y-6">
          
          <!-- Personal Info Block -->
          <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50">
              <h3 class="text-lg font-bold">Información Personal</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">Actualiza tu información y correo electrónico.</p>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Name') }}</label>
                  <input type="text" :value="$page.props.auth.user.name" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" disabled>
                </div>
                <div class="space-y-1">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Email') }}</label>
                  <input type="email" :value="$page.props.auth.user.email" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" disabled>
                </div>
                <div class="space-y-1 sm:col-span-2">
                  <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Teléfono</label>
                  <input type="text" placeholder="+591 60000000" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
                </div>
              </div>
              <div class="mt-6 flex justify-end">
                <button class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 font-semibold py-2 px-6 rounded-xl shadow-sm transition-colors text-sm">
                  Guardar Cambios
                </button>
              </div>
            </div>
          </div>

          <!-- Preferences / Alerts Placeholder -->
          <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
              <div>
                <h3 class="text-lg font-bold">Mis Alertas de Zona</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Te avisaremos cuando haya propiedades nuevas aquí.</p>
              </div>
              <div class="bg-emerald-100 text-[#008f39] dark:bg-emerald-900/30 dark:text-emerald-400 p-2 rounded-xl">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
              </div>
            </div>
            <div class="p-6 flex flex-col items-center justify-center text-center py-12">
              <svg class="w-16 h-16 text-gray-300 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
              <h4 class="text-base font-medium text-gray-900 dark:text-white mb-1">Aún no tienes alertas</h4>
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Busca en el mapa y crea tu primera alerta geolocalizada.</p>
              <Link href="/" class="text-[#008f39] font-medium hover:underline text-sm flex items-center gap-1">
                Explorar el Mapa
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
              </Link>
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
