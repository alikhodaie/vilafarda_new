import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
Vue.component('dropzone', vue2Dropzone);

import numeral from 'numeral';
Vue.filter("formatNumber", function (value) {
    return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});

import VuePersianDatetimePicker from 'vue-persian-datetime-picker';
Vue.use(VuePersianDatetimePicker, {
    name: 'date-picker',
    props: {
        format: 'YYYY/MM/DD',
        displayFormat: 'jYYYY/jMM/jDD',
        inputClass: '',
        color: '#D39D1A',
        autoSubmit: true,
        clearable: true,
        locale: "fa,en"
    }
});

import {StarRating} from 'vue-rate-it';
Vue.component('star-rating', StarRating);

// File Pond
import VueFilePond from 'vue-filepond';

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

const FilePond = VueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageTransform,
    FilePondPluginFileValidateSize
)
Vue.component('file-pond', FilePond)


// File agent
import Vue from 'vue';
import VueFileAgent from 'vue-file-agent';
import VueFileAgentStyles from 'vue-file-agent/dist/vue-file-agent.css';
import { SlickList, SlickItem } from 'vue-slicksort';

Vue.use(VueFileAgent);
Vue.component('vfa-sortable-list', SlickList);
Vue.component('vfa-sortable-item', SlickItem);

// Draggable
import draggable from 'vuedraggable';
Vue.component('draggable', draggable);

// Leaflet
import { LMap, LTileLayer, LMarker, LCircle, LPopup } from 'vue2-leaflet';
import "leaflet/dist/leaflet.css";

Vue.component('l-map', LMap);
Vue.component('l-tile-layer', LTileLayer);
Vue.component('l-marker', LMarker);
Vue.component('l-circle', LCircle);
Vue.component('l-popup', LPopup);
import { Icon } from 'leaflet';

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});


// Switch
import Switches from 'vue-switches';
Vue.component('switches', Switches)

// V-Calendar
import VCalendar from 'v-calendar';
// Note: v-calendar CSS import removed due to build issues - styles may need to be added manually if needed
Vue.use(VCalendar, {});
