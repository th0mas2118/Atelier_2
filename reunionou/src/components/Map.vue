<template>
    <div class="mapbox">
        <h2>Carte Open Street Map</h2>

        <l-map ref="map" class="map" v-model:center="center" v-model:zoom="zoom" :max-zoom="maxZoom" :min-zoom="minZoom"
            :zoom-control="false">
            <l-tile-layer :url="osmUrl" />

            <l-marker v-for="marker in markers" :key="marker.id" :lat-lng="marker.coordinates" />

        </l-map>
    </div>
</template>

<script>
import 'leaflet/dist/leaflet.css';
import { LMap, LTileLayer, LMarker } from "@vue-leaflet/vue-leaflet"

export default {
    name: 'CarteOS',
    components: { LMap, LTileLayer, LMarker },

    data() {
        return {
            osmUrl: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            center: [48.6937223, 6.1792289],
            zoom: 13,
            maxZoom: 18, // indispensable pour faire fonctionner le zoom sur la carte
            minZoom: 1, // idem
            markers: [
                { id: 1, coordinates: [48.6964074, 6.1792289] },
                { id: 2, coordinates: [48.6844, 6.185] },
                { id: 3, coordinates: [48.6935244, 6.1832861] }
            ]
        }
    }
}
</script>

<style>
.map {
    position: absolute;
}

.mapbox {
    position: relative;
    margin: auto;
    width: 600px;
    height: 500px;
}
</style>