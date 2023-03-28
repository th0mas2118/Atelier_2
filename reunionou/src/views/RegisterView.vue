<script setup lang="ts">
import { useUserStore } from "@/stores/user";
import { reactive } from 'vue'
import router from "@/router";

const user = useUserStore();
let log = reactive({
    username: "",
    firstname: '',
    lastname: '',
    email: "",
});
let password = reactive({
    password: "",
    passwordConfirmation: "",
});


const Register = async () => {
    if (password.password !== password.passwordConfirmation || log.email == "" || log.firstname == "" || log.lastname == "" || log.username == "") {
        return;
    }
    await fetch('http://api.frontoffice.reunionou:49383/signup', {
        method: 'POST',
        mode: 'cors',
        body: JSON.stringify({
            username: log.username,
            firstname: log.firstname,
            lastname: log.lastname,
            email: log.email,
            password: password.password
        })
    }).then((response) => router.push('/login'))
};

</script>


<template>
    <div class="register-view">
        <h1>Register</h1>
        <form type="register" onsubmit="return false" @submit="Register()">
            <input type="text" placeholder="Username" v-model="log.username" />
            <input type="text" placeholder="Firstname" v-model="log.firstname" />
            <input type="text" placeholder="Lastname" v-model="log.lastname" />
            <input type="email" placeholder="Email" v-model="log.email" />
            <input type="password" placeholder="Password" v-model="password.password" />
            <input type="password" placeholder="Password confirmation" v-model="password.passwordConfirmation" />
            <button @click="Register()">Register</button>
        </form>
    </div>
</template>

<style>
@media (min-width: 1024px) {
    .register-view {
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
}
</style>
