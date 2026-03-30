<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

declare const __: any;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin Dashboard',
        href: '/admin-portal/dashboard',
    },
];

defineProps<{
    stats: {
        total_users: number;
        total_properties: number;
        active_properties: number;
        draft_properties: number;
    }
}>();
</script>

<template>
    <Head :title="__('Admin Dashboard')" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-card dark:bg-zinc-800/50 p-6 rounded-2xl shadow-sm border border-border">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-muted-foreground uppercase">{{ __('Total Users') }}</p>
                            <h3 class="text-2xl font-black text-foreground mt-1">{{ stats.total_users }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-card dark:bg-zinc-800/50 p-6 rounded-2xl shadow-sm border border-border">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-muted-foreground uppercase">{{ __('Total Properties') }}</p>
                            <h3 class="text-2xl font-black text-foreground mt-1">{{ stats.total_properties }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-card dark:bg-zinc-800/50 p-6 rounded-2xl shadow-sm border border-border">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-muted-foreground uppercase">{{ __('Active Listings') }}</p>
                            <h3 class="text-2xl font-black text-foreground mt-1">{{ stats.active_properties }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-card dark:bg-zinc-800/50 p-6 rounded-2xl shadow-sm border border-border">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-muted-foreground uppercase">{{ __('Draft Properties') }}</p>
                            <h3 class="text-2xl font-black text-foreground mt-1">{{ stats.draft_properties }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-card dark:bg-zinc-800/50 p-8 rounded-2xl shadow-sm border border-border flex flex-col items-center justify-center flex-1 min-h-[400px]">
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 rounded-full mb-4">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-foreground mb-2">{{ __('Analytics Coming Soon') }}</h3>
                <p class="text-muted-foreground max-w-sm text-center">{{ __('We are building administrative web analytics to give you deeper insights into platform traffic, property views, and conversion rates.') }}</p>
            </div>
        </div>
    </AppLayout>
</template>
