<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRoute } from 'vue-router';
import axios from 'axios';

let user_data = ref({})
axios.get(`http://api.frontoffice.reunionou:49383/user/${useRoute().params['id']}`).then((response) => {
    user_data.value = response.data.user
})


</script>
<template lang="" >
    <div class="bg-cwhite text-cblack flex flex-col justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden">
        <div class='content flex flex-row  w-11/12 gap-11 mt-5 mb-6'>
            <div class="avatar">
                <img class="w-[200px] h-[200px] overflow-hidden rounded-full"  src="https://i.pravatar.cc/150?img=1" alt="avatar">
            </div>
            <div class="info flex-auto self-center">
                <p>Identifiant : {{user_data.id}}</p>
                <p>Prénom : {{user_data.firstname}}</p>
                <p>Nom : {{user_data.lastname}}</p>
                <p>Nom d'utilisateur : {{user_data.username}}</p>
                <p>Email : {{user_data.mail}}</p>
                <p>Adresse : {{(user_data.adress=== null)? 'Non renseigné' : user_data.adress}}</p>
            </div>
        </div>
    </div>
</template>