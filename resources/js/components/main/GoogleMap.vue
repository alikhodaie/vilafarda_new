<template>
    <div>
        <div :id="id" :style="`height: ${height}px`"></div>
        <div v-if="can_select">
            <input type="hidden" name="latitude" v-model="latitude_data">
            <input type="hidden" name="longitude" v-model="longitude_data">
        </div>
    </div>
</template>

<script>
export default {
    name: "GoogleMap",
    props: {
        can_select: {
            default: false,
            type: Boolean
        },
        watch_province: {
            default: false,
            type: Boolean
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
        should_mark: {
            default: true,
            type: Boolean
        }
    },
    data() {
        return {
            id: 1,
            latitude_data: null,
            longitude_data: null,
            map: {
                instance: null,
                latitude: 0,
                longitude: 0,
                zoom: 11,
                markers: []
            }
        }
    },
    watch: {
        latitude_data: (lat) => {
            window.eventBus.$emit('lat_updated', lat)
        },
        longitude_data: (lng) => {
            window.eventBus.$emit('lng_updated', lng)
        },
    },
    created() {
        this.id = 'map-' + Math.random().toString().split(".")[1]

        setTimeout(function(){
            if (! this.can_select) {
                this.map.zoom = 15
            }
            this.setLatLng(this.latitude, this.longitude);
        }.bind(this), 500)

        setTimeout(function(){
            if (this.should_mark){
                const latLng = {lat: this.latitude, lng: this.longitude}
                this.setMarker(latLng);
            }
        }.bind(this), 1000)
    },
    mounted() {
        if (this.watch_province){
            window.eventBus.$on('province_updated', (province) => {
                this.setLatLng(province.latitude, province.longitude);
            })
        }
    },
    methods: {
        makeMap(){
            const latLng = {lat: this.map.latitude, lng: this.map.longitude};

            this.map.instance = new google.maps.Map(document.getElementById(this.id), {
                center: latLng,
                zoom: this.map.zoom,
            });

            if (this.can_select){
                this.map.instance.addListener("click", (e) => {
                    this.setMarker(e.latLng.toJSON())
                });
            }
            else if (this.should_mark) {
                this.setMarker(latLng);
            }
        },
        setMarker(latLng){
            const map = this.map.instance
            this.removeMarkers()
            const marker = new google.maps.Marker({
                position: latLng,
                map
            })

            this.map.markers.push(marker);

            map.panTo(latLng)
            if (this.can_select){
                this.setValue(latLng);
            }
        },
        removeMarkers(){
            this.map.markers.map((marker) => {
                marker.setMap(null);
            })
        },
        setLatLng(latitude, longitude){
            this.map.latitude = parseFloat(latitude)
            this.map.longitude = parseFloat(longitude)

            this.makeMap()
        },
        setValue(latLng){
            this.latitude_data = latLng.lat
            this.longitude_data = latLng.lng
        }
    }
}
</script>

<style scoped>

</style>
