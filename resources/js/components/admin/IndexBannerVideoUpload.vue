<template>
    <div class="index-banner-video-upload">
        <div v-if="previewSrc" class="mb-2 rounded-3 overflow-hidden border bg-light">
            <video
                class="w-100"
                style="max-height: 200px; object-fit: cover;"
                controls
                autoplay
                loop
                muted
                playsinline
                :src="previewSrc"
            ></video>
            <div class="small text-center py-2 border-top text-muted bg-white">
                <span v-if="busy" class="text-primary">
                    <span class="fas fa-spinner fa-spin me-1"></span>
                    {{ statusText || 'در حال پردازش…' }}
                    <span v-if="progress > 0"> ({{ progress }}٪)</span>
                </span>
                <span v-else-if="readyFileName">{{ readyFileName }} — {{ readySizeLabel }}</span>
                <span v-else>پیش‌نمایش ویدئوی فعلی</span>
            </div>
        </div>

        <div
            class="rounded-3 border border-2 border-dashed bg-light p-3 text-center"
            :class="{ 'opacity-75 pe-none': busy }"
        >
            <label :for="inputId" class="mb-0 cursor-pointer d-block">
                <span class="fas fa-film fa-2x text-secondary mb-2 d-block"></span>
                <span class="d-block text-secondary small mb-2">{{ hint }}</span>
                <span class="btn btn-sm btn-primary px-4 rounded-pill">
                    {{ readyFileName ? 'تغییر ویدئو' : 'انتخاب ویدئو' }}
                </span>
            </label>
            <input
                :id="inputId"
                ref="fileInput"
                type="file"
                class="d-none"
                accept="video/*,.mp4,.mov,.webm,.mkv,.avi"
                :disabled="busy"
                @change="onFileSelected"
            >
        </div>

        <p v-if="errorText" class="text-danger small mt-2 mb-0">{{ errorText }}</p>
        <p v-else-if="statusText && !errorText" class="text-muted small mt-2 mb-0">{{ statusText }}</p>
    </div>
</template>

<script>
import { compressUploadBannerVideo } from '../../src/compressUploadBannerVideo';

export default {
    name: 'IndexBannerVideoUpload',
    props: {
        uploadUrl: {
            type: String,
            required: true,
        },
        maxUploadBytes: {
            type: Number,
            default: 7 * 1024 * 1024,
        },
        currentSrc: {
            type: String,
            default: '',
        },
        hint: {
            type: String,
            default: 'ویدئو در مرورگر آماده و مستقیم ذخیره می‌شود.',
        },
    },
    data() {
        return {
            inputId: `banner-video-${Math.random().toString(36).slice(2, 11)}`,
            busy: false,
            progress: 0,
            statusText: '',
            errorText: '',
            previewSrc: this.currentSrc || '',
            readyFileName: '',
            readySizeLabel: '',
            videoSaved: false,
            formEl: null,
        };
    },
    computed: {
        compressTargetBytes() {
            return Math.min(this.maxUploadBytes, 12 * 1024 * 1024);
        },
    },
    mounted() {
        this.formEl = this.$el.closest('form');
        if (this.formEl) {
            this.formEl.addEventListener('submit', this.onFormSubmit, true);
        }
    },
    beforeDestroy() {
        if (this.formEl) {
            this.formEl.removeEventListener('submit', this.onFormSubmit, true);
        }
        if (this.previewSrc && this.previewSrc.startsWith('blob:')) {
            URL.revokeObjectURL(this.previewSrc);
        }
    },
    methods: {
        onFormSubmit(event) {
            if (this.busy) {
                event.preventDefault();
                event.stopPropagation();
                window.alert('ویدئو در حال پردازش است؛ لطفاً صبر کنید.');
            }
        },
        async onFileSelected(event) {
            const input = event.target;
            const file = input.files && input.files[0];
            this.errorText = '';
            this.videoSaved = false;

            if (!file) {
                return;
            }

            this.busy = true;
            this.progress = 0;
            this.statusText = 'شروع فشرده‌سازی…';
            this.readyFileName = '';
            this.readySizeLabel = '';

            try {
                const compressed = await compressUploadBannerVideo(file, {
                    targetMaxBytes: this.compressTargetBytes,
                    onProgress: (n) => {
                        this.progress = n;
                    },
                    onStatus: (s) => {
                        this.statusText = s;
                    },
                });

                if (compressed.size > this.maxUploadBytes) {
                    throw new Error(
                        `حجم ویدئو (${(compressed.size / (1024 * 1024)).toFixed(1)} مگ) از سقف سرور `
                        + `(${(this.maxUploadBytes / (1024 * 1024)).toFixed(1)} مگ) بیشتر است. ویدئو را کوتاه‌تر کنید.`
                    );
                }

                this.statusText = 'در حال ذخیره روی سرور…';
                this.progress = 0;

                const { data } = await window.axios.post(this.uploadUrl, compressed, {
                    headers: { 'Content-Type': 'video/mp4' },
                    onUploadProgress: (e) => {
                        if (e.total) {
                            this.progress = Math.min(99, Math.round((e.loaded / e.total) * 100));
                        }
                    },
                });

                input.value = '';

                if (this.previewSrc && this.previewSrc.startsWith('blob:')) {
                    URL.revokeObjectURL(this.previewSrc);
                }

                this.previewSrc = data.url || URL.createObjectURL(compressed);
                this.readyFileName = compressed.name;
                this.readySizeLabel = `${(compressed.size / (1024 * 1024)).toFixed(1)} مگابایت`;
                this.statusText = 'ویدئو ذخیره شد. بقیه فیلدها را ویرایش کنید و «ثبت» را بزنید.';
                this.videoSaved = true;
            } catch (e) {
                const msg = e.response?.data?.message || e.message || 'ذخیره ویدئو انجام نشد.';
                this.errorText = msg;
                input.value = '';
            } finally {
                this.busy = false;
            }
        },
    },
};
</script>

<style scoped>
.cursor-pointer {
    cursor: pointer;
}
</style>
