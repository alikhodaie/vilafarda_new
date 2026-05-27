<template>
    <div>
        <div class="d-flex justify-content-end">
            <button @click="addItem()" class="btn btn-falcon-success" type="button"><i class="fa fa-plus"></i></button>
        </div>
        <div v-for="(item, index) in items" :key="rowKey(item, index)" class="row mt-2 align-items-start">
            <div class="col-1">
                <button @click="deleteItem(index)" class="btn btn-falcon-danger" type="button"><i class="fa fa-times"></i></button>
            </div>
            <div class="col-12 col-md-5">
                <input
                    v-if="item.storedFilename && !item.preview"
                    type="hidden"
                    :name="`${name}[${index}][keep_image]`"
                    :value="item.storedFilename"
                >
                <admin-image-picker
                    :name="`${name}[${index}][image]`"
                    :image-src="imagePreview(item)"
                    :file-name="item.fileName"
                    :compressing="item.compressing"
                    hint="تصویر اسلایدر — قبل از ارسال در مرورگر و روی سرور فشرده می‌شود."
                    @change="onImageSelected($event, index)"
                />
            </div>
            <div class="col-12 col-md-6">
                <input type="url" :name="`${name}[${index}][link]`" class="form-control mb-2" v-model="item.link" :placeholder="url_text">
                <input type="text" :name="`${name}[${index}][alt]`" class="form-control" v-model="item.alt"
                       maxlength="120" placeholder="متن alt برای سئو (مثلاً: تخفیف ویلاهای شمال)">
                <small class="text-muted d-block mt-1">اگر خالی بماند از عنوان بنر صفحه اصلی استفاده می‌شود.</small>
            </div>
        </div>
        <div v-if="items.length === 0" class="alert alert-info text-center">
            {{ empty_message }}
        </div>
    </div>
</template>

<script>
import { compressUploadImage } from '../../src/compressUploadImage';

const CLIENT_MAX_EDGE = 1200;
const CLIENT_QUALITY = 0.85;
const SLIDER_IMAGE_BASE = '/files/setting/slider/';

export default {
    name: "Sliders",
    props: {
        url_text: String,
        empty_message: String,
        old: {
            type: [Array, Object, String],
            default: () => [],
        },
        name: String,
    },
    data(){
        return {
            items: []
        }
    },
    mounted() {
        this.loadSavedItems(this.old)
    },
    methods: {
        loadSavedItems(items) {
            this.normalizeSavedItems(items).forEach((item) => {
                if (!item) {
                    return
                }
                this.addItem(item.link || '', item.image || '', item.image_file || '', item.alt || '')
            })
        },
        normalizeSavedItems(items) {
            if (Array.isArray(items)) {
                return items
            }
            if (items && typeof items === 'object') {
                return Object.values(items)
            }
            if (typeof items === 'string' && items !== '') {
                try {
                    return this.normalizeSavedItems(JSON.parse(items))
                } catch (e) {
                    return []
                }
            }
            return []
        },
        addItem(link = '', imageUrl = '', storedFilename = '', alt = ''){
            const filename = storedFilename || this.filenameFromUrl(imageUrl)
            this.items.push({
                link: link,
                alt: alt,
                image: imageUrl,
                storedFilename: filename,
                preview: null,
                fileName: imageUrl || filename ? 'تصویر فعلی' : '',
                compressing: false,
            })
        },
        filenameFromUrl(url) {
            if (!url || typeof url !== 'string') {
                return ''
            }
            try {
                const path = url.indexOf('://') !== -1 ? new URL(url).pathname : url
                return path.split('/').filter(Boolean).pop() || ''
            } catch (e) {
                return url.split('/').filter(Boolean).pop() || ''
            }
        },
        imagePreview(item) {
            if (item.preview) {
                return item.preview
            }
            if (item.image) {
                return item.image
            }
            if (item.storedFilename) {
                return SLIDER_IMAGE_BASE + item.storedFilename
            }
            return ''
        },
        rowKey(item, index) {
            return `slider-${index}-${item.storedFilename || item.link || 'new'}`
        },
        async onImageSelected(event, index) {
            const input = event.target;
            const file = input.files && input.files[0];
            const row = this.items[index];

            if (!file || !row) {
                return;
            }

            this.$set(row, 'compressing', true);

            try {
                const compressed = await compressUploadImage(file, {
                    maxEdge: CLIENT_MAX_EDGE,
                    quality: CLIENT_QUALITY,
                });
                const transfer = new DataTransfer();
                transfer.items.add(compressed);
                input.files = transfer.files;

                if (row.preview) {
                    URL.revokeObjectURL(row.preview);
                }
                this.$set(row, 'preview', URL.createObjectURL(compressed));
                this.$set(row, 'fileName', compressed.name);
                this.$set(row, 'storedFilename', '');
            } catch (e) {
                console.warn('compressUploadImage', e);
            } finally {
                this.$set(row, 'compressing', false);
            }
        },
        deleteItem(index) {
            const row = this.items[index];
            if (row && row.preview) {
                URL.revokeObjectURL(row.preview);
            }
            this.items.splice(index, 1);
        }
    },
    beforeDestroy() {
        this.items.forEach((item) => {
            if (item.preview) {
                URL.revokeObjectURL(item.preview);
            }
        });
    },
}
</script>

<style scoped>

</style>
