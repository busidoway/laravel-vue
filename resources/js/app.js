/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// import UserVideo from "./components/UserVideo";

import VueCompositionAPI from '@vue/composition-api';
import router from "./router";
import store from "./store";
import VCalendar from 'v-calendar';

// import 'v-calendar/dist/style.css';

Vue.use(VueCompositionAPI);

Vue.use(VCalendar, {
    componentPrefix: 'vc',  // Опционально можно задать префикс для компонентов
});

Vue.config.productionTip = false;

Vue.component('event-person', require('./components/admin/EventPerson.vue').default);

Vue.component('user-video', require('./components/admin/UserVideo.vue').default);

Vue.component('menu-list', require('./components/admin/MenuList.vue').default);

Vue.component('program-list', require('./components/admin/ProgramList.vue').default);

Vue.component('date-list', require('./components/admin/DateList.vue').default);

Vue.component('user-set', require('./components/admin/users/UserSet.vue').default);

Vue.component('event-file', require('./components/admin/components/FileUploadList.vue').default);

Vue.component('event-format', require('./components/admin/EventFormat.vue').default);

Vue.component('event-video', require('./components/admin/EventVideo.vue').default);

Vue.component('calendar-exam', require('./components/main/calendar-exam/CalendarExam.vue').default);

Vue.component('event-categories', require('./components/admin/events/events/event_categories/EventCategories.vue').default);

Vue.component('event-periods', require('./components/admin/events/events/event_periods/EventPeriods.vue').default);

Vue.component('composition-api', require('./components/admin/test/CounterButton.vue').default);

// Vue.component('user-video', {
//     template: UserVideo
// })

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if ( document.getElementById("app_vue") ) {
    const app_vue = new Vue({
        el: '#app_vue'
    });
}

if ( document.getElementById("app_event_person") ) {
    const app_event_person = new Vue({
        el: '#app_event_person'
    });
}

if ( document.getElementById("app_video") ) {
    const app_video = new Vue({
        el: '#app_video'
    });
}

if ( document.getElementById("app_menu_list") ) {
    const app_menu = new Vue({
        el: '#app_menu_list'
    });
}

if ( document.getElementById("app_program_list") ) {
    const app_program_list = new Vue({
        el: '#app_program_list'
    });
}

if ( document.getElementById("app_date_list") ) {
    const app_date_list = new Vue({
        el: '#app_date_list'
    });
}

if ( document.getElementById("app_user_set") ) {
    const app_user_set = new Vue({
        el: '#app_user_set'
    });
}

if ( document.getElementById("app_event_file") ) {
    const app_event_file = new Vue({
        el: '#app_event_file'
    });
}

if ( document.getElementById("app_event_format") ) {
    const app_event_format = new Vue({
        el: '#app_event_format'
    });
}

if ( document.getElementById("app_event_video") ) {
    const app_event_video = new Vue({
        el: '#app_event_video'
    });
}

if ( document.getElementById("app_applications") ) {
    const app_applications = new Vue({
        el: '#app_applications',
        router
    });
}

if ( document.getElementById("app_reestr") ) {
    const app_reestr = new Vue({
        el: '#app_reestr',
        router,
        store
    });
}

if ( document.getElementById("app_router") ) {
    const app_router = new Vue({
        el: '#app_router',
        router
    });
}

if ( document.getElementById("app_programs") ) {
    const app_programs = new Vue({
        el: '#app_programs',
        router
    });
}

if ( document.getElementById("app_event_categories") ) {
    const app_event_categories = new Vue({
        el: '#app_event_categories'
    });
}

if ( document.getElementById("app_event_period") ) {
    const app_programs = new Vue({
        el: '#app_event_period',
        router
    });
}

if ( document.getElementById("app_test_composition_api") ) {
    const app_test_composition_api = new Vue({
        el: '#app_test_composition_api',
        router
    });
}
