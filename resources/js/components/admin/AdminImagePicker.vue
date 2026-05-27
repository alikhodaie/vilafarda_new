<template>
    <div class="setting-image-picker">
        <div
            v-if="imageSrc && !imageBroken"
            class="setting-image-preview mb-2 rounded-3 overflow-hidden border shadow-sm bg-light"
        >
            <a :href="imageSrc" target="_blank" rel="noopener" class="d-block text-decoration-none">
                <img :src="imageSrc" alt="پیش‌نمایش" class="setting-image-preview__img" @error="onImgError">
            </a>
            <div class="setting-image-preview__caption small text-center py-2 bg-white border-top text-muted">
                <span v-if="compressing" class="text-primary">
                    <span class="fas fa-spinner fa-spin me-1"></span>
                    در حال فشرده‌سازی…
                </span>
                <span v-else>{{ fileName || 'پیش‌نمایش تصویر' }}</span>
            </div>
        </div>

        <div
            class="rounded-3 border border-2 border-dashed bg-light p-3 text-center setting-image-dropzone"
            :class="{ 'setting-image-dropzone--busy': compressing }"
        >
            <label :for="inputId" class="mb-0 cursor-pointer d-block" :class="{ 'pe-none opacity-75': compressing }">
                <span class="fas fa-cloud-upload-alt fa-2x text-secondary mb-2 d-block"></span>
                <span class="d-block text-secondary small mb-2">{{ hint }}</span>
                <span class="btn btn-sm btn-primary px-4 rounded-pill">
                    {{ imageSrc ? 'تغییر تصویر' : 'انتخاب تصویر' }}
                </span>
            </label>
            <input
                :id="inputId"
                ref="fileInput"
                type="file"
                class="d-none"
                :name="name"
                :accept="accept"
                :disabled="compressing"
                @change="onChange"
            >
        </div>
    </div>
</template>

<script>
export default {
    name: 'AdminImagePicker',
    props: {
        name: {
            type: String,
            required: true,
        },
        imageSrc: {
            type: String,
            default: '',
        },
        fileName: {
            type: String,
            default: '',
        },
        compressing: {
            type: Boolean,
            default: false,
        },
        hint: {
            type: String,
            default: 'تصویر به WebP فشرده می‌شود؛ HEIC در صورت نیاز روی سرور تبدیل می‌شود.',
        },
        accept: {
            type: String,
            default: 'image/jpeg,image/png,image/webp,image/gif,image/bmp,image/heic,image/heif,.heic,.heif',
        },
    },
    data() {
        return {
            inputId: `setting-img-${Math.random().toString(36).slice(2, 11)}`,
            imageBroken: false,
        };
    },
    watch: {
        imageSrc() {
            this.imageBroken = false;
        },
    },
    methods: {
        onImgError() {
            this.imageBroken = true;
        },
        onChange(event) {
            this.$emit('change', event);
        },
        getInput() {
            return this.$refs.fileInput;
        },
    },
};
</script>

<style scoped>
.setting-image-preview__img {
    display: block;
    width: 100%;
    max-height: 160px;
    object-fit: cover;
    background: #f8f9fa;
}

.setting-image-dropzone {
    transition: border-color 0.2s ease, background-color 0.2s ease;
}

.setting-image-dropzone:hover {
    border-color: rgba(211, 157, 26, 0.65) !important;
    background-color: #fffdf8 !important;
}

.setting-image-dropzone--busy {
    pointer-events: none;
    opacity: 0.85;
}

.cursor-pointer {
    cursor: pointer;
}
</style>
