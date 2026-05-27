<template>
    <div>
        <div class="form-group">
            <div v-for="option in values">
                <span class="px-5">
                    <input type="checkbox" :id="`option-${option.id}`" class="form-check-input" :value="option.id" v-model="options">
                </span>
                <label :for="`option-${option.id}`" class="form-label">
                    <img :src="option.icon_path" :alt="option.title" width="30">
                    {{ option.title }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep8",
    props: ['old', 'validate_route', 'values'],
    data() {
        return {
            can_update: false,
            options: []
        }
    },
    created() {
        if (this.old){
            this.old.map((option) => {
                this.options.push(option.id)
            })
        }

        setTimeout(function (){
            this.can_update = true

            window.eventBus.$emit('next_page', 10)
        }.bind(this), 500)
    },
    watch: {
        options: function (options){
            window.eventBus.$emit('next_page', 10)

            this.validate()
        },
    },
    methods: {
        validate(){
            if (! this.can_update){
                return false;
            }

            let params = {
                options: this.options
            };

            axios.post(this.validate_route, params)
                .then((response) => {
                })
                .catch((error) => {
                    // let message = '';
                    // this.$root.formatErrors(error).map(item => {
                    //     message += item + '\n'
                    // })
                    // this.$root.showAlert(message, 'error', true)
                });
        }
    }
}
</script>

<style scoped>

</style>
