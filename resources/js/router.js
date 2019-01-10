//Админка
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


//UI
import UI from './components/ui/main.vue';
import Cabinet from './components/ui/cabinet.vue';
import ProductDetail from './components/ui/product_detail.vue';
import Registration from './components/auth/registration';

import store from './store/modules/auth.js';

const ifNotAuthenticated = (to, from, next) => {
    const isAuth = localStorage.getItem('token') || '';
    if (! isAuth) {
        next()
        return
    }
    next('/admin/products')
};

const ifAuthenticated = (to, from, next) => {
    const isAuth = localStorage.getItem('token') || '';
    if (isAuth) {
        next()
        return
    }
    // next('/login')
    next('/')
};

export const routes = [
    {
      path: '/',
      component: UI,
    },
    {
        path: '/cabinet/:id',
        component: Cabinet,
    },
    {
        path: '/movie/:id',
        component: ProductDetail,
    },
    {
        path: '/registration',
        component: Registration
    },
    {
        path: '/admin/users',
        component: Users,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/user/add',
        component: UserForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/user/edit/:id',
        component: UserForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/audios',
        component: Audios,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/audio/add',
        component: AudioForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/audio/edit/:id',
        component: AudioForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/genres',
        component: Genres,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/genre/add',
        component: GenreForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/genre/edit/:id',
        component: GenreForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/screens',
        component: Screens,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/screen/add',
        component: ScreenForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/screen/edit/:id',
        component: ScreenForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/videos',
        component: Videos,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/video/add',
        component: VideoForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/video/edit/:id',
        component: VideoForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/orders',
        component: Orders,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/order/add',
        component: OrderForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/order/edit/:id',
        component: OrderForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/products',
        component: Products,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/product/add',
        component: ProductForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/admin/product/edit/:id',
        component: ProductForm,
        beforeEnter: ifAuthenticated
    },
    {
        path: '/login',
        component: Login,
        beforeEnter: ifNotAuthenticated
    },
    {
        path: '/logout',
        component: Logout,
        beforeEnter: ifAuthenticated
    }
];