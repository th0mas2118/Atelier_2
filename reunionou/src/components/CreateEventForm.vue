<script setup lang="ts">
import { onMounted, onUnmounted, reactive, ref } from 'vue'
import EmojiPicker from 'vue3-emoji-picker'
import axios from 'axios'
import 'vue3-emoji-picker/css'

const currentPage = ref(0)
const showEmojiPicker = ref(false)
const gpsError = ref(false)

const setPage = async (page: number) => {
  if (page == 1) {
    if (
      eventData.title.trim() == '' ||
      eventData.address.trim() == '' ||
      eventData.date.trim() == '' ||
      eventData.description.trim() == ''
    ) {
      return
    }

    await getGPS(eventData.address)

    if (eventData.gps[0] == 0 && eventData.gps[1] == 0) {
      return
    }
  }
  currentPage.value = page
  showEmojiPicker.value = false
}

const setEmoji = (emoji: string) => {
  eventData.icon = emoji.i
  showEmojiPicker.value = false
}

const listenClick = (e: MouseEvent) => {
  const target = e.target as HTMLInputElement

  if (target && target.id !== 'event-icon') {
    showEmojiPicker.value = false
  }
}

const getGPS = async (address: string) => {
  const res = await axios.get(`https://geocode.maps.co/search?q=${address}`)

  if (res?.data[0]?.lat == undefined || res?.data[0]?.lon == undefined) {
    eventData.gps = [0, 0]
    gpsError.value = true
    return
  }
  eventData.gps = [parseFloat(res?.data[0]?.lat), parseFloat(res?.data[0]?.lon)]
  gpsError.value = false
}

const createEvent = async () => {
  const res = await axios.post(`${import.meta.env.VITE_API_HOST}/events`, eventData, {
    headers: {
      'Content-Type': 'application/json'
    }
  })
  console.log(res)
  if (res.status == 200) {
    window.location.href = '/'
  }
}

const eventData = reactive({
  title: '',
  date: '',
  address: '',
  gps: [0, 0],
  description: '',
  icon: '',
  isPrivate: false,
  organizer_id: '',
  participants: []
})

onMounted(async () => {
  window.addEventListener('click', listenClick)
})

onUnmounted(() => {
  window.removeEventListener('click', listenClick)
})
</script>

<template>
  <form
    action=""
    class="bg-cwhite text-cblack flex flex-col min-h-[600px] justify-start items-center max-w-[800px] w-full h-full m-auto md:rounded-3xl shadow-lg text-cblack overflow-x-hidden m-4"
  >
    <h1 class="text-cblack font-bold min-w-[250px] text-2xl w-full text-center p-4">
      CRÉER UN EVENEMENT
    </h1>
    <div
      class="h-full w-full flex flex-grow transition-all duration-500 ease-in-out"
      :style="{ transform: `translateX(-` + currentPage * 100 + `%)` }"
    >
      <section id="page1" class="flex-shrink-0 w-full h-full p-4">
        <div id="inputs" class="flex w-full h-full flex-grow flex-wrap">
          <div class="flex flex-col w-2/4 p-4 gap-4">
            <div>
              <label class="block text-white-700 text-sm font-bold mb-2" for="titre">Titre</label>
              <input
                :class="`${
                  'shadow appearance-none ' +
                  (eventData.title.trim() == '' ? 'border-cred' : 'border-[#E5E7EB]') +
                  ' rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]'
                }`"
                name="titre"
                id="titre"
                type="text"
                v-model="eventData.title"
                placeholder="Titre"
              />
            </div>
            <div>
              <label class="block text-white-700 text-sm font-bold mb-2" for="date">Date</label>
              <input
                :class="`${
                  'shadow appearance-none ' +
                  (eventData.date.trim() == '' ? 'border-cred' : 'border-[#E5E7EB]') +
                  ' rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]'
                }`"
                name="date"
                id="date"
                type="datetime-local"
                v-model="eventData.date"
                placeholder="Date"
              />
            </div>
          </div>
          <div class="flex flex-col w-2/4 p-4 gap-4">
            <div>
              <label class="block text-white-700 text-sm font-bold mb-2" for="adresse">
                Adresse
              </label>
              <input
                :class="`${
                  'shadow appearance-none ' +
                  (eventData.address.trim() == '' || gpsError
                    ? 'border-cred'
                    : 'border-[#E5E7EB]') +
                  ' rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]'
                }`"
                name="adresse"
                id="adresse"
                type="text"
                v-model="eventData.address"
                placeholder="Adresse"
              />
            </div>
          </div>
          <div class="mb-4 w-full p-4 flex-grow">
            <label class="block text-white-700 text-sm font-bold mb-2" for="username"
              >Description</label
            >
            <textarea
              :class="`${
                'shadow appearance-none ' +
                (eventData.description.trim() == '' ? 'border-cred' : 'border-[#E5E7EB]') +
                ' rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]'
              }`"
              name="description"
              id="description"
              type="text"
              v-model="eventData.description"
              placeholder="Description"
              rows="8"
            />
          </div>
        </div>
      </section>
      <section id="page2" class="flex-shrink-0 w-full h-full p-4">
        <div id="inputs" class="flex w-full h-full flex-grow flex-wrap">
          <div class="flex flex-col w-2/4 p-4 gap-4">
            <div>
              <input
                checked
                id="isPrivate"
                name="isPrivate"
                type="checkbox"
                value=""
                v-model="eventData.isPrivate"
                class="w-5 h-5 text-cpurple bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-cpurple dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              />
              <label
                for="isPrivate"
                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                >Évenement privé</label
              >
            </div>
            <div>
              <h3 class="text-cpurple font-bold min-w-[250px] mb-4">Invités</h3>

              <ul class="flex flex-col gap-2 overflow-auto max-h-[328px]">
                <!-- <li class="flex items-center gap-2">
                  <div
                    class="w-10 h-10 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
                  >
                    <img
                      src="https://www.gala.fr/imgre/fit/~1~gal~2021~08~02~ddad7c39-59ab-49d3-ba37-04f203f8029c.jpeg/480x480/quality/80/focus-point/757%2C593/photos-emmanuel-macron-en-t-shirt-a-bregancon-pas-le-1er-president-a-se-lacher-en-vacances.jpg"
                      alt=""
                      srcset=""
                    />
                  </div>
                  <p>John Doe</p>
                  <div>
                    <i class="fa-solid fa-xmark text-cred"></i>
                  </div>
                </li> -->
              </ul>
            </div>
          </div>
          <div class="flex flex-col w-2/4 p-4 gap-4">
            <div>
              <header class="flex flex-col mb-4">
                <h3 class="text-cpurple font-bold min-w-[250px] mb-4">Rechercher</h3>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg
                      aria-hidden="true"
                      class="w-5 h-5 text-gray-500 dark:text-gray-400"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                      ></path>
                    </svg>
                  </div>
                  <input
                    type="search"
                    id="default-search"
                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-cpurple focus:border-cpurple dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-cpurple dark:focus:border-cpurple"
                    placeholder="Nom, prénom, email, nom d'utilisateur"
                    required
                  />
                </div>
              </header>
              <ul class="flex flex-col gap-2 overflow-auto max-h-[314px]">
                <!-- <li class="flex items-center gap-2">
                  <div
                    class="w-10 h-10 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
                  >
                    <img
                      src="https://www.gala.fr/imgre/fit/~1~gal~2021~08~02~ddad7c39-59ab-49d3-ba37-04f203f8029c.jpeg/480x480/quality/80/focus-point/757%2C593/photos-emmanuel-macron-en-t-shirt-a-bregancon-pas-le-1er-president-a-se-lacher-en-vacances.jpg"
                      alt=""
                      srcset=""
                    />
                  </div>
                  <p>John Doe</p>
                  <div>
                    <i class="fa-solid fa-plus text-cgreen"></i>
                  </div>
                </li> -->
              </ul>
            </div>
          </div>
        </div>
      </section>
      <!--  -->
      <section id="page3" class="flex-shrink-0 w-full h-full p-4 relative">
        <div id="summary" class="flex flex-col gap-4">
          <div class="flex items-center gap-4 flex-col w-full sm:flex-row text-center md:text-left">
            <div
              id="event-icon"
              class="w-16 h-16 rounded-full bg-cwhite2 flex items-center justify-center text-2xl aspect-square cursor-pointer"
              @click="showEmojiPicker = true"
            >
              <i v-if="eventData.icon == ''" class="fa-solid fa-plus animate-bounce"></i>
              {{ eventData.icon }}
              <div
                id="emoji-picker"
                v-if="showEmojiPicker"
                class="absolute m-auto left-0 right-0 top-1/2 transform -translate-y-1/2 z-10 w-[320px]"
                @click="($event) => $event.stopPropagation()"
              >
                <EmojiPicker :native="true" @select="setEmoji" />
              </div>
            </div>
            <div>
              <h1 class="text-2xl">{{ eventData.title }}</h1>
              <p class="text-md text-cgray">10 invités</p>
            </div>
          </div>
          <span>Adresse: {{ eventData.address }}</span>
          <span>Date: {{ eventData.date }}</span>
          <span>Description: </span>
          <p>
            {{ eventData.description }}
          </p>
        </div>
      </section>
    </div>
    <div class="flex justify-between w-full p-4">
      <button
        type="button"
        v-if="currentPage > 0"
        @click="setPage(currentPage - 1)"
        class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap"
      >
        Précedent
      </button>
      <button
        type="button"
        v-if="currentPage < 2"
        @click="setPage(currentPage + 1)"
        class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap self-end ml-auto"
      >
        Suivant
      </button>
      <button
        type="button"
        v-if="currentPage == 2"
        @click="createEvent"
        class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap self-end ml-auto"
      >
        Créer l'évenement
      </button>
    </div>
  </form>
</template>

<style lang=""></style>
