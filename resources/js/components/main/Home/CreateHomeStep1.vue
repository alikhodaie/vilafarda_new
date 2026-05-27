<template>
    <div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="province" class="form-label">{{ province_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <province-input
                    :placeholder="province_place_holder"
                    :select_label="select_label"
                    :selected_label="selected_label"
                    :deselect_label="deselect_label"
                    :no_result_text="no_result_text"
                    :no_options_text="no_options_text"
                    :route="province_route"
                    :old="province"
                ></province-input>
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="city" class="form-label">{{ city_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <city-input
                    :placeholder="city_place_holder"
                    :select_label="select_label"
                    :selected_label="selected_label"
                    :deselect_label="deselect_label"
                    :no_result_text="no_result_text"
                    :no_options_text="no_options_text"
                    :old="city"
                ></city-input>
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="address" class="form-label">{{ address_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <textarea id="address" class="form-control" name="address" v-model="address"></textarea>
                <p class="mt-2 text-muted">آدرس اقامتگاه را با جزییات کامل وارد کنید تا میهمان پس از رزرو به راحتی بتوانند اقامتگاه را پیدا کنند.</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep1",
    props: {
        old: {},
        validate_route: '',
        province_title: '',
        city_title: '',
        address_title: '',
        province_route: '',
        city_place_holder: '',
        province_place_holder: '',
        select_label: '',
        selected_label: '',
        deselect_label: '',
        no_result_text: '',
        no_options_text: '',
    },
    data() {
        return {
            can_update: false,
            province: null,
            city: null,
            address: null
        }
    },
    watch: {
        province: function (province){
            if (! this.old.city){
                this.city = null
            }
            this.check()
        },
        city: function (city){
            this.check()
        },
        address: function (address){
            this.check()
        }
    },
    created() {
        if (this.old.province){
            this.province = this.old.province
        }
        if (this.old.city){
            this.city = this.old.city
        }
        if (this.old.address){
            this.address = this.old.address
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    mounted() {
        window.eventBus.$on('province_updated', (province) => {
            this.province = province
        })
        window.eventBus.$on('city_updated', (city) => {
            this.city = city
        })
    },
    methods: {
        activeNextPage(){
            window.eventBus.$emit('next_page', 2)
        },
        inactiveNextPage(){
            window.eventBus.$emit('next_page', 1)
        },
        check(){
            if (this.city && this.province && this.address){
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
                province: this.province.id,
                city: this.city.id,
                address: this.address
            };

            axios.post(this.validate_route, params)
                .then((response) => {
                    if (response.data){
                        this.activeNextPage()
                    }
                })
                .catch((error) => {
                    // let message = '';
                    // error.response.data.errors.map(item => {
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
