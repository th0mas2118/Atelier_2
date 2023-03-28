<script lang='ts' setup>
import { useUserStore } from "@/stores/user";
const user = useUserStore();

</script>

<template>
    <header class="navbar">
        <h2>Title</h2>
        <nav>
            <RouterLink to="/">Home</RouterLink>
            <RouterLink to="/about">About</RouterLink>
            <RouterLink v-if="!user.isConnected" to="/login">Login</RouterLink>
            <RouterLink v-if="!user.isConnected" to="/register">Register</RouterLink>
            <button v-if="user.isConnected" @click="user.disconnect">Logout</button>
        </nav>
    </header>
</template>