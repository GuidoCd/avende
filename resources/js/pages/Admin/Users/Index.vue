<template>
    <Head :title="__('Users Management')" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-zinc-900/50 text-gray-700 dark:text-gray-300 text-xs uppercase font-bold border-b border-gray-100 dark:border-zinc-700">
                        <tr>
                            <th class="px-6 py-4">{{ __('ID') }}</th>
                            <th class="px-6 py-4">{{ __('Name') }}</th>
                            <th class="px-6 py-4">{{ __('Email') }}</th>
                            <th class="px-6 py-4">{{ __('Joined') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id" class="border-b border-gray-100 dark:border-zinc-800 hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">#{{ user.id }}</td>
                            <td class="px-6 py-4">{{ user.name }}</td>
                            <td class="px-6 py-4">{{ user.email }}</td>
                            <td class="px-6 py-4">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>{{ __('Showing') }} {{ users.from }} {{ __('to') }} {{ users.to }} {{ __('of') }} {{ users.total }} {{ __('results') }}</span>
                <!-- Pagination can be added here -->
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
        title: 'Users Management',
        href: '/admin-portal/users',
    },
];

defineProps<{
    users: any;
}>();
</script>
