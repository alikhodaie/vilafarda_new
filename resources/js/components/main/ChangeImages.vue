<template>
    <div>
        <p>حداقل 7 مورد انتخاب کنید. <span>{{ data_images.length }} مورد انتخاب شده</span></p>
        <file-dropzone class="mb-2" :route="route" :csrf="csrf" :max_files="21" :max_filesize="max_size / 1000"></file-dropzone>
        <div class="p-5 p-md-0">
            <draggable v-bind="dragOptions" v-model="data_images" @sort="changeImageOrder">
                <transition-group type="transition">
                    <li v-for="(image, index) in data_images" :key="image.id" class="mb-3 list-group-item">
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <img class="w-100 rounded" :src="image.url">
                            </div>
                            <div class="col-12 col-md-4 mt-3 mt-md-0 align-self-center">{{ image.name }}</div>
                            <div class="col-12 col-md-3 mt-3 mt-md-0 align-self-center">{{ image.sizeText }}</div>
                            <div class="col-12 col-md-3 mt-3 mt-md-0 align-self-center">
                                <button type="button" class="btn btn-danger w-100" @click="removeImage(index)">
                                    حذف
                                </button>
                            </div>
                        </div>
                    </li>
                </transition-group>
            </draggable>
        </div>
    </div>
</template>

<script>
export default {
    name: "ChangeImages",
    props: ['route', 'max_size', 'csrf', 'images'],
    data() {
        return {
            data_images: []
        }
    },
    watch: {
        data_images: function (date_images){
            let max_files = 21 - date_images.length
            window.eventBus.$emit('max_images_updated', max_files)
            window.eventBus.$emit('count_images_updated', date_images.length)
        },
    },
    computed: {
        dragOptions() {
            return {
                animation: 200
            };
        }
    },
    created(){
        if (this.images){
            this.images.map((image) => {
                this.addImage(image.id, image.original_name, image.size, image.type, image.path)
            })
        }
        else {
            this.getImages()
        }
    },
    mounted(){
        window.eventBus.$on('image_added', function (){
            this.getImages()
        }.bind(this))
    },
    methods: {
        addImage(id = '', name = '', size = '', type = '', path = '', ){
            name = (name.length > 12) ? name.substring(0,12)+'...': name;

            this.data_images.push({
                id: id,
                name: name,
                size: size,
                sizeText: this.formatNumber(size) + ' KB',
                type: type,
                url: path
            })
        },
        getImages(){
            this.data_images = [];

            axios.get(this.route)
                .then((response) => {
                    response.data.map((image) => {
                        this.addImage(image.id, image.original_name, image.size, image.type, image.path)
                    })
                })
                .catch((error) => {
                    let message = '';
                    this.$root.formatErrors(error).map(item => {
                        message += item + '\n'
                    })
                    this.$root.showAlert(message, 'error', true)
                });
        },
        showError(response){
            response = JSON.parse(response)
            let message = '';
            $.each(response.errors, function( index, errors ) {
                $.each(errors, function( index, error ) {
                    message += error + '\n'
                });
            });
            this.$root.showAlert(message, 'error', true)
        },
        removeImage(index){
            window.eventBus.$emit('show_loader', true)

            let id = this.data_images[index].id;
            let url = this.route + '/' + id

            axios.delete(url)
                .then((response) => {
                    this.data_images.splice(index, 1);
                    window.eventBus.$emit('hide_loader', true)
                })
                .catch((error) => {
                    let message = '';
                    this.$root.formatErrors(error).map(item => {
                        message += item + '\n'
                    })
                    this.$root.showAlert(message, 'error', true)
                });
        },
        changeImageOrder(event){
            this.data_images.map((image, index) => {
                this.updateImageOrder(image.id, index)
            })
        },
        updateImageOrder(id, position){
            let url = this.route + '/' + id
            axios.patch(url, {
                position: position
            })
                .then((response) => {
                })
                .catch((error) => {
                    let message = '';
                    this.$root.formatErrors(error).map(item => {
                        message += item + '\n'
                    })
                    this.$root.showAlert(message, 'error', true)
                });
        }
    }
}
</script>

<style scoped>

</style>
