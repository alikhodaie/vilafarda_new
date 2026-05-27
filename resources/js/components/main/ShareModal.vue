<template>
    <div v-if="is_open" class="modal show" tabindex="-1" style="display: block; background-color: rgba(0, 0, 0, .5); transition: opacity .3s ease;">
        <div class="modal-dialog modal-lg mt-6" role="document">
            <div class="modal-content border-0">
                <div class="modal-body p-0">
                    <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                        <h4 class="mb-1 mr-4" style="text-align: center">اشتراک گذاری</h4>
                    </div>
                    <div class="p-5 d-flex justify-content-around">
                        <a :href="`mailto:?subject=${title};body=${url}`">
                            <i class="fa fa-envelope fa-2x ml-2"></i>
                        </a>
                        <a :href="`whatsapp://send?text=${url}`" data-action="share/whatsapp/share">
                            <i class="fab fa-whatsapp fa-2x ml-2"></i>
                        </a>
                        <a :href="`telegram://send?text=${url}`" data-action="share/telegram/share">
                            <i class="fab fa-telegram fa-2x ml-2"></i>
                        </a>
                        <a target="_blank" :href="`https://twitter.com/intent/tweet?text=${title};url=${url}`">
                            <i class="fab fa-twitter fa-2x ml-2"></i>
                        </a>
                        <a target="_blank" :href="`https://www.facebook.com/sharer/sharer.php?u=${url}`">
                            <i class="fab fa-facebook fa-2x ml-2"></i>
                        </a>
                        <a target="_blank" :href="`http://www.linkedin.com/shareArticle?mini=true&url${url}=&title=${title}`">
                            <i class="fab fa-linkedin-in fa-2x ml-2"></i>
                        </a>
                        <a target="_blank" :href="`http://pinterest.com/pin/create/link/?url=${url}`">
                            <i class="fab fa-pinterest fa-2x ml-2"></i>
                        </a>
                        <a target="_blank" :href="`http://www.reddit.com/submit?url=${url};title=${title}`">
                            <i class="fab fa-reddit fa-2x ml-2"></i>
                        </a>
                    </div>

                    <div class="mb-4 d-flex justify-content-center">
                        <button type="button" class="btn btn-danger rounded" @click="is_open = false">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ShareModal",
    data() {
        return {
            is_open: false,

            url: '',
            title: '',
        }
    },
    mounted() {
        window.eventBus.$on('open_share_modal', (data) => {
            if (data.title){
                this.title = data.title
            }
            if (data.url){
                this.url = data.url
            }

            this.is_open = true
        })
    }
}
</script>

<style scoped>

</style>
