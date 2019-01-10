<template>
    <div class="row">
        <div class="col-md-12 movie-section" style="display: inline-flex">
            <div class="col-md-4">
                <img :src="movie.cover" alt="" style="width:100%;">
            </div>
            <div class="col-md-8" style="text-align: start">
                <div class="form-group">
                    <strong><h6>Жанр</h6></strong>
                    <span v-for="genre in movie.genres"> {{ genre.name }}</span>
                </div>
                <div class="form-group">
                    <strong><h6>Озвучка</h6></strong>
                    <span v-for="audio in movie.audios"> {{ audio.name }}</span>
                </div>
                <div class="form-group">
                    <strong><h6>Описание</h6></strong>
                    <p>{{ movie.description }}</p>
                </div>
                <div class="form-group">
                    <strong><h6>{{ movie.price }} руб.</h6></strong>
                    <button class="btn btn-outline-danger" v-if="movie.activity === '1'" @click="makeOrder(movie.id)">
                        На прокат
                    </button>
                    <button class="btn btn-outline-danger" v-else-if="movie.activity === '2'" @click="makeOrder(movie.id)">
                        Купить
                    </button>
                </div>
                <div class="form-group" v-if="movie.video">
                    <strong><h6>Трейлер</h6></strong>
                    <iframe :src="movie.video.url" width="50%" height="70%"></iframe>
                </div>
                <div class="form-group" v-if="movie.screens.length">
                    <strong><h6>Кадры из фильма</h6></strong>
                    <img class="card-img-bottom" :src="screen.url" alt="Card image" style="width:25%" v-for="screen in movie.screens">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    export default {
        name: "product_detail",
        data() {
            return {
                movie: {}
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
                    return false;
                }
                this.setUser(response.data.user);
            },
            async loadMovie() {
                const response = await axios.get('/products/edit/' + this.$route.params.id);
                if (response.status === 200) {
                    this.movie = response.data.product;
                }
            },

            async makeOrder(id) {
                if (! this.user.length) {
                    if (this.user.role_id === 2 ){
                        this.$swal('Внимание!', 'Администратор не может арендовать или приобрести фильм.', 'warning');
                        return false;
                    }
                    if (this.user.role_id !== 1) {
                        this.$swal('Внимание!', 'Чтобы совершить покупку или взять в аренду фильм необходимо авторизоваться', 'warning');
                        return false;
                    }
                }
                const response = await axios.post('/orders/create', { user_id: this.user.id, product_id: id });
                if (response.status === 200) {
                    this.$swal('Успешно', 'Фильм успешно добавлен в личный кабинет!', 'success');
                    return true;
                }  else {
                    this.$swal('Ошибка!', 'Не удалось совершить операцию. Повторите позднее.', 'error');
                    return false;
                }
            },
        },

        created() {
            this.loadMovie();
        }
    }
</script>

<style scoped>
    h6 {
        font-weight: 600;
    }
</style>