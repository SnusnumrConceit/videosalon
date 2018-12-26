<template>
    <div class="row">
        <div class="collapse navbar-collapse col-md-12" id="navbarSupportedContent">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav mr-auto pull-right" v-if="token && user">

                <li class="nav-item">
                    <a>
                        {{ user.last_name + ' ' + user.first_name }}
                    </a>
                </li>
                <li class="nav-item">
                    <router-link to="/logout">
                        Выйти
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="products-section" v-if="products.length" style="display: inline-flex">
                <div class="card col-md-2" v-for="product in products">
                    <h4 class="card-title">{{ product.name }}</h4>
                    <img class="card-img-bottom" :src="product.cover" alt="Подробнее" style="width:100%; cursor: pointer" @click="$router.push({ path: '/movie/' + product.id })">
                    <div class="card-body">
                        <button class="btn btn-outline-danger" v-if="product.activity === '1'">
                            На прокат
                        </button>
                        <button class="btn btn-outline-danger" v-else-if="product.activity === '2'">
                            Купить
                        </button>
                        <span>{{ product.price }} руб.</span>
                    </div>
                </div>
            </div>
            <div v-else>
                Не найдено ни одного фильма
            </div>
            <div class="" v-if="products.length">
                <paginate
                        v-model="pagination.page"
                        :page-count="pagination.last_page"
                        :container-class="'pagination'"
                        :page-class="'page-item'"
                        :page-link-class="'page-link'"
                        :prev-text="'След.'"
                        :prev-class="'page-item'"
                        :prev-link-class="'page-link'"
                        :next-text="'Пред.'"
                        :next-class="'page-item'"
                        :next-link-class="'page-link'"
                        :click-handler="switchPage">
                </paginate>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: "main",

        data() {
            return {
                products: [],

                pagination: {
                    page: 1,
                    last_page: 1
                },

                search: {
                    keyword: '',
                    is_search: false
                }
            }
        },

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
                const response = await axios.get('/auth');
                if (! response.status) throw response;
                if (response.data.user === null) {
                    console.log(response.data.user);
                    return false;
                }
                this.setUser(response.data.user);
            },

            switchPage(page) {
                this.pagination.page = page;
                if (! this.search.is_search) {
                    this.loadData();
                } else {
                    this.searchData();
                }
            },

            resetFilter() {
                this.search = {
                    keyword: '',
                    is_search: false,
                };
                this.switchPage(1);
            },

            async searchData() {
                let response = await axios.get('/products/search/', { params: { keyword: this.search.keyword, page: this.pagination.page } });
                if (! response.status) throw response;
                if (response.status === 200) {
                    this.products = response.data.products.data;
                    this.pagination.last_page = response.data.products.last_page;
                } else if (response.data.status === 'error') {
                    this.$swal('Ошибка!', response.data.msg, 'error');
                    return false;
                }
            },

            async loadProducts() {
                const response = await axios.get('/products', { page: this.pagination.page });
                if (response.status === 200) {
                    this.products = response.data.products.data;
                    this.pagination.last_page = response.data.products.last_page;
                }
            }
        },

        watch: {
            'search.keyword': function (newVal, oldVal) {
                this.searchData();
            }
        },

        created() {
            if (! this.user.length) {
                this.getUser();
            }
            this.loadProducts();
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

<style scoped>

</style>