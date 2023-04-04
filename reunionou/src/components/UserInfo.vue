<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useUserStore } from '@/stores/user'
import router from '@/router'
const user = useUserStore()

const user_data = ref({})

const isMyPage = user.member.id == useRoute().params['id']
const modify = ref(false)

const userModify = reactive({
  adresse: user_data.value.adress,
  mail: user_data.value.mail
})
const currentView = ref(0)

console.log(user_data)
console.log(userModify)

const avatarUrl = ref(
  user.isConnected
    ? `${import.meta.env.VITE_API_HOST}/avatars/${useRoute().params['id']}/200/200`
    : ''
)

const file = ref(null)

const onFileChange = (e: any) => {
  file.value = e.target.files[0]
}

const modifyUser = async (e: Event) => {
  e.preventDefault()

  const modifyUser =
    (userModify.adresse != '' && userModify.adresse != user_data.value.adresse) ||
    (userModify.mail != '' && userModify.mail != user_data.value.mail)

  if (modifyUser) {
    try {
      const res = await axios.put(
        `http://api.frontoffice.reunionou:49383/user/${user.member.id}`,
        userModify,
        {
          headers: {
            Authorization: `Bearer ${user.member.acces_token}`
          }
        }
      )
    } catch (error) {
      console.error(error)
    }
  }

  if (file.value != null) {
    try {
      const formData = new FormData()
      console.log(file.value)
      formData.append('avatar', file.value)
      await axios.post(
        `http://api.frontoffice.reunionou:49383/avatars/${user.member.id}/`,
        formData,
        {
          headers: {
            Authorization: `Bearer ${user.member.acces_token}`,
            'Content-Type': 'multipart/form-data'
          }
        }
      )
    } catch (error) {
      console.error(error)
    }
  }

  if (modifyUser || file.value != null) {
    router.go(0)
  }
}

onMounted(() => {
  axios
    .get(`http://api.frontoffice.reunionou:49383/user/${useRoute().params['id']}`)
    .then((response) => {
      user_data.value = response.data.user
      userModify.adresse = user_data.value.adresse
      userModify.mail = user_data.value.mail
      console.log(us)
    })
    .catch((error) => {
      console.error(error)
    })
})
</script>
<template lang="">
  <div
    class="bg-cwhite text-cblack flex flex-col justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack"
  >
    <section id="content" class="content flex flex-row w-11/12 gap-11 mt-5 mb-6">
      <div class="avatar">
        <img
          class="w-[200px] h-[200px] overflow-hidden rounded-full"
          :src="avatarUrl"
          alt="avatar"
        />
      </div>
      <div class="info flex-auto self-center">
        <p>Identifiant : {{ user_data.id }}</p>
        <p>Prénom : {{ user_data.firstname }}</p>
        <p>Nom : {{ user_data.lastname }}</p>
        <p>Nom d'utilisateur : {{ user_data.username }}</p>
        <p>Email : {{ user_data.mail }}</p>
        <p>Adresse : {{ user_data.adresse === null ? 'Non renseigné' : user_data.adresse }}</p>
      </div>
      <div class="button form flex flex-col justify-center" v-if="isMyPage">
        <button
          class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          @click="modify = true"
        >
          Modifier
        </button>
      </div>
    </section>
    <section id="form" class="form-user-info" v-if="isMyPage && modify">
      <form
        @submit="return"
        class="bg-cwhite text-cblack flex flex-col justify-start items-center p-10 h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
      >
        <div class="flex flex-col w-full">
          <label for="adress">Email</label>
          <input
            type="text"
            name="email"
            id="email"
            class="border-2 border-cpurple rounded-md p-2"
            v-model="userModify.mail"
          />
        </div>
        <div class="flex flex-col w-full">
          <label for="adress">Adresse</label>
          <input
            type="text"
            name="adress"
            id="adress"
            class="border-2 border-cpurple rounded-md p-2"
            v-model="userModify.adresse"
          />
        </div>
        <div class="flex flex-col w-full">
          <label for="adress">Avatar</label>
          <input
            @change="onFileChange"
            type="file"
            name="avatar"
            id="avatar"
            class="border-2 border-cpurple rounded-md p-2"
          />
        </div>
        <div class="flex flex-row w-full mt-5 justify-between">
          <button
            class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click="modify = false"
          >
            Annuler
          </button>
          <button
            class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click="modifyUser"
          >
            Valider
          </button>
        </div>
      </form>
    </section>
  </div>
</template>
