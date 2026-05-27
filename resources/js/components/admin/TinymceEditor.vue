<template>
    <editor
        :name="name"
        :value="value"
        @input="emitChanges"
        tinymceScriptSrc="/assets/admin/tinymce/tinymce.min.js"
        :init="{
    plugins: 'print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    menubar: 'file edit view insert format tools table help',
    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview print | insertfile image media template link anchor codesample | ltr rtl',
    toolbar_sticky: true,
    content_css: ['/assets/admin/tinymce/tinymce-fonts.css'],
    font_formats: 'IRANSans=IRANSans',
    language: lang,
    relative_urls: false,
    images_upload_handler: tinyMCEFileUpload,
    remove_script_host: false,
    convert_urls: true,
    image_advtab: true,
    importcss_append: true,
    height: 350,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_noneditable_class: 'mceNonEditable',
    toolbar_drawer: 'sliding',
    contextmenu: 'link image imagetools table',
}"
    ></editor>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'

export default {
    name: "TinymceEditor",
    props: ['js_src', 'css_src', 'upload_file_route', 'upload_directory', 'name', 'value', 'lang'],
    components: {
        'editor': Editor
    },

    methods: {

        emitChanges(event) {
            this.$emit('input', event);
        },

        tinyMCEFileUpload(blobInfo, success, failure) {
            let formDataObj = new FormData();
            formDataObj.append('file', blobInfo.blob(), blobInfo.filename());
            formDataObj.append('directory', this.upload_directory);

            axios.post(this.upload_file_route, formDataObj)
                .then(response => {
                    success(response.data.location);
                }).catch(errors => {
                alert('خطایی حین آپلود تصویر رخ داد')
                failure(errors.data);
            })
        },
    }
}
</script>

<style>
.tox-editor-header {
    font-family: IranSans !important;
}

.tox .tox-edit-area__iframe {
    background-color: #fff !important
}

.tox-menu {
    font-family: IranSans !important;

}

.tox-dialog__title {
    font-family: IranSans !important;

}

.tox-dialog__body {
    font-family: IranSans !important;

}

.tox-button {
    font-family: IranSans !important;

}
</style>
