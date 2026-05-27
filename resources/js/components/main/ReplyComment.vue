<template>
    <div class="d-inline">
        <!-- Button trigger modal -->
        <a href="javascript:" :class="button_class" @click="showModal = true">{{ button_text }}</a>

        <div v-if="showModal" class="modal show" tabindex="-1" style="display: block; background-color: rgba(0, 0, 0, .5); transition: opacity .3s ease;">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-body p-0">
                        <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                            <h4 class="mb-1 mr-4" style="text-align: right">{{ title }}</h4>
                        </div>
                        <div class="p-4">
                            <form method="POST" :action="route" class="row">
                                <input type="hidden" name="_token" :value="csrf">
                                <input type="hidden" name="reply_id" :value="comment_id">

                                <div class="col-12">
                                    <p>{{ text }}</p>

                                    <textarea type="text" name="comment" class="form-control" :placeholder="comment_text"></textarea>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="d-flex justify-content-around">
                                        <button class="btn btn-success mb-1">{{ button_submit_text }}</button>
                                        <button type="button" @click="showModal = false" class="btn btn-danger mb-1">
                                            {{ button_cancel_text }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ReplyComment",
    props: ['title', 'comment_id', 'comment_text', 'button_text', 'button_class', 'text', 'button_cancel_text', 'button_submit_text', 'route', 'csrf'],
    data() {
        return {
            showModal: false
        }
    }
}
</script>

<style scoped>

</style>
