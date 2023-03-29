<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

let user_friends = ref({})
axios.get(`http://api.frontoffice.reunionou:49383/user/${useRoute().params['id']}/friends`).then((response) => {
    user_friends.value = response.data.friends
}).then((response) => console.log(user_friends))

</script>

<template lang="">
    <div class="bg-cwhite text-cblack flex flex-col justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden">
        <div class='friends-list flex flex-col  w-11/12 gap-11 mt-5 mb-6'>
            <ul>
                <li v-for="friends in user_friends">
                    <p>{{friends}}</p>
                </li>
            </ul>
        </div>
    </div>
</template>