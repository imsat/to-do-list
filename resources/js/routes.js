import {createRouter, createWebHistory} from "vue-router";
import {get} from "./utils/localStorage.js";

import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import Register from './pages/Register.vue'

const routes = [
    {path: '/', name: 'home', component: Home},
    {path: '/login', name: 'login', component: Login, meta: {guest: true}},
    {path: '/register', name: 'register', component: Register, meta: {guest: true}},
    // {path: '/tasks', name: 'tasks', component: Form, meta: {requiresAuth: true}},
    // {path: '/tasks/create', name: 'tasks-create', component: CreateForm, meta: {requiresAuth: true}},
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'link-secondary' //Update
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (get('token')) {
            next();
            return;
        }
        next("/login");
    } else {
        next();
    }
});

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.guest)) {
        if (get('token')) {
            next("/");
            return;
        }
        next();
    } else {
        next();
    }
});


export default router;

