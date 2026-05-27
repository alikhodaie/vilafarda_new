<template>
    <div>
        <star-rating v-model="rating" :rtl="true" :max-rating="5" :show-rating="false" :item-size="30"></star-rating>
        <input type="hidden" :name="data_name" v-model="rating">
    </div>
</template>

<script>
export default {
    name: "ScoreStars",
    props: ['old', 'name'],
    data(){
        return {
            rating: 5,
            data_name: 'score'
        }
    },
    created(){
        if (this.old){
            this.rating = this.old
        }
        if (this.name){
            this.data_name = this.name
        }
    }
}
</script>

<style scoped>

</style>
