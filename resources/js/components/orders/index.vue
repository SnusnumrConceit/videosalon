<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="links">
                    <button class="btn btn-outline-primary" @click="$router.push({ path: '/admin/order/add' })">
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
        </div>
        <div class="col-md-12">
            <div class="orders-container" v-if="orders.length">
                <table width="100%" class="table">
                    <thead>
                        <th>Номер заказа</th>
                        <th>Заказчик</th>
                        <th>Фильм</th>
                        <th>Стоимость</th>
                        <th>Услуга</th>
                    </thead>
                    <tbody>
                    <tr v-for="order in orders">
                        <td>{{ order.id }}</td>
                        <td>{{ order.user.last_name }} {{ order.user.first_name }}</td>
                        <td>{{ order.product.name }}</td>
                        <td>{{ order.product.price }}</td>
                        <td>{{ activity_format(order.product.activity) }}</td>
                        <td>
                            <button class="btn btn-outline-warning" @click="$router.push({ path: '/admin/order/edit/' + order.id })">
                                Редактировать
                            </button>
                            <button class="btn btn-outline-danger" @click="remove(order.id)">
                                Удалить
                            </button>
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
                Не найдено ни одного заказа
            </div>
        </div>
    </div>
</template>

<script>
    import debounce from 'v-debounce';
    export default {
        name: "index",
        directives: { debounce },
        data() {
            return {
                orders: [],

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
        methods: {
            activity_format(id) {
              switch (id) {
                  case '1': return 'На прокат';
                  case '2': return 'Покупка';
              }
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

            async remove(id) {
                let response = await axios.post('/orders/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Запись о заказе успешно удалена!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить запись о заказе!', 'error');
                }
            },


            async loadData() {
                const response = await axios.get('/orders', { params: { page: this.pagination.page }});
                if (response.status === 200) {
                    this.orders = response.data.orders.data;
                    this.pagination.last_page = response.data.orders.last_page;
                }
            },

            async searchData() {
                let response = await axios.get('/orders/search/', { params: { keyword: this.search.keyword, page: this.pagination.page } });
                if (! response.status) throw response;
                console.log('поиск без ошибок');
                if (response.status === 200) {
                    console.log('200?');
                    this.orders = response.data.orders.data;
                    this.pagination.last_page = response.data.orders.last_page;
                } else if (response.data.status === 'error') {
                    this.$swal('Ошибка!', response.data.msg, 'error');
                    return false;
                }

            },
        },

        created() {
            this.loadData();
        },

        watch: {
            'search.keyword': function () {
                this.searchData();
            }
        }
    }
</script>

<style scoped>

</style>