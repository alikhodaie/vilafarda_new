<template>
    <div :class="button_class">
        <i v-if="is_favorite" @click="removeFromFavorite" class="text-danger fa fa-heart" style="font-size: 20px;"></i>
        <i v-else @click="addToFavorite" class="text-danger far fa-heart" style="font-size: 20px;"></i>
    </div>
</template>

<script>
export default {
    name: "Favorite",
    props: ['event', 'route', 'old', 'auth_check', 'text_please_login', 'button_class'],
    data() {
        return {
            is_favorite: false
        }
    },
    created() {
        if (this.old){
            this.is_favorite = this.old
        }
    },
    mounted() {
        if (this.event){
            window.eventBus.$on(this.event, (is_favorite) => {
                this.is_favorite = is_favorite
            });
        }
    },
    methods: {
        addToFavorite() {
            if (this.auth_check){
                window.eventBus.$emit('show_loader')

                axios.post(this.route)
                    .then((response) => {
                        if (response.data){
                            this.is_favorite = true
                            window.eventBus.$emit('hide_loader')

                            if (this.event) {
                                window.eventBus.$emit(this.event, true)
                            }
                        }
                    })
            }
            else {
                this.$root.showAlert(this.text_please_login, 'error', true)
            }
        },
        removeFromFavorite() {
            if (this.auth_check){
                window.eventBus.$emit('show_loader')

                axios.delete(this.route)
                    .then((response) => {
                        if (response.data){
                            this.is_favorite = false
                            window.eventBus.$emit('hide_loader')

                            if (this.event) {
                                window.eventBus.$emit(this.event, false)
                            }
                        }
                    })
            }
            else {
                this.$root.showAlert(this.text_please_login, 'error', true)
            }
        }
    }
}
</script>

<style scoped>

</style>
