<script setup lang="ts">
import FormButton from '../components/FormButton.vue'
import { useUserStore } from '@/stores/user'
import { reactive } from 'vue'
const user = useUserStore()
let log = reactive({
  email: '',
  password: ''
})
</script>

<template>
  <div class="login-container flex w-full justify-center">
    <div class="login-view mt-8 shadow-">
      <form class="shadow-3xl rounded px-16 pt-16 pb-8 mb-8" type="login" onsubmit="return false"
        @submit="user.setConnected(log)" @keyup.enter="user.setConnected(log)">
        <h1 class="text-3xl font-bold mb-8 text-center">Se Connecter</h1>
        <div class="mb-4">
          <label class="block text-white-700 text-sm font-bold mb-2" for="username"> Username </label>
          <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            type="email" placeholder="Email" v-model="log.email" />
        </div>
        <div class="mb-6">
          <label class="block text-white-700 text-sm font-bold mb-2" for="password"> Password </label>
          <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            type="password" placeholder="Password" v-model="log.password" />
        </div>
        <div class="flex items-center justify-between">
          <FormButton :function="user.setConnected" :email=log.email :password=log.password></FormButton>
          <router-link to="/register"
            class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">S'inscrire ?
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<style>
.login-view {
  justify-content: center;
}

/* @media (min-width: 1024px) {
  .login-view {
     min-height: 100vh;
    display: flex;
    align-items: center;
  }
} */
</style>
