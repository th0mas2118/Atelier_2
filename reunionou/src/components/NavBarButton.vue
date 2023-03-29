<script lang="ts">
export default {
    props: {
        text: String,
        route: String,
        user: String,
    },
}

</script>

<template lang="">
    <div v-if="!user">
        <router-link
        class="mr-2 inline-block bg-white text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0"
        :to="{name:route}">{{text}}</router-link>
    </div>
    <div v-if="user">
        <router-link
        class="mr-2 inline-block bg-white text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0"
        :to="`/${route}/${user}`">{{text}}</router-link>
    </div>
</template>