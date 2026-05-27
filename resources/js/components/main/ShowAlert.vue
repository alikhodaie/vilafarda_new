<template>
</template>

<script>
export default {
    name: "ShowAlert",
    props: {
        message: '',
        type: '',
        is_toast: {
            default: false
        }
    },
    mounted() {
        this.$root.showAlert(this.message, this.type, this.is_toast)
    }
}
</script>

<style scoped>

</style>
