<script setup lang="ts">
import ChatBox from '../components/ChatBox.vue'
import Members from '../components/Members.vue'
</script>

<template>
  <div
    id="message-content"
    class="w-full h-full flex min-h-[400px] justify-between items-start p-4 gap-2"
  >
    <ChatBox></ChatBox>
    <Members></Members>
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
