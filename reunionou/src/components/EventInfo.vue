<script setup lang="ts">
import { ref } from 'vue'

const currentView = ref(0)
const showOtherMenu = ref(false)
</script>

<template lang="">
  <div
    class="bg-cwhite text-cblack flex flex-col min-h-[600px] justify-start items-center w-full h-full m-4 rounded-3xl shadow-lg text-cblack overflow-x-hidden"
  >
    <header
      class="p-8 w-full h-full flex flex-col md:flex-row justify-between items-start md:items-center border-solid border-b-2 border-cwhite2 gap-4"
    >
      <div
        id="header-presentation"
        class="flex items-center gap-4 flex-col w-full sm:flex-row text-center md:text-left"
      >
        <div
          class="w-16 h-16 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default aspect-square"
        >
          🔥
        </div>
        <div>
          <h1 class="text-2xl">GROSSE MANIFESTATION OUAIS</h1>
          <p class="text-md text-cgray">10 members</p>
        </div>
      </div>
      <div
        id="header-controls"
        class="flex items-center justify-center md:w-auto md:justify-between gap-2 w-full flex-wrap md:flex-nowrap"
      >
        <button
          :class="`${
            currentView == 0
              ? 'bg-cpurple text-cwhite2 hover:bg-[#9a69fe]'
              : 'bg-cwhite2 text-cpurple hover:bg-[#ececec]'
          }  w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 aspect-square`"
          @click="currentView = 0"
        >
          <i class="fa-solid fa-info"></i>
        </button>
        <button
          :class="`${
            currentView == 1
              ? 'bg-cpurple text-cwhite2 hover:bg-[#9a69fe]'
              : 'bg-cwhite2 text-cpurple hover:bg-[#ececec]'
          }  w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 aspect-square`"
          @click="currentView = 1"
        >
          <i class="fa-solid fa-map"></i>
        </button>
        <button
          :class="`${
            currentView == 2
              ? 'bg-cpurple text-cwhite2 hover:bg-[#9a69fe]'
              : 'bg-cwhite2 text-cpurple hover:bg-[#ececec]'
          }  w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 aspect-square`"
          @click="currentView = 2"
        >
          <i class="fa-solid fa-cloud"></i>
        </button>
        <button
          class="bg-cpurple hover:bg-[#9a69fe] text-cwhite py-2 px-4 rounded-3xl transition-all duration-300 overflow-hidden whitespace-nowrap"
        >
          Participer
        </button>
        <button
          class="bg-cwhite text-cgray w-10 h-10 flex justify-center items-center rounded-full transition-all duration-300 hover:bg-[#ececec] aspect-square"
          @click="showOtherMenu = !showOtherMenu"
        >
          <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
      </div>
    </header>

    <div
      id="content"
      class="h-full w-full flex flex-grow min-h-[400px] transition-all duration-500 ease-in-out"
      :style="{ transform: `translateX(-` + currentView * 100 + `%)` }"
    >
      <section
        id="event-info"
        class="w-full h-full flex flex-col md:flex-row justify-between items-start p-4 gap-2 flex-shrink-0"
      >
        <div id="event-description" class="w-full md:w-2/4">
          <h3 class="text-cpurple font-bold min-w-[250px] mb-4">Description</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque omnis perferendis
            doloremque ex ratione officiis, odio nulla ipsum iusto delectus cupiditate in debitis
            aperiam libero recusandae maxime quibusdam. Mollitia, consectetur! Lorem, ipsum dolor
            sit amet consectetur adipisicing elit. Accusamus quas aspernatur est fugiat, vero harum.
            Officiis recusandae libero, ipsa nesciunt voluptatem consequatur repudiandae voluptas
            facere ullam rerum quis iste optio.
          </p>
        </div>
        <div id="participant-list" class="max-h-[500px] overflow-y-auto">
          <h3 class="text-cpurple font-bold min-w-[250px] mb-4">Invités</h3>
          <ul class="flex flex-col gap-2 overflow-auto">
            <li class="flex items-center gap-2">
              <div
                class="w-12 h-12 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
              >
                <img
                  src="https://www.gala.fr/imgre/fit/~1~gal~2021~08~02~ddad7c39-59ab-49d3-ba37-04f203f8029c.jpeg/480x480/quality/80/focus-point/757%2C593/photos-emmanuel-macron-en-t-shirt-a-bregancon-pas-le-1er-president-a-se-lacher-en-vacances.jpg"
                  alt=""
                  srcset=""
                />
              </div>
              <p>John Doe</p>
              <div>
                <i class="fa-solid fa-check-circle text-cgreen"></i>
              </div>
            </li>
            <li class="flex items-center gap-2">
              <div
                class="w-12 h-12 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
              >
                <img
                  src="https://upload.wikimedia.org/wikipedia/commons/2/2d/Brigitte_Macron_%28July_2017%29_%28cropped%29.JPG"
                  alt=""
                  srcset=""
                />
              </div>
              <p>John Doe</p>
              <div>
                <i class="fa-solid fa-xmark-circle text-cred"></i>
              </div>
            </li>
            <li class="flex items-center gap-2">
              <div
                class="w-12 h-12 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
              >
                <img
                  src="https://upload.wikimedia.org/wikipedia/commons/2/2d/Brigitte_Macron_%28July_2017%29_%28cropped%29.JPG"
                  alt=""
                  srcset=""
                />
              </div>
              <p>John Doe</p>
              <div>
                <i class="fa-solid fa-xmark-circle text-cred"></i>
              </div>
            </li>
            <li class="flex items-center gap-2">
              <div
                class="w-12 h-12 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
              >
                <img
                  src="https://upload.wikimedia.org/wikipedia/commons/2/2d/Brigitte_Macron_%28July_2017%29_%28cropped%29.JPG"
                  alt=""
                  srcset=""
                />
              </div>
              <p>John Doe</p>
              <div>
                <i class="fa-solid fa-xmark-circle text-cred"></i>
              </div>
            </li>
            <li class="flex items-center gap-2">
              <div
                class="w-12 h-12 rounded-full bg-cwhite2 flex items-center justify-center text-2xl transition-all hover:text-3xl duration-300 cursor-default overflow-hidden"
              >
                <img
                  src="https://www.gala.fr/imgre/fit/~1~gal~2021~08~02~ddad7c39-59ab-49d3-ba37-04f203f8029c.jpeg/480x480/quality/80/focus-point/757%2C593/photos-emmanuel-macron-en-t-shirt-a-bregancon-pas-le-1er-president-a-se-lacher-en-vacances.jpg"
                  alt=""
                  srcset=""
                />
              </div>
              <p>John Doe</p>
              <div>
                <i class="fa-solid fa-check-circle text-cgreen"></i>
              </div>
            </li>
          </ul>
        </div>
      </section>
      <section
        id="event-map"
        class="w-full h-full flex justify-between items-start p-4 gap-2 flex-shrink-0"
      >
        MAP
      </section>
      <section
        id="event-meteo"
        class="w-full h-full flex justify-between items-start p-4 gap-2 flex-shrink-0"
      >
        METEO
      </section>
    </div>
  </div>
</template>
<style lang=""></style>
