<template>
    <div class="content full-height">
        <div class="row">
            <div class="authenticable col-md-12" v-if="token && user">
                <div class="links" style="float: left">
                    <div class="list-group">
                        <router-link to="/contracts" class="list-group-item list-group-item-action">Заказы</router-link>
                        <router-link to="/providers" class="list-group-item list-group-item-action">Поставщики</router-link>
                        <router-link to="/shops" class="list-group-item list-group-item-action">Магазины</router-link>
                        <router-link to="/products" class="list-group-item list-group-item-action">Продукция</router-link>
                        <router-link to="/users" class="list-group-item list-group-item-action" v-if="user.role === 1 || user.role_id === 1">Пользователи</router-link>
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
    export default {
        name: "app"
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