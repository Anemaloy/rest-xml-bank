import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/js/pages/Home';

Vue.use(VueRouter);

let MainRouter = new VueRouter({
    mode: "history",
    routes: [{
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            breadcrumb: 'Главная',
            title: "REST APP || парсинг XML и работа с данными"
        },
    }]
});


export { MainRouter };