<template>
    <div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="name" class="form-label">{{ name_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input type="text" id="name" class="form-control" name="name" v-model="name">
            </div>
        </div>
        <div class="form-group mt-5 row">
            <div class="col-12 col-md-4">
                <label for="description" class="form-label">{{ description_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <textarea name="description" id="description" cols="30" rows="10" class="form-control" v-model="description"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep4",
    props: ['rules_title', 'old', 'validate_route', 'name_title', 'description_title'],
    data() {
        return {
            can_update: false,
            name: '',
            description: ''
        }
    },
    created() {
        if (this.old.name){
            this.name = this.old.name
        }
        if (this.old.description){
            this.description = this.old.description
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    watch: {
        name: function (name){
            this.check()
        },
        description: function (description){
            this.check()
        }
    },
    methods: {
        activeNextPage(){
            window.eventBus.$emit('next_page', 5)
        },
        inactiveNextPage(){
            window.eventBus.$emit('next_page', 4)
        },
        check(){
            if (this.name && this.description){
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
                name: this.name,
                description: this.description
            };

            axios.post(this.validate_route, params)
                .then((response) => {
                    if (response.data){
                        this.activeNextPage()
                    }
                })
                .catch((error) => {
                    let message = '';
                    this.$root.formatErrors(error).map(item => {
                        message += item + '\n'
                    })
                    this.$root.showAlert(message, 'error', true)
                    this.inactiveNextPage()
                });
        }
    }
}
</script>

<style scoped>

</style>
