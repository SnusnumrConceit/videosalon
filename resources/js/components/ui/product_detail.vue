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
                    <button class="btn btn-outline-danger" v-if="movie.activity === '1'">
                        На прокат
                    </button>
                    <button class="btn btn-outline-danger" v-else-if="movie.activity === '2'">
                        Купить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "product_detail",
        data() {
            return {
                movie: {}
            }
        },

        methods: {
            async loadMovie() {
                const response = await axios.get('/products/edit/' + this.$route.params.id);
                if (response.status === 200) {
                    this.movie = response.data.product;
                }
            }
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