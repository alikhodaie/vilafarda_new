<template>
    <div class="w-100 row">
        <div class="col-12 col-md-6 mb-3">
            <label for="type">{{ type_title }}</label>
            <select name="type" id="type" class="form-control" v-model="type">
                <option v-for="item in types" :value="item.value">{{ item.title }}</option>
            </select>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <template v-if="type === 'home'">
                <label for="home">{{ home_title }}</label>
                <home-input
                    :old="old_home"
                    :route="home_route"
                    :filter_user="true"
                    :placeholder="placeholder"
                    :select_label="select_label"
                    :selected_label="selected_label"
                    :deselect_label="deselect_label"
                    :no_result_text="no_result_text"
                    :no_options_text="no_options_text"
                ></home-input>
            </template>
            <template v-if="type === 'article'">
                <label for="article">{{ article_title }}</label>
                <article-input
                    :old="old_article"
                    :route="article_route"
                    :filter_user="true"
                    :placeholder="placeholder"
                    :select_label="select_label"
                    :selected_label="selected_label"
                    :deselect_label="deselect_label"
                    :no_result_text="no_result_text"
                    :no_options_text="no_options_text"
                ></article-input>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: "SelectCommentable",
    props: [
        'type_title', 'article_title', 'home_title', 'home_route', 'placeholder',
        'select_label', 'selected_label', 'deselect_label', 'no_result_text',
        'no_options_text', 'home_route', 'old_home', 'old_article', 'article_route'
    ],
    data() {
        return {
            user: null,
            types: [
                {title: this.article_title, value: 'article'},
                {title: this.home_title, value: 'home'}
            ],
            type: 'home'
        }
    },
    mounted() {
        window.eventBus.$on('user_updated', (user) => {
            this.user = user;
        })
    },
    watch: {
        type: function (value) {
            setTimeout(() => {
                window.eventBus.$emit('user_updated', this.user);
            }, 500)
        }
    }
}
</script>

<style scoped>

</style>
