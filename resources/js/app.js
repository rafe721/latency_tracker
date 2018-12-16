
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
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
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

Vue.prototype.$arrayHelper = new ArrayHelper();
Vue.prototype.$stringHelper = new StringHelper();

// Adding components here so wh can control their tagnames..
Vue.component('latency-list', require('./components/LatencyListComponent'));
Vue.component('host-table-record', require('./components/HostTableRecordComponent'));
Vue.component('add-host-record', require('./components/AddHostRecordComponent'));
Vue.component('countdown', require('./components/CountdownComponent'));

Vue.component('host-record', require('./components/HostRecordComponent'));
Vue.component('add-host', require('./components/AddHostComponent'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    name : 'LatencyTracker',
    components :{
        // we have already added all the required components
    },
    mounted() {
        // on load..
        console.log(this.host_map);
    },
    data() {
        return  {
            host_map : {
                "google.com" : 'TBA',
                "192.168.0.1" : 'TBA',
                "bad_host" : 'TBA',
                "127.0.0.1" : 'TBA',
            }
        }
    },
    methods : {
        defaultCall() {
            console.log('default call');
        }
    }
});

