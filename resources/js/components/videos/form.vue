<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Название фильма</label>
                    <input type="text" v-model="video.name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">URL</label>
                    <input type="text" v-model="video.url" class="form-control">
                </div>
                <div class="form-group" v-if="movies.length">
                    <label for="">Фильм</label>
                    <select v-model="video.product_id" class="form-control">
                        <option value="movie.id" v-for="movie in movies">{{ movie.name }}</option>
                    </select>
                </div>
                <div class="form-group" v-else="">
                    <i>Не найдено ни одного фильма. Вы можете их добавить по ссылке.</i>
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/videos'})">Отмена</button>
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
                video: {},
                movies: []
            }
        },
        methods: {
            validate() {
                if (this.video.name === null || this.video.name === undefined || !this.video.name) {
                    this.$swal('Ошибка!', 'Вы не указали название фильма', 'error');
                    return false;
                }
                if (this.video.url === null || this.video.url === undefined || !this.video.url) {
                    this.$swal('Ошибка!', 'Вы не указали url', 'error');
                    return false;
                }
                if (this.video.product_id === null || this.video.product_id === undefined || !this.video.product_id) {
                    this.$swal('Ошибка!', 'Вы не указали фильм', 'error');
                    return false;
                }
                return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.video.id) {
                    const response = await axios.post('/videos/update/' + this.video.id, {...this.video});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись о трейлере успешно обновлена', 'success');
                        this.$router.push('/videos');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/videos/create', {...this.video});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись о трейлере успешно обновлена', 'success');
                        this.$router.push('/videos');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/videos/edit/' + this.$route.params.id);
                if (! response.status) throw response;
                this.video = response.data.video;
                this.movies = response.data.movies;
            },
        },

        created() {
            if (this.$route.params.id) {
                this.loadData();
            }
        }
    }
</script>

<style scoped>

</style>