<script setup lang="ts">
import { useUserStore } from "@/stores/user";
import { reactive } from 'vue'
const user = useUserStore();
let log = reactive({
    email: "",
    password: "",
});
</script>

<template>
    <div class="login-view">
        <h1>Login</h1>
        <form type="login" onsubmit="return false" @submit="user.setConnected(log)">
            <input type="email" placeholder="Email" v-model="log.email" />
            <input type="password" placeholder="Password" v-model="log.password" />
            <button @click="user.setConnected(log)">Login</button>
        </form>
    </div>
</template>

<style>
@media (min-width: 1024px) {
    .login-view {
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
}
</style>
