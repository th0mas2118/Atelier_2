<script lang="ts" setup>
import { ref } from 'vue'
import { useUserStore } from '@/stores/user'
import NavBarButton from '../components/NavBarButton.vue'
const user = useUserStore()

let showNav = false

</script>

<template>
  <header class="navbar">
    <nav class="flex items-center justify-between flex-wrap bg-cpurple p-6">
      <div class="flex items-center flex-shrink-0 text-white mr-6">
        <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z" />
        </svg>
        <span class="font-semibold text-xl tracking-tight">Tailwind CSS</span>
      </div>
      <div class="block lg:hidden">
        <button id="button-burger"
          class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white"
          @click="showNav = !showNav">
          <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <title>Menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
          </svg>
        </button>
        <nav v-show="showNav" class="burger-route flex flex-col nav bg-white hidden fixed top-0 left-0 w-full h-full">
          <router-link to="/">HOME</router-link>
          <router-link to="/about">ABOUT</router-link>
          <router-link v-if="!user.isConnected" to="/login">LOGIN</router-link>
          <router-link v-if="!user.isConnected" to="/register">REGISTER</router-link>
          <button v-if="user.isConnected" @click="user.disconnect()">LOGOUT</button>
        </nav>
      </div>
      <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto hidden lg:visible">
        <div class="text-sm lg:flex-grow">
          <router-link class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4" to="/">
            Home
          </router-link>
          <router-link class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4" to="/about">
            About
          </router-link>
        </div>
        <NavBarButton v-if="!user.isConnected" route='login' text="Se connecter"></NavBarButton>
        <NavBarButton v-if="!user.isConnected" route='register' text="S'inscrire"></NavBarButton>
        <NavBarButton v-if="user.isConnected" route='user' :user=user.member.id text="Profil"></NavBarButton>
        <div>
          <button v-if="user.isConnected"
            class="inline-block bg-white text-black text-sm px-4 py-2 leading-none border rounded  border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0"
            @click="user.disconnect()">Se déconnecter</button>
        </div>
      </div>
    </nav>
  </header>
</template>
