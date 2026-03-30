<template>
  <Head :title="__('Admin Portal Access')" />

  <div class="min-h-screen bg-slate-950 flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8 font-sans relative overflow-hidden">
    
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
      <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
      
      <!-- Logo / Header -->
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl mb-4 shadow-[0_0_30px_-5px_var(--tw-shadow-color)] shadow-emerald-500/20">
            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </div>
        <h2 class="text-3xl font-extrabold text-white tracking-tight">{{ __('System Portal') }}</h2>
        <p class="mt-2 text-sm text-slate-400">{{ __('Enter your credentials to access the administrative panel.') }}</p>
      </div>

      <!-- Login Form Card -->
      <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl p-8 backdrop-blur-sm">
        
        <form @submit.prevent="submit" class="space-y-6">
          
          <div v-if="form.errors.email" class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-start gap-3">
             <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
             <span class="text-sm font-medium text-red-400">{{ form.errors.email }}</span>
          </div>

          <!-- Email Input -->
          <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Email Address') }}</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
              </div>
              <input id="email" type="email" v-model="form.email" required autofocus
                class="block w-full pl-11 pr-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors sm:text-sm" 
                placeholder="admin@domain.com">
            </div>
          </div>

          <!-- Password Input -->
          <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Password') }}</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                 <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
              </div>
              <input id="password" type="password" v-model="form.password" required
                class="block w-full pl-11 pr-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors sm:text-sm" 
                placeholder="••••••••">
            </div>
          </div>

          <!-- Checkbox -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember" type="checkbox" v-model="form.remember"
                class="h-4 w-4 rounded border-slate-700 bg-slate-950 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900 border transition-colors">
              <label for="remember" class="ml-2 block text-sm text-slate-400">
                {{ __('Remember me') }}
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" :disabled="form.processing"
            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_0_20px_-5px_rgba(16,185,129,0.3)] text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-emerald-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            {{ form.processing ? __('Authenticating...') : __('Secure Login') }}
          </button>
        </form>
      </div>
      
      <!-- Footer details -->
      <div class="mt-8 text-center text-xs text-slate-600 font-medium tracking-wide">
        &copy; {{ new Date().getFullYear() }} AlquilaVende Portal. {{ __('All rights reserved.') }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

declare const __: any;

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/admin-portal/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
