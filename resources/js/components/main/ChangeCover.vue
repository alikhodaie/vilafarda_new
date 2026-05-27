<template>
    <file-pond
        :server="cover_server"
        @processfile="updateCover"
        name="file"
        accepted-file-types="image/jpeg, image/png"
        :files="data_cover"
        :maxFileSize="max_size + 'KB'"
        labelMaxFileSizeExceeded="حجم نصویر زیاد است"
        labelMaxFileSize="حداکثر حجم تصویر {filesize} است"
        labelIdle="تصویر اصلی را کشیده و اینجا رها کنید، و یا کلیک کنید و تصویر اصلی را انتخاب نمایید"
        labelInvalidField="فیلد حاوی فایل های نامعتبر است"
        labelFileWaitingForSize="در انتظار سایز"
        labelFileSizeNotAvailable="سایز معتبر نیست"
        labelFileLoading="بارگذاری"
        labelFileLoadError="خطا در بارگذاری"
        labelFileProcessing="در حال آپلود"
        labelFileProcessingComplete="آپلود به اتمام رسید"
        labelFileProcessingAborted="آپلود لغو شد"
        labelFileProcessingError="خطا در آپلود"
        labelFileProcessingRevertError="خطا در هنگام برگرداندن"
        labelFileRemoveError="خطا در هنگام حذف"
        labelTapToCancel="ضربه برای لغو"
        labelTapToRetry="ضربه برای تکرار"
        labelTapToUndo="ضربه برای بازگرداندن"
        labelButtonRemoveItem="حذف"
        labelButtonAbortItemLoad="رد"
        labelButtonRetryItemLoad="دوباره"
        labelButtonAbortItemProcessing="لغو"
        labelButtonUndoItemProcessing="بازگرداندن"
        labelButtonRetryItemProcessing="دوباره"
        labelButtonProcessItem="آپلود"
    />
</template>

<script>
export default {
    name: "ChangeCover",
    props: ['max_size', 'route', 'csrf', 'cover'],
    data() {
        return {
            cover_server: {
                url: this.route,
                process: {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    ondata: (formData) => {
                        formData.append('_method', 'PUT');
                        formData.append('_token', this.csrf);
                        return formData;
                    },
                    onerror: (response) => this.showError(response)
                },
                remove: (source, load, error) => {
                    this.removeCover()

                    load();
                },
                revert: (uniqueFileId, load, error) => {
                    this.removeCover()

                    load();
                },
                load: (source, load, error, progress, abort, headers) => {
                    let url = new Request(source);

                    fetch(url).then(function(response) {
                        response.blob().then(function(image) {
                            load(image)
                        });
                    });
                },
            },
            data_cover: [],
        }
    },
    created() {
        if (this.cover !== null){
            this.data_cover.push({
                source: this.cover.image_path,
                options: {
                    type: 'local'
                },
            })
        }
    },
    watch: {
        data_cover: (data_cover) => {
            window.eventBus.$emit('count_cover_updated', data_cover.length)
        }
    },
    methods: {
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
        updateCover(error, file){
            this.data_cover = []
            if (! error){
                this.data_cover.push(file)
            }
        },
        removeCover(){
            axios.delete(this.route)
                .then((response) => {
                    this.data_cover = []
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
