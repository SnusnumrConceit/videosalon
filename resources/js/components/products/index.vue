<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="links">
                    <button class="btn btn-outline-primary" @click="$router.push({ path: '/admin/product/add' })">
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
            <div class="products-container" v-if="products.length">
                <div class="card" style="width:400px" v-for="product in products">
                    <h4 class="card-title">{{ product.name }}</h4>
                    <img class="card-img-bottom" :src="product.cover" alt="Card image" style="width:100%">
                    <div class="card-body">
                        <button class="btn btn-outline-warning" @click="$router.push({ path: '/admin/product/edit/' + product.id })">
                            Редактировать
                        </button>
                        <button class="btn btn-outline-danger" @click="remove(product.id)">
                            Удалить
                        </button>
                    </div>
                </div>
                <!--<table>-->
                    <!--<thead>-->
                    <!--<th>Фильм</th>-->
                    <!--<th>Обложка</th>-->
                    <!--</thead>-->
                    <!--<tbody>-->
                    <!--<tr v-for="product in products">-->
                        <!--<td>{{ product.name }}</td>-->
                        <!--<td>-->
                            <!--<button class="btn btn-outline-warning" @click="$router.push({ path: '/admin/product/edit/' + product.id })">-->
                                <!--Редактировать-->
                            <!--</button>-->
                            <!--<button class="btn btn-outline-danger" @click="remove(product.id)">-->
                                <!--Удалить-->
                            <!--</button>-->
                        <!--</td>-->
                    <!--</tr>-->
                    <!--</tbody>-->
                <!--</table>-->
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
                Не найдено ни одного фильма
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
                products: [],

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
                let response = await axios.post('/products/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Фильм успешно удален!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить фильм!', 'error');
                }
            },


            async loadData() {
                const response = await axios.get('/products', { params: { page: this.pagination.page }});
                if (response.status === 200) {
                    this.products = response.data.products.data;
                    this.pagination.last_page = response.data.products.last_page;
                }
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
        },

        watch: {
          'search.keyword': function (newVal, oldVal) {
              this.searchData();
          }
        },

        created() {
            this.loadData();
        }
    }
</script>

<style scoped>

</style>