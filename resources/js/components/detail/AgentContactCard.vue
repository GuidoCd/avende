<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Phone, Mail, MessageCircle, Send, CheckCircle2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface PropertyAgent {
  name: string;
  title: string;
  agency: string;
  phone: string;
  whatsapp: string;
  email: string;
  avatar?: string;
}

declare const __: any;

const props = defineProps<{
  agent: PropertyAgent | null;
  propertyTitle: string;
  propertyId: string;
}>();

const emit = defineEmits(['requireAuth']);

const page = usePage();
const user = computed(() => page.props.auth?.user); // Computed for reactivity

const showPhone = ref(false);
const form = ref({ 
  name: user.value?.name || "", 
  phone: user.value?.phone || "", 
  email: user.value?.email || "", 
  message: "" 
});
const sent = ref(false);
const loading = ref(false);

const handleShowPhone = () => {
  if (!user.value) {
    emit('requireAuth');
  } else {
    showPhone.value = true;
    axios.post(`/api/properties/${props.propertyId}/contact-views`).catch(() => {});
  }
};

const handleSubmit = async (e: Event) => {
  e.preventDefault();
  
  loading.value = true;
  try {
    await axios.post(`/api/properties/${props.propertyId}/leads`, form.value);
    sent.value = true;
  } catch (error) {
    console.error("Error sending lead", error);
  } finally {
    loading.value = false;
  }
};

const getDefaultAvatar = () => 'https://ui-avatars.com/api/?name=Agente&background=008f39&color=fff';

</script>

<template>
  <div class="rounded-2xl border border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
    <template v-if="agent">
      <!-- Agent info -->
      <div class="p-5 border-b border-gray-200 dark:border-zinc-800">
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">{{ __('Agente a cargo') }}</p>
        <div class="flex items-center gap-3">
          <div class="relative w-11 h-11 rounded-full overflow-hidden flex-shrink-0 ring-2 ring-[#008f39]/20 bg-gray-100">
            <img :src="agent.avatar || getDefaultAvatar()" :alt="agent.name" class="w-full h-full object-cover" />
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ agent.name }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ agent.title }} &middot; {{ agent.agency }}</p>
          </div>
        </div>

        <!-- Quick contact buttons -->
        <div class="flex gap-2 mt-4">
          <template v-if="showPhone">
            <a
              :href="`https://wa.me/${agent.whatsapp}?text=Hola, me interesa la propiedad: ${propertyTitle}`"
              target="_blank"
              rel="noopener noreferrer"
              class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl bg-[#25D366] text-white text-xs font-semibold hover:bg-[#1ebe5d] transition-colors"
              :aria-label="__('Contactar por WhatsApp')"
            >
              <MessageCircle :size="13" />
              WhatsApp
            </a>
            <a
              :href="`tel:${agent.phone}`"
              class="flex items-center justify-center px-3 h-9 rounded-xl bg-[#008f39]/10 text-[#008f39] hover:bg-[#008f39]/20 transition-colors text-xs font-bold whitespace-nowrap"
              :aria-label="__('Llamar al agente')"
            >
              <Phone :size="13" class="mr-1" />
              {{ agent.phone }}
            </a>
            <a
              :href="`mailto:${agent.email}`"
              class="flex items-center justify-center w-9 h-9 rounded-xl bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-zinc-700 transition-colors"
              :aria-label="__('Enviar correo al agente')"
            >
              <Mail :size="14" />
            </a>
          </template>
          <template v-else>
            <button
              @click="handleShowPhone"
              class="w-full flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-[#008f39] text-white text-sm font-semibold hover:bg-[#008f39]/90 transition-colors shadow-sm"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
              {{ __('Mostrar datos de contacto') }}
            </button>
          </template>
        </div>
      </div>
    </template>
    <template v-else>
       <div class="p-5 text-center border-b border-gray-200 dark:border-zinc-800">
         <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Esta propiedad no tiene un agente asignado o los datos están ocultos por el publicador.') }}</p>
       </div>
    </template>

    <!-- Contact form -->
    <div class="p-5">
      <div v-if="sent" class="flex flex-col items-center gap-3 py-4 text-center">
        <div class="w-12 h-12 rounded-full bg-[#008f39]/10 flex items-center justify-center">
          <CheckCircle2 :size="24" class="text-[#008f39]" />
        </div>
        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Mensaje enviado') }}</p>
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('El agente se pondrá en contacto contigo pronto.') }}</p>
        <button
          @click="sent = false; form = { name: '', phone: '', email: '', message: '' }"
          class="text-xs font-medium text-[#008f39] hover:underline mt-1"
        >
          {{ __('Enviar otro mensaje') }}
        </button>
      </div>
      <form v-else @submit="handleSubmit" class="space-y-3">
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('Envíanos un mensaje') }}</p>
        
        <template v-if="!user">
          <input
            v-model="form.name"
            type="text"
            :placeholder="__('Nombre completo *')"
            required
            class="w-full text-sm px-3.5 py-2.5 rounded-xl bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 focus:border-[#008f39] focus:outline-none focus:ring-1 focus:ring-[#008f39]/30 transition-all placeholder:text-gray-400 dark:text-white"
          />
          <input
            v-model="form.email"
            type="email"
            :placeholder="__('Correo electrónico *')"
            required
            class="w-full text-sm px-3.5 py-2.5 rounded-xl bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 focus:border-[#008f39] focus:outline-none focus:ring-1 focus:ring-[#008f39]/30 transition-all placeholder:text-gray-400 dark:text-white"
          />
        </template>
        
        <input
          v-model="form.phone"
          type="tel"
          :placeholder="__('Teléfono (opcional)')"
          class="w-full text-sm px-3.5 py-2.5 rounded-xl bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 focus:border-[#008f39] focus:outline-none focus:ring-1 focus:ring-[#008f39]/30 transition-all placeholder:text-gray-400 dark:text-white"
        />
        
        <textarea
          v-model="form.message"
          :placeholder="__('Me interesa esta propiedad, quisiera más información...')"
          rows="3"
          required
          class="w-full text-sm px-3.5 py-2.5 rounded-xl bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 focus:border-[#008f39] focus:outline-none focus:ring-1 focus:ring-[#008f39]/30 transition-all placeholder:text-gray-400 resize-none dark:text-white"
        ></textarea>
        <button
          type="submit"
          :disabled="loading"
          class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-[#008f39] text-white text-sm font-semibold hover:bg-[#008f39]/90 active:scale-[0.98] transition-all disabled:opacity-60"
        >
          <span v-if="loading" class="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
          <Send v-else :size="14" />
          {{ loading ? __('Enviando...') : __('Contactar agente') }}
        </button>
        <p class="text-[10px] text-gray-500 dark:text-gray-400 text-center leading-relaxed">
          {{ __('Al enviar aceptas los') }}
          <span class="text-[#008f39] underline cursor-pointer">{{ __('Términos de Uso') }}</span>
          {{ __('y la') }}
          <span class="text-[#008f39] underline cursor-pointer">{{ __('Política de Privacidad') }}</span>.
        </p>
      </form>
    </div>
  </div>
</template>
