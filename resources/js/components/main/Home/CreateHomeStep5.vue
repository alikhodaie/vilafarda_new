<template>
    <div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="atmosphere" class="form-label">{{ atmosphere_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <select name="atmosphere" id="atmosphere" class="form-control" v-model="atmosphere">
                    <option v-for="item in atmospheres" :value="item.value">{{ item.fa_text }}</option>
                </select>
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="type" class="form-label">{{ type_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <select name="type" id="type" class="form-control" v-model="type">
                    <option v-for="item in types" :value="item.value">{{ item.fa_text }}</option>
                </select>
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="area" class="form-label">{{ area_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <select name="area" id="area" class="form-control" v-model="area">
                    <option v-for="item in areas" :value="item.value">{{ item.fa_text }}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
    name: "CreateHomeStep5",
    components: {Multiselect},
    props: ['validate_route', 'old', 'atmosphere_title', 'type_title', 'area_title', 'atmospheres', 'types', 'areas', 'options_title', 'place_holder', 'select_label', 'selected_label', 'deselect_label', 'no_result_text', 'no_options_text', 'values'],
    data() {
        return {
            can_update: false,
            atmosphere: null,
            type: null,
            area: null
        }
    },
    watch: {
        atmosphere: function (atmosphere){
            this.check()
        },
        type: function (type){
            this.check()
        },
        area: function (area){
            this.check()
        },
    },
    created() {
        if (this.old.atmosphere) {
            this.atmosphere = this.old.atmosphere
        }
        if (this.old.type) {
            this.type = this.old.type
        }
        if (this.old.area) {
            this.area = this.old.area
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    methods: {
        activeNextPage(){
            window.eventBus.$emit('next_page', 6)
        },
        inactiveNextPage(){
            window.eventBus.$emit('next_page', 5)
        },
        check(){
            if (this.atmosphere && this.type && this.area){
                this.validate()
            }
            else {
                this.inactiveNextPage()
            }
        },
        validate(){
            if (! this.can_update){
                return false;
            }

            let params = {
                atmosphere: this.atmosphere,
                type: this.type,
                area: this.area
            };

            axios.post(this.validate_route, params)
                .then((response) => {
                    if (response.data){
                        this.activeNextPage()
                    }
                })
                .catch((error) => {
                    // let message = '';
                    // this.$root.formatErrors(error).map(item => {
                    //     message += item + '\n'
                    // })
                    // this.$root.showAlert(message, 'error', true)
                    this.inactiveNextPage()
                });
        }
    }
}
</script>

<style scoped>

</style>
