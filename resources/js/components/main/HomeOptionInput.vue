<template>
    <div dir="rtl">
        <multiselect :placeholder="place_holder"
                     id="options"
                     v-model="option"
                     :options="options"
                     :selectLabel="select_label"
                     :selectedLabel="selected_label"
                     :deselectLabel="deselect_label"
                     :multiple="true"
                     :close-on-select="false"
                     :searchable="true"
                     label="title"
                     track-by="id">
            <template slot="option" slot-scope="props">
                <img width="30" class="ml-1" :src="props.option.icon_path" :alt="props.option.title">
                {{ props.option.title }}
            </template>
            <template slot="noResult">{{ no_result_text }}</template>
            <template slot="noOptions">{{ no_options_text }}</template>
        </multiselect>

        <input name="options[]" type="hidden" v-for="selected in option" :value="selected.id">
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
    name: "HomeOptionInput",
    components: {Multiselect},
    props: ['options_title', 'place_holder', 'select_label', 'selected_label', 'deselect_label', 'no_result_text', 'no_options_text', 'values', 'old'],
    data() {
        return {
            option: null,
            options: []
        }
    },
    mounted() {
        this.options = this.values
        if (this.old) {
            this.option = this.old
        }
    }
}
</script>

<style scoped>

</style>
