<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Home, Search, Heart, Bell, ShieldCheck, TrendingUp, Users, ArrowRight } from 'lucide-vue-next';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();
</script>

<template>
    <Head title="Sobre Nosotros | AlquilaVende.com" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-zinc-950 text-gray-900 dark:text-gray-100 font-sans selection:bg-[#008f39] selection:text-white transition-colors duration-300">
        <!-- Minimal Nav -->
        <nav class="absolute top-0 w-full z-50 px-6 py-4 flex justify-between items-center backdrop-blur-sm bg-black/10">
            <Link href="/" class="text-2xl font-black text-white tracking-tighter flex items-center gap-2 drop-shadow-md">
                <Home class="w-8 h-8 text-[#008f39]" />
                AlquilaVende<span class="text-[#008f39]">.</span>
            </Link>
            <div class="flex gap-4">
                <Link href="/" class="text-white hover:text-emerald-300 font-medium transition drop-shadow-md my-auto">Explorar</Link>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="relative min-h-[85vh] flex items-center justify-center overflow-hidden bg-zinc-900">
            <!-- Background Elements -->
            <div class="absolute inset-0 z-0 bg-black">
                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&q=80" alt="Mansion Background" class="w-full h-full object-cover opacity-50 scale-105 animate-[pulse_20s_ease-in-out_infinite]" />
                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-zinc-900/80 to-zinc-950"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#008f39]/20 via-transparent to-transparent"></div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-10 text-center px-6 max-w-5xl mx-auto space-y-8 animate-[fade-in-up_1s_ease-out_forwards]">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium text-sm mb-4">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    El futuro del sector inmobiliario en Bolivia
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold text-white tracking-tight leading-tight drop-shadow-lg">
                    Encuentra tu próximo <br/> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#008f39] to-emerald-300">
                        hogar soñado.
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-zinc-300 max-w-3xl mx-auto font-light leading-relaxed drop-shadow-md">
                    AlquilaVende.com revoluciona la forma de alquilar, comprar y vender propiedades. 
                    Conectamos personas con espacios increíbles de forma rápida, segura e inteligente.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
                    <Link href="/" class="group px-8 py-4 bg-[#008f39] hover:bg-emerald-600 text-white text-lg font-bold rounded-2xl transition-all shadow-[0_0_40px_-10px_rgba(0,143,57,0.5)] transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        Explorar Propiedades
                        <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                    </Link>
                    <Link href="/?auth=register" class="px-8 py-4 bg-white/5 hover:bg-white/10 backdrop-blur-md text-white text-lg font-bold border border-white/10 rounded-2xl transition-all transform hover:-translate-y-1 flex items-center justify-center">
                        Conviértete en Publicante
                    </Link>
                </div>
            </div>
        </header>

        <!-- Use Cases Split Section -->
        <section class="py-24 px-6 max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-4xl md:text-5xl font-bold mb-6 text-zinc-900 dark:text-white">Una plataforma, dos perspectivas</h2>
                <p class="text-xl text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto">Ya sea que busques tu nueva casa o quieras vender tu propiedad, tenemos las herramientas perfectas para ti.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Para Visitantes -->
                <div class="space-y-10 order-2 lg:order-1">
                    <div class="inline-block px-4 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full font-semibold text-sm tracking-wide">
                        PARA BUSCADORES
                    </div>
                    <h3 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white leading-tight">
                        Explora sin límites, <br />encuentra sin estrés.
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <Search class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-zinc-900 dark:text-white">Mapa Interactivo en Tiempo Real</h4>
                                <p class="text-zinc-600 dark:text-zinc-400">Navega por las diferentes zonas, dibuja áreas de interés y descubre propiedades exactamente donde quieres vivir.</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center text-rose-600 dark:text-rose-400">
                                <Heart class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-zinc-900 dark:text-white">Favoritos y Colecciones</h4>
                                <p class="text-zinc-600 dark:text-zinc-400">Guarda las propiedades que más te gustan, compáralas y toma la mejor decisión con tu familia.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                <Bell class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-zinc-900 dark:text-white">Alertas de Zonas</h4>
                                <p class="text-zinc-600 dark:text-zinc-400">Define tus zonas favoritas y recibe notificaciones instantáneas cuando una nueva propiedad esté disponible.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Imagen Visitante -->
                <div class="relative order-1 lg:order-2 h-[500px] rounded-3xl overflow-hidden shadow-2xl group border border-zinc-200 dark:border-zinc-800">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&q=80" alt="Explorar Propiedades" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/80 to-transparent mix-blend-overlay"></div>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px border-t border-dashed border-zinc-200 dark:border-zinc-800 my-24"></div>

            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Imagen Publicante -->
                <div class="relative h-[500px] rounded-3xl overflow-hidden shadow-2xl group border border-zinc-200 dark:border-zinc-800">
                    <img src="https://images.unsplash.com/photo-1556156653-e5a7c69cc263?auto=format&fit=crop&q=80" alt="Gestión Inmobiliaria" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                    <div class="absolute inset-0 bg-gradient-to-bl from-[#008f39]/80 to-transparent mix-blend-overlay"></div>
                </div>

                <!-- Para Publicantes -->
                <div class="space-y-10">
                    <div class="inline-block px-4 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-[#008f39] dark:text-emerald-400 rounded-full font-semibold text-sm tracking-wide">
                        PARA PUBLICANTES Y AGENTES
                    </div>
                    <h3 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white leading-tight">
                        Potencia tus ventas, <br />controla tu negocio.
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-[#008f39]">
                                <TrendingUp class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-zinc-900 dark:text-white">Estadísticas Detalladas</h4>
                                <p class="text-zinc-600 dark:text-zinc-400">Analiza vistas, clics de contacto e interacciones. Entiende qué propiedades atraen más y ajusta tu estrategia.</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <Users class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-zinc-900 dark:text-white">Gestor de Leads (CRM Integrado)</h4>
                                <p class="text-zinc-600 dark:text-zinc-400">No pierdas ningún cliente. Todos los interesados se guardan en tu panel para realizar seguimiento eficiente.</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                <ShieldCheck class="w-6 h-6" />
                            </div>
                            <div>
                                <h4 class="text-xl font-bold mb-2 text-zinc-900 dark:text-white">Validación de Identidad</h4>
                                <p class="text-zinc-600 dark:text-zinc-400">Otorgamos insignias de "Agente Verificado" mediante validación de CI/Pasaporte para generar máxima confianza.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-zinc-900 text-center px-6 border-t border-zinc-800/50">
            <div class="max-w-4xl mx-auto space-y-8">
                <h2 class="text-4xl md:text-6xl font-black text-white">¿Listo para dar el siguiente paso?</h2>
                <p class="text-xl text-zinc-400 max-w-2xl mx-auto">Únete a la plataforma inmobiliaria más avanzada del mercado. Miles de búsquedas te esperan.</p>
                <div class="pt-8">
                    <Link href="/?auth=register" class="inline-block px-12 py-5 bg-[#008f39] hover:bg-emerald-600 text-white text-xl font-bold rounded-2xl transition-all shadow-[0_0_40px_-10px_rgba(0,143,57,0.5)] transform hover:-translate-y-1">
                        Comenzar Gratis
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-zinc-950 py-12 text-center border-t border-zinc-900">
            <p class="text-zinc-500 font-medium tracking-wide">© {{ new Date().getFullYear() }} AlquilaVende.com. Todos los derechos reservados.</p>
        </footer>
    </div>
</template>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
