<script setup lang="ts">
import { useUserStore } from '@/stores/user'
import { reactive, ref } from 'vue'
import router from '@/router'
import axios from 'axios'

const user = useUserStore()
let log = reactive({
  username: '',
  firstname: '',
  lastname: '',
  email: ''
})
let password = reactive({
  password: '',
  passwordConfirmation: ''
})

const Register = async () => {
  error.value = ''
  if (
    password.password == '' ||
    password.passwordConfirmation == '' ||
    password.password !== password.passwordConfirmation ||
    log.email == '' ||
    log.firstname == '' ||
    log.lastname == '' ||
    log.username == ''
  ) {
    error.value = 'Veuillez remplir tous les champs'
  }

  try {
    const response = await axios.post(`${import.meta.env.VITE_API_HOST}/signup`, {
      username: log.username,
      firstname: log.firstname,
      lastname: log.lastname,
      email: log.email,
      password: password.password
    })

    if (response.status == 201) {
      router.push({ name: 'login' })
    }
  } catch (errorResponse: any) {
    console.log(error)
    if (errorResponse.response.status == 409) {
      error.value = "Adresse Email ou nom d'uilisateur déjà utilisé"
    }
  }
}

const error = ref('')
</script>

<template>
  <div class="register-view flex w-full justify-center">
    <div class="login-view mt-8 shadow-">
      <form
        class="bg-cwhite text-cblack flex flex-col h-auto justify-start items-center p-10 m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
      >
        <h1 class="text-3xl font-bold mb-8 text-center">S'inscrire</h1>
        <div class="mb-4">
          <label class="block text-white-700 font-bold mb-2" for="username"
            >Nom d'utilisateur</label
          >
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="text"
            placeholder="Username"
            v-model="log.username"
          />
        </div>
        <div class="mb-4">
          <label class="block text-white-700 font-bold mb-2" for="fistname">Prénom</label>
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="text"
            placeholder="Firstname"
            v-model="log.firstname"
          />
        </div>
        <div class="mb-4">
          <label class="block text-white-700 font-bold mb-2" for="lastname">Nom</label>
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="text"
            placeholder="Lastname"
            v-model="log.lastname"
          />
        </div>
        <div class="mb-4">
          <label class="block text-white-700 font-bold mb-2" for="email">Adresse email</label>
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="email"
            placeholder="Email"
            v-model="log.email"
          />
        </div>
        <div class="mb-6">
          <label class="block text-white-700 font-bold mb-2" for="password">Mot de passe</label>
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="password"
            placeholder="Password"
            v-model="password.password"
          />
        </div>
        <div class="mb-6">
          <label class="block text-white-700 font-bold mb-2" for="password"
            >Confirmation de mot de passe</label
          >
          <input
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
            type="password"
            placeholder="Password confirmation"
            v-model="password.passwordConfirmation"
          />
        </div>
        <div class="flex items-center justify-between gap-6">
          <button
            class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="button"
            @click="Register()"
          >
            S'inscrire
          </button>
          <router-link
            to="/login"
            class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
          >
            Déjà membre ?
          </router-link>
        </div>
        <span v-if="error" class="pt-5 text-cred text-center">{{ error }}</span>
      </form>
    </div>
  </div>
</template>

<style>
form {
  justify-content: center;
}

/* @media (min-width: 1024px) {
  .register-view {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
} */
</style>
