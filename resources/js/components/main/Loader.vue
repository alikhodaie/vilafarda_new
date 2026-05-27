<template>
    <div v-if="is_loading" class="preloader"></div>
</template>

<script>
export default {
    name: "Loader",
    data(){
        return {
            is_loading: false
        }
    },
    mounted() {
        window.eventBus.$on('show_loader', () => {
            this.is_loading = true
        })

        window.eventBus.$on('hide_loader', () => {
            this.is_loading = false
        })
    }
}
</script>

<style scoped>

</style>
