
/**
 *
 */

require('./bootstrap');

import _ from 'lodash';
import swal from 'sweetalert2';
import StringHelper from './StringHelper';
import ArrayHelper from './ArrayHelper';

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.Vue = require('vue');
window._ = _;
window.toast = toast;

/**
 * Augument Vue with required Objects and register required components.
 */

Vue.prototype.$arrayHelper = new ArrayHelper();
Vue.prototype.$stringHelper = new StringHelper();

// Adding components here so wh can control their tagnames..
Vue.component('latency-list', require('./components/LatencyListComponent'));
Vue.component('host-table-record', require('./components/HostTableRecordComponent'));
Vue.component('add-host-record', require('./components/AddHostRecordComponent'));
Vue.component('countdown', require('./components/CountdownComponent'));

/**
 * Finally initialize the Vue application on the page and
 */
const app = new Vue({
    el: '#app',
    name : 'LatencyTracker',
    components :{
        // we have already added all the required components
    },
    mounted() {
        // on load..
        // console.log(this.host_map);
    },
    data() {
        return  {
            host_map : {
                "google.com" : 'TBA',
                "192.168.0.1" : 'TBA',
                "bad_host" : 'TBA',
                "127.0.0.1" : 'TBA',
            },
            misnomer : "misnomer",
        }
    },
    methods : {
        defaultCall() {
            console.log('default call');
        }
    }
});

// window.app = app;

