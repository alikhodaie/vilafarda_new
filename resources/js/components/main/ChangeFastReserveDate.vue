<template>
    <form class="row" method="POST" :action="route">
        <input type="hidden" name="date" v-model="date">
        <input type="hidden" name="_token" v-model="csrf">
        <div class="col-12 col-md-6 mt-3">
            <date-picker
                :min="min"
                range
                type="date"
                v-model="date"
                display-format="dddd jDD jMMMM jYYYY"
                format="YYYY-MM-DD"
            ></date-picker>
        </div>
        <div class="col-12 col-md-1 mt-3">
            <button class="btn btn-success">{{ button_title }}</button>
        </div>
    </form>
</template>

<script>
export default {
    name: "ChangeFastReserveDate",
    props: ['csrf', 'old', 'route', 'min', 'button_title'],
    data() {
        return {
            date: ''
        }
    },
    created() {
        if (this.old[0] && this.old[1]){
            this.date = this.old
        }
    }
}
</script>

<style scoped>

</style>
