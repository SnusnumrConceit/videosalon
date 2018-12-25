import Audios from './components/audios/index.vue';
import AudioForm from './components/audios/form.vue';

import Products from './components/products/index.vue';
import ProductForm from './components/products/form.vue'

import Genres from './components/genres/index.vue';
import GenreForm from './components/genres/form.vue'

import Screens from './components/screens/index.vue';
import ScreenForm from './components/screens/form.vue';

import Videos from './components/videos/index.vue';
import VideoForm from './components/videos/form.vue';

import Orders from  './components/orders/index.vue';
import OrderForm from './components/orders/form.vue';

import Users from  './components/users/index.vue';
import UserForm from './components/users/form.vue';

import Login from './components/auth/sign_in.vue';
import Logout from './components/auth/logout.vue';


import store from './store/modules/auth.js';

const ifNotAuthenticated = (to, from, next) => {
    const isAuth = localStorage.getItem('token') || '';
    if (! isAuth) {
        next()
        return
    }
    next('/contracts')
};

const ifAuthenticated = (to, from, next) => {
    const isAuth = localStorage.getItem('token') || '';
    if (isAuth) {
        next()
        return
    }
    next('/login')
};

export const routes = [
    {
        path: '/users',
        component: Users,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/user/add',
        component: UserForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/user/edit/:id',
        component: UserForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/audios',
        component: Audios,
        // beforeEnter: ifNotAuthenticated
    },
    {
        path: '/audio/add',
        component: AudioForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/audio/edit/:id',
        component: AudioForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/genres',
        component: Genres,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/genre/add',
        component: GenreForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/genre/edit/:id',
        component: GenreForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/screens',
        component: Screens,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/screen/add',
        component: ScreenForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/screen/edit/:id',
        component: ScreenForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/videos',
        component: Videos,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/video/add',
        component: VideoForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/video/edit/:id',
        component: VideoForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/orders',
        component: Orders,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/order/add',
        component: OrderForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/order/edit/:id',
        component: OrderForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/products',
        component: Products,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/product/add',
        component: ProductForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/product/edit/:id',
        component: ProductForm,
        // beforeEnter: ifAuthenticated
    },
    {
        path: '/login',
        component: Login,
        // beforeEnter: ifNotAuthenticated
    },
    {
        path: '/logout',
        component: Logout,
        // beforeEnter: ifAuthenticated
    }
];