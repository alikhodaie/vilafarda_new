<template>
    <div class="col-12 row">
        <div class="col-12 col-md-6 mb-5">
            <label class="form-label" for="title">{{ title_text }}<span>*</span></label>
            <input class="form-control" name="title" id="title" type="text" v-model="title"/>
        </div>
        <div class="col-12 col-md-6 mb-5">
            <label class="form-label" for="placeholder">{{ placeholder_text }}<span>*</span></label>
            <input class="form-control" name="placeholder" id="placeholder" type="text" v-model="placeholder"/>
        </div>
        <div class="col-12 col-md-6 mb-5">
            <label class="form-label" for="type">{{ type_text }}<span>*</span></label>
            <select class="form-control" name="type" id="type" v-model="type">
                <option value="">{{ select_text }}</option>
                <option v-for="item in types" :value="item.value">{{ item.fa_text }}</option>
            </select>
        </div>
        <div class="col-12 col-md-6 mb-5">
            <label class="form-label" for="input_type">{{ input_type_text }}<span>*</span></label>
            <select class="form-control" name="input_type" id="input_type" v-model="input_type">
                <option value="">{{ select_text }}</option>
                <option v-for="item in input_types" :value="item.value">{{ item.fa_text }}</option>
            </select>
        </div>
        <div v-if="input_type === 'select' || input_type === 'check_box'" class="col-12 mb-5">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4>{{ options_text }}</h4>
                        <button @click="addOption()" class="btn btn-falcon-success" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div v-for="(option, index) in options" class="row mb-3">
                        <div class="col-1">
                            <button @click="deleteOption(index)" class="btn btn-falcon-danger" type="button"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="col-11">
                            <input type="hidden" :name="`options[${index}][id]`" v-model="option.id">
                            <input class="form-control" type="text" :name="`options[${index}][name]`" v-model="option.text">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "VariableForm",
    props: ['title_text', 'placeholder_text', 'type_text', 'input_type_text',
        'select_text', 'types', 'input_types', 'options_text', 'old'],
    data() {
        return {
            title: '',
            placeholder: '',
            type: '',
            input_type: '',
            options: []
        }
    },
    created() {
        if (this.old && this.old.options){
            this.old.options.map((option) => {
                this.addOption(option.name, option.id)
            })
        }
        else {
            this.addOption()
        }
        if (this.old && this.old.title){
            this.title = this.old.title
        }
        if (this.old && this.old.placeholder){
            this.placeholder = this.old.placeholder
        }
        if (this.old && this.old.type){
            this.type = this.old.type
        }
        if (this.old && this.old.input_type){
            this.input_type = this.old.input_type
        }
    },
    methods: {
        addOption: function (text = '', id = '') {
            this.options.push({'id': id, 'text': text});
        },
        deleteOption: function (index) {
            if (this.options.length > 1) {
                this.options.splice(index, 1);
            }
        }
    }
}
</script>

<style scoped>

</style>
