<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  event: {
    required: true
  }
})

const organizerId = ref(props.event.organizer.id)
</script>

<template lang="">
  <div
    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-cwhite py-1 shadow-lg ring-1 ring-cblack ring-opacity-5 focus:outline-none text-cblack"
    role="menu"
    aria-orientation="vertical"
    aria-labelledby="user-menu-button"
    tabindex="-1"
  >
    <!-- Active: "bg-gray-100", Not Active: "" -->
    <a
      v-if="user.isConnected && user.member.id == organizerId"
      class="block px-4 py-2 text-sm text-cblack cursor-pointer"
      role="menuitem"
      tabindex="-1"
      id="user-menu-item-1"
      >Créer un lien d'invitation</a
    >
  </div>
</template>
