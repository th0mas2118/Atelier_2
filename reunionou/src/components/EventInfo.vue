<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import router from '@/router'
import axios from 'axios'
import { useUserStore } from '@/stores/user'
import EventDropdownMenu from './EventDropdownMenu.vue'
import CreateInvitationLinkPopup from './CreateInvitationLinkPopup.vue'
const user = useUserStore()

const currentView = ref(0)
const showOtherMenu = ref(false)
const event = ref({})
const hasAccess = ref(false)

const getConfirmatedParticipantsCount = (): number => {
  return event.value.participants.filter((participant: any) => participant.status == 'confirmed')
    .length
}

const participate = async () => {
  let eventParticipant = event.value.participants.findIndex(
    (participant: any) => participant.user.id == user.member.id
  )

  try {
    const result = await axios.patch(
      `${import.meta.env.VITE_API_HOST}/events/${event.value.id}/participate`,
      {
        user_id: user.member.id,
        status: 'confirmed'
      }
    )

    event.value.participants[eventParticipant].status = 'confirmed'
  } catch (error) {
    console.error(error)
  }
}

const decline = async () => {
  let eventParticipant = event.value.participants.findIndex(
    (participant: any) => participant.user.id == user.member.id
  )

  try {
    const result = await axios.patch(
      `${import.meta.env.VITE_API_HOST}/events/${event.value.id}/participate`,
      {
        user_id: user.member.id,
        status: 'declined'
      }
    )

    event.value.participants[eventParticipant].status = 'declined'
  } catch (error) {
    console.error(error)
  }
}

const listenClick = (e: MouseEvent) => {
  const target = e.target as HTMLInputElement

  if (
    target &&
    target.id !== 'event-dropdown-menu' &&
    target.id !== 'event-dropdown-button' &&
    showOtherMenu.value == true
  ) {
    showOtherMenu.value = false
  }
}

onUnmounted(() => {
  window.removeEventListener('click', listenClick)
})

// get id from router
onMounted(() => {
  window.addEventListener('click', listenClick)
  const id = useRoute().params.id
  axios
    .get(`${import.meta.env.VITE_API_HOST}/events/${id}`)
    .then((response) => {
      if (!response.data.event.title) {
        router.push({ name: 'home' })
        return
      }
      event.value = response.data.event

      if (
        response.data.event.participants.find((x: any) => x.user.id == user.member.id) ||
        response.data.event.organizer.id == user.member.id ||
        user.member.level > 0 ||
        response.data.event.isPrivate == false
      ) {
        hasAccess.value = true
      } else {
        router.push({ name: 'home' })
      }
      console.log(event)
    })
    .catch((error) => {
      router.push({ name: 'home' })
      console.log(error)
    })
})
</script>

<template lang="">
  <div
    class="bg-cwhite text-cblack flex flex-col min-h-[600px] justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
    v-if="event.title && hasAccess"
  >
    <!-- <CreateInvitationLinkPopup></CreateInvitationLinkPopup> -->
    <header
      class="p-8 w-full h-full flex flex-col md:flex-row justify-between items-start md:items-center border-solid border-b-2 border-cwhite2 gap-4"
    >
      <div
        id="header-presentation"
        class="flex items-center gap-4 flex-col w-full sm:flex-row text-center md:text-left"
      >
        <div
          class="w-16 h-16 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default aspect-square"
        >
          🔥
        </div>
        <div>
          <h1 class="text-2xl">{{ event.title }}</h1>
          <p class="text-md text-cgray">
            {{ event.participants ? getConfirmatedParticipantsCount() : 0 }} participants
          </p>
        </div>
      </div>
      <div
        id="header-controls"
        class="flex items-center justify-center md:w-auto md:justify-between gap-2 w-full flex-wrap md:flex-nowrap relative"
      >
        <button
          :class="`${
            currentView == 0
              ? 'bg-cpurple text-cwhite2 hover:bg-[#9a69fe]'
              : 'bg-cwhite2 text-cpurple hover:bg-[#ececec]'
          }  w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 aspect-square`"
          @click="currentView = 0"
        >
          <i class="fa-solid fa-info"></i>
        </button>
        <button
          :class="`${
            currentView == 1
              ? 'bg-cpurple text-cwhite2 hover:bg-[#9a69fe]'
              : 'bg-cwhite2 text-cpurple hover:bg-[#ececec]'
          }  w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 aspect-square`"
          @click="currentView = 1"
        >
          <i class="fa-solid fa-map"></i>
        </button>
        <button
          :class="`${
            currentView == 2
              ? 'bg-cpurple text-cwhite2 hover:bg-[#9a69fe]'
              : 'bg-cwhite2 text-cpurple hover:bg-[#ececec]'
          }  w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 aspect-square`"
          @click="currentView = 2"
        >
          <i class="fa-solid fa-cloud"></i>
        </button>
        <button
          :class="`${
            currentView == 3
              ? 'bg-cpurple text-cwhite2 hover:bg-[#9a69fe]'
              : 'bg-cwhite2 text-cpurple hover:bg-[#ececec]'
          }  w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 aspect-square`"
          @click="currentView = 3"
        >
          <i class="fa-solid fa-comment"></i>
        </button>
        <button
          v-if="event.participants.find((x: any) => x.user.id == user.member.id && x.status != 'confirmed') && event.organizer.id != user.member.id"
          class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap"
          @click="participate"
        >
          Participer
        </button>
        <button
          v-if="event.participants.find((x: any) => x.user.id == user.member.id && x.status == 'confirmed') && event.organizer.id != user.member.id"
          class="bg-cred hover:bg-[#ea384e] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap"
          @click="decline"
        >
          Décliner
        </button>
        <button
          id="event-dropdown-button"
          class="bg-cwhite text-cgray w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 hover:bg-[#ececec] aspect-square"
          @click="showOtherMenu = !showOtherMenu"
        >
          <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <EventDropdownMenu
          id="event-dropdown-menu"
          v-if="showOtherMenu"
          :user="user"
          :event="event"
        ></EventDropdownMenu>
      </div>
    </header>

    <div
      id="content"
      class="h-full w-full flex flex-grow min-h-[400px] transition-all duration-500 ease-in-out"
      :style="{ transform: `translateX(-` + currentView * 100 + `%)` }"
    >
      <section
        id="event-info"
        class="w-full h-full flex flex-col md:flex-row justify-between items-start p-4 gap-2 flex-shrink-0"
      >
        <div id="event-description" class="w-full md:w-2/4">
          <h3 class="text-cpurple font-bold min-w-[250px] mb-4">Description</h3>
          <p>
            {{ event.description }}
          </p>
        </div>
        <div id="participant-list" class="max-h-[500px] overflow-y-auto">
          <h3 class="text-cpurple font-bold min-w-[250px] mb-4">
            Invités ({{ event.participants.length }})
          </h3>
          <ul class="flex flex-col gap-2 overflow-auto">
            <li
              class="flex items-center gap-2"
              v-for="user in event.participants"
              :key="user.user.id"
            >
              <div
                class="w-12 h-12 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
              >
                <img
                  src="https://www.gala.fr/imgre/fit/~1~gal~2021~08~02~ddad7c39-59ab-49d3-ba37-04f203f8029c.jpeg/480x480/quality/80/focus-point/757%2C593/photos-emmanuel-macron-en-t-shirt-a-bregancon-pas-le-1er-president-a-se-lacher-en-vacances.jpg"
                  alt=""
                  srcset=""
                />
              </div>
              <p>{{ user.user.firstname + ' ' + user.user.lastname }}</p>
              <div>
                <i
                  v-if="user.status == 'confirmed'"
                  class="fa-solid fa-check-circle text-cgreen"
                ></i>
                <i v-if="user.status == 'declined'" class="fa-solid fa-xmark-circle text-cred"></i>
                <i v-if="user.status == 'waiting'" class="fa-solid fa-clock text-cblack"></i>
              </div>
            </li>
          </ul>
        </div>
      </section>
      <section
        id="event-map"
        class="w-full h-full flex justify-between items-start p-4 gap-2 flex-shrink-0"
      >
        MAP
      </section>
      <section
        id="event-meteo"
        class="w-full h-full flex justify-between items-start p-4 gap-2 flex-shrink-0"
      >
        METEO
      </section>
      <section
        id="event-map"
        class="w-full h-full flex justify-between items-start p-4 gap-2 flex-shrink-0"
      >
        COMMENTS
      </section>
    </div>
  </div>
  <div
    v-if="!event.title"
    id="loading-spinner"
    class="flex justify-end align-center absolute top-[calc(50%-3rem)] right-[calc(50%-3rem)]"
  >
    <svg
      aria-hidden="true"
      class="w-12 h-12 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-cpurple"
      viewBox="0 0 100 101"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
        fill="currentColor"
      />
      <path
        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
        fill="currentFill"
      />
    </svg>
    <span class="sr-only">Loading...</span>
  </div>
</template>
<style lang=""></style>
