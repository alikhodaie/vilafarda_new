<template>
    <div>
        <div class="form-group">
            <leaftlet-map :latitude="latitude" :longitude="longitude" :watch_province="true"></leaftlet-map>
            <p class="mt-2 text-muted">با ضربه روی نقشه مکان اقامتگاه را ثبت کنید. با استفاده از کلید + بر روی نقشه زوم کنید.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep2",
    props: {
        old: [],
        validate_route: '',
        select_label: '',
        selected_label: '',
        deselect_label: '',
        no_result_text: '',
        no_options_text: '',
    },
    data(){
        return {
            should_mark: false,

            latitude: null,
            longitude: null
        }
    },
    mounted() {
        window.eventBus.$on('lat_updated', (lat) => {
            this.latitude = lat
        })
        window.eventBus.$on('lng_updated', (lng) => {
            this.longitude = lng
        })
    },
    watch: {
        latitude: function (latitude){
            this.check()
        },
        longitude: function (longitude){
            this.check()
        },
    },
    created() {
        let x = 0

        if (this.old.latitude){
            x++
            this.latitude = parseFloat(this.old.latitude)
        }
        if (this.old.longitude){
            x++
            this.longitude = parseFloat(this.old.longitude)
        }

        if (x === 2){
            this.should_mark = true
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    methods: {
        activeNextPage(){
            window.eventBus.$emit('next_page', 3)
        },
        inactiveNextPage(){
            window.eventBus.$emit('next_page', 2)
        },
        check(){
            if (this.longitude && this.latitude){
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
                longitude: this.longitude,
                latitude: this.latitude,
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
