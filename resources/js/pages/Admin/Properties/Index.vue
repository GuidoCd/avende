<template>
    <Head :title="__('Properties Management')" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-zinc-900/50 text-gray-700 dark:text-gray-300 text-xs uppercase font-bold border-b border-gray-100 dark:border-zinc-700">
                        <tr>
                            <th class="px-6 py-4">{{ __('ID') }}</th>
                            <th class="px-6 py-4">{{ __('Title') }}</th>
                            <th class="px-6 py-4">{{ __('Publisher') }}</th>
                            <th class="px-6 py-4">{{ __('Status') }}</th>
                            <th class="px-6 py-4">{{ __('Created') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="property in properties.data" :key="property.id" class="border-b border-gray-100 dark:border-zinc-800 hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ property.id }}</td>
                            <td class="px-6 py-4">
                                <div class="truncate max-w-xs">{{ property.title || __('Untitled') }}</div>
                                <span class="text-xs text-gray-400 capitalize">{{ property.publishing_status }}</span>
                            </td>
                            <td class="px-6 py-4">{{ property.user?.name || __('Unknown') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold" :class="property.is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-gray-300'">
                                    {{ property.is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ new Date(property.created_at).toLocaleDateString() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-zinc-800">
                <span>{{ __('Showing') }} {{ properties.from || 0 }} {{ __('to') }} {{ properties.to || 0 }} {{ __('of') }} {{ properties.total || 0 }} {{ __('results') }}</span>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

declare const __: any;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin Portal',
        href: '/admin-portal/dashboard',
    },
    {
        title: 'Properties Management',
        href: '/admin-portal/properties',
    },
];

defineProps<{
    properties: any;
}>();
</script>
