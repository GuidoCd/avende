<template>
  <div class="space-y-6">
    
    <!-- Personal Info Block -->
    <form @submit.prevent="submit" class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('Personal Information') }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Update your information and email address.') }}</p>
        </div>
        <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
            <div v-if="form.recentlySuccessful" class="text-sm text-[#008f39] font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('Saved.') }}
            </div>
        </transition>
      </div>
      <div class="p-6">
        
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
          <div class="w-20 h-20 shrink-0 rounded-full overflow-hidden bg-gray-100 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
            <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover">
            <svg v-else class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
          </div>
          <div>
            <label class="px-4 py-2 inline-block bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm font-semibold cursor-pointer text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors shadow-sm">
              {{ __('Upload Photo') }}
              <input type="file" class="hidden" @change="handleFileChange" accept="image/*">
            </label>
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">{{ __('JPG, PNG or GIF. Max 5MB.') }}</p>
            <p v-if="form.errors.avatar" class="text-xs text-red-500 mt-1">{{ form.errors.avatar }}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Name') }}</label>
            <input type="text" v-model="form.name" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
            <p v-if="form.errors.name" class="text-xs text-red-500 mt-1">{{ form.errors.name }}</p>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Nickname') }}</label>
            <input type="text" v-model="form.nickname" :placeholder="__('Preferred short name')" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
            <p v-if="form.errors.nickname" class="text-xs text-red-500 mt-1">{{ form.errors.nickname }}</p>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Email') }}</label>
            <input type="email" v-model="form.email" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
            <p v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</p>
          </div>
          <div class="space-y-1">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Phone') }}</label>
            <input type="text" v-model="form.phone" placeholder="+591 60000000" class="w-full px-4 py-2.5 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
            <p v-if="form.errors.phone" class="text-xs text-red-500 mt-1">{{ form.errors.phone }}</p>
          </div>
        </div>
        <div class="mt-6 flex justify-end min-h-[40px]">
          <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-2">
            <button v-show="form.isDirty || form.avatar !== null" type="submit" :disabled="form.processing" :class="{'opacity-75 cursor-wait': form.processing}" class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 font-semibold py-2 px-6 rounded-xl shadow-sm transition-colors text-sm flex items-center gap-2">
              <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 text-white dark:text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              {{ __('Save Changes') }}
            </button>
          </transition>
        </div>
      </div>
    </form>

    <!-- Preferences / Alerts Placeholder -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold">{{ __('My Zone Alerts') }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('We will notify you when there are new properties here.') }}</p>
        </div>
        <div class="bg-emerald-100 text-[#008f39] dark:bg-emerald-900/30 dark:text-emerald-400 p-2 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </div>
      </div>
      <div class="p-6 flex flex-col items-center justify-center text-center py-12">
        <svg class="w-16 h-16 text-gray-300 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-1">{{ __('You have no alerts yet') }}</h4>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Search on the map and create your first geolocated alert.') }}</p>
        <Link href="/" class="text-[#008f39] font-medium hover:underline text-sm flex items-center gap-1">
          {{ __('Explore the Map') }}
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </Link>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import Compressor from 'compressorjs';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

defineOptions({ layout: ProfileLayout })
declare const __: any;

const page = usePage();
const user = page.props.auth.user;
const avatarUrl = (page.props.avatar_url as string | null) || null;

const form = useForm({
    name: user.name,
    nickname: user.nickname || '',
    email: user.email,
    phone: user.phone || '',
    avatar: null as File | null,
});

const avatarPreview = ref<string | null>(avatarUrl || null);

const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        new Compressor(file, {
            quality: 0.8,
            maxWidth: 1000,
            success(result) {
                const optimizedFile = new File([result], file.name, { type: result.type });
                form.avatar = optimizedFile;
                avatarPreview.value = URL.createObjectURL(optimizedFile);
            },
            error(err) {
                console.error('Compression error:', err.message);
                form.avatar = file;
                avatarPreview.value = URL.createObjectURL(file);
            }
        });
    }
};

const submit = () => {
    form.post('/profile', {
        preserveScroll: true,
    });
};
</script>
