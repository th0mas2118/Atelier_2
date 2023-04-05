<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios';
import { useRoute } from 'vue-router';
import router from '@/router';

const invitations = ref({})

const userId = useRoute().params['id']
const participate = async (id: String) => {
    const result = await axios.patch(
        `${import.meta.env.VITE_API_HOST}/events/${id}/participate`,
        {
            user_id: userId,
            status: 'confirmed',
            type: 'user'
        }
    ).then((response) => {
        location.reload()
    })

}
const decline = async (id: String) => {
    const result = await axios.patch(
        `${import.meta.env.VITE_API_HOST}/events/${id}/participate`,
        {
            user_id: userId,
            status: 'declined',
            type: 'user'
        }
    ).then((response) => {
        location.reload()
    })

}

axios
    .get(`http://api.frontoffice.reunionou:49383/user/${useRoute().params['id']}/invitations`)
    .then((response) => {
        invitations.value = response.data.invitations

    }).catch((error) => console.log(error))
</script>

<template>
    <div
        class="invitation_list bg-cwhite text-cblack flex flex-col  justify-start items-center w-10/12 h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden">
        <div v-for="invit in invitations" class="m-8 flex flex-row justify-between w-11/12 ">

            <div class="flex flex-col justify-center">
                <div>Organisateur : {{ invit.organizer.username }}</div>
                <div>Nom de l'événement : {{ invit.event_title }}</div>
            </div>
            <div class="flex flex-col gap-2">
                <div class="bg-cgreen text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-center"
                    v-if="invit.accepted == true">PARTICIPE</div>
                <div class="bg-cred text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-center"
                    v-if="invit.accepted == false">NE PARTICIPE PAS</div>
                <div class="flex flex-row gap-3">
                    <div
                        :class="`${invit.accepted ? 'bg-cgray' : 'bg-cgreen hover:bg-blue-700 '}  text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-center`">
                        <button :disabled="invit.accepted" @click="participate(invit.event_id, true)">Accepter</button>
                    </div>
                    <div
                        :class="`${!invit.accepted ? 'bg-cgray' : 'bg-cred hover:bg-blue-700'}   text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline text-center`">
                        <button :disabled="!invit.accepted" @click="decline(invit.event_id, false)">Refuser</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>