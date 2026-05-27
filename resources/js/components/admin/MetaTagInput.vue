<template>
    <div dir="rtl">
        <multiselect :placeholder="placeholder"
                     v-model="meta"
                     :taggable="true"
                     @tag="addValue"
                     :options="metas"
                     :multiple="true"
                     :selectLabel="select_label"
                     :selectedLabel="selected_label"
                     :deselectLabel="deselect_label"
                     :tag-placeholder="tag_placeholder"
                     :close-on-select="false"
                     label="name"
                     track-by="name">
            <template slot="noResult">{{ no_result_text }}</template>
            <template slot="noOptions">{{ no_options_text }}</template>
        </multiselect>
        <input :name="`${name}`" type="hidden" v-for="selected in meta" :value="selected['name']">
    </div>
</template>

<script>
export default {
    name: "meta-tag-input",
    props: {
        placeholder: '',
        select_label: '',
        selected_label: '',
        deselect_label: '',
        tag_placeholder: '',
        no_result_text: '',
        no_options_text: '',
        name: {
            default: 'metas[]'
        },
        values: {},
    },
    data() {
        return {
            meta: [],
            metas: []
        }
    },
    watch: {
        meta: function (meta) {
            window.eventBus.$emit('meta_updated', meta)
        }
    },
    created() {
        this.setValue()
    },
    methods: {
        setValue() {
            if (this.values){
                this.values.map(item => {
                    this.metas.push({'name': item})
                    this.meta.push({'name': item})
                })
            }
        },
        addValue(value) {
            this.metas.push({'name': value})
            this.meta.push({'name': value})
        }
    }
}
</script>

<style scoped>

</style>
