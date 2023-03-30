<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useUserStore } from '@/stores/user';
const user = useUserStore()

let user_data = ref({})
axios.get(`http://api.frontoffice.reunionou:49383/user/${useRoute().params['id']}`).then((response) => {
    user_data.value = response.data.user
})
const isMyPage = user.member.id == useRoute().params['id']
const modify = ref(false)
let userModify = reactive({
    adresse: ''
})
const currentView = ref(0)
let file: any

const onFileChange = (e: File) => {
    file = ref(['file']).value
}

const modifyUser = () => {
    if (userModify.adresse === '' && file === undefined) {
        return
    }
    else {
        if (userModify.adresse !== '') {
            axios.put(`http://api.frontoffice.reunionou:49383/user/${user.member.id}`, userModify, {
                headers: {
                    'Authorization': `Bearer ${user.member.acces_token}`
                },
            }).then((response) => {
                console.log(response)
            }).catch((error) => {
                console.log(error)
            })
        }
        if (file !== undefined) {
            const formData = new FormData()
            formData.append('file', file)
            console.log(file)
            console.log(formData)
            axios.put(`http://api.frontoffice.reunionou:49383/user/${user.member.id}/avatar`, formData).then((response) => console.log(response)).catch((error) => console.log(error))
        }
    }

}


</script>
<template lang="" >
    <div class="bg-cwhite text-cblack flex flex-col justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack">
        <section id='content' class='content flex flex-row  w-11/12 gap-11 mt-5 mb-6'>
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
            <div class="button form flex flex-col justify-center" v-if="isMyPage">
                <button class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="modify=true">Modifier</button>
            </div>
            
        </section>
        <section id='form' class="form-user-info" v-if="isMyPage && modify">
            <form @submit="return"
      class="bg-cwhite text-cblack flex flex-col justify-start items-center p-10 h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden">
                <div class="flex flex-col w-full">
                    <label for="adress">Adresse</label>
                    <input type="text" name="adress" id="adress" class="border-2 border-cpurple rounded-md p-2" v-model="userModify.adresse">
                </div>
                <div class="flex flex-col w-full">
                    <label for="adress">Avatar</label>
                    <input @change="onFileChange"  ref="file" type="file" name="avatar" id="avatar" class="border-2 border-cpurple rounded-md p-2">
                </div>
                <div class="flex flex-row w-full mt-5 justify-between">
                    <button class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="modify=false">Annuler</button>
                    <button class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" @click="modifyUser">Valider</button>
                </div>
            </form>
        </section>
    </div>
</template>