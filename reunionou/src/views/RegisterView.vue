<script setup lang="ts">
import { useUserStore } from '@/stores/user'
import { reactive } from 'vue'
import router from '@/router'

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
  if (
    password.password !== password.passwordConfirmation ||
    log.email == '' ||
    log.firstname == '' ||
    log.lastname == '' ||
    log.username == ''
  ) {
    return
  }
  await fetch('http://api.frontoffice.reunionou:49383/signup', {
    method: 'POST',
    mode: 'cors',
    body: JSON.stringify({
      username: log.username,
      firstname: log.firstname,
      lastname: log.lastname,
      email: log.email,
      password: password.password
    })
  }).then((response) => router.push('/login'))
}
</script>

<template>
  <div class="register-view">
    <!-- <h1>Register</h1>
        <form type="register" onsubmit="return false" @submit="Register()">
            <input type="text" placeholder="Username" v-model="log.username" />
            <input type="text" placeholder="Firstname" v-model="log.firstname" />
            <input type="text" placeholder="Lastname" v-model="log.lastname" />
            <input type="email" placeholder="Email" v-model="log.email" />
            <input type="password" placeholder="Password" v-model="password.password" />
            <input type="password" placeholder="Password confirmation" v-model="password.passwordConfirmation" />
            <button @click="Register()">Register</button>
        </form> -->

    <form class="shadow-xl max-w-xl mx-auto rounded px-16 pt-16 pb-8 mb-8">
      <h1 class="text-3xl font-bold mb-8 text-center">Register</h1>
      <div class="mb-4">
        <label class="block text-white-700 font-bold mb-2" for="username">Nom d'Utilisateur</label>
        <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          type="text"
          placeholder="Username"
          v-model="log.username"
        />
      </div>
      <div class="mb-4">
        <label class="block text-white-700 font-bold mb-2" for="fistname">Prenom</label>
        <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          type="text"
          placeholder="Firstname"
          v-model="log.firstname"
        />
      </div>
      <div class="mb-4">
        <label class="block text-white-700 font-bold mb-2" for="lastname">Nom</label>
        <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          type="text"
          placeholder="Lastname"
          v-model="log.lastname"
        />
      </div>
      <div class="mb-4">
        <label class="block text-white-700 font-bold mb-2" for="email">Adresse email</label>
        <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          type="email"
          placeholder="Email"
          v-model="log.email"
        />
      </div>
      <div class="mb-6">
        <label class="block text-white-700 font-bold mb-2" for="password">Mot de passe</label>
        <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          type="password"
          placeholder="Password"
          v-model="password.password"
        />
      </div>
      <div class="mb-6">
        <label class="block text-white-700 font-bold mb-2" for="password">Confirmation mdp</label>
        <input
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          type="password"
          placeholder="Password confirmation"
          v-model="password.passwordConfirmation"
        />
      </div>
      <div class="flex items-center justify-between mt-11">
        <button
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
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
    </form>
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
