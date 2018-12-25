<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">URL</label>
                    <input type="text" v-model="screen.url" class="form-control">
                </div>
                <div class="form-group" v-if="movies.length">
                    <label for="">Фильм</label>
                    <select v-model="screen.product_id" class="form-control">
                        <option :value="movie.id" v-for="movie in movies">{{ movie.name }}</option>
                    </select>
                </div>
                <div class="form-group" v-else="">
                    <i>Не найдено ни одного фильма. Вы можете их добавить по <router-link to="/admin/product/add">ссылке</router-link>.</i>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-success"
                            type="button"
                            v-if="$route.params.id"
                            @click="save()">
                        Сохранить
                    </button>
                    <button class="btn btn-outline-success"
                            type="button"
                            v-else
                            @click="save()">
                        Добавить
                    </button>
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/admin/screens'})">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "form",
        data() {
            return {
                screen: {},
                movies: []
            }
        },
        methods: {
            validate() {
                if (this.screen.url === null || this.screen.url === undefined || !this.screen.url) {
                    this.$swal('Ошибка!', 'Вы не указали url', 'error');
                    return false;
                }
                if (this.screen.product_id === null || this.screen.product_id === undefined || !this.screen.product_id) {
                    this.$swal('Ошибка!', 'Вы не указали фильм', 'error');
                    return false;
                }
                return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.screen.id) {
                    const response = await axios.post('/screens/update/' + this.screen.id, {...this.screen});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Скриншот  успешно обновлен', 'success');
                        this.$router.push('/admin/screens');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/screens/create', {...this.screen});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Скриншот успешно добавлен', 'success');
                        this.$router.push('/admin/screens');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/screens/' + this.$route.params.id);
                if (! response.status) throw response;
                this.screen = response.data.screen;
            },

            async loadAddinationalData() {
                const response = await axios.get('/screens/form_info');
                if (! response.status) throw response;
                this.movies = response.data.movies;
            }
        },

        created() {
            if (this.$route.params.id) {
                this.loadData();
            }
            this.loadAddinationalData();
        }
    }
</script>

<style scoped>

</style>