<script setup lang="ts">
import FormButton from '../components/FormButton.vue'
import { useUserStore } from '@/stores/user'
import { reactive, ref } from 'vue'
const user = useUserStore()
let log = reactive({
  email: '',
  password: ''
})

const error = ref('')
</script>

<template>
  <div class="login-container flex w-full justify-center">
    <div class="login-view mt-8 shadow-">
      <form
        class="bg-cwhite text-cblack flex flex-col justify-start items-center p-10 m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
        type="login"
        onsubmit="return false"
        @submit="user.setConnected(log)"
        @keyup.enter="user.setConnected(log)"
      >
        <h1 class="text-3xl font-bold mb-8 text-center">Connexion</h1>
        <div class="mb-4">
          <label class="block text-white-700 text-sm font-bold mb-2" for="username"> Email </label>
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="email"
            placeholder="Email"
            v-model="log.email"
          />
        </div>
        <div class="mb-6">
          <label class="block text-white-700 text-sm font-bold mb-2" for="password">
            Mot de passe
          </label>
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="password"
            placeholder="Password"
            v-model="log.password"
          />
        </div>
        <div class="flex items-center justify-between gap-5">
          <FormButton
            name="login"
            :function="user.setConnected"
            :email="log.email"
            :password="log.password"
          ></FormButton>
          <router-link
            to="/register"
            class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
            >S'inscrire ?
          </router-link>
        </div>
        <span v-if="!error" class="pt-5 text-cred">{{ error }}</span>
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
