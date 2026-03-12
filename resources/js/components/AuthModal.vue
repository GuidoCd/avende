<template>
  <transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('close')"></div>

      <!-- Modal -->
      <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
        enter-to-class="opacity-100 scale-100 translate-y-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100 translate-y-0"
        leave-to-class="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
      >
        <div v-if="isOpen" class="relative bg-[#f5f5f5] dark:bg-zinc-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden flex flex-col">
          
          <!-- Header (Green Area) -->
          <div class="bg-[#008f39] p-8 text-white relative">
            <button @click="$emit('close')" class="absolute top-4 right-4 text-emerald-200 hover:text-white transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="flex items-center gap-2 mb-6">
              <div class="bg-white/20 p-1.5 rounded-lg backdrop-blur-sm">
                <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
              </div>
              <span class="font-bold">Vive Aquí</span>
            </div>
            <h2 class="text-3xl font-bold mb-2">{{ activeTab === 'register' ? 'Crea tu cuenta' : 'Bienvenido de nuevo' }}</h2>
            <p class="text-emerald-100 text-sm">Guarda favoritos y recibe alertas personalizadas</p>
          </div>

          <!-- Tabs -->
          <div class="flex border-b border-gray-200 dark:border-zinc-800 bg-[#f5f5f5] dark:bg-zinc-900">
            <button 
              @click="activeTab = 'login'"
              class="flex-1 py-4 text-sm font-semibold transition-colors border-b-2"
              :class="activeTab === 'login' ? 'text-[#008f39] border-[#008f39]' : 'text-gray-500 border-transparent hover:text-gray-700 dark:hover:text-gray-300'"
            >
              Iniciar sesión
            </button>
            <button 
              @click="activeTab = 'register'"
              class="flex-1 py-4 text-sm font-semibold transition-colors border-b-2"
              :class="activeTab === 'register' ? 'text-[#008f39] border-[#008f39]' : 'text-gray-500 border-transparent hover:text-gray-700 dark:hover:text-gray-300'"
            >
              Registrarse
            </button>
          </div>

          <!-- Form Area -->
          <div class="p-8">
            <form @submit.prevent="handleSubmit" class="space-y-4">
              
              <!-- General Error -->
              <div v-if="Object.keys(form.errors).length > 0" class="p-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-sm mb-4">
                Por favor corrige los errores ingresados.
              </div>
              
              <!-- Full Name (Only Register) -->
              <div v-if="activeTab === 'register'" class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Name') }}</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                  </div>
                  <input v-model="form.name" type="text" placeholder="Ana Garcia" class="w-full pl-10 pr-4 py-3 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" required>
                </div>
                <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
              </div>

              <!-- Email (Both) -->
              <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Email') }}</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                  </div>
                  <input v-model="form.email" type="email" placeholder="tu@email.com" class="w-full pl-10 pr-4 py-3 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" required>
                </div>
                <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
              </div>

              <!-- Password (Both) -->
              <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Password') }}</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                  </div>
                  <input v-model="form.password" :type="showPassword ? 'text' : 'password'" placeholder="Min. 8 caracteres" class="w-full pl-10 pr-10 py-3 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" required>
                  <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                  </button>
                </div>
                <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
              </div>

              <!-- Confirm Password (Only Register) -->
              <div v-if="activeTab === 'register'" class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('Confirm Password') }}</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                  </div>
                  <input v-model="form.password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" placeholder="Repite tu contraseña" class="w-full pl-10 pr-10 py-3 bg-transparent border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-2 focus:ring-[#008f39] focus:border-[#008f39] outline-none transition-shadow text-gray-900 dark:text-white" required>
                  <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg v-if="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                  </button>
                </div>
              </div>

              <div class="pt-4">
                <button type="submit" :disabled="form.processing" class="w-full bg-[#008f39] hover:bg-emerald-700 disabled:opacity-50 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition-colors flex justify-center items-center gap-2">
                  <span v-if="form.processing">Procesando...</span>
                  <span v-else>{{ activeTab === 'register' ? __('Register') : __('Log in') }}</span>
                  <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
              </div>

              <!-- Google Login -->
              <template v-if="activeTab === 'login'">
                <div class="mt-4 relative flex py-2 items-center">
                  <div class="grow border-t border-gray-200 dark:border-zinc-700"></div>
                  <span class="shrink-0 px-4 text-gray-400 text-xs">O continuar con</span>
                  <div class="grow border-t border-gray-200 dark:border-zinc-700"></div>
                </div>

                <button type="button" class="w-full bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-white font-semibold py-3 px-4 rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors flex justify-center items-center gap-3">
                  <svg viewBox="0 0 24 24" class="w-5 h-5"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"></path><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"></path><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"></path><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"></path><path fill="none" d="M1 1h22v22H1z"></path></svg>
                  Google
                </button>
              </template>

            </form>

            <p class="text-center text-xs text-gray-500 mt-6 font-medium">
              Al {{ activeTab === 'register' ? 'registrarte' : 'ingresar' }} aceptas nuestros <a href="#" class="text-[#008f39] hover:underline">Términos</a> y <a href="#" class="text-[#008f39] hover:underline">Privacidad</a>
            </p>
          </div>

        </div>
      </transition>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close']);

const activeTab = ref('login');
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  remember: false,
});

watch(activeTab, () => {
  form.clearErrors();
});

const handleSubmit = () => {
  if (activeTab.value === 'login') {
    form.post('/login', {
      onSuccess: () => emit('close'),
      onFinish: () => form.reset('password'),
    });
  } else {
    form.post('/register', {
      onSuccess: () => emit('close'),
      onFinish: () => form.reset('password', 'password_confirmation'),
    });
  }
}
</script>
