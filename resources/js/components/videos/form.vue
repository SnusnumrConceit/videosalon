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
                <div class="form-group" v-if="movies.length && ! this.$route.params.id">
                    <label for="">Фильм</label>
                    <select v-model="video.product_id" class="form-control">
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/admin/videos'})">Отмена</button>
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
                video: {
                    url: 'https://www.youtube.com/embed/YOUTUBE_IDENTITY'
                },
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
                let regExp = /https:\/\/www.youtube.com\/embed\//;
                let res = regExp.exec(this.video.url);
                if (res === null) {
                    this.$swal('Ошибка!', 'Неверный формат URL', 'error');
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
                        this.$router.push('/admin/videos');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/videos/create', {...this.video});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись о трейлере успешно обновлена', 'success');
                        this.$router.push('/admin/videos');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/videos/' + this.$route.params.id);
                if (! response.status) throw response;
                this.video = response.data.video;
            },

            async loadAdditionalData() {
                const response = await axios.get('/videos/form_info');
                if (! response.status) throw response;
                this.movies = response.data.movies;
            }
        },

        created() {
            if (this.$route.params.id) {
                this.loadData();
            }
            this.loadAdditionalData();
        },
        
        watch: {
            'video.product_id': function (newVal, oldVal) {
                
            }
        }
    }
</script>

<style scoped>

</style>