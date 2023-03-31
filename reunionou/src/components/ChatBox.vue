<script setup lang="ts">
import { useUserStore } from '@/stores/user'
import { reactive, onMounted, ref } from 'vue'
import router from '@/router'
import { useRoute } from 'vue-router'
import axios from 'axios'

let route = useRoute()

const user = useUserStore()
let message = reactive({
  content: '',
  event_id: '150',
  member_id: user.member.id
})

const state = reactive({
  channels: []
})

console.log(' User ' + user.member.id)
console.log(' Message ' + message.member_id)

const Comment = async () => {
  if (message.content == '') {
    return alert('message vide')
  }
  await fetch('http://api.frontoffice.reunionou:49383/messages', {
    method: 'POST',
    mode: 'cors',
    body: JSON.stringify({
      content: message.content,
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
onMounted(() => {
  axios
    .get(`http://api.frontoffice.reunionou:49383/messages/${route.params['id']}/event`)
    .then((response) => {
      //state.channels = response.data.comment
      state.channels = response.data.comments
    })
    .then((response) => {
      console.log(state.channels)
    })
})
</script>

<template>
  <!-- <div
      id="message-content"
      class="w-full h-full flex min-h-[400px] justify-between items-start p-4 gap-2"
    > -->
  <div class="container mx-auto h-[60vh] flex flex-col">
    <div class="flex-grow overflow-y-scroll px-4">
      <div v-for="channel in state.channels">
        <div class="flex justify-end" v-if="channel.member_id == user.member.id">
          <div class="flex flex-col bg-cpurple rounded-lg list-inside mt-4 p-4 w-6/12">
            <div class="flex items-center mb-4">
              <div>
                <p class="leading-normal">
                  {{ channel.content }}
                </p>
              </div>
            </div>
          </div>
          <img
            class="w-12 h-12 object-cover rounded-full mr-4 self-end ml-4"
            src="https://images.unsplash.com/photo-1519681393784-d120267933ba"
            alt="avatar"
          />
        </div>
        <div class="flex justify-start" v-else>
          <img
            class="w-12 h-12 object-cover rounded-full mr-4 self-end ml-4"
            src="https://images.unsplash.com/photo-1519681393784-d120267933ba"
            alt="avatar"
          />
          <div class="flex flex-col bg-gray-100 rounded-lg list-inside mt-4 p-4 w-6/12">
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
      <!-- <div class="flex justify-start">
        <img
          class="w-12 h-12 object-cover rounded-full mr-4 self-end ml-4"
          src="https://images.unsplash.com/photo-1519681393784-d120267933ba"
          alt="avatar"
        />
        <div class="flex flex-col bg-gray-100 rounded-lg list-inside mt-4 p-4 w-6/12">
          <div class="flex items-center mb-4">
            <div>
              <p class="leading-normal">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin quis orci erat. Ut
                nec justo sit amet velit auctor feugiat eu at ex. Sed feugiat nisi sed mauris
                laoreet euismod.orem Ipsum is simply dummy text of the printing and typesetting
                industry. Lorem Ipsum has been the industry's standard dummy text ever since the
              </p>
            </div>
          </div>
        </div>
      </div> -->
    </div>
    <form>
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
