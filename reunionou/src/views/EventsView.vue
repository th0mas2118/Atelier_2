<script setup lang="ts">
import EventList from '../components/EventList.vue'
</script>

<template>
  <EventList></EventList>
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
