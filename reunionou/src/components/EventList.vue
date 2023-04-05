<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import { useUserStore } from '@/stores/user'

const user = useUserStore()
const createdByMe = ref([])
const invitedTo = ref([])

onMounted(async () => {
  try {
    const createdByMeRes = await axios.get(
      `${import.meta.env.VITE_API_HOST}/user/${user.member.id}/events`
    )

    const invitedToRes = await axios.get(
      `${import.meta.env.VITE_API_HOST}/user/${user.member.id}/invitations`
    )

    const createdByMeData = createdByMeRes.data.events
    const invitedToData = invitedToRes.data.invitations

    createdByMe.value = createdByMeData.map((event: any) => {
      return {
        id: event.id,
        title: event.title
      }
    })

    invitedTo.value = invitedToData.map((invitation: any) => {
      return {
        id: invitation.event_id,
        title: invitation.event_title
      }
    })

    console.log(createdByMe, invitedTo)
  } catch (error) {
    console.log(error)
  }
})
</script>

<template lang="">
  <div
    class="p-4 bg-cwhite text-cblack flex flex-col min-h-[600px] justify-start items-center max-w-[800px] w-full h-[calc(100vh-80px)] md:h-full m-auto md:rounded-3xl shadow-lg text-cblack overflow-x-hidden m-4"
  >
    <h1 class="text-cblack font-bold min-w-[250px] text-2xl w-full text-center p-4">
      Mes évenements
    </h1>
    <div class="flex flex-col md:flex-row justify-center gap-2 w-full">
      <div class="w-full md:w-2/4 flex flex-col gap-2">
        <h2 class="block text-cpurple text-xl font-bold mb-2 text-center">
          Crées par moi ({{ createdByMe.length }})
        </h2>
        <ul>
          <li class="text-center py-2 mb-2 rounded-lg border-2 border-amber-300 hover:border-amber-600 shadow-lg hover:shadow-blue-500/50 transition duration-250 ease-out hover:ease-in" v-for="event in createdByMe" :key="event.id">
            <router-link
              :to="{ name: 'event', params: { id: event.id } }"
              active-class="bg-cpurple text-cwhite"
              class="text-cblack hover:text-[#9a69fe] rounded-md px-3 py-2 text-sm font-medium"
            >
              {{ event.title }}
            </router-link>
          </li>
        </ul>
      </div>
      <div class="w-full md:w-2/4 flex flex-col gap-2">
        <h2 class="block text-cpurple text-xl font-bold mb-2 text-center">
          Je suis invité ({{ invitedTo.length }})
        </h2>
        <ul>
          <li class="text-center py-2 mb-2 rounded-lg border-2 border-amber-300 hover:border-amber-600 shadow-lg hover:shadow-blue-500/50 transition duration-250 ease-out hover:ease-in" v-for="event in invitedTo" :key="event.id">
            <router-link
              :to="{ name: 'event', params: { id: event.id } }"
              active-class="bg-cpurple text-cwhite"
              class="text-cblack hover:text-[#9a69fe] rounded-md px-3 py-2 text-sm font-medium"
            >
              {{ event.title }}
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style lang=""></style>
