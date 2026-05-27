<template>
    <div>
        <input hidden type="file" @change="updateText()" :id="id" :name='name' multiple :accept="accept">
        <span @click="clickInput()" class="btn btn-danger">{{ text }}</span>
    </div>
</template>

<script>
export default {
    name: "FileInput",
    props: {
        'accept': 'image/png, image/jpeg, application/zip, application/vnd.rar',
        'id': '',
        'name': '',
        'text_select': '',
        'text_selected': ''
    },
    data(){
        return {
            text: this.text_select
        }
    },
    methods: {
        clickInput(){
            document.getElementById(this.id).click()
        },
        updateText(){
            let files = document.getElementById(this.id).files;

            this.text = files.length + ' ' + this.text_selected
        }
    }
}
</script>

<style scoped>

</style>
