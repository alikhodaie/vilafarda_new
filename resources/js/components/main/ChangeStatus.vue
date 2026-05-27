<template>
    <div :class="main_class">
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
                            <div class="row">
                                <div class="col-12">
                                    <p class="d-flex justify-content-center">{{ text }}</p>
                                </div>
                                <div class="col-12 mt-3">
                                    <form method="POST" :action="route">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="_method" value="PATCH">

                                        <div v-if="rejectReasons" class="mb-3 text-right">
                                            <p class="mb-2 fw-semibold">علت رد درخواست:</p>
                                            <label v-for="(label, value) in rejectReasons"
                                                   :key="value"
                                                   class="d-flex align-items-center gap-2 mb-2">
                                                <input type="radio"
                                                       name="reject_reason"
                                                       :value="value"
                                                       required>
                                                <span>{{ label }}</span>
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-around">
                                            <button class="btn btn-success mb-1">{{ button_submit_text }}</button>
                                            <button type="button" @click="showModal = false" class="btn btn-danger mb-1">
                                                {{ button_cancel_text }}
                                            </button>
                                        </div>
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
    name: "ChangeStatus",
    props: ['title', 'button_text', 'button_class', 'text', 'button_cancel_text', 'button_submit_text', 'route', 'csrf', 'class', 'rejectReasons'],
    data() {
        return {
            showModal: false,
            main_class: 'd-inline'
        }
    },
    created(){
        if (this.class){
            this.main_class = this.class
        }
    }
}
</script>

<style scoped>

</style>
