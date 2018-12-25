<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="links">
                    <button class="btn btn-outline-primary" @click="$router.push({ path: '/admin/user/add' })">
                        Добавить
                    </button>
                </div>
                <div class="search-group col">
                    <div class="search-group__filter">
                        <input class="form-control" v-model.lazy="search.keyword" v-debounce="500">
                        <button class="btn btn-outline-danger" type="button" @click="resetFilter()">Очистить</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="users-container" v-if="users.length">
                    <table class="table" width="100%">
                        <thead>
                        <tr>
                            <th>Email/Логин</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Права</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(user, index) in users">
                            <td>{{ user.email }}</td>
                            <td>{{ user.first_name }}</td>
                            <td>{{ user.last_name }}</td>
                            <td>{{ role(user.role_id) }}</td>
                            <td>
                                <a @click="$router.push({ path: '/admin/user/edit/' + user.id })">
                                    <i class="text-success fa fa-gear"></i>
                                </a>
                                <a @click="remove(user.id)">
                                    <i class="text-danger fa fa-ban"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
                <div v-else>
                    <i>Не найдено ни одного пользователя</i>
                </div>
            </div>
            </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import debounce from 'v-debounce';
    export default {
        directives: { debounce },
        data() {
            return {
                users: [],
                pagination: {
                    last_page: 1,
                    page: 1
                },

                search: {
                    keyword: '',
                    is_search: false,
                },
            }
        },
        computed: {
            ...mapGetters('auth', {
                user: 'getUser'
            })
        },

        methods: {
            role(id) {
                switch (id) {
                    case 1: return 'Пользователь';
                    case 2: return 'Администратор';
                }
            },

            switchPage(page) {
                this.pagination.page = page;
                this.loadData();
            },

            resetFilter() {
                this.search = {
                    keyword: '',
                    is_search: false,
                };
                this.switchPage(1);
            },

            async remove(id) {
                const response = await axios.post('/users/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Запись о пользователе успешно удалена!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить запись о пользователе!', 'error');
                }
            },

            async loadData() {
                let response = await axios.get('/users', { params: { 'page': this.pagination.page } });
                if (response.status === 200) {
                    this.users = response.data.users.data;
                    this.pagination.last_page = response.data.users.last_page;
                }
            },

            async searchData() {
                let response = await axios.get('/users/search/', { params: { keyword: this.search.keyword, page: this.pagination.page } });
                if (! response.status) throw response;
                if (response.status === 200) {
                    this.users = response.data.users.data;
                    this.pagination.last_page = response.data.users.last_page;
                } else if (response.data.status === 'error') {
                    this.$swal('Ошибка!', response.data.msg, 'error');
                    return false;
                }

            },
        },

        watch: {
            'search.keyword': function (newVal, oldVal) {
                this.searchData();
            }
        },

        mounted() {
            // if (this.user.role_id !== 1) {
            //     this.$router.push('/products');
            // }
            this.loadData();
        }
    }
</script>

<style scoped>

</style>