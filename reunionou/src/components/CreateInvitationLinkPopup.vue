<script setup lang="ts">
import axios from 'axios'
import { reactive, ref } from 'vue'

const guestData = reactive({
  guest_firstname: '',
  guest_lastname: ''
})

const invitationLink = ref('')

const props = defineProps({
  event: {
    required: true
  }
})

const createInvitationLink = () => {
  if (guestData.guest_firstname == '' || guestData.guest_lastname == '') return
  console.log(props.event)
  axios
    .post(`${import.meta.env.VITE_API_HOST}/invitations/${props.event.id}/guest`, guestData)
    .then((response) => {
      invitationLink.value =
        window.location.host + '/event/' + props.event.id + '?guest=' + response.data.invitation.id
    })
    .catch((error) => {
      console.log(error)
    })
}
</script>
<template>
  <div
    class="fixed w-screen h-screen text-cblack flex align-center justify-center top-0 bg-cblack z-50 p-2"
  >
    <div
      class="relative m-auto p-2 bg-cwhite text-cblack max-w-[500px] flex flex-col max-h-[350px] justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
    >
      <div id="close-button" class="">
        <button @click="$emit('close')" class="absolute top-0 right-0 m-2 text-cblack">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
      <h2 class="font-bold text-xl">Créer un lien d'invitation</h2>
      <i class="text-xs text-cgray text-center mb-4"
        >Ce lien permet à un utilisateur non inscrit à la plateforme de participer un évenement</i
      >
      <div class="flex flex-col align-center justify-center gap-2">
        <div>
          <label class="block text-white-700 text-sm mb-2" for="password">Nom de l'invité</label>
          <input
            name="lastname"
            id="lastname"
            v-model="guestData.guest_lastname"
            type="text"
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
          />
        </div>
        <div>
          <label class="block text-white-700 text-sm mb-2" for="password">Prénom de l'invité</label>
          <input
            name="firstname"
            id="firstname"
            v-model="guestData.guest_firstname"
            type="text"
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
          />
        </div>
        <button
          @click="createInvitationLink"
          class="bg-cpurple hover:bg-[#9a69fe] mt-2 text-cwhite py-2 px-4 rounded-xl transition-all duration-300 overflow-hidden whitespace-nowrap"
        >
          {{ invitationLink == '' ? 'Créer le lien' : 'Lien crée' }}
        </button>
        <div v-if="invitationLink != ''" class="text-center">
          <p class="text-xs">Lien d'invitation</p>
          <p class="text-xs">http://{{ invitationLink }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
