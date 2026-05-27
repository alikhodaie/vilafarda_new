<template>
    <div>
        <div class="d-flex justify-content-end">
            <button @click="addItem()" class="btn btn-falcon-success" type="button"><i class="fa fa-plus"></i></button>
        </div>
        <div v-for="(item, index) in items" class="row mt-2">
            <div class="col-1">
                <button @click="deleteItem(index)" class="btn btn-falcon-danger" type="button"><i class="fa fa-times"></i></button>
            </div>
            <div class="col-12 col-md-5">
                <input type="text" :name="`${name}[${index}][title]`" class="form-control" v-model="item.title" :placeholder="title_text">
            </div>
            <div class="col-12 col-md-6">
                <input type="url" :name="`${name}[${index}][link]`" class="form-control" v-model="item.link" :placeholder="url_text">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "FooterLink",
    props: ['title_text', 'url_text', 'old', 'name'],
    data(){
        return {
            items: []
        }
    },
    mounted() {
        if (this.old){
            this.old.map((item) => {
                this.addItem(item.title, item.link)
            })
        }
        else {
            this.addItem()
        }
    },
    methods: {
        addItem(title = '', link = ''){
            this.items.push({title: title, link: link})
        },
        deleteItem(index) {
            this.items.splice(index, 1);
            if (this.items.length === 0) {
                this.addItem()
            }
        }
    }
}
</script>

<style scoped>

</style>
