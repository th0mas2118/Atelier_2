<script setup>
import { useUserStore } from '@/stores/user'
import { reactive, onMounted, ref } from 'vue'
import router from '@/router'
import { useRoute } from 'vue-router'
import axios from 'axios'

let route = useRoute()

const user = useUserStore()
const event = route.params['id']
const props = defineProps({
  guest: {
    required: false
  }
})

const avatarUrl = import.meta.env.VITE_API_HOST + '/avatars/'

let message = reactive({
  content: '',
  firstname: user.isConnected == true ? user.member.firstname : props.guest.user.firstname,
  lastname: user.isConnected == true ? user.member.lastname : props.guest.user.lastname,
  event_id: event,
  member_id: user.isConnected == true ? user.member.id : props.guest.user.id
})

const state = reactive({
  channels: []
})
console.log(props.guest.status, 'ljkhklhlh')
const Comment = async () => {
  if (message.content == '') {
    return alert('message vide')
  }
  await fetch('http://api.frontoffice.reunionou:49383/messages', {
    method: 'POST',
    mode: 'cors',
    body: JSON.stringify({
      content: message.content,
      firstname: message.firstname,
      lastname: message.lastname,
      event_id: message.event_id,
      member_id: message.member_id
    })
  }).then((response) => {
    console.log('message envoyee')
    setTimeout(() => {
      location.reload()
    }, 2000)
  })
}

function getComment() {
  axios
    .get(`http://api.frontoffice.reunionou:49383/messages/${event}/event`)
    .then((response) => {
      state.channels = response.data.comments
    })
    .then((response) => {
      console.log(state.channels)
    })
}

function scrollToBottom() {
  const el = document.getElementById('chatbox')
  el.scrollTo(0, el.scrollHeight)
}

onMounted(() => {
  getComment()
  window.addEventListener('load', scrollToBottom)
})
</script>

<template>
  <!-- <div
      id="message-content"
      class="w-full h-full flex min-h-[400px] justify-between items-start p-4 gap-2"
    > -->
  <div class="container mx-auto h-[60vh] flex flex-col">
    <div class="flex-grow overflow-y-scroll px-4" id="chatbox">
      <div v-for="channel in state.channels">
        <div class="flex justify-end mb-4" v-if="channel.member_id == message.member_id">
          <div class="w-6/12">
            <span class="text-sm text-cpurple">{{
              channel.firstname.toLowerCase() + ' ' + channel.lastname.toLowerCase()
            }}</span>
            <div class="flex flex-col bg-cpurple rounded-lg list-inside mt-2 p-4">
              <div class="flex items-center mb-4">
                <p class="leading-normal">
                  {{ channel.content }}
                </p>
              </div>
            </div>
          </div>
          <img
            class="w-12 h-12 object-cover rounded-full mr-4 self-end ml-4"
            :src="avatarUrl + channel.member_id"
            alt="avatar"
          />
        </div>
        <div class="flex justify-start mb-4" v-else>
          <img
            class="w-12 h-12 object-cover rounded-full mr-4 self-end ml-4"
            :src="avatarUrl + channel.member_id"
            alt="avatar"
          />
          <div class="w-6/12">
            <span class="text-sm text-cpurple">{{
              channel.firstname.toLowerCase() + ' ' + channel.lastname.toLowerCase()
            }}</span>
            <div class="flex flex-col bg-gray-100 rounded-lg list-inside mt-2 p-4">
              <div class="flex items-center mb-4">
                <div>
                  <p class="leading-normal">
                    {{ channel.content }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form v-if="props.guest.status == 'confirmed'">
      <div class="flex items-center border-b border-cpurple py-2">
        <input
          class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
          type="text"
          placeholder="ECRIVEZ VOTRE MESSAGE ..."
          aria-label="Full name"
          v-model="message.content"
        />
        <button
          class="flex-shrink-0 bg-cpurple hover:bg-purple-700 hover:border-purple-700 text-sm text-white py-3 px-4 rounded"
          type="button"
          @click="Comment()"
        >
          Envoyer
        </button>
      </div>
    </form>
  </div>
  <!-- </div> -->
</template>
