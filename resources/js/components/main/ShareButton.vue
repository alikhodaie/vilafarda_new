<template>
    <div @click="openShareModal" :class="button_class">
        <i class="fa fa-share-alt" style="font-size: 20px;"></i>
    </div>
</template>

<script>
export default {
    name: "ShareButton",
    props: ['title', 'url', 'button_class'],
    methods: {
        openShareModal() {
            window.eventBus.$emit('open_share_modal', {title: this.title, url: this.url})
        }
    }
}
</script>

<style scoped>

</style>
