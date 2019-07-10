
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import router from "./router";
// import customers from './views/Customers.vue';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data: {
        // programmers: [],
    },

    components: {
        // 'programmer-list': programmers
        // 'customers': customers
    },

    mounted() {
        //make an ajax request to our server
        // axios.get('/api/programmers')
        //     .then(response => this.programmers = response.data);
    },

    methods: {
        // addNew() {
        //     this.programmers.push({
        //         name: this.newName
        //     });
        //     this.newName = '1';
        // }
    },

    computed: {
        // computedNewName() {
        //     return this.newName.split('').reverse('').join('');
        // }
    }
});
