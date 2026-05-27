<template>
    <div class="row">
        <div class="col-12 text-right mb-2">
            <button @click="add()" class="btn btn-falcon-success" type="button"><i class="fa fa-plus"></i></button>
        </div>

        <template v-for="(comment, index) in comments">
            <div class="col-12 col-md-2 mt-3 mt-md-1">
                <input type="text" class="form-control" v-model="comment.name" :name="`comments[${index}][name]`" :placeholder="text_name">
            </div>
            <div class="col-12 col-md-2 mt-1">
                <input type="text" class="form-control" v-model="comment.job" :name="`comments[${index}][job]`" :placeholder="text_job">
            </div>
            <div class="col-12 col-md-2 mt-1">
                <input type="number" step="0.1" max="5" min="0" class="form-control" v-model="comment.score" :name="`comments[${index}][score]`" :placeholder="text_score">
            </div>
            <div class="col-12 col-md-5 mt-1">
                <input type="text" class="form-control" v-model="comment.description" :name="`comments[${index}][description]`" :placeholder="text_description">
            </div>
            <div class="col-12 col-md-1 mt-1">
                <button @click="remove(index)" class="btn btn-falcon-danger" type="button"><i class="fa fa-times"></i></button>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    name: "AboutUsComments",
    props: ['text_name', 'text_job', 'text_score', 'text_description', 'values'],
    data() {
        return {
            comments: []
        }
    },
    mounted() {
        if (this.values){
            this.values.map((value) => {
                this.add(value.name, value.job, value.score, value.description)
            })
        }
    },
    methods: {
        add(name = '', job = '', score = '', description = ''){
            this.comments.push({name: name, job: job, score: score, description: description})
        },
        remove(index){
            this.comments.splice(index, 1);
        }
    }
}
</script>

<style scoped>

</style>
