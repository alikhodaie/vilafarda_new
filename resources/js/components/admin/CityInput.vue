<template>
    <div dir="rtl">
        <multiselect :placeholder="placeholder"
                     v-model="city"
                     :options="cities"
                     :selectLabel="select_label"
                     :selectedLabel="selected_label"
                     :deselectLabel="deselect_label"
                     label="name"
                     track-by="id">
            <template slot="noResult">{{ no_result_text }}</template>
            <template slot="noOptions">{{ no_options_text }}</template>
        </multiselect>
        <input type="hidden" :name="name" :value="city ? city.id : null">
    </div>


</template>

<script>
import Multiselect from "vue-multiselect";

export default {
    name: "CityInput",
    props: {
        placeholder: '',
        select_label: '',
        selected_label: '',
        deselect_label: '',
        no_result_text: '',
        no_options_text: '',
        name: {
            default: 'city'
        },
        old: {}
    },
    components: {Multiselect},
    data() {
        return {
            city: null,
            cities: []
        }
    },
    watch: {
        cities: function (cities) {
            if (this.old) {
                cities.map((item) => {
                    if (item.id == this.old) {
                        this.city = item
                    }
                })
            }
        },
        city: function (city) {
            window.eventBus.$emit('city_updated', city)
        }
    },
    mounted() {
        window.eventBus.$on('province_updated', (province) => {
            this.city = null;
            this.cities = [];
            if (province != null) {
                this.cities = province.cities;
            }
        })
    },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
