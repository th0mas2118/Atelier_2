<script setup lang="ts">
import NavbarVue from './components/NavBar.vue'
</script>

<template>
  <NavbarVue> </NavbarVue>
  <main class="flex w-full h-full max-w-7xl m-auto min-h-[calc(100vh-80px)]">
    <RouterView />
  </main>
</template>

<style>
@import './assets/main.css';
</style>
