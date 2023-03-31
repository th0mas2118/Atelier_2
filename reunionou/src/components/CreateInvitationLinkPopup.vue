<script setup lang="ts">
import axios from 'axios'

const createInvitationLink = () => {}
</script>
<template>
  <div
    class="fixed w-screen h-screen text-cblack flex align-center justify-center top-0 bg-cblack z-50 p-2"
  >
    <div
      class="m-auto p-2 bg-cwhite text-cblack max-w-[500px] flex flex-col max-h-[300px] justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
    >
      <h2 class="font-bold text-xl">Créer un lien d'invitation</h2>
      <i class="text-xs text-cgray text-center mb-4"
        >Ce lien permet à un utilisateur non inscrit à la plateforme de participer un évenement</i
      >
      <div class="flex flex-col align-center justify-center gap-2">
        <div>
          <label class="block text-white-700 text-sm mb-2" for="password">Nom de l'invité</label>
          <input
            type="text"
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
          />
        </div>
        <div>
          <label class="block text-white-700 text-sm mb-2" for="password">Prénom de l'invité</label>
          <input
            type="text"
            class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
          />
        </div>
        <button
          class="bg-cpurple hover:bg-[#9a69fe] mt-2 text-cwhite py-2 px-4 rounded-xl transition-all duration-300 overflow-hidden whitespace-nowrap"
        >
          Créer le lien
        </button>
      </div>
    </div>
  </div>
</template>
