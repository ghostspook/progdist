/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
import Notifications from 'vue-notification'
import VModal from 'vue-js-modal'


// ************ Font-Awesome for VueJS *********************
import { library } from '@fortawesome/fontawesome-svg-core'
import { faClock, faCalendarDay, faChalkboard,
    faChalkboardTeacher, faBook,
    faLink, faHourglassStart, faUsers }
    from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import JsonExcel from "vue-json-excel";
import Multiselect from 'vue-multiselect'

import areaApi from "./services/area";

library.add(faClock, faCalendarDay, faChalkboard,
    faChalkboardTeacher, faBook, faLink, faHourglassStart,
    faUsers)
Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false
// *********************************************************

Vue.component('bookings-view', require('./components/BookingsView.vue').default);
Vue.component('bookings-calendar', require('./components/BookingsCalendar.vue').default);
Vue.component('basic-bookings-calendar', require('./components/BasicBookingsCalendarEnhanced.vue').default);
Vue.component('add-meeting', require('./components/AddMeeting.vue').default);
Vue.component('instructor-conflict', require('./components/InstructorConflict.vue').default);

Vue.component('instructor-area-view', require('./components/InstructorAreaView.vue').default);

Vue.component('virtual-room', require('./components/VirtualRoom.vue').default);

Vue.component('booking-cloning-list', require('./components/BookingCloningList.vue').default);

Vue.component('fitting-virtual-room', require('./components/FittingVirtualRoom.vue').default);


Vue.component("downloadExcel", JsonExcel);
Vue.component("Multiselect", Multiselect);
Vue.use(Notifications)
Vue.use(VModal)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

// Required to enable jquery datatables interaction with vue component
//window.app = app;
