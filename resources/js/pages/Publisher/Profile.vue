<template>
  <div class="space-y-6 max-w-4xl mx-auto py-8">
    
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Public Profile') }}</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-1">{{ __('This is how you will appear to users on your property listings.') }}</p>
    </div>

    <form @submit.prevent="submit" class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
      <div class="p-6">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          
          <div class="space-y-1">
            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Public Email') }}</label>
            <input type="email" v-model="form.public_email" :placeholder="__('Business Email')" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
            <p v-if="form.errors.public_email" class="text-xs text-red-500 mt-1">{{ form.errors.public_email }}</p>
          </div>

          <div class="space-y-1">
            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Public Phone') }}</label>
            <input type="text" v-model="form.public_phone" :placeholder="__('Business Phone Number')" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white">
            <p v-if="form.errors.public_phone" class="text-xs text-red-500 mt-1">{{ form.errors.public_phone }}</p>
          </div>

          <div class="space-y-3 sm:col-span-2 mt-2">
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" v-model="form.has_whatsapp" class="w-5 h-5 rounded border-gray-300 dark:border-zinc-600 text-[#008f39] focus:ring-[#008f39]">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                 <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.126.549 4.195 1.594 6.015L.145 23.855l5.968-1.564A11.97 11.97 0 0012.031 24c6.645 0 12.031-5.385 12.031-12.031S18.677 0 12.031 0zM17.5 16.5c-.328.983-1.637 1.83-2.618 1.956-.843.109-2.022.067-3.69-.607-2.625-1.06-4.298-3.76-4.428-3.935-.13-.174-1.054-1.402-1.054-2.673 0-1.27.653-1.895.882-2.155.23-.26.5-.326.664-.326.164 0 .327 0 .459.006.14.007.33-.053.516.395.197.472.67 1.634.73 1.765.06.13.1.284.018.448-.083.164-.124.266-.248.411-.124.146-.263.313-.374.437-.123.136-.254.286-.11.533.143.248.64 1.059 1.378 1.717.95.845 1.745 1.108 1.993 1.231.248.125.394.103.541-.065.147-.168.636-.74.806-.994.17-.253.34-.212.565-.125.226.087 1.432.675 1.678.798.246.123.411.185.47.288.06.103.06.602-.267 1.585z"/></svg>
                {{ __('Enable WhatsApp shortcut for this number') }}
              </span>
            </label>
          </div>

          <div class="space-y-1 sm:col-span-2">
            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Bio / About the Agent') }}</label>
            <textarea v-model="form.bio" rows="4" :placeholder="__('Write a brief description about your real estate experience...')" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white resize-none"></textarea>
            <p v-if="form.errors.bio" class="text-xs text-red-500 mt-1">{{ form.errors.bio }}</p>
          </div>

        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between">
            <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                <div v-if="form.recentlySuccessful" class="text-sm text-[#008f39] font-medium flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Your public profile has been updated.') }}
                </div>
                <div v-else></div>
            </transition>
          <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-2">
            <button v-show="form.isDirty" type="submit" :disabled="form.processing" :class="{'opacity-75 cursor-wait': form.processing}" class="bg-[#008f39] hover:bg-[#007a31] text-white font-semibold flex items-center gap-2 py-2.5 px-8 rounded-xl shadow-sm transition-colors text-sm">
              <svg v-if="form.processing" class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              {{ __('Save Public Profile') }}
            </button>
          </transition>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

defineOptions({ layout: ProfileLayout })

declare const __: any;

const page = usePage();
// publicProfile could be null if it's new
const profile = page.props.publicProfile as Record<string, any> || {};

const form = useForm({
    public_email: profile.public_email || '',
    public_phone: profile.public_phone || '',
    has_whatsapp: !!profile.has_whatsapp,
    bio: profile.bio || '',
});

const submit = () => {
    // We use the traditional POST wrapper for Inertia files / REST updates to preserve ziggy route calls if it existed. We will use a POST to /publisher/profile
    form.post('/publisher/profile', {
        preserveScroll: true,
    });
};
</script>
