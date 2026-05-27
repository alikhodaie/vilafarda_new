<template>
    <div dir="rtl">
        <multiselect :placeholder="placeholder"
                     v-model="province"
                     :options="provinces"
                     :selectLabel="select_label"
                     :selectedLabel="selected_label"
                     :deselectLabel="deselect_label"
                     label="name"
                     track-by="id">
            <template slot="noResult">{{ no_result_text }}</template>
            <template slot="noOptions">{{ no_options_text }}</template>
        </multiselect>
        <input type="hidden" :name="name" :value="province ? province.id : null">
    </div>


</template>

<script>
import Multiselect from "vue-multiselect";

export default {
    name: "ProvinceInput",
    props: {
        only_has_home: false,
        placeholder: '',
        select_label: '',
        selected_label: '',
        deselect_label: '',
        no_result_text: '',
        no_options_text: '',
        route: {},
        name: {
            default: 'province'
        },
        old: {}
    },
    components: {Multiselect},
    data() {
        return {
            province: null,
            provinces: []
        }
    },
    watch: {
        provinces: function (provinces) {
            if (this.old) {
                provinces.map((item) => {
                    if (item.id == this.old) {
                        this.province = item
                    }
                })
            }
        },
        province: function (province) {
            window.eventBus.$emit('province_updated', province)
        }
    },
    created() {
        this.get()
    },
    methods: {
        get() {
            let route = this.route
            if (this.only_has_home){
                route += "?" + new URLSearchParams({has_home: true}).toString()
            }

            axios.get(route).then(response => {
                this.provinces = response.data
            }).catch(error => {

            })
        },
    }


}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
@media (max-width: 770px)
{
    .multiselect, .multiselect__input, .multiselect__single, .multiselect__tags
    {
        font-size: 10px;
    }

    .multiselect__option::after
    {
        font-size: 10px;
    }
}
</style>
