<template>
    <div class="d-inline">
        <button class="btn p-0 ms-2" type="button" data-bs-placement="top" @click="showModal = true" :title="button_hover_text">
            <span :class="this.button_class"></span>
        </button>

        <div v-if="showModal" class="modal fade show d-block" style="background-color: rgba(0, 0, 0, .5); transition: opacity .3s ease;" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-body p-0">
                        <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                            <h4 class="mb-1" style="text-align: right" id="staticBackdropLabel">{{ title }}</h4>
                        </div>
                        <div class="p-4">
                            <div class="row">
                                <div class="col-12">
                                    <p class="d-flex justify-content-center">{{ text }}</p>
                                </div>
                                <div class="col-12 mt-3">
                                    <form method="POST" :action="route" class="d-flex justify-content-around">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="_method" value="DELETE">

                                        <button class="btn btn-falcon-danger me-1 mb-1">{{ button_submit_text }}</button>
                                        <a href="javascript:" class="btn btn-falcon-success me-1 mb-1" @click="showModal = false">
                                            {{ button_cancel_text }}
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DeleteModal",
    props: ['title', 'button_hover_text', 'text', 'button_cancel_text', 'button_submit_text', 'route', 'csrf', 'btn_class'],
    data() {
        return {
            button_class: 'text-500 fas fa-trash-alt',
            showModal: false
        }
    },
    mounted() {
        if (this.btn_class){
            this.button_class = this.btn_class
        }
    }
}
</script>

<style scoped>

</style>
