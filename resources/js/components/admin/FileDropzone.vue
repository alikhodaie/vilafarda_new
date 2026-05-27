<template>
    <div>
        <dropzone @vdropzone-queue-complete="filesUploaded" style="display: inline-table; width: 100%" ref="vueDropzone" id="dropzone" :options="dropzone"></dropzone>
    </div>
</template>

<script>
export default {
    name: "FileDropzone",
    props: {
        route: '',
        csrf: '',
        max_files: 0,
        max_filesize: 0
    },
    data(){
        return {
            dropzone: {
                url: this.route,
                maxFiles: this.max_files,
                maxFilesize: this.max_filesize,
                addRemoveLinks: false,
                acceptedFiles: '.jpg,.jpeg,.png',
                dictFileTooBig: 'حجم فایل انتخابی بیش از حد مجاز است',
                dictDefaultMessage: 'برای آپلود، تصاویر را کشیده و اینجا رها کنید، و یا کلیک کنید و تصاویر خود را انتخاب نمایید',
                dictInvalidFileType: 'فرمت فایل انتخابی معتبر نمی‌باشد',
                dictResponseError: 'خطای {{statusCode}} رخ داد',
                dictCancelUpload: 'لغو آپلود',
                dictUploadCanceled: 'آپلود لغو شد',
                dictCancelUploadConfirmation: 'آیا مطمئنید که می‌خواهید آپلود را لغو نمایید؟',
                dictRemoveFile: 'حذف تصویر',
                dictRemoveFileConfirmation: 'آیا از حذف تصویر مطمئنید؟',
                params: {
                    '_token': this.csrf
                }
            }
        }
    },
    mounted(){
        window.eventBus.$on('max_images_updated', (max_files) => {
            this.$refs.vueDropzone.options.maxFiles = max_files
        })
    },
    methods: {
        filesUploaded(){
            window.eventBus.$emit('image_added', true)
            this.$refs.vueDropzone.removeAllFiles();
        }
    }
}
</script>

<style>
    .vue-dropzone {
        font-family: 'IranYekan';
        text-align: end
    }
    .vue-dropzone > .dz-preview .dz-remove {
        opacity: 1;
        color: red;
        border: 2px solid red;
    }
</style>
