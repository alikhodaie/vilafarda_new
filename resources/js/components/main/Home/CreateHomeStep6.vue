<template>
    <div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="main_guest" class="form-label">{{ main_guest_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" id="main_guest" class="form-control" name="main_guest" v-model="main_guest">
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="extra_guest" class="form-label">{{ extra_guest_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" id="extra_guest" class="form-control" name="extra_guest" v-model="extra_guest">
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="yard" class="form-label">{{ yard_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" name="yard" v-model="yard" hidden>
                <money v-model="yard"
                       v-bind="money"
                       inputmode="numeric"
                       id="yard"
                       class="form-control"
                       min="0"
                ></money>
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="infrastructure" class="form-label">{{ infrastructure_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="number" name="yard" v-model="infrastructure" hidden>
                <money v-model="infrastructure"
                       v-bind="money"
                       inputmode="numeric"
                       id="infrastructure"
                       class="form-control"
                       min="0"
                ></money>
            </div>
        </div>
    </div>
</template>

<script>
import {Money} from 'v-money'

export default {
    components: {Money},
    name: "CreateHomeStep6",
    props: ['old', 'validate_route', 'main_guest_title', 'extra_guest_title', 'yard_title', 'infrastructure_title'],
    data() {
        return {
            can_update: false,
            money: {
                prefix: 'متر ',
                precision: 0
            },
            main_guest: '',
            extra_guest: '',
            yard: '',
            infrastructure: ''
        }
    },
    created() {
        if (this.old.main_guest){
            this.main_guest = this.old.main_guest
        }
        if (this.old.extra_guest){
            this.extra_guest = this.old.extra_guest
        }
        if (this.old.yard){
            this.yard = this.old.yard
        }
        if (this.old.infrastructure){
            this.infrastructure = this.old.infrastructure
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    watch: {
        main_guest: function (main_guest){
            this.check()
        },
        extra_guest: function (extra_guest){
            this.check()
        },
        yard: function (yard){
            this.check()
        },
        infrastructure: function (infrastructure){
            this.check()
        }
    },
    methods: {
        activeNextPage(){
            window.eventBus.$emit('next_page', 10)
        },
        inactiveNextPage(){
            window.eventBus.$emit('next_page', 6)
        },
        check(){
            if (this.main_guest && this.extra_guest && this.yard && this.infrastructure){
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
                main_guest: this.main_guest,
                extra_guest: this.extra_guest,
                yard: this.yard,
                infrastructure: this.infrastructure
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
