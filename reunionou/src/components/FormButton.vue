<script lang="ts">
import { useUserStore } from '@/stores/user'
const user = useUserStore()

export default {
  props: {
    function: {
      type: Function
    },
    email: String,
    password: String,
    name: String
  },
  methods: {
    execute() {
      if (this.function) {
        console.log(this.name)
        if (this.name == 'login') {
          this.function({ email: this.email, password: this.password })
        }
      }
    }
  }
}
</script>

<template lang="">
  <button
    class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-xl transition-all duration-300 overflow-hidden whitespace-nowrap"
    type="button"
    @click="execute"
  >
    Se Connecter
  </button>
</template>
