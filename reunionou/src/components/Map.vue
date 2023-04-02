<template>
  <div class="mapbox">
    <l-map
      @click="onMapClick"
      ref="map"
      class="map"
      v-model:center="props.center"
      v-model:zoom="zoom"
      :max-zoom="maxZoom"
      :min-zoom="minZoom"
      :zoom-control="false"
      :useGlobalLeaflet="false"
    >
      <l-tile-layer :url="osmUrl" />

      <l-marker
        v-for="marker in props.replaceMarker
          ? clickLocation
            ? [clickLocation]
            : props.markers
          : clickLocation
          ? [...props.markers, clickLocation]
          : props.markers"
        :key="marker.id.toString()"
        :lat-lng="marker.coordinates"
      >
        <l-popup>{{ marker.address }}</l-popup>
      </l-marker>
    </l-map>
  </div>
</template>

<script setup lang="ts">
import 'leaflet/dist/leaflet.css'
import { LMap, LTileLayer, LMarker, LPopup } from '@vue-leaflet/vue-leaflet'
import { ref, reactive, onMounted } from 'vue'
import type { LeafletMouseEvent } from 'leaflet'
import axios from 'axios'

const emit = defineEmits(['onMapClick'])

const onMapClick = async (e: LeafletMouseEvent) => {
  if (!e.latlng || !props.allowClick) return
  clickLocation.value = {
    id: 0,
    coordinates: [e.latlng.lat, e.latlng.lng],
    address: (await getAddress([e.latlng.lat, e.latlng.lng])) ?? 'Adresse inconnue'
  }

  emit('onMapClick', clickLocation.value)
}

const getAddress = async (gps: [Number, Number]): Promise<string | null> => {
  try {
    const res = await axios.get(`https://geocode.maps.co/reverse?lat=${gps[0]}&lon=${gps[1]}`)
    const address = res.data.address

    const formatedAddress = `${address.house_number ?? ''} ${address.road ?? ''}, ${
      address.postcode ?? ''
    } ${address.city ?? ''} ${address.country ?? ''}`.trim()
    return formatedAddress
  } catch (error) {
    return null
  }
}

const props = defineProps({
  markers: {
    type: Array<{
      id: Number
      coordinates: [Number, Number]
      address: String
    }>,
    required: false
  },
  center: {
    type: Array<Number>,
    required: true
  },
  allowClick: {
    type: Boolean,
    required: false
  },
  replaceMarker: {
    type: Boolean,
    required: false
  }
})

const osmUrl = ref('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
const zoom = ref(13)
const maxZoom = ref(18)
const minZoom = ref(1)
const clickLocation: object | null = ref(null)
</script>

<style>
.map {
  position: absolute;
}

.mapbox {
  position: relative;
  margin: auto;
  width: 100%;
  height: 100%;
}
</style>
