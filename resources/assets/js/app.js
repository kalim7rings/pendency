
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('header-component', require('./components/HeaderComponent.vue'));
Vue.component('footer-component', require('./components/FooterComponent.vue'));
Vue.component('home-component', require('./components/HomeComponent.vue'));
Vue.component('dashboard-component', require('./components/DashboardComponent.vue'));
Vue.component('logout-component', require('./components/LogoutComponent.vue'));

const app = new Vue({
    el: '#app'
});
