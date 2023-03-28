<script setup lang="ts">
import NavbarVue from './components/NavBar.vue'
</script>

<template>
  <NavbarVue> </NavbarVue>
  <main class="">
    <RouterView />
  </main>
</template>

<style>
@import './assets/main.css';
</style>
