<template>
  <div class="min-h-screen bg-[#f5f5f5] dark:bg-zinc-900 text-gray-900 dark:text-white font-sans">
    
    <header class="bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700 shadow-sm sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          
          <div class="flex items-center gap-4">
            <Link href="/" class="hover:opacity-80 transition-opacity">
              <div class="flex items-center gap-2">
                <div class="bg-[#008f39] p-1.5 rounded-lg">
                  <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <span class="font-bold text-lg hidden sm:block text-[#008f39]">Vive Aquí</span>
              </div>
            </Link>
          </div>

          <div class="flex items-center gap-1 sm:gap-2">
            <div class="hidden md:flex items-center bg-gray-100 dark:bg-zinc-900 p-1 rounded-full border border-gray-200 dark:border-zinc-700 mx-2">
              <button 
                @click="toggleProfileMode('visitor')" 
                :class="activeProfileMode === 'visitor' ? 'bg-white dark:bg-zinc-700 shadow-sm text-[#008f39] dark:text-emerald-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" 
                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200"
              >
                {{ __('Explorar') }}
              </button>
              <button 
                @click="toggleProfileMode('publisher')" 
                :class="activeProfileMode === 'publisher' ? 'bg-white dark:bg-zinc-700 shadow-sm text-[#008f39] dark:text-emerald-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" 
                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200"
              >
                {{ __('Mis Anuncios') }}
              </button>
            </div>

            <div class="w-px h-6 bg-gray-200 dark:bg-zinc-700 mx-1 hidden sm:block"></div>

            <div class="relative">
              <button @click="isLangMenuOpen = !isLangMenuOpen" class="flex items-center gap-1 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                <span class="text-xs font-semibold uppercase hidden sm:block">{{ currentLocale }}</span>
              </button>

              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div v-if="isLangMenuOpen" class="absolute right-0 mt-2 w-32 rounded-xl shadow-lg bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5 dark:ring-white/10 overflow-hidden text-sm z-50">
                  <div class="py-1">
                    <button @click="changeLanguage('es')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'es', 'text-gray-700 dark:text-gray-300': currentLocale !== 'es'}">
                      Español <span v-if="currentLocale === 'es'">✓</span>
                    </button>
                    <button @click="changeLanguage('en')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'en', 'text-gray-700 dark:text-gray-300': currentLocale !== 'en'}">
                      English <span v-if="currentLocale === 'en'">✓</span>
                    </button>
                    <button @click="changeLanguage('pt')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'pt', 'text-gray-700 dark:text-gray-300': currentLocale !== 'pt'}">
                      Português <span v-if="currentLocale === 'pt'">✓</span>
                    </button>
                  </div>
                </div>
              </transition>
            </div>

            <button @click="toggleDarkMode" class="p-2 ml-1 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
               <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
               <svg v-else class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>

            <Link href="/logout" method="post" as="button" class="hidden md:flex ml-2 text-sm font-medium text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition-colors items-center gap-1 px-2 py-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              <span>{{ __('Log Out') }}</span>
            </Link>

            <button @click="isMobileMenuOpen = true" class="md:hidden p-2 ml-1 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto pt-10 pb-24 md:pb-10 px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 lg:gap-8">
        
        <div class="md:col-span-1 space-y-6">
          
          <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-6 flex-col items-center text-center" :class="$page.url === '/profile' ? 'flex' : 'hidden md:flex'">
            <div class="relative mb-4">
              <div v-if="$page.props.auth.avatar_url" class="w-24 h-24 rounded-full border-4 border-white dark:border-zinc-800 shadow-lg overflow-hidden flex items-center justify-center bg-gray-100 dark:bg-zinc-800">
                <img :src="$page.props.auth.avatar_url" class="w-full h-full object-cover">
              </div>
              <div v-else class="w-24 h-24 rounded-full bg-linear-to-br from-[#008f39] to-emerald-300 flex items-center justify-center text-white text-3xl font-bold uppercase shadow-lg border-4 border-white dark:border-zinc-800">
                {{ $page.props.auth.user.name.charAt(0) }}
              </div>
            </div>
            
            <h2 class="text-xl font-bold">{{ $page.props.auth.user.name }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $page.props.auth.user.email }}</p>
            
            <span v-if="$page.props.auth.user.is_verified" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              {{ __('Verificado') }}
            </span>
            <span v-else class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-gray-300">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
              {{ __('No Verificado') }}
            </span>
          </div>

          <nav class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-2 hidden md:block">
            
            <div v-show="activeProfileMode === 'visitor'" class="space-y-1">
              <span class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2 block">{{ __('My Account') }}</span>
              <Link href="/profile" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url === '/profile' ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('My Account') }}
              </Link>
              <Link href="/profile/identity-verification" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/profile/identity-verification') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                {{ __('Validación de identidad') }}
              </Link>
              <Link href="/profile/favorites" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/profile/favorites') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                {{ __('My Favorites') }}
              </Link>
              <Link href="/profile/security" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/profile/security') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                {{ __('Security') }}
              </Link>
            </div>

            <div v-show="activeProfileMode === 'publisher'" class="space-y-1">
               <span class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2 block">{{ __('Publisher Hub') }}</span>
               
               <Link href="/publisher/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/publisher/dashboard') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                 {{ __('Dashboard') }}
               </Link>

               <Link href="/publisher/profile" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/publisher/profile') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                 {{ __('Public Profile') }}
               </Link>

               <Link href="/publisher/leads" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/publisher/leads') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                 {{ __('Leads & Contactos') }}
               </Link>

               <Link href="/publisher/properties" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/publisher/properties') && ! $page.url.includes('/create') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                 {{ __('My Properties') }}
               </Link>

               <Link href="/publisher/properties/create" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/publisher/properties/create') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                 {{ __('Publish Property') }}
               </Link>
            </div>
          </nav>
        </div>

        <div class="md:col-span-3">
          <slot />
        </div>

      </div>
    </main>

    <transition
      enter-active-class="transition ease-in-out duration-300 transform"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition ease-in-out duration-300 transform"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div v-show="isMobileMenuOpen" class="md:hidden fixed inset-0 z-50 bg-white dark:bg-zinc-900 flex flex-col h-[100dvh]">
        <div class="h-16 flex justify-between items-center px-6 border-b border-gray-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm shrink-0">
          <span class="font-bold text-lg text-[#008f39]">{{ __('Menu') }}</span>
          <button @click="isMobileMenuOpen = false" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-8 flex flex-col gap-6 bg-gray-50/50 dark:bg-zinc-900/50">

          <div class="bg-gray-100 dark:bg-zinc-800 p-1.5 rounded-2xl flex items-center justify-between shadow-inner shrink-0">
              <button 
                @click="toggleProfileMode('visitor', true)" 
                :class="activeProfileMode === 'visitor' ? 'bg-white dark:bg-zinc-700 shadow-sm text-[#008f39] dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'" 
                class="w-1/2 py-3 rounded-xl text-sm font-bold transition-all duration-200"
              >
                {{ __('Explorar') }}
              </button>
              <button 
                @click="toggleProfileMode('publisher', true)" 
                :class="activeProfileMode === 'publisher' ? 'bg-white dark:bg-zinc-700 shadow-sm text-[#008f39] dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400'" 
                class="w-1/2 py-3 rounded-xl text-sm font-bold transition-all duration-200"
              >
                {{ __('Mis Anuncios') }}
              </button>
          </div>

          <nav v-show="activeProfileMode === 'visitor'" class="flex flex-col gap-3 shrink-0">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 px-2">{{ __('My Account') }}</span>
            <Link @click="isMobileMenuOpen = false" href="/profile" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url === '/profile' ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('My Account') }}</span>
            </Link>
            <Link @click="isMobileMenuOpen = false" href="/profile/identity-verification" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/profile/identity-verification') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('Validación de identidad') }}</span>
            </Link>
            <Link @click="isMobileMenuOpen = false" href="/profile/favorites" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/profile/favorites') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('My Favorites') }}</span>
            </Link>
            <Link @click="isMobileMenuOpen = false" href="/profile/security" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/profile/security') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('Security') }}</span>
            </Link>
          </nav>

          <nav v-show="activeProfileMode === 'publisher'" class="flex flex-col gap-3 shrink-0">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 px-2">{{ __('Publisher Hub') }}</span>
            <Link @click="isMobileMenuOpen = false" href="/publisher/dashboard" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/publisher/dashboard') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('Dashboard') }}</span>
            </Link>
            <Link @click="isMobileMenuOpen = false" href="/publisher/profile" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/publisher/profile') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('Public Profile') }}</span>
            </Link>
            
            <Link @click="isMobileMenuOpen = false" href="/publisher/leads" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/publisher/leads') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('Leads & Contactos') }}</span>
            </Link>
            <Link @click="isMobileMenuOpen = false" href="/publisher/properties" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/publisher/properties') && ! $page.url.includes('/create') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg></div>
              <span class="font-bold text-lg">{{ __('My Properties') }}</span>
            </Link>
            <Link @click="isMobileMenuOpen = false" href="/publisher/properties/create" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/publisher/properties/create') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
              <span class="font-bold text-lg">{{ __('Publish Property') }}</span>
            </Link>
          </nav>
        </div>
        
        <div class="p-6 bg-white dark:bg-zinc-900 border-t border-gray-100 dark:border-zinc-800 shrink-0">
            <Link @click="isMobileMenuOpen = false" href="/logout" method="post" as="button" class="flex items-center justify-center gap-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 font-bold w-full py-4 rounded-xl border border-red-100 dark:border-red-900/30 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              {{ __('Log Out') }}
            </Link>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';

declare const __: any;

const page = usePage();
const currentLocale = computed(() => page.props.locale || 'es');

const isLangMenuOpen = ref(false);
const isDark = ref(false);

// Role Switcher State
const initialMode = page.url.startsWith('/publisher') ? 'publisher' : 'visitor';
const activeProfileMode = ref<string>(initialMode);
const isMobileMenuOpen = ref(false);

const toggleProfileMode = (mode: string, isFromMobile: boolean = false) => {
  activeProfileMode.value = mode;
  if (!isFromMobile) {
    isMobileMenuOpen.value = false;
  }
  const destinationUrl = mode === 'publisher' ? '/publisher/dashboard' : '/profile';
  router.visit(destinationUrl, {
    preserveState: true,
    preserveScroll: true
  });
};

const changeLanguage = (lang: string) => {
  isLangMenuOpen.value = false;
  router.post('/language', { locale: lang }, {
    preserveScroll: true,
    preserveState: false, 
  });
};

const closeDropdowns = (e: Event) => {
  if (isLangMenuOpen.value && !(e.target as Element).closest('.relative')) {
    isLangMenuOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropdowns);
  // Check localStorage or initial class
  isDark.value = document.documentElement.classList.contains('dark');
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns);
});

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};
</script>