<template>
    <Head :title="__('Leads Management')" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-zinc-900/50 text-gray-700 dark:text-gray-300 text-xs uppercase font-bold border-b border-gray-100 dark:border-zinc-700">
                        <tr>
                            <th class="px-6 py-4">{{ __('ID') }}</th>
                            <th class="px-6 py-4">{{ __('Name') }}</th>
                            <th class="px-6 py-4">{{ __('Email') }} / {{ __('Phone') }}</th>
                            <th class="px-6 py-4">{{ __('Property') }}</th>
                            <th class="px-6 py-4">{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="lead in leads.data" :key="lead.id" class="border-b border-gray-100 dark:border-zinc-800 hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ lead.id }}</td>
                            <td class="px-6 py-4">{{ lead.name }}</td>
                            <td class="px-6 py-4">
                                <div>{{ lead.email }}</div>
                                <div class="text-xs text-gray-400">{{ lead.phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="truncate max-w-xs">{{ lead.property?.title || __('Deleted Property') }}</div>
                            </td>
                            <td class="px-6 py-4">{{ new Date(lead.created_at).toLocaleDateString() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-zinc-800">
                <span>{{ __('Showing') }} {{ leads.from || 0 }} {{ __('to') }} {{ leads.to || 0 }} {{ __('of') }} {{ leads.total || 0 }} {{ __('results') }}</span>
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
        title: 'Leads Management',
        href: '/admin-portal/leads',
    },
];

defineProps<{
    leads: any;
}>();
</script>
