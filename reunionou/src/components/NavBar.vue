<script lang="ts" setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { useUserStore } from '@/stores/user'
import NavBarButton from '../components/NavBarButton.vue'
const user = useUserStore()

const showMobileMenu = ref(false)
const showUserMenu = ref(false)

const listenClick = (e: MouseEvent) => {
  const target = e.target as HTMLInputElement

  if (target && target.id !== 'profile-dropdown' && showUserMenu.value == true) {
    showUserMenu.value = false
  }
}

const avatarUrl = ref(
  user.isConnected ? `${import.meta.env.VITE_API_HOST}/avatars/${user.member.id}/50/50` : ''
)

onMounted(() => {
  window.addEventListener('click', listenClick)
})

onUnmounted(() => {
  window.removeEventListener('click', listenClick)
})
</script>

<template>
  <nav class="bg-cblack">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 relative">
      <div class="relative flex h-16 items-center justify-between">
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <!-- Mobile menu button-->
          <button
            @click="showMobileMenu = !showMobileMenu"
            type="button"
            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
            aria-controls="mobile-menu"
            aria-expanded="false"
          >
            <span class="sr-only">Open main menu</span>
            <!--
              Icon when menu is closed.
  
              Menu open: "hidden", Menu closed: "block"
            -->
            <svg
              class="block h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
              />
            </svg>
            <!--
              Icon when menu is open.
  
              Menu open: "block", Menu closed: "hidden"
            -->
            <svg
              class="hidden h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
          <div class="flex flex-shrink-0 items-center">
            <img class="block h-8 w-auto lg:hidden" src="/images/logo.svg" alt="Your Company" />
            <img class="hidden h-8 w-auto lg:block" src="/images/logo.svg" alt="Your Company" />
          </div>
          <div class="hidden sm:ml-6 sm:block">
            <div class="flex space-x-4">
              <router-link
                v-if="!user.isConnected"
                :to="{ name: 'home' }"
                active-class="bg-cpurple text-cwhite hover:text-cwhite"
                class="text-cwhite hover:text-[#9a69fe] rounded-md px-3 py-2 text-sm font-medium"
                >Accueil</router-link
              >

              <router-link
                v-if="user.isConnected"
                :key="$route.fullPath"
                :to="{ name: 'newEvent' }"
                active-class="bg-cpurple text-cwhite"
                class="text-cwhite hover:text-[#9a69fe] rounded-md px-3 py-2 text-sm font-medium"
                >Créer un évenement</router-link
              >

              <router-link
                v-if="user.isConnected"
                to="/events"
                active-class="bg-cpurple text-cwhite"
                class="text-cwhite hover:text-[#9a69fe] rounded-md px-3 py-2 text-sm font-medium"
                >Mes évenements</router-link
              >
            </div>
          </div>
        </div>
        <div class="hidden sm:ml-6 sm:block" v-if="!user.isConnected">
          <div class="flex space-x-4">
            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
            <router-link
              v-if="!user.isConnected"
              :to="{ name: 'login' }"
              active-class="bg-cpurple text-cwhite hover:text-cwhite"
              class="text-cwhite hover:text-[#9a69fe] rounded-md px-3 py-2 text-sm font-medium"
              >Connexion</router-link
            >

            <router-link
              v-if="!user.isConnected"
              :to="{ name: 'register' }"
              active-class="bg-cpurple text-cwhite hover:text-cwhite"
              class="text-cwhite hover:text-[#9a69fe] rounded-md px-3 py-2 text-sm font-medium"
              >Inscription</router-link
            >
          </div>
        </div>
        <div
          v-if="user.isConnected"
          class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0"
        >
          <button
            type="button"
            class="rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
          >
            <span class="sr-only">View notifications</span>
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
              />
            </svg>
          </button>

          <!-- Profile dropdown -->
          <div
            class="relative ml-3"
            @click="($event) => $event.stopPropagation()"
            id="profile-dropdown"
          >
            <div>
              <button
                @click="showUserMenu = !showUserMenu"
                type="button"
                class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 transition-all duration-300 hover:scale-110"
                id="user-menu-button"
                aria-expanded="false"
                aria-haspopup="true"
              >
                <span class="sr-only">Open user menu</span>
                <img class="h-8 w-8 rounded-full" :src="avatarUrl" alt="" />
              </button>
            </div>
            <div
              v-if="showUserMenu"
              class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-cwhite py-1 shadow-lg ring-1 ring-cblack ring-opacity-5 focus:outline-none text-cblack"
              role="menu"
              aria-orientation="vertical"
              aria-labelledby="user-menu-button"
              tabindex="-1"
            >
              <!-- Active: "bg-gray-100", Not Active: "" -->
              <router-link
                :to="{ name: 'user', params: { id: user.member.id } }"
                active-class="text-cpurple"
                class="block px-4 py-2 text-sm text-cblack"
                >Mon profile</router-link
              >

              <a
                @click="user.disconnect()"
                class="block px-4 py-2 text-sm text-cblack cursor-pointer"
                role="menuitem"
                tabindex="-1"
                id="user-menu-item-1"
                >Se deconnecter</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div
      class="absolute bg-cblack w-full sm:hidden z-[1000]"
      id="mobile-menu"
      v-if="showMobileMenu"
    >
      <div class="space-y-1 px-2 pb-3 pt-2 text-center">
        <router-link
          @click="showMobileMenu = false"
          v-if="!user.isConnected"
          :to="{ name: 'home' }"
          active-class="bg-cpurple text-cwhite hover:text-cwhite"
          class="text-cwhite hover:text-[#9a69fe] block rounded-md px-3 py-2 text-base font-medium"
          >Accueil</router-link
        >

        <router-link
          @click="showMobileMenu = false"
          v-if="user.isConnected"
          :to="{ name: 'newEvent' }"
          active-class="bg-cpurple text-cwhite"
          class="text-cwhite hover:text-[#9a69fe] block rounded-md px-3 py-2 text-base font-medium"
          >Créer un évenement</router-link
        >

        <router-link
          @click="showMobileMenu = false"
          v-if="user.isConnected"
          :to="{ name: 'events' }"
          active-class="bg-cpurple text-cwhite"
          class="text-cwhite hover:text-[#9a69fe] block rounded-md px-3 py-2 text-base font-medium"
          >Mes évenements</router-link
        >

        <router-link
          @click="showMobileMenu = false"
          v-if="!user.isConnected"
          :to="{ name: 'login' }"
          active-class="bg-cpurple text-cwhite"
          class="text-cwhite hover:text-[#9a69fe] block rounded-md px-3 py-2 text-base font-medium"
          >Connexion</router-link
        >

        <router-link
          @click="showMobileMenu = false"
          v-if="!user.isConnected"
          :to="{ name: 'register' }"
          active-class="bg-cpurple text-cwhite"
          class="text-cwhite hover:text-[#9a69fe] block rounded-md px-3 py-2 text-base font-medium"
          >Inscription</router-link
        >
      </div>
    </div>
  </nav>
</template>
