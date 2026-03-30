<template>
    <Head :title="__('Identidades Verificadas')" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
            
            <div class="p-6 border-b border-gray-100 dark:border-zinc-700 flex justify-between items-center bg-gray-50/50 dark:bg-zinc-900/50">
                <div class="flex gap-2">
                    <Link href="/admin-portal/identity-verifications?status=all" 
                          class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors"
                          :class="filters.status === 'all' ? 'bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-zinc-800'">
                        {{ __('Todas') }}
                    </Link>
                    <Link href="/admin-portal/identity-verifications?status=pending" 
                          class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors flex items-center gap-2"
                          :class="filters.status === 'pending' ? 'bg-amber-100 text-amber-900 dark:bg-amber-900/30 dark:text-amber-400' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-zinc-800'">
                        {{ __('Pendientes') }}
                        <span v-if="pendingCount > 0" class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-xs">{{ pendingCount }}</span>
                    </Link>
                    <Link href="/admin-portal/identity-verifications?status=approved" 
                          class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors"
                          :class="filters.status === 'approved' ? 'bg-emerald-100 text-[#008f39] dark:bg-emerald-900/30 dark:text-emerald-400' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-zinc-800'">
                        {{ __('Aprobadas') }}
                    </Link>
                    <Link href="/admin-portal/identity-verifications?status=rejected" 
                          class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors"
                          :class="filters.status === 'rejected' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-zinc-800'">
                        {{ __('Rechazadas') }}
                    </Link>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-zinc-900/50 text-gray-700 dark:text-gray-300 text-xs uppercase font-bold border-b border-gray-100 dark:border-zinc-700">
                        <tr>
                            <th class="px-6 py-4">{{ __('Usuario') }}</th>
                            <th class="px-6 py-4">{{ __('Tipo') }}</th>
                            <th class="px-6 py-4">{{ __('Estado') }}</th>
                            <th class="px-6 py-4">{{ __('Fecha') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="verif in verifications.data" :key="verif.id" class="border-b border-gray-100 dark:border-zinc-800 hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    {{ verif.user.name }}
                                    <svg v-if="verif.user.is_verified" class="w-4 h-4 text-[#008f39]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="text-xs text-gray-400">{{ verif.user.email }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium uppercase text-xs tracking-wider">
                                {{ verif.document_type === 'ci_dni' ? 'DNI / CI' : 'Pasaporte' }}
                            </td>
                            <td class="px-6 py-4">
                                <span v-if="verif.status === 'pending'" class="px-2 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-md text-xs font-bold">{{ __('Pendiente') }}</span>
                                <span v-else-if="verif.status === 'approved'" class="px-2 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-md text-xs font-bold">{{ __('Aprobada') }}</span>
                                <span v-else-if="verif.status === 'rejected'" class="px-2 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-md text-xs font-bold">{{ __('Rechazada') }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium">{{ new Date(verif.created_at).toLocaleString() }}</td>
                            <td class="px-6 py-4 text-right">
                                <button @click="openModal(verif)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold transition-colors">
                                    {{ __('Revisar Documentos') }}
                                </button>
                            </td>
                        </tr>
                        
                        <tr v-if="verifications.data.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                {{ __('No se encontraron solicitudes de verificación en este estado.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Info -->
            <div class="p-4 border-t border-gray-100 dark:border-zinc-700 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-zinc-800">
                <span>{{ __('Showing') }} {{ verifications.from || 0 }} {{ __('to') }} {{ verifications.to || 0 }} {{ __('of') }} {{ verifications.total || 0 }} {{ __('results') }}</span>
            </div>
        </div>
        
        <!-- Review Modal -->
        <div v-if="selectedVerification" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto">
            <div class="bg-white dark:bg-zinc-900 w-full max-w-5xl rounded-3xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-zinc-800 flex justify-between items-center shrink-0">
                    <h3 class="text-xl font-bold dark:text-white">{{ __('Revisión de Identidad') }}: {{ selectedVerification.user.name }}</h3>
                    <button @click="closeModal" class="p-2 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full transition-colors">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="p-6 flex-1 overflow-y-auto bg-gray-50 dark:bg-zinc-900/50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Front -->
                        <div v-if="selectedVerification.images.front" class="space-y-2">
                            <h4 class="font-bold text-sm text-gray-500 uppercase tracking-wider text-center">{{ __('Anverso') }}</h4>
                            <div class="bg-black/5 rounded-2xl p-2 border border-gray-200 dark:border-zinc-700">
                                <a :href="selectedVerification.images.front" target="_blank">
                                    <img :src="selectedVerification.images.front" class="w-full h-auto aspect-4/3 object-contain rounded-xl bg-black">
                                </a>
                            </div>
                        </div>

                        <!-- Back -->
                        <div v-if="selectedVerification.images.back" class="space-y-2">
                            <h4 class="font-bold text-sm text-gray-500 uppercase tracking-wider text-center">{{ __('Reverso') }}</h4>
                            <div class="bg-black/5 rounded-2xl p-2 border border-gray-200 dark:border-zinc-700">
                                <a :href="selectedVerification.images.back" target="_blank">
                                    <img :src="selectedVerification.images.back" class="w-full h-auto aspect-4/3 object-contain rounded-xl bg-black">
                                </a>
                            </div>
                        </div>

                        <!-- Selfie -->
                        <div v-if="selectedVerification.images.selfie" class="space-y-2">
                            <h4 class="font-bold text-sm text-gray-500 uppercase tracking-wider text-center">{{ __('Selfie') }}</h4>
                            <div class="bg-black/5 rounded-2xl p-2 border border-gray-200 dark:border-zinc-700">
                                <a :href="selectedVerification.images.selfie" target="_blank">
                                    <img :src="selectedVerification.images.selfie" class="w-full h-auto aspect-4/3 object-contain rounded-xl bg-black">
                                </a>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div v-if="selectedVerification.status === 'rejected'" class="mt-6 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 p-4 rounded-xl border border-red-100 dark:border-red-900/30">
                        <p class="font-bold text-sm">{{ __('Motivo histórico del rechazo:') }}</p>
                        <p>{{ selectedVerification.rejected_reason }}</p>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div v-if="selectedVerification.status === 'pending'" class="p-6 border-t border-gray-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shrink-0 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    
                    <!-- Reject Form -->
                    <div class="flex-1 flex w-full gap-2">
                        <input v-model="rejectReason" type="text" placeholder="Motivo de rechazo (Ej. Foto borrosa)" class="flex-1 rounded-xl border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white px-4 py-2">
                        <button @click="rejectVerification" :disabled="!rejectReason || isProcessing" class="px-6 py-2 bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 font-bold rounded-xl transition-colors disabled:opacity-50">
                            {{ __('Rechazar') }}
                        </button>
                    </div>
                    
                    <!-- Approve Action -->
                    <button @click="approveVerification" :disabled="isProcessing" class="w-full sm:w-auto px-8 py-3 bg-[#008f39] hover:bg-emerald-600 shadow-lg text-white font-bold rounded-xl transition-colors disabled:opacity-50">
                        {{ __('✅ Aprobar Verificación') }}
                    </button>
                </div>
            </div>
        </div>
        
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

declare const __: any;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin Portal',
        href: '/admin-portal/dashboard',
    },
    {
        title: 'Identidades',
        href: '/admin-portal/identity-verifications',
    },
];

defineProps<{
    verifications: any;
    filters: any;
}>();

const pendingCount = computed(() => {
    // If we wanted to pass a global unread count we could, but let's assume it's just a general badge that we can load
    // Actually in the prop we only have paginated `verifications`. 
    // It's just a visual badge for the pending tab.
    return 0;
});

const selectedVerification = ref<any>(null);
const rejectReason = ref('');
const isProcessing = ref(false);

const openModal = (verif: any) => {
    selectedVerification.value = verif;
    rejectReason.value = '';
};

const closeModal = () => {
    selectedVerification.value = null;
};

const approveVerification = () => {
    if (!selectedVerification.value) return;
    isProcessing.value = true;
    router.post(`/admin-portal/identity-verifications/${selectedVerification.value.id}/approve`, {}, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => { isProcessing.value = false; }
    });
};

const rejectVerification = () => {
    if (!selectedVerification.value || !rejectReason.value) return;
    isProcessing.value = true;
    router.post(`/admin-portal/identity-verifications/${selectedVerification.value.id}/reject`, {
        reason: rejectReason.value
    }, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => { isProcessing.value = false; }
    });
};
</script>
