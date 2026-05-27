<template>
    <div>
        <div class="form-group row">
            <div class="col-12 col-md-4">
                <label for="rules" class="form-label">{{ rules_title }}</label>
            </div>
            <div class="col-12 col-md-8">
                <textarea name="rules" id="rules" cols="30" rows="10" class="form-control" v-model="rules"></textarea>
            </div>
        </div>

        <div v-for="input in inputs_data" class="form-group mt-5">
            <label :for="input.id + 'Variable'" class="form-label">{{ input.title }}</label>
            <p class="text-muted" v-if="input.input_type === 'check_box'">{{ input.place_holder }}</p>
            <select v-model="input.option" v-if="input.input_type === 'select'" :name="'variables[' + input.id + ']'" :id="input.id + 'Variable'" class="form-control">
                <option value="">{{ input.place_holder }}</option>
                <option v-for="option in input.options" :value="option.id">{{ option.name }}</option>
            </select>
            <input v-model="input.option" v-if="input.input_type === 'text'" :name="'variables[' + input.id + ']'" :id="input.id + 'Variable'" :placeholder="input.place_holder" type="text" class="form-control">

            <div v-if="input.input_type === 'check_box'" v-for="option in input.options" class="form-group">
                <input v-if="input.input_type === 'check_box'" :checked="input.option === option.id" @click="input.option = (input.option === option.id) ? null: option.id" class="form-check-input" :value="option.id" :name="'variables['+ input.id +']'" :id="option.id + 'Option'" type="checkbox">
                <label :for="option.id + 'Option'" class="form-label" style="margin-right: 30px;">{{ option.name }}</label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CreateHomeStep13",
    props: ['old', 'validate_route', 'rules_title', 'inputs'],
    data() {
        return {
            can_update: false,
            inputs_data: [],
            rules: ''
        }
    },
    created() {
        this.inputs_data = this.inputs

        if (this.old.rules){
            this.rules = this.old.rules
        }
        if (this.old.variables){
            this.old.variables.map((variable) => {
                this.inputs_data.map((input) => {
                    if (input.id === variable.variable_id){
                        input.option = variable.value ?? variable.option_id
                    }
                })
            })
        }

        setTimeout(function (){
            this.can_update = true
        }.bind(this), 500)
    },
    watch: {
        rules: function (rules){
            this.validate()
        },
        inputs_data: {
            handler(inputs){
                this.validate()
            },
            deep: true
        },
    },
    methods: {
        activeNextPage() {
            window.eventBus.$emit('next_page', 14)
        },
        inactiveNextPage() {
            window.eventBus.$emit('next_page', 13)
        },
        validate(){
            if (! this.can_update){
                return false;
            }

            let params = {
                variables: [],
                rules: this.rules
            };

            this.inputs_data.map((data) => {
                params.variables.push({variable: data.id, option: data.option})
            })

            axios.post(this.validate_route, params)
                .then((response) => {
                    this.activeNextPage()
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
