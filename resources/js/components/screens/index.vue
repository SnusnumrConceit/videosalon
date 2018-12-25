<template>
    <div class="row">
        <div class="col-md-12">
            <div class="form-inline">
                <div class="links">
                    <button class="btn btn-outline-primary" @click="$router.push({ path: '/admin/screen/add' })">
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
            <div class="audios-container" v-if="screens.length">
                <div class="card" style="width:400px" v-for="screen in screens">
                    <img class="card-img-bottom" :src="screen.url" alt="Card image" style="width:100%">
                    <div class="card-body">
                        <h4 class="card-title">{{ screen.name }}</h4>
                        <button class="btn btn-outline-warning" @click="$router.push({ path: '/admin/screen/edit/' + screen.id })">
                            Редактировать
                        </button>
                        <button class="btn btn-outline-danger" @click="remove(screen.id)">
                            Удалить
                        </button>
                    </div>
                </div>
                <!--<table>-->
                    <!--<thead>-->
                    <!--<th>Фильм</th>-->
                    <!--<th>Трейлер</th>-->
                    <!--</thead>-->
                    <!--<tbody>-->
                    <!--<tr v-for="screen in screens">-->
                        <!--<td>{{ screen.name }}</td>-->
                        <!--<td>-->
                            <!--<button class="btn btn-outline-warning" @click="$router.push({ path: '/screen/edit/' + screen.id })">-->
                                <!--Редактировать-->
                            <!--</button>-->
                            <!--<button class="btn btn-outline-danger" @click="remove(screen.id)">-->
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
                Не найдено ни одного скриншота
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
                screens: [],

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
                let response = await axios.post('/screens/delete/' + id);
                if (response.data.status === 'success') {
                    this.$swal('Успешно!', 'Запись об озвучке успешно удалена!', 'success');
                    this.loadData();
                } else {
                    this.$swal('Ошибка!', 'Не удалось удалить запись об озвучке!', 'error');
                }
            },


            async loadData() {
                const response = await axios.get('/screens', { params: { page: this.pagination.page }});
                if (response.status === 200) {
                    this.screens = response.data.screens.data;
                    this.pagination.last_page = response.data.screens.last_page;
                }
            },

            async searchData() {
                let response = await axios.get('/screens/search/', { params: { keyword: this.search.keyword, page: this.pagination.page } });
                if (! response.status) throw response;
                if (response.data.status === 'success') {
                    this.screens = response.data.screens.data;
                    this.pagination.last_page = response.data.screens.last_page;
                } else if (response.data.status === 'error') {
                    this.$swal('Ошибка!', response.data.msg, 'error');
                    return false;
                }

            },
        },

        created() {
            this.loadData();
        }
    }
</script>

<style scoped>

</style>