<template>
  <Head :title="__('Validación de Identidad')" />
  
  <ProfileLayout>
    <div class="max-w-3xl mx-auto">
      
      <!-- Existing Verification Layout -->
      <div v-if="verification && !showFormOverride" class="bg-white dark:bg-zinc-800 rounded-3xl shadow-sm border border-gray-100 dark:border-zinc-700 p-8 text-center">
        <div v-if="verification.status === 'pending'" class="text-amber-500 mb-6">
          <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div v-else-if="verification.status === 'approved'" class="text-[#008f39] mb-6">
          <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div v-else-if="verification.status === 'rejected'" class="text-red-500 mb-6">
          <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <h2 class="text-2xl font-bold mb-4">{{ statusTitle }}</h2>
        <p class="text-gray-500 dark:text-gray-400 max-w-lg mx-auto mb-8">{{ statusDescription }}</p>
        
        <div v-if="verification.status === 'rejected'" class="bg-red-50 dark:bg-red-900/20 text-red-600 border border-red-100 dark:border-red-900/30 p-4 rounded-xl mb-8 font-medium">
          {{ __('Motivo del rechazo:') }} {{ verification.rejected_reason }}
        </div>
        
        <button v-if="verification.status === 'rejected'" @click="resetForm" class="bg-[#008f39] hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-full transition-colors">
          {{ __('Volver a intentar') }}
        </button>
      </div>

      <!-- Verification Process Layout -->
      <div v-else class="bg-white dark:bg-zinc-800 rounded-3xl shadow-sm border border-gray-100 dark:border-zinc-700 overflow-hidden">
        
        <!-- Step 1: Introduction -->
        <div v-if="currentStep === 'intro'" class="p-8 md:p-12 text-center">
            <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center rounded-full mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h1 class="text-3xl font-black mb-4">{{ __('Validación de Identidad') }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-lg mb-8 max-w-lg mx-auto">
                {{ __('Para mantener nuestra comunidad segura, necesitamos verificar tu identidad. Este proceso requiere que tomes fotos de tu documento oficial y una foto de tu rostro (selfie). Necesitaremos acceso a tu cámara.') }}
            </p>
            <button @click="currentStep = 'document_type'" class="bg-[#008f39] hover:bg-emerald-600 text-white font-bold text-lg py-4 px-12 rounded-full transition-colors shadow-lg hover:shadow-xl w-full sm:w-auto">
                {{ __('Comenzar verificación') }}
            </button>
        </div>

        <!-- Step 2: Document Selection -->
        <div v-if="currentStep === 'document_type'" class="p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Selecciona tu documento') }}</h2>
            <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <button @click="selectDocumentType('ci_dni')" class="flex-1 p-6 border-2 rounded-2xl transition-all hover:border-[#008f39] dark:hover:border-emerald-500" :class="form.document_type === 'ci_dni' ? 'border-[#008f39] bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-200 dark:border-zinc-700'">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <span class="font-bold block text-lg">{{ __('CI / DNI') }}</span>
                    <span class="text-xs text-gray-500 mt-2 block">{{ __('Requiere frente y reverso') }}</span>
                </button>
                <button @click="selectDocumentType('pasaporte')" class="flex-1 p-6 border-2 rounded-2xl transition-all hover:border-[#008f39] dark:hover:border-emerald-500" :class="form.document_type === 'pasaporte' ? 'border-[#008f39] bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-200 dark:border-zinc-700'">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="font-bold block text-lg">{{ __('Pasaporte') }}</span>
                    <span class="text-xs text-gray-500 mt-2 block">{{ __('Requiere solapa de datos') }}</span>
                </button>
            </div>
            <div class="mt-10 flex justify-between max-w-md mx-auto">
                <button @click="currentStep = 'intro'" class="text-gray-500 hover:text-gray-800 font-bold py-2 px-4 transition-colors">{{ __('Volver') }}</button>
                <button @click="startCameraFlow" :disabled="!form.document_type" class="bg-gray-900 hover:bg-black text-white px-8 py-3 rounded-xl font-bold transition-all disabled:opacity-50">
                    {{ __('Continuar') }}
                </button>
            </div>
        </div>

        <!-- Camera Step -->
        <div v-show="currentStep === 'camera'" class="p-6 md:p-8 bg-zinc-900 text-white rounded-3xl m-2 relative min-h-[500px] flex flex-col">
            <div class="mb-4 text-center">
                <h3 class="text-xl font-black mb-1 capitalize">{{ cameraInstructionTitle }}</h3>
                <p class="text-zinc-400 text-sm">{{ cameraInstructionDesc }}</p>
            </div>
            
            <div class="flex-1 relative bg-black rounded-2xl overflow-hidden shadow-2xl flex items-center justify-center">
                <video v-if="!cameraCapturedImage" ref="videoEl" class="w-full h-full object-cover" autoplay playsinline></video>
                <img v-else :src="cameraCapturedImage" class="w-full h-full object-contain bg-black">
                
                <!-- Camera Overlay Frame -->
                <div v-if="!cameraCapturedImage" class="absolute inset-0 pointer-events-none border-4 border-zinc-700/50 m-6 rounded-xl box-border" :class="{'border-dashed border-white': isSelfieStage}"></div>
                
                <!-- Loading State waiting for Camera -->
                <div v-if="!cameraActive && !cameraCapturedImage && !cameraError" class="absolute inset-0 flex items-center justify-center bg-black/80 z-10">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
                </div>
                
                <!-- Error State -->
                <div v-if="cameraError" class="absolute inset-0 flex items-center justify-center bg-black/90 z-20 p-6 text-center">
                    <div>
                        <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-white">{{ cameraError }}</p>
                        <button @click="initCamera" class="mt-4 bg-white text-black px-6 py-2 rounded-lg font-bold">{{ __('Reintentar') }}</button>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="mt-6 flex justify-center gap-4">
                <template v-if="!cameraCapturedImage">
                    <button @click="takePhoto" :disabled="!cameraActive" class="w-16 h-16 rounded-full bg-white border-4 border-zinc-300 hover:bg-gray-200 transition-colors disabled:opacity-50 shadow-lg flex items-center justify-center">
                        <div class="w-12 h-12 rounded-full border border-gray-300"></div>
                    </button>
                </template>
                <template v-else>
                    <button @click="retakePhoto" class="px-6 py-3 rounded-xl font-bold bg-zinc-800 text-white hover:bg-zinc-700 transition">
                        {{ __('Tomar de nuevo') }}
                    </button>
                    <button @click="acceptPhoto" class="px-6 py-3 rounded-xl font-bold bg-[#008f39] text-white hover:bg-emerald-600 shadow-emerald-900/50 shadow-lg transition">
                        {{ __('Usar esta foto') }}
                    </button>
                </template>
            </div>
            
            <!-- Close Camera Flow -->
            <button @click="cancelCameraFlow" class="absolute top-4 right-4 p-2 bg-black/50 hover:bg-black rounded-full transition-colors text-white z-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Step 4: Final Submit -->
        <div v-if="currentStep === 'submit'" class="p-8 md:p-12 text-center">
            <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/20 text-[#008f39] flex items-center justify-center rounded-full mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl font-black mb-4">{{ __('Todo listo') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">{{ __('Tus imágenes han sido procesadas de forma segura de forma local y listas para enviarse.') }}</p>
            
            <div class="flex flex-wrap gap-4 justify-center mb-10">
                <div v-if="previewImages.document_front" class="w-24 h-24 rounded-lg overflow-hidden border">
                    <img :src="previewImages.document_front" class="w-full h-full object-cover">
                </div>
                <div v-if="previewImages.document_back" class="w-24 h-24 rounded-lg overflow-hidden border">
                    <img :src="previewImages.document_back" class="w-full h-full object-cover">
                </div>
                <div v-if="previewImages.selfie" class="w-24 h-24 rounded-lg overflow-hidden border">
                    <img :src="previewImages.selfie" class="w-full h-full object-cover">
                </div>
            </div>

            <button @click="submitVerification" :disabled="form.processing" class="bg-[#008f39] hover:bg-emerald-600 text-white font-bold py-4 px-12 rounded-full transition-colors shadow-lg disabled:opacity-75 flex items-center justify-center mx-auto min-w-[200px]">
                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? __('Enviando...') : __('Enviar solicitud') }}
            </button>
        </div>

      </div>
    </div>
  </ProfileLayout>
</template>

<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import Compressor from 'compressorjs';
import { ref, computed, onBeforeUnmount } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

declare const __: any;
declare const route: any;

const props = defineProps<{
    verification: any;
}>();

const currentStep = ref('intro'); // intro, document_type, camera, submit
const cameraInstructionTitle = ref('');
const cameraInstructionDesc = ref('');
const requiredCaptureStages = ref<string[]>([]);
const currentCaptureStageIndex = ref(0);
const showFormOverride = ref(false);

// Video & Canvas Refs
const videoEl = ref<HTMLVideoElement | null>(null);
const cameraStream = ref<MediaStream | null>(null);
const cameraActive = ref(false);
const cameraError = ref('');
const cameraCapturedImage = ref<string | null>(null);

const form = useForm({
    document_type: '',
    document_front: null as File | null,
    document_back: null as File | null,
    selfie: null as File | null,
});

const previewImages = ref({
    document_front: null as string | null,
    document_back: null as string | null,
    selfie: null as string | null,
});

const isSelfieStage = computed(() => {
    return requiredCaptureStages.value[currentCaptureStageIndex.value] === 'selfie';
});

const statusTitle = computed(() => {
    if (!props.verification) return '';
    if (props.verification.status === 'pending') return 'Verificación en Revisión';
    if (props.verification.status === 'approved') return 'Identidad Verificada';
    if (props.verification.status === 'rejected') return 'Verificación Rechazada';
    return '';
});

const statusDescription = computed(() => {
    if (!props.verification) return '';
    if (props.verification.status === 'pending') return 'Hemos recibido tus documentos y nuestro equipo los está revisando. Esto puede llegar a tomar hasta 24 horas laborables.';
    if (props.verification.status === 'approved') return 'Tu identidad ha sido verificada con éxito. Ya puedes disfrutar de todos los beneficios de una cuenta verificada.';
    if (props.verification.status === 'rejected') return 'Lamentablemente no pudimos verificar tu identidad con los documentos provistos. Por favor, asegúrate que sean legibles y oficiales.';
    return '';
});

const selectDocumentType = (type: string) => {
    form.document_type = type;
};

const startCameraFlow = () => {
    if (form.document_type === 'ci_dni') {
        requiredCaptureStages.value = ['document_front', 'document_back', 'selfie'];
    } else {
        requiredCaptureStages.value = ['document_front', 'selfie'];
    }
    currentCaptureStageIndex.value = 0;
    currentStep.value = 'camera';
    updateCameraInstructions();
    initCamera();
};

const updateCameraInstructions = () => {
    const stage = requiredCaptureStages.value[currentCaptureStageIndex.value];
    if (stage === 'document_front') {
        cameraInstructionTitle.value = 'Anverso del documento';
        cameraInstructionDesc.value = 'Asegúrate de que haya buena iluminación y sin destellos';
    } else if (stage === 'document_back') {
        cameraInstructionTitle.value = 'Reverso del documento';
        cameraInstructionDesc.value = 'Asegúrate de que tus datos sean legibles';
    } else if (stage === 'selfie') {
        cameraInstructionTitle.value = 'Toma de Rostro con Documento';
        cameraInstructionDesc.value = 'Sostén tu documento a la altura de tu rostro, cuidando que ambos sean claramente legibles y estén bien iluminados.';
    }
};

const initCamera = async () => {
    cameraError.value = '';
    cameraActive.value = false;
    cameraCapturedImage.value = null;

    stopCamera(); // Stop any pending streams

    try {
        // Decide facing mode based on stage
        const isSelfie = isSelfieStage.value;
        const facingMode = isSelfie ? 'user' : 'environment';

        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: facingMode,
                width: { ideal: 1920 },
                height: { ideal: 1080 }
            },
            audio: false
        });

        if (videoEl.value) {
            videoEl.value.srcObject = stream;
            cameraStream.value = stream;
            
            // Wait for video to be ready to play
            videoEl.value.onloadedmetadata = () => {
                videoEl.value?.play();
                cameraActive.value = true;
            };
        }
    } catch (err: any) {
        console.error('Camera Error:', err);
        cameraError.value = 'No pudimos acceder a la cámara. Por favor verifica tus permisos e intenta de nuevo.';
    }
};

const takePhoto = () => {
    if (!videoEl.value || !cameraActive.value) return;

    const canvas = document.createElement('canvas');
    canvas.width = videoEl.value.videoWidth;
    canvas.height = videoEl.value.videoHeight;
    const ctx = canvas.getContext('2d');
    
    if (ctx) {
        ctx.drawImage(videoEl.value, 0, 0, canvas.width, canvas.height);
        // Get high quality base64 preview
        cameraCapturedImage.value = canvas.toDataURL('image/jpeg', 0.9);
    }
};

const retakePhoto = () => {
    cameraCapturedImage.value = null;
};

// Convert base64 to File blob and compress
const compressAndStoreImage = (dataUrl: string, stage: string): Promise<void> => {
    return new Promise((resolve, reject) => {
        
        // Convert base64 to blob
        fetch(dataUrl)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], `capture_${stage}.jpg`, { type: 'image/jpeg' });
                
                new Compressor(file, {
                    quality: 0.6,
                    maxWidth: 1600,
                    success(result) {
                        const optimizedFile = new File([result], file.name, { type: result.type });
                        
                        // Use base64 for preview and Form payload (simpler to handle in Laravel via Request File unless we use Inertia's File object)
                        // Actually, Inertia forms handle File objects perfectly natively!
                        
                        if (stage === 'document_front') {
                            form.document_front = optimizedFile;
                        } else if (stage === 'document_back') {
                            form.document_back = optimizedFile;
                        } else if (stage === 'selfie') {
                            form.selfie = optimizedFile;
                        }

                        resolve();
                    },
                    error(err) {
                        console.error('Compression error:', err.message);
                        reject(err);
                    },
                });
            })
            .catch(reject);
    });
};

const acceptPhoto = async () => {
    if (!cameraCapturedImage.value) return;
    
    const stage = requiredCaptureStages.value[currentCaptureStageIndex.value];
    
    try {
        await compressAndStoreImage(cameraCapturedImage.value, stage);
        
        // Move to next stage or finish
        if (currentCaptureStageIndex.value < requiredCaptureStages.value.length - 1) {
            // Re-init camera with potential facingMode switch automatically handled
            currentCaptureStageIndex.value++;
            updateCameraInstructions();
            initCamera(); 
        } else {
            // Done capturing
            stopCamera();
            currentStep.value = 'submit';
            
            // Generate visual previews since form variables are now File objects
            if (form.document_front instanceof File) previewImages.value.document_front = URL.createObjectURL(form.document_front);
            if (form.document_back instanceof File) previewImages.value.document_back = URL.createObjectURL(form.document_back);
            if (form.selfie instanceof File) previewImages.value.selfie = URL.createObjectURL(form.selfie);
        }
    } catch {
        alert('Error optimizando imagen. Intente de nuevo.');
    }
};

const stopCamera = () => {
    if (cameraStream.value) {
        cameraStream.value.getTracks().forEach(track => track.stop());
        cameraStream.value = null;
    }
};

const cancelCameraFlow = () => {
    stopCamera();
    currentStep.value = 'document_type';
};

const resetForm = () => {
    form.reset();
    previewImages.value = {
        document_front: null,
        document_back: null,
        selfie: null,
    };
    currentStep.value = 'intro';
    showFormOverride.value = true;
};

const submitVerification = () => {
    form.post('/profile/identity-verification', {
        preserveScroll: true,
        onSuccess: () => {
            // Props will refresh from controller
        }
    });
};

onBeforeUnmount(() => {
    stopCamera();
});
</script>
