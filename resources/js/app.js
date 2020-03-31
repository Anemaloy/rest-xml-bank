//Import plugins
import './bootstrap';
import Vue from 'vue';
import { MainRouter } from '@/js/routes.js';
import App from '@/js/views/App';
import api from '@/js/api.js';

//Settings
Object.defineProperty(Vue.prototype, '$api', { value: api });
let token = document.head.querySelector('meta[name="csrf-token"]');
Object.defineProperty(Vue.prototype, '$token', { value: token.content });

if (document.getElementById("app")) {
    const app = new Vue({
        el: '#app',
        router: MainRouter,
        render: h => h(App),
    });
}