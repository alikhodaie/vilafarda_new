<template>
    <div :style="`height: ${height}px`">
        <l-map ref="map" :style="`height: ${height}px`" :zoom.sync="data_zoom" :center.sync="map_coordinates" @click="mapClicked"
               @ready="onReady" @update:zoom="zoomUpdated" @update:center="centerUpdated">
            <l-tile-layer :url="'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'" :attribution="attribution"></l-tile-layer>
            <l-marker v-if="!layer && target_latitude !== null && target_longitude !== null" :lat-lng="target_coordinates"></l-marker>
            <l-circle
                v-if="layer && target_coordinates"
                :lat-lng="target_coordinates"
                :radius="radius"
                :color="circleColor"
                :fill-color="circleFillColor"
                :fill-opacity="circleFillOpacity"
                :weight="2"
            />
        </l-map>

        <input type="hidden" name="latitude" v-model="target_latitude">
        <input type="hidden" name="longitude" v-model="target_longitude">
    </div>
</template>

<script>


export default {
    name: "LeaftletMap",
    props: {
        layer: {
            default: false,
            type: Boolean
        },
        readonly: {
            default: false,
            type: Boolean
        },
        watch_province: {
            default: false,
            type: Boolean
        },
        zoom: {
            default: 11,
            type: Number
        },
        height: {
            default: 300,
            type: Number
        },
        latitude: {
            default: 35.7050808,
            type: Number
        },
        longitude: {
            default: 51.4057646,
            type: Number
        },
        radius: {
            default: 750,
            type: Number
        },
        circleColor: {
            default: '#D39D1A',
            type: String
        },
        circleFillColor: {
            default: '#D39D1A',
            type: String
        },
        circleFillOpacity: {
            default: 0.22,
            type: Number
        },
    },
    data() {
        return {
            map: null,
            data_zoom: 0,
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',

            map_coordinates: null,

            target_coordinates: null,
            target_latitude: null,
            target_longitude: null
        }
    },
    watch: {
        target_latitude: (lat) => {
            window.eventBus.$emit('lat_updated', lat)
        },
        target_longitude: (lng) => {
            window.eventBus.$emit('lng_updated', lng)
        },
    },
    created() {
        this.data_zoom = this.zoom

        if (this.latitude && this.longitude){
            this.setMarker(this.latitude, this.longitude)
        }
    },
    mounted() {
        if (this.watch_province){
            window.eventBus.$on('province_updated', (province) => {
                if (this.map){
                    this.map.mapObject.setView({lat: province.latitude, lng: province.longitude}, this.zoom)
                    this.resize()
                }
                else {
                    this.centerUpdated({lat: province.latitude, lng: province.longitude})
                }
            })
        }
    },
    methods: {
        onReady(){
            this.$nextTick(() => {
                this.map = this.$refs.map;
            });
        },
        zoomUpdated(zoom){
            this.data_zoom = zoom;
        },
        centerUpdated(center){
            this.map_coordinates = [center.lat, center.lng]
            this.resize()
        },
        resize(){
            if (this.map){
                this.map.mapObject._onResize();
            }
        },
        mapClicked(data){
            if (! this.readonly){
                this.setMarker(data.latlng.lat, data.latlng.lng)
            }
        },
        setMarker(lat, lng){
            this.centerUpdated({lat: lat, lng: lng})
            this.target_latitude = lat
            this.target_longitude = lng
            this.target_coordinates = [lat, lng]
        }
    }
}
</script>

<style>

</style>
