<script setup lang="ts">
import { ref } from 'vue'

const currentPage = ref(0)
</script>

<template>
  <form
    action=""
    class="bg-cwhite text-cblack flex flex-col min-h-[600px] justify-start items-center max-w-[800px] w-full h-full m-auto rounded-3xl shadow-lg text-cblack overflow-x-hidden"
  >
    <h1 class="text-cblack font-bold min-w-[250px] text-2xl w-full text-center p-4">
      CRÉER UN EVENEMENT
    </h1>
    <div
      class="h-full w-full flex flex-grow transition-all duration-500 ease-in-out"
      :style="{ transform: `translateX(-` + currentPage * 100 + `%)` }"
    >
      <section id="page1" class="flex-shrink-0 w-full h-full p-4">
        <div id="inputs" class="flex w-full h-full flex-grow flex-wrap">
          <div class="flex flex-col w-2/4 p-4 gap-4">
            <div>
              <label class="block text-white-700 text-sm font-bold mb-2" for="titre">Titre</label>
              <input
                class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
                name="titre"
                id="titre"
                type="text"
                placeholder="Titre"
              />
            </div>
            <div>
              <label class="block text-white-700 text-sm font-bold mb-2" for="date">Date</label>
              <input
                class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
                name="date"
                id="date"
                type="datetime-local"
                placeholder="Date"
              />
            </div>
          </div>
          <div class="flex flex-col w-2/4 p-4 gap-4">
            <div>
              <label class="block text-white-700 text-sm font-bold mb-2" for="adresse">
                Adresse
              </label>
              <input
                class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
                name="adresse"
                id="adresse"
                type="text"
                placeholder="Adresse"
              />
            </div>
          </div>
          <div class="mb-4 w-full p-4 flex-grow">
            <label class="block text-white-700 text-sm font-bold mb-2" for="username"
              >Description</label
            >
            <textarea
              class="shadow appearance-none border-[#E5E7EB] rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-transparent focus:border-[#E5E7EB]"
              name="description"
              id="description"
              type="text"
              placeholder="Description"
              rows="8"
            />
          </div>
        </div>
      </section>
      <section id="page2" class="flex-shrink-0 w-full h-full p-4">
        <div id="inputs" class="flex w-full h-full flex-grow flex-wrap">
          <div class="flex flex-col w-2/4 p-4 gap-4">
            <div>
              <input
                checked
                id="purple-checkbox"
                type="checkbox"
                value=""
                class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              />
              <label
                for="purple-checkbox"
                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                >Purple</label
              >
            </div>
          </div>
        </div>
      </section>
    </div>
    <div class="flex justify-between w-full p-4">
      <button
        type="button"
        v-if="currentPage > 0"
        @click="currentPage--"
        class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap"
      >
        Précedent
      </button>
      <button
        type="button"
        v-if="currentPage < 2"
        @click="currentPage++"
        class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap self-end ml-auto"
      >
        Suivant
      </button>
      <button
        type="button"
        v-if="currentPage == 2"
        @click=""
        class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap self-end ml-auto"
      >
        Créer l'évenement
      </button>
    </div>
  </form>
</template>

<style lang=""></style>
