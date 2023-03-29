<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'
import router from '@/router';

let user_friends = ref({})
axios
    .get(`http://api.frontoffice.reunionou:49383/user/${useRoute().params['id']}/friends`)
    .then((response) => {
        user_friends.value = response.data.friends
    })
    .then((response) => console.log(user_friends))

const goTo = (id: string) => {
    console.log(id)
    router.push(`/user/${id}`)
}



</script>

<template lang="">
  <div
    class="bg-cwhite text-cblack flex flex-col justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
  >
    <div class="friends-list flex flex-col w-11/12 gap-11 mt-5 mb-6">
      <div v-for="friend in user_friends">
        <div class="flex flex-row gap-4 items-center">
          <div
            class="w-12 h-12 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
          >
            
            <button @click="goTo(friend.friend_id)" ><img src="https://i.pravatar.cc/150?img=1" alt="" srcset="" /></button>
          </div>
          <p>{{ friend.lastname.toUpperCase() }} {{ friend.firstname }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
