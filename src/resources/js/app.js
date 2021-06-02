/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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

Vue.component('home', require('./components/Home.vue').default);
Vue.component('register', require('./components/Register.vue').default);
Vue.component('payment', require('./components/Payment.vue').default);

Vue.component('account-profile', require('./components/account/Profile.vue').default);
Vue.component('account-password', require('./components/account/Password.vue').default);
Vue.component('account-orders', require('./components/account/Orders.vue').default);
Vue.component('account-orders-incoming', require('./components/account/OrdersIncoming.vue').default);
Vue.component('account-review', require('./components/account/Review.vue').default);

Vue.component('add-product', require('./components/products/AddProduct.vue').default);
Vue.component('my-product', require('./components/products/MyProduct.vue').default);
Vue.component('change-product', require('./components/products/ChangeMyProduct.vue').default);
Vue.component('detail-product', require('./components/products/DetailProduct.vue').default);

Vue.component('my-cart', require('./components/carts/MyCart.vue').default);

Vue.component('transaction',require('./components/admin/Transaction.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import vSelect from 'vue-select'
Vue.component('v-select', vSelect)

import VueBootstrapToasts from "vue-bootstrap-toasts";
Vue.use(VueBootstrapToasts);

Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '.app',
});
