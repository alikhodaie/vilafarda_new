<template>
    <div>
        <div class="form-group">
            <change-cover
                :csrf="csrf"
                :route="route_change_cover"
                :max_size="max_size"
                :cover="cover"
            ></change-cover>
        </div>

        <div class="form-group mt-5">
            <change-images
                :csrf="csrf"
                :route="route_change_image"
                :max_size="max_size"
                :images="images"
            ></change-images>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep3",
    props: ['validate_route', 'cover', 'route_change_cover', 'route_change_image', 'csrf', 'cover', 'images', 'max_size'],
    data(){
        return {
            can_update: false,
            count_cover: 0,
            count_images: 0
        }
    },
    watch: {
        count_cover: function (count_cover){
            this.check()
        },
        count_images: function (count_images){
            this.check()
        }
    },
    created(){
        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    mounted(){
        window.eventBus.$on('count_images_updated', (count) => {
            this.count_images = count
        })
        window.eventBus.$on('count_cover_updated', (count) => {
            this.count_cover = count
        })
    },
    methods: {
        showError(response){
            response = JSON.parse(response)
            let message = '';
            $.each(response.errors, function( index, errors ) {
                $.each(errors, function( index, error ) {
                    message += error + '\n'
                });
            });
            this.$root.showAlert(message, 'error', true)
        },
        activeNextPage() {
            window.eventBus.$emit('next_page', 4)
        },
        inactiveNextPage() {
            window.eventBus.$emit('next_page', 3)
        },
        check() {
            if (this.count_cover === 1 && this.count_images >= 7){
                this.activeNextPage()
            }
            else {
                this.inactiveNextPage()
            }
        }
    }
}
</script>

<style scoped>

</style>
