
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import sanitizeHTML from 'sanitize-html';
Vue.prototype.$sanitize = sanitizeHTML;
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import Vuex from 'vuex';
Vue.use(Vuex);
import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)
// Bootstrap-Vue
window.BootstrapVue = require('bootstrap-vue');
Vue.use(BootstrapVue);
import Loading from 'vue-loading-overlay';
Vue.component('loading', Loading)
import 'vue-loading-overlay/dist/vue-loading.min.css';
import 'vue-loading-overlay/dist/vue-loading.min.css';
// https://www.npmjs.com/package/vue-full-calendar
import FullCalendar from 'vue-full-calendar';
Vue.use(FullCalendar)
import 'fullcalendar/dist/fullcalendar.css';
import 'fullcalendar-scheduler/dist/scheduler.min.js';
import 'fullcalendar-scheduler/dist/scheduler.min.css';
// https://github.com/robinvdvleuten/vuex-persistedstate
import Multiselect from 'vue-multiselect'
Vue.component('multiselect', Multiselect)
import 'vue-multiselect/dist/vue-multiselect.min.css';

import createPersistedState from 'vuex-persistedstate';
import DatePickerStandard from 'vue-mg-date-picker-standard'
Vue.component('DatePickerStandard', DatePickerStandard)
import DatePickerFromTo from 'vue-mg-date-picker-from-to'
Vue.component('DatePickerFromTo', DatePickerFromTo)
import ModalKeypad from 'vue-mg-modal-keypad'
Vue.component('ModalKeypad', ModalKeypad)
import ImageFileUploader from 'vue-mg-image-file-uploader'
Vue.component('ImageFileUploader', ImageFileUploader)
import TimePickerSelector from 'vue-mg-time-picker-selector'
Vue.component('TimePickerSelector', TimePickerSelector)
// import Paginate from 'vuejs-paginate'
// Vue.component('paginate', Paginate)
import ToggleSelector from 'vue-mg-toggle-selector'
Vue.component('ToggleSelector', ToggleSelector)
import ModalDialog from "vue-mg-modal-dialog";
Vue.component("ModalDialog", ModalDialog);
// import DateTimeSelector from "vue-mg-date-time-selector";
// Vue.component("DateTimeSelector", DateTimeSelector);
import CsvImporter from 'vue-mg-csv-importer'
Vue.component('CsvImporter', CsvImporter)
import VueInputCalculator from "vue-input-calculator";
Vue.component('VueInputCalculator', VueInputCalculator);
import ModalSearchMaster from 'vue-mg-modal-search-master';
import Vue from 'vue';
Vue.component('ModalSearchMaster', ModalSearchMaster);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('main-component', require('./components/MainComponent.vue').default);
Vue.component('back-button', require('./components/commons/buttons/BackButton.vue').default);
Vue.component('date-increment-picker', require('./components/commons/DateIncrementPicker.vue').default);
Vue.component('date-time-selector', require('./components/commons/DateTimeSelector.vue').default);
Vue.component('pagination', require('./components/commons/Pagination.vue').default);
Vue.component('barcode-component', require('./components/commons/BarcodeComponent.vue').default);
Vue.component('modal-employee-select', require('./components/commons/ModalEmployeeSelect.vue').default);
Vue.component('select-name', require('./components/commons/SelectName.vue').default);
Vue.component("number-input", require("./components/commons/NumberInput.vue").default);
Vue.component('main-menu', require('./components/MainMenu.vue').default);
Vue.component('review-create', require('./components/ReviewCreate.vue').default);
Vue.component('main-map', require('./components/MainMap.vue').default);
Vue.component('main-map2', require('./components/MainMap2.vue').default);
Vue.component('main-map3', require('./components/MainMap3.vue').default);
Vue.component('main-map4', require('./components/MainMap4.vue').default);


const router = new VueRouter({
    mode: 'history',
    routes: [
        //　課題
        { name: 'menu', path: '/', component: require('./components/MainMenu.vue').default },
        // { name: 'map', path: '/map', component: require('./components/MainMap.vue').default },
        { name: 'map', path: '/map', component: require('./components/vue2Map2.vue').default },
        { name: 'create', path: '/create/:restname', component: require('./components/ReviewCreate.vue').default },
        { name: 'create', path: '/create', component: require('./components/ReviewCreate.vue').default },
        // mock
        // { name: 'mock', path: '/mock', component: require('./components/mocks/MockBarcode.vue').default },
        // { name: 'home', path: '/', component: require('./components/HomeComponent.vue').default },
        // 包装ライン日報
        { name: 'report', path: '/report', component: require('./components/reports/Index.vue').default },
        { name: 'report.create', path: '/report/create', component: require('./components/reports/CreateUpdate.vue').default },
        { name: 'report.show', path: '/report/show/:report_id', component: require('./components/reports/CreateUpdate.vue').default, props: true },

        // 設定
        { name: 'setting', path: '/setting', component: require('./components/settings/Index.vue').default },

        // 従業員
        { name: 'user', path: '/user', component: require('./components/users/Index.vue').default },
        { name: 'user.create', path: '/user/create', component: require('./components/users/CreateUpdate.vue').default },
        { name: 'user.show', path: '/user/show/:user_id', component: require('./components/users/CreateUpdate.vue').default, props: true },

        // 商品
        { name: 'item', path: '/item', component: require('./components/items/Index.vue').default },
        { name: 'item.create', path: '/item/create', component: require('./components/items/CreateUpdate.vue').default, props: true },
        { name: 'item.show', path: '/item/show/:item_id', component: require('./components/items/CreateUpdate.vue').default, props: true },

        // Lesson
        { name: 'lesson1', path: '/lesson1', component: require('./components/lessons/Lesson1.vue').default },
        { name: 'lesson2', path: '/lesson2', component: require('./components/lessons/Lesson2.vue').default },
        { name: 'lesson3', path: '/lesson3', component: require('./components/lessons/Lesson3.vue').default },
        { name: 'lesson4', path: '/lesson4', component: require('./components/lessons/Lesson4.vue').default },
        { name: 'lesson5', path: '/lesson5', component: require('./components/lessons/Lesson5.vue').default },
        { name: 'lesson6', path: '/lesson6', component: require('./components/lessons/Lesson6.vue').default },
        { name: 'lesson7', path: '/lesson7', component: require('./components/lessons/Lesson7.vue').default },
        { name: 'lesson8', path: '/lesson8', component: require('./components/lessons/Lesson8.vue').default, props: true },
        { name: 'lesson8-1', path: '/lesson8-1', component: require('./components/lessons/Lesson8-1.vue').default },
        { name: 'lesson8-2', path: '/lesson8-2', component: require('./components/lessons/Lesson8-2.vue').default, props: true },
        { name: 'lesson9', path: '/lesson9', component: require('./components/lessons/Lesson9.vue').default },
        { name: 'lesson10', path: '/lesson10', component: require('./components/lessons/Lesson10.vue').default },

        //顧客
        { name: 'customer', path: '/customer', component: require('./components/customers/Index.vue').default },
        { name: 'customer.create', path: '/customer/create', component: require('./components/customers/CreateUpdate.vue').default, props: true },
        { name: 'customer.show', path: '/customer/show/:customer_id', component: require('./components/customers/CreateUpdate.vue').default, props: true },
        // not found
        { path: '*', component: require('./components/commons/NotFoundComponent.vue').default },
    ]
});


const store = new Vuex.Store({
    state: {
        user: '',
        sort: {
            key: '', // ソートキー
            isAsc: false // 昇順ならtrue,降順ならfalse
        },
    },
    mutations: {
        async getUser (state, payload) {
            const res = await axios.get('/api/user/info')
            state.user = res.data
            // console.log('getUser')
        },
    },
    actions: {
        async getUser (context) {
        //   context.commit('getUser')
          const res = await axios.get('/api/user/info')
          this.state.user = res.data
          console.log("getUser2")
        }
    },
    plugins: [createPersistedState()],
});

router.beforeEach(async(to, from, next) => {
    if(to.path == '/login') {
        next()
        return
    }
    await store.dispatch('getUser')
    if (store.state.user.is_admin) {
        next()
    } else {
        // 一般ユーザ
        if (to.path == '/') {
            router.push({ name: 'map' })
        }
    }
    next()
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 import * as VueGoogleMaps from 'vue2-google-maps'
 Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyDoT7ZAEG-K-twxebevNoXlOXY6P7v69GA',
        libraries: 'places',
        region: 'JP',
        language: 'ja',
    }
})

const app = new Vue({
    el: '#app',
    store,
    router,
});
