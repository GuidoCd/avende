<template>
  <div class="space-y-6">
    
    <!-- List Header Block -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('Security') || 'Seguridad' }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your password and security settings') || 'Gestiona tu contraseña y ajustes de seguridad.' }}</p>
        </div>
        <div class="bg-amber-50 text-amber-500 dark:bg-amber-900/30 dark:text-amber-400 p-2 rounded-xl">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
      </div>

      <div class="p-6">
        <div class="grid grid-cols-1 gap-6">
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Current Password') || 'Contraseña Actual' }}</label>
            <input type="password" class="w-full md:w-1/2 px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
          </div>
          
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('New Password') || 'Nueva Contraseña' }}</label>
            <input type="password" class="w-full md:w-1/2 px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
          </div>

          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Confirm New Password') || 'Confirmar Nueva Contraseña' }}</label>
            <input type="password" class="w-full md:w-1/2 px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
          </div>
          
          <div class="mt-4">
            <button class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 font-semibold py-2 px-6 rounded-xl shadow-sm transition-colors text-sm">
              {{ __('Update Password') || 'Actualizar Contraseña' }}
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

defineOptions({ layout: ProfileLayout });
</script>
