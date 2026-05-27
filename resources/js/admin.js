import Vue from 'vue'
window.Vue = Vue;
require('./bootstrap');

// Highchart
import HighchartsVue from 'highcharts-vue'
import Highcharts from "highcharts";
import hcMore from "highcharts/highcharts-more";
hcMore(Highcharts);

Vue.use(HighchartsVue)
Vue.use(Highcharts)

//region multiselect
import Multiselect from 'vue-multiselect'

import 'vue-multiselect/dist/vue-multiselect.min.css';
Vue.component('multiselect', Multiselect);
//endregion

//region v money
import money from 'v-money'

Vue.use(money, {precision: 0, prefix: 'تومان '})
//endregion

//region dropzone
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

Vue.component('dropzone', vue2Dropzone);
//endregion

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

// Date Picker
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

// Numeral
import numeral from 'numeral';
Vue.filter("formatNumber", function (value) {
    return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});

// Switch
import Switches from 'vue-switches';
Vue.component('switches', Switches)

require('./src/plugins');
require('./src/mixin');

import CustomDate from './components/main/Home/CustomDate.vue';
import PersianCalendar from './components/main/PersianCalendar.vue';

Vue.component('CustomDate', CustomDate);
Vue.component('PersianCalendar', PersianCalendar);

const files = require.context('./components/admin/', true, /\.vue$/i)
files.keys().forEach((key) => {
    const componentName = key.split('/').pop().split('.')[0];
    if (componentName === 'CustomDate') {
        return;
    }
    Vue.component(componentName, files(key).default);
})

window.Vue = require('vue').default;
window.eventBus = new Vue({});
const app = new Vue({
    el: '#app',
    // Blade uses {{ }}; avoid compiling the same tokens in server-rendered HTML.
    delimiters: ['${', '}'],
});
