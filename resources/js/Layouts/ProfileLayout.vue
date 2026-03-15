<template>
  <div class="min-h-screen bg-[#f5f5f5] dark:bg-zinc-900 text-gray-900 dark:text-white font-sans">
    
    <!-- Top Navigation Bar -->
    <header class="bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700 shadow-sm sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          
          <!-- Logo & Back to Home -->
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

          <!-- Right side actions -->
          <div class="flex items-center gap-1 sm:gap-2">

            <!-- Divisor -->
            <div class="w-px h-6 bg-gray-200 dark:bg-zinc-700 mx-1 hidden sm:block"></div>

            <!-- Language Selector -->
            <div class="relative">
              <button @click="isLangMenuOpen = !isLangMenuOpen" class="flex items-center gap-1 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                <span class="text-xs font-semibold uppercase hidden sm:block">{{ currentLocale }}</span>
              </button>

              <!-- Language Dropdown Menu -->
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

            <!-- Dark Mode Toggle -->
            <button @click="toggleDarkMode" class="p-2 ml-1 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
               <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
               <svg v-else class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>

            <!-- Logout -->
            <Link href="/logout" method="post" as="button" class="hidden md:flex ml-2 text-sm font-medium text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition-colors items-center gap-1 px-2 py-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              <span>{{ __('Log Out') }}</span>
            </Link>

            <!-- Mobile Menu Toggle -->
            <button @click="isMobileMenuOpen = true" class="md:hidden p-2 ml-1 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Profile Content -->
    <main class="max-w-7xl mx-auto pt-10 pb-24 md:pb-10 px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 lg:gap-8">
        
        <!-- Left Sidebar / Navigation -->
        <div class="md:col-span-1 space-y-6">
          
          <!-- User Card -->
          <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-6 flex flex-col items-center text-center">
            <div class="relative mb-4">
              <!-- Avatar -->
              <div class="w-24 h-24 rounded-full bg-linear-to-br from-[#008f39] to-emerald-300 flex items-center justify-center text-white text-3xl font-bold uppercase shadow-lg border-4 border-white dark:border-zinc-800">
                {{ $page.props.auth.user.name.charAt(0) }}
              </div>
              <button class="absolute bottom-0 right-0 bg-white dark:bg-zinc-700 text-gray-600 dark:text-gray-300 p-1.5 rounded-full shadow border border-gray-200 dark:border-zinc-600 hover:text-[#008f39] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
              </button>
            </div>
            
            <h2 class="text-xl font-bold">{{ $page.props.auth.user.name }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $page.props.auth.user.email }}</p>
            
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              {{ __('Active Account') }}
            </span>
          </div>

          <!-- Role Switcher (Visible only if user is publisher) -->
          <div v-if="isPublisher" class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-4">
             <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Active Mode') }}</span>
             </div>
             <div class="flex items-center gap-3">
               <span class="text-sm font-semibold transition-colors" :class="activeProfileMode === 'visitor' ? 'text-gray-900 dark:text-white' : 'text-gray-400'">{{ __('Comprador') }}</span>
               
               <button @click="toggleProfileMode(activeProfileMode === 'visitor' ? 'publisher' : 'visitor')" type="button" class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#008f39] focus:ring-offset-2" :class="activeProfileMode === 'publisher' ? 'bg-[#008f39]' : 'bg-gray-300 dark:bg-zinc-600'">
                 <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="activeProfileMode === 'publisher' ? 'translate-x-5' : 'translate-x-0'"></span>
               </button>

               <span class="text-sm font-semibold transition-colors" :class="activeProfileMode === 'publisher' ? 'text-[#008f39]' : 'text-gray-400'">{{ __('Propietario') }}</span>
             </div>
          </div>

          <!-- Desktop Section Navigation Menu -->
          <nav class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-2 hidden md:block">
            
            <!-- Visitor Menu -->
            <div v-show="activeProfileMode === 'visitor'" class="space-y-1">
              <Link href="/profile" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url === '/profile' ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('My Account') }}
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

            <!-- Publisher Menu -->
            <div v-show="activeProfileMode === 'publisher'" class="space-y-1">
               <span class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2 block">{{ __('Publisher Hub') }}</span>
               
               <Link href="/publisher/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium text-sm" :class="$page.url.startsWith('/publisher/dashboard') ? 'bg-emerald-50 text-[#008f39] dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-zinc-700/50'">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                 {{ __('Dashboard') }}
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

        <!-- Right Content Area (Injected from pages) -->
        <div class="md:col-span-3">
          <slot />
        </div>

      </div>
    </main>

    <!-- Mobile Menu Drawer (App-like full screen sliding menu) -->
    <transition
      enter-active-class="transition ease-in-out duration-300 transform"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition ease-in-out duration-300 transform"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div v-show="isMobileMenuOpen" class="md:hidden fixed inset-0 z-50 bg-white dark:bg-zinc-900 flex flex-col h-[100dvh]">
        <!-- Header with close button -->
        <div class="h-16 flex justify-between items-center px-6 border-b border-gray-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm shrink-0">
          <span class="font-bold text-lg text-[#008f39]">{{ __('Menu') }}</span>
          <button @click="isMobileMenuOpen = false" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-8 flex flex-col gap-8 bg-gray-50/50 dark:bg-zinc-900/50">
          
          <!-- Role Switcher in Mobile -->
          <div v-if="isPublisher" class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700 p-5 shrink-0">
             <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Active Mode') }}</span>
             </div>
             <div class="flex items-center justify-between">
               <span class="text-base font-semibold transition-colors" :class="activeProfileMode === 'visitor' ? 'text-gray-900 dark:text-white' : 'text-gray-400'">{{ __('Comprador') }}</span>
               
               <button @click="toggleProfileMode(activeProfileMode === 'visitor' ? 'publisher' : 'visitor')" type="button" class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#008f39] focus:ring-offset-2" :class="activeProfileMode === 'publisher' ? 'bg-[#008f39]' : 'bg-gray-300 dark:bg-zinc-600'">
                 <span class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="activeProfileMode === 'publisher' ? 'translate-x-6' : 'translate-x-0'"></span>
               </button>

               <span class="text-base font-semibold transition-colors" :class="activeProfileMode === 'publisher' ? 'text-[#008f39]' : 'text-gray-400'">{{ __('Propietario') }}</span>
             </div>
          </div>

          <!-- Visitor Mobile Links -->
          <nav v-if="activeProfileMode === 'visitor'" class="flex flex-col gap-3 shrink-0">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 px-2">{{ __('Visitor Options') }}</span>
            <Link @click="isMobileMenuOpen = false" href="/profile" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url === '/profile' ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('My Account') }}</span>
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

          <!-- Publisher Mobile Links -->
          <nav v-if="activeProfileMode === 'publisher'" class="flex flex-col gap-3 shrink-0">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 px-2">{{ __('Publisher Hub') }}</span>
            <Link @click="isMobileMenuOpen = false" href="/publisher/dashboard" class="flex items-center gap-4 p-4 rounded-2xl shadow-sm border transition-colors" :class="$page.url.startsWith('/publisher/dashboard') ? 'bg-emerald-50 border-emerald-100 text-[#008f39] dark:bg-emerald-900/20 dark:border-emerald-800/30 dark:text-emerald-400' : 'bg-white border-gray-100 text-gray-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-700'">
              <div class="p-2 bg-white dark:bg-zinc-700 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
              <span class="font-bold text-lg">{{ __('Dashboard') }}</span>
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
        
        <!-- Mobile Drawer Logout -->
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

<script setup lang="js">
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';

const page = usePage();
const currentLocale = computed(() => page.props.locale || 'es');

const isLangMenuOpen = ref(false);
const isDark = ref(false);

// Role Switcher State
const isPublisher = true;
// Determine initial mode based on current URL
const initialMode = page.url.startsWith('/publisher') ? 'publisher' : 'visitor';
const activeProfileMode = ref(initialMode);
const isMobileMenuOpen = ref(false);

const toggleProfileMode = (mode) => {
  activeProfileMode.value = mode;
  isMobileMenuOpen.value = false;
  if (mode === 'publisher') {
    router.visit('/publisher/dashboard');
  } else {
    router.visit('/profile');
  }
};

const changeLanguage = (lang) => {
  isLangMenuOpen.value = false;
  router.post('/language', { locale: lang }, {
    preserveScroll: true,
    preserveState: false, // Force a hard refresh of Inertia state
  });
};

const closeDropdowns = (e) => {
  if (isLangMenuOpen.value && !e.target.closest('.relative')) {
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
