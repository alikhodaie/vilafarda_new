<template>
    <div class="input-group">
        <input type="email" v-model="email" class="form-control" :placeholder="email_text">
        <div class="input-group-append">
            <button type="button" @click="sendRequest" class="input-group-text theme-bg b-0 text-light">{{ subscribe_text }}</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Newsletter",
    props: ['route', 'subscribe_text', 'email_text'],
    data() {
        return {
            email: ''
        }
    },
    methods: {
        sendRequest() {
            window.eventBus.$emit('show_loader', true)

            axios.post(this.route, {
                email: this.email
            }).then(response => {

                this.$root.showAlert(response.data, 'success', true)
                window.eventBus.$emit('hide_loader', true)

            }).catch(error => {
                let message = '';
                this.$root.formatErrors(error).map(item => {
                    message += item + '\n'
                })
                this.$root.showAlert(message, 'error', true)

                window.eventBus.$emit('hide_loader', true)
            })
        }
    }
}
</script>

<style scoped>

</style>
