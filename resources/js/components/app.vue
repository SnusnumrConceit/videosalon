<template>
    <div class="content full-height">
        <div class="row">
            <div class="collapse navbar-collapse col-md-12" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav mr-auto pull-right" v-if="! token">

                    <li class="nav-item">
                        <router-link to="/login">
                            Войти
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/registration">
                            Регистрация
                        </router-link>
                    </li>
                </ul>
                <ul class="navbar-nav mr-auto pull-right" v-if="token && user">

                    <li class="nav-item">
                        <router-link :to="'/cabinet/'+ user.id">
                            {{ user.last_name + ' ' + user.first_name }}
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/logout">
                            Выйти
                        </router-link>
                    </li>
                </ul>
            </div>
            <div class="authenticable col-md-12" v-if="token && user">
                <div class="links" style="float: left" v-if="user.role_id === 2">
                    <div class="list-group">
                        <router-link to="/admin/products" class="list-group-item list-group-item-action">Фильмы</router-link>
                        <router-link to="/admin/genres" class="list-group-item list-group-item-action">Жанры</router-link>
                        <router-link to="/admin/audios" class="list-group-item list-group-item-action">Озвучки</router-link>
                        <router-link to="/admin/videos" class="list-group-item list-group-item-action">Трейлеры</router-link>
                        <router-link to="/admin/orders" class="list-group-item list-group-item-action">Заказы</router-link>
                        <router-link to="/admin/screens" class="list-group-item list-group-item-action">Скриншоты</router-link>
                        <router-link to="/admin/users" class="list-group-item list-group-item-action" v-if="user.role === 2 || user.role_id === 2">Пользователи</router-link>
                    </div>
                </div>
                <router-view></router-view>
            </div>
            <div class="main col-md-12 col-md-offset-4" v-else-if="! token || !user">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    export default {
        name: "app",

        computed: {
            ...mapGetters('auth', {
                token: 'isAuthenticated',
                user: 'getUser'
            }),
        },
        methods: {
            ...mapActions('auth', {
                setUser: 'setUser',
                setToken: 'setToken'
            }),
            async getUser() {
                const response = await axios.post('/auth', { token: localStorage.getItem('token')} );
                if (! response.status) throw response;
                if (response.data.user === null) {
                    this.$router.push({ path: '/'});
                    console.log(response.data.user);
                    return false;
                }
                this.setUser(response.data.user);
            }
        },
        created() {
            if (! this.user.length) {
                this.getUser();
            }
        },
        mounted() {

        }
    }
</script>

<style scoped lang="scss">
    .collapse {
        display: block;
    }
    .navbar-nav {
        flex-direction: unset;
    .nav-item {
        padding: 0px 50px;
    }
    }
</style>