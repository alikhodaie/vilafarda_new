<template>
    <div dir="rtl">
        <multiselect
            :placeholder="placeholder"
            :selectLabel="select_label"
            :selectedLabel="selected_label"
            :deselectLabel="deselect_label"
            v-model="article"
            :options="articles"
            label="title"
            track-by="id"
            :searchable="true"
            :internal-search="false"
            :options-limit="300"
            :limit="3"
            :max-height="600"
            @search-change="asyncFind"
        >
            <template slot="noResult">{{ no_result_text }}</template>
            <template slot="noOptions">{{ no_options_text }}</template>
        </multiselect>
        <input type="hidden" :name="name" :value="article ? article.id : null">
    </div>


</template>

<script>
export default {
    name: "UsersInput",
    props: {
        placeholder: '',
        select_label: '',
        selected_label: '',
        deselect_label: '',
        tag_placeholder: '',
        no_result_text: '',
        no_options_text: '',
        route: {},
        name: {
            default: 'article'
        },
        old: {}
    },
    data() {
        return {
            article: null,
            articles: [],
        }
    },
    created() {
        if (this.old) {
            this.setOld()
        }
        else {
            this.asyncFind('')
        }
    },
    watch: {
        article: function (article) {
            const value = article ? article.id : null;

            this.$emit('input', value);
            window.eventBus.$emit('article_updated', value);
        }
    },
    methods: {
        asyncFind(query) {
            axios.get(this.route, {
                params: {
                    search: query
                }
            }).then(response => {
                this.articles = response.data.data
            }).catch(error => {

            })

        },
        setOld() {
            axios.get(this.route, {
                params: {
                    id: this.old
                }
            }).then(response => {
                this.articles = response.data.data
                this.article = response.data.data[0]

            }).catch(error => {

            })
        }
    }


}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
    .multiselect__tags {
        display: block;
        width: 100%;
        font-size: 1rem;
        line-height: 1.25;
        background-color: #fff;
        background-image: none;
        background-clip: padding-box;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        color: #FDFDFD;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 0;
        padding: 5px 12px;
    }
    .multiselect__input, .multiselect__single {
        background-color: rgba(255, 255, 255, 0.1);
        color: #FDFDFD;
        border-radius: 0;
        padding: 5px 12px;
    }
</style>

<style scoped>

</style>
