<template>
  <Head :title="__('Page Not Found')" />
  <div class="min-h-screen bg-gray-50 dark:bg-zinc-950 flex flex-col items-center justify-center p-4 relative overflow-hidden font-sans">
    
    <!-- Background Animated Blobs -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-400/20 dark:bg-emerald-600/10 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-lighten animate-blob"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-blue-400/20 dark:bg-blue-600/10 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-lighten animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-indigo-400/20 dark:bg-indigo-600/10 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-lighten animate-blob animation-delay-4000"></div>

    <div class="relative z-10 text-center max-w-lg space-y-8 animate-fade-in-up">
      <!-- Animated Isometric 404 Illustration placeholder -->
      <div class="relative w-64 h-64 mx-auto mb-8 hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-emerald-500 rounded-3xl rotate-6 opacity-20 dark:opacity-10 scale-105"></div>
        <div class="relative bg-white dark:bg-zinc-900 shadow-2xl rounded-3xl w-full h-full flex flex-col items-center justify-center border border-gray-100 dark:border-zinc-800">
          <span class="text-8xl font-black text-transparent bg-clip-text bg-linear-to-br from-emerald-500 to-blue-500">404</span>
          <div class="w-16 h-1.5 bg-gray-200 dark:bg-zinc-700 rounded-full mt-4"></div>
          <div class="w-10 h-1.5 bg-gray-200 dark:bg-zinc-700 rounded-full mt-2"></div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute -top-6 -right-6 w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl border-4 border-white dark:border-zinc-950 flex items-center justify-center shadow-lg animate-bounce" style="animation-duration: 3s">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full border-4 border-white dark:border-zinc-950 flex items-center justify-center shadow-lg animate-bounce" style="animation-duration: 2.5s; animation-delay: 0.5s">
            <span class="text-xl">🤔</span>
        </div>
      </div>

      <div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-4">
          {{ __('Oops! Looks like you\'re lost.') }}
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
          {{ __('The page you are looking for does not exist, has been removed, or you don\'t have permission to view it.') }}
        </p>
      </div>

      <div class="pt-6">
        <Link 
            :href="redirectionData.url" 
            class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white bg-gray-900 dark:bg-white dark:text-gray-900 rounded-full overflow-hidden transition-transform hover:scale-105 active:scale-95 shadow-[0_0_40px_-10px_rgba(0,0,0,0.3)] dark:shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)]">
            <span class="absolute inset-0 w-full h-full bg-linear-to-br from-emerald-500 to-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            <span class="relative flex items-center gap-2">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ redirectionData.text }}
            </span>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

declare const __: any;

const page = usePage();
const auth = page.props.auth as any;

const redirectionData = computed(() => {
    if (auth?.user) {
        if (auth.roles?.includes('admin') || auth.roles?.includes('support')) {
            return { url: '/admin-portal/dashboard', text: __('Return to Admin Dashboard') };
        }
        if (auth.roles?.includes('publisher')) {
             return { url: '/publisher/dashboard', text: __('Return to Publisher Portal') };
        }
        return { url: '/', text: __('Return to Home') };
    }
    return { url: '/', text: __('Return to Home') };
});
</script>

<style>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out forwards;
}
</style>
