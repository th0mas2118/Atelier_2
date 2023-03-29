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
                if (this.name == "login") {
                    this.function({ email: this.email, password: this.password })
                }
            }
        }
    }
}



</script>

<template lang="">
    <button
            class="bg-cpurple hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="button" @click="execute">
            Se Connecter
          </button>
</template>