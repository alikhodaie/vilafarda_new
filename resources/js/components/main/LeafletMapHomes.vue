<template>
    <l-map ref="map" style="height: 100%" :zoom.sync="zoom" :center.sync="coordinates" @update:zoom="zoomUpdated" @update:center="centerUpdated">
        <l-tile-layer :url="'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'" :attribution="attribution"></l-tile-layer>
        <template v-for="home in homes">
            <l-marker :lat-lng="[home.latitude, home.longitude]">
                <l-popup>
                    <div class="map-popup-wrap">
                        <div class="map-popup">
                            <div class="_RentUP_proprty_grid">
                                <div class="_RentUP_prt_grid_thumb">
                                    <a :href="home.link">
                                        <img :src="home.cover_path" class="img-fluid" :alt="home.name" />
                                    </a>
                                    <div class="rhomy_abs_caption">
                                        <h4 class="rhomy_pr_name">{{ home.show_price }}</h4>
                                    </div>
                                </div>
                                <div class="_RentUP_prt_grid_caption">
                                    <div class="_RentUP_prt_head text-right">
                                        <h5 class="_RentUP_prt_title">{{ home.name }}</h5>
                                        <span class="_RentUP_prt_location">
                                            <i class="ti-location-pin ml-1"></i>
                                            {{ home.province.name }}, {{ home.city.name }}
                                        </span>
                                    </div>
                                    <div class="_RentUP_prt_bot">
                                        <div class="_RentUP_prt_bot_flex"></div>
                                        <div class="_RentUP_prt_bot_left">
                                            <a :href="home.link" class="mp_rhomy_btn">جزئیات</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </l-popup>
            </l-marker>
        </template>
    </l-map>
</template>

<script>
export default {
    name: "LeafletMapHomes",
    props: {
        homes: [],
        latitude: {
            default: null,
            type: Number
        },
        longitude: {
            default: null,
            type: Number
        }
    },
    data() {
        return {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            zoom: 6,
            coordinates: [35.7050808, 51.4057646]
        }
    },
    created() {
        if (this.latitude && this.longitude){
            this.coordinates = [this.latitude, this.longitude]
        }
    },
    mounted() {
        setInterval(() => {
            this.$refs.map.mapObject.invalidateSize();
        }, 1000);
    },
    methods: {
        zoomUpdated(zoom) {
            this.zoom = zoom;
        },
        centerUpdated(center) {
            this.coordinates = [center.lat, center.lng]
        },
    }
}
</script>

<style scoped>

</style>
