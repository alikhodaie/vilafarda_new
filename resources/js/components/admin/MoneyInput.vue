<template>
    <div>
        <input
            :type="type"
            :name="name"
            v-model="value"
            hidden>
        <money v-model="value"
               inputmode="numeric"
               :class="class_input"
               :min="min"
               :max="max"
        ></money>
    </div>
</template>

<script>
export default {
    name: "MoneyInput",
    props: ['type', 'name', 'class_input', 'old', 'min', 'max'],
    data() {
        return {
            value: ''
        }
    },
    created() {
        if (this.old){
            this.value = this.old
        }
    }
}
</script>

<style scoped>

</style>
