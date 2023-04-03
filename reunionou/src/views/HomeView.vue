<script setup lang="ts">
import { useUserStore } from '@/stores/user'
const user = useUserStore()
</script>
<template>
  
  <main>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center pt-14">Bienvenue sur REUNIONOU</h1>

    <div class="grid grid-cols-2 gap-4 place-content-center h-screen pb-28">

      <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 h-80">
        <h5 class="mb-10 text-2xl font-bold text-gray-900 dark:text-white">Vous possedez un compte ?</h5>
          <p class="mb-12 font-normal text-gray-700 dark:text-gray-400">Si vous possedez un compte vous pouvez dès à présent vous connecter en cliquant ci-dessous</p>
          <router-link
              v-if="!user.isConnected"
              :to="{ name: 'login' }"
              active-class="bg-cpurple text-cwhite hover:text-cwhite"
              class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-cpurple rounded-lg hover:bg-[#9a69fe] focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              >Se connecter
              <svg aria-hidden="true" class="w-6 h-6 ml-4 -mr-2" fill="currentColor" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
          </router-link>
        </div>

      <div class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 h-80">
        <h5 class="mb-10 text-2xl font-bold text-gray-900 dark:text-white">Vous n'êtes pas encore inscrit ?</h5>  
          <p class="mb-12 font-normal text-gray-700 dark:text-gray-400">Si vous n'êtes pas inscrit, vous pouvez vous créer un compte gratuitement en cliquant ci-dessous</p>
          <RouterLink
            v-if="!user.isConnected"
            :to="{ name: 'register' }"
            active-class="bg-cpurple text-cwhite hover:text-cwhite"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-cpurple rounded-lg hover:bg-[#9a69fe] focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          >Créer un compte
          <svg aria-hidden="true" class="w-6 h-6 ml-4 -mr-2" fill="currentColor" viewBox="0 0 30 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </RouterLink>
      </div>
    </div>
  </main>
</template>

<style>
/* @media (min-width: 1024px) {
  main {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
} */
</style>
