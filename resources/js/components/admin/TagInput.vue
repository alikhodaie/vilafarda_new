<template>
    <div dir="rtl">
        <multiselect :placeholder="placeholder"
                     v-model="tag"
                     :taggable="true"
                     @tag="addValue"
                     :options="tags"
                     :multiple="true"
                     :selectLabel="select_label"
                     :selectedLabel="selected_label"
                     :deselectLabel="deselect_label"
                     :tag-placeholder="tag_placeholder"
                     :close-on-select="false"
                     label="name"
                     :loading="isLoading"
                     @search-change="search"
                     :searchable="true"
                     :internal-search="false"
                     :options-limit="300"
                     track-by="id">
            <template slot="noResult">{{ no_result_text }}</template>
            <template slot="noOptions">{{ no_options_text }}</template>
        </multiselect>
        <input :name="name" type="hidden" v-for="selected in tag" :value="selected['id']">
    </div>
</template>

<script>
export default {
    name: "tag-input",
    props: {
        placeholder: '',
        select_label: '',
        selected_label: '',
        deselect_label: '',
        tag_placeholder: '',
        no_result_text: '',
        no_options_text: '',
        route_index: {},
        route_store: {},
        name: {
            default: 'tags[]'
        },
        old: {},
    },
    data() {
        return {
            tag: [],
            tags: [],
            isLoading: false,
        }
    },
    watch: {
        tag: function (tag) {
            window.eventBus.$emit('tag_updated', tag)
        }
    },
    created() {
        this.setValue()
    },
    methods: {
        setValue() {
            if(this.old){
                this.tags = this.old
                this.tag = this.old
            }
        },
        addValue(value) {
            axios.post(this.route_store, {
                tag: value
            }).then(response => {
                this.tags.push(response.data)
                this.tag.push(response.data)

                this.isLoading = false
            }).catch(error => {
                let message = '';

                error.response.data.errors.tag.map(item => {
                    message += item + '\n'
                })
                alert(message)
            })
        },
        search(query) {
            axios.get(this.route_index, {
                params: {
                    search: query
                }
            }).then(response => {
                this.tags = response.data.data
                this.isLoading = false
            })

        },
    }
}
</script>

<style scoped>

</style>
