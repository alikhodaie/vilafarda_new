<template>
    <div>
        <div class="d-flex justify-content-end">
            <button @click="addItem()" class="btn btn-falcon-success" type="button"><i class="fa fa-plus"></i></button>
        </div>
        <div v-for="(item, index) in data" :key="rowKey(item, index)" class="row mt-3 align-items-start">
            <div class="col-12 col-md-1">
                <button @click="deleteItem(index)" class="btn btn-falcon-danger" type="button"><i class="fa fa-times"></i></button>
            </div>
            <div class="col-12 col-md-3">
                <multiselect :placeholder="province_placeholder"
                             v-model="item.province"
                             :options="provinces"
                             :selectLabel="select_text"
                             :selectedLabel="selected_text"
                             :deselectLabel="remove_text"
                             label="name"
                             @input="setCityItem(index, item.province)"
                             track-by="id">
                    <template slot="noResult">{{ empty_result_text }}</template>
                    <template slot="noOptions">{{ empty_list_text }}</template>
                </multiselect>
                <input type="hidden" :name="`cities[${index}][province]`" :value="item.province ? item.province.id : null">
            </div>
            <div class="col-12 col-md-3">
                <multiselect :placeholder="city_placeholder"
                             v-model="item.city"
                             :options="item.cities"
                             :selectLabel="select_text"
                             :selectedLabel="selected_text"
                             :deselectLabel="remove_text"
                             label="name"
                             track-by="id">
                    <template slot="noResult">{{ empty_result_text }}</template>
                    <template slot="noOptions">{{ empty_list_text }}</template>
                </multiselect>
                <input type="hidden" :name="`cities[${index}][city]`" :value="item.city ? item.city.id : null">
            </div>
            <div class="col-12 col-md-5">
                <input
                    v-if="item.storedFilename && !item.preview"
                    type="hidden"
                    :name="`cities[${index}][keep_image]`"
                    :value="item.storedFilename"
                >
                <admin-image-picker
                    :name="`cities[${index}][image]`"
                    :image-src="imagePreview(item)"
                    :file-name="item.fileName"
                    :compressing="item.compressing"
                    hint="تصویر شهر — قبل از ارسال در مرورگر و روی سرور فشرده می‌شود."
                    @change="onImageSelected($event, index)"
                />
            </div>
        </div>
        <div v-if="data.length === 0" class="alert alert-info text-center">
            {{ empty_message }}
        </div>
    </div>
</template>

<script>
import { compressUploadImage } from '../../src/compressUploadImage';

const CLIENT_MAX_EDGE = 1200;
const CLIENT_QUALITY = 0.85;
const CITY_IMAGE_BASE = '/files/setting/cities/';

export default {
    name: "IndexCities",
    props: {
        select_text: String,
        selected_text: String,
        old: {
            type: [Array, Object, String],
            default: () => [],
        },
        remove_text: String,
        empty_result_text: String,
        empty_list_text: String,
        province_placeholder: String,
        city_placeholder: String,
        provinces_prop: {
            type: [Array, Object],
            default: () => [],
        },
        empty_message: String,
    },
    data() {
        return {
            data: [],
            provinces: [],
        }
    },
    created() {
        this.provinces = this.normalizeProvinces(this.provinces_prop)
        this.loadSavedItems(this.old)
    },
    methods: {
        loadSavedItems(items) {
            this.normalizeSavedItems(items).forEach((item) => {
                if (!item || !item.province || !item.city) {
                    return
                }

                const province = this.findProvince(item.province.id) || this.fallbackProvince(item.province)
                const city = this.findCity(province, item.city.id, item.count_homes) || this.fallbackCity(item.city, item.count_homes)

                this.addItem(
                    province,
                    city,
                    item.image || '',
                    item.image_file || ''
                )
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
        normalizeProvinces(provinces) {
            const list = Array.isArray(provinces) ? provinces : Object.values(provinces || {})

            return list.map((province) => {
                const cities = Array.isArray(province.cities) ? province.cities : []
                return {
                    ...province,
                    name: `${province.name} (${province.homes_count || 0})`,
                    cities: cities.map((city) => ({
                        ...city,
                        name: `${city.name} (${city.homes_count || 0})`,
                    })),
                }
            })
        },
        findProvince(provinceId) {
            const id = Number(provinceId)
            return this.provinces.find((province) => Number(province.id) === id) || null
        },
        fallbackProvince(province) {
            return {
                id: Number(province.id),
                name: `${province.name} (${province.homes_count || 0})`,
                homes_count: province.homes_count || 0,
                cities: [],
            }
        },
        findCity(province, cityId, countHomes) {
            if (!province || !Array.isArray(province.cities)) {
                return null
            }

            const id = Number(cityId)
            const city = province.cities.find((item) => Number(item.id) === id)
            if (!city) {
                return {
                    id: id,
                    name: `شهر (${countHomes || 0})`,
                }
            }

            return city
        },
        fallbackCity(city, countHomes) {
            return {
                id: Number(city.id),
                name: `${city.name} (${countHomes || 0})`,
            }
        },
        addItem(province = null, city = null, imageUrl = '', storedFilename = '') {
            const filename = storedFilename || this.filenameFromUrl(imageUrl)
            this.data.push({
                province: province,
                city: city,
                image: imageUrl,
                storedFilename: filename,
                preview: null,
                fileName: imageUrl || filename ? 'تصویر فعلی' : '',
                compressing: false,
                cities: province && Array.isArray(province.cities) ? province.cities : [],
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
                return CITY_IMAGE_BASE + item.storedFilename
            }
            return ''
        },
        rowKey(item, index) {
            const provinceId = item.province ? item.province.id : 'p'
            const cityId = item.city ? item.city.id : 'c'
            return `city-${index}-${provinceId}-${cityId}-${item.storedFilename || 'new'}`
        },
        async onImageSelected(event, index) {
            const input = event.target;
            const file = input.files && input.files[0];
            const row = this.data[index];

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
            const row = this.data[index]
            if (row && row.preview) {
                URL.revokeObjectURL(row.preview)
            }
            this.data.splice(index, 1)
        },
        setCityItem(index, province) {
            if (!this.data[index]) {
                return
            }

            this.data[index].cities = province && Array.isArray(province.cities) ? province.cities : []
            this.data[index].city = null
        },
    },
    beforeDestroy() {
        this.data.forEach((item) => {
            if (item.preview) {
                URL.revokeObjectURL(item.preview);
            }
        });
    },
}
</script>

<style scoped>

</style>
