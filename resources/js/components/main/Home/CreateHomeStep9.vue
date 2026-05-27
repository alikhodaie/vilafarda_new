<template>
    <div>
        <div class="form-group" v-for="health in values">
            <input type="checkbox" :id="`health-${health.id}`" class="form-check-input" :value="health.id" v-model="healths">
            <label :for="`health-${health.id}`" class="form-label" style="margin-right: 30px;">
                {{ health.title }}
            </label>
        </div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="more" class="form-label">سایر موارد</label>
            </div>
            <div class="col-12 col-md-8">
                <input id="more" class="form-control" v-model="more">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep9",
    props: ['old', 'validate_route', 'values'],
    data() {
        return {
            can_update: false,
            healths: [],
            more: ''
        }
    },
    created() {
        if (this.old.healths){
            this.old.healths.map((health) => {
                this.healths.push(health.id)
            })
        }
        if (this.old.more){
            this.more = this.old.more
        }

        setTimeout(function (){
            this.can_update = true

            window.eventBus.$emit('next_page', 10)
        }.bind(this), 500)
    },
    watch: {
        healths: function (healths){
            window.eventBus.$emit('next_page', 10)

            this.validate()
        },
        more: function (more){
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
                healths: this.healths,
                more: this.more
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
