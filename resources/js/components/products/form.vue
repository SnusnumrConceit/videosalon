<template>
    <div class="row">
        <form action="">
            <div class="form-group">
                <label for="">Обложка</label>
                <input type="text" class="form-control" v-model.trim="product.cover">
            </div>
            <div class="form-group">
                <label for="">Название</label>
                <input type="text" class="form-control" v-model.trim="product.name">
            </div>
            <div class="form-group">
                <label for="">Год</label>
                <input type="text" class="form-control" v-model.trim="product.year">
            </div>
            <div class="form-group">
                <label for="">Описание</label>
                <textarea class="form-control" v-model.trim="product.description" rows="15"></textarea>
            </div>
            <div class="form-group">
                <label for="">Цена</label>
                <input type="text" class="form-control" v-model.trim="product.price">
            </div>
            <div class="form-group">
                <label for="">Услуга</label>
                <select class="form-control" v-model="product.activity">
                    <option value="1">Прокат</option>
                    <option value="2">Продажа</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Озвучка</label>
            </div>
            <div class="form-group" v-for="audio in audios">
                <label for="">{{ audio.name }}
                    <input type="checkbox" :value="audio.id" v-model="product.audios">
                </label>
            </div>
            <div class="form-group">
                <label for="">Жанры</label>
            </div>
            <div class="form-group" v-for="genre in genres">
                <label for="">{{ genre.name }}
                    <input type="checkbox"
                           :value="genre.id"
                           v-model="product.genres">
                </label>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-primary"
                        @click="save()"
                        v-if="$route.params.id">
                    Сохранить
                </button>
                <button class="btn btn-outline-primary"
                        @click="save()"
                        v-else>
                    Добавить
                </button>
                <button class="btn btn-outline-default" @click="$router.push({ path: '/admin/products' })">
                    Отменить
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "form",
        data() {
            return {
                product: {
                    genres: [],
                    audios: []
                },

                audios: [],
                genres: []
            }
        },
        methods: {
            validate() {
                if (this.product.name === null || this.product.name === undefined || !this.product.name) {
                    this.$swal('Ошибка!', 'Вы не указали название фильма', 'error');
                    return false;
                }
                if (this.product.cover === null || this.product.cover === undefined || !this.product.cover) {
                    this.$swal('Ошибка!', 'Вы не указали обложку к фильму', 'error');
                    return false;
                }
                if (this.product.year === null || this.product.year === undefined || !this.product.year) {
                    this.$swal('Ошибка!', 'Вы не указали год выпуска фильма', 'error');
                    return false;
                }
                if (this.product.description === null || this.product.description === undefined || !this.product.description) {
                    this.$swal('Ошибка!', 'Вы не указали название фильма', 'error');
                    return false;
                }
                if (this.product.price === null || this.product.price === undefined || !this.product.price) {
                    this.$swal('Ошибка!', 'Вы не указали название фильма', 'error');
                    return false;
                }
                if (this.product.activity === null || this.product.activity === undefined || !this.product.activity) {
                    this.$swal('Ошибка!', 'Вы не указали название фильма', 'error');
                    return false;
                }
                if (this.product.name.length < 2 || this.product.name.length > 50) {
                    this.$swal('Ошибка!', 'Название фильма должно быть длиной от 2 до 50 символов', 'error');
                    return false;
                }
                if (this.product.cover.length < 10 || this.product.cover.length > 100) {
                    this.$swal('Ошибка!', 'Слишком короткая ссылка', 'error');
                    return false;
                }
                if (this.product.year.length !== 4) {
                    this.$swal('Ошибка!', 'Неверная длина года', 'error');
                    return false;
                }
                if (this.product.description.length < 10 || this.product.description > 1000) {
                    this.$swal('Ошибка!', 'Описание должно быть длиной от 10 до 1000', 'error');
                    return false;
                }
                if (this.product.price.length > 3  || this.product.price.length < 2) {
                    this.$swal('Ошибка!', 'Цена может быть от 100 до 999 рублей', 'error');
                    return false;
                }
                let productRegExp = /([А-ЯЁа-яё ]{3,50})/;
                let descRegExp = /([А-ЯЁа-яё ;,.\-]{10,1000})/;
                let res = productRegExp.exec(this.product.name);
                if (res === null) {
                    this.$swal('Ошибка!', 'Название фильма должно состоять из кириллистических букв', 'error');
                    return false;
                } else {
                    if (res[0] !== this.product.name) {
                        this.$swal('Ошибка!', 'Название фильма должно состоять из кириллистических букв', 'error');
                        return false;
                    }
                }
                if (! parseInt(this.product.year)) {
                    this.$swal('Ошибка!', 'Год выпуска фильма должен состоять из цифр', 'error');
                    return false;
                }
                res = descRegExp.exec(this.product.description);
                if (res === null) {
                    this.$swal('Ошибка!', 'Описание должно состоять из кириллистических букв', 'error');
                    return false;
                } else {
                    if (res[0] !== this.product.description) {
                        this.$swal('Ошибка!', 'Описание должно состоять из кириллистических букв', 'error');
                        return false;
                    }
                }
                if (! parseInt(this.product.price)) {
                    this.$swal('Ошибка!', 'Цена фильма должна состоять из цифр', 'error');
                    return false;
                }

                return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.product.id) {
                    const response = await axios.post('/products/update/' + this.product.id, {...this.product});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Информация о фильме  успешно обновлена', 'success');
                        this.$router.push('/admin/products');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/products/create', {...this.product});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Фильм успешно добавлен', 'success');
                        this.$router.push('/admin/products');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/products/edit/' + this.$route.params.id);
                if (! response.status) throw response;
                this.product = response.data.product;
                this.product.audios = this.product.audios.map(audio => audio.id);
                this.product.genres = this.product.genres.map(genre => genre.id);
            },
            async loadAdditionalData() {
                const response = await axios.get('products/form_info');
                if (! response.status) throw response;
                this.audios = response.data.audios;
                this.genres = response.data.genres;
            }
        },

        created() {
            if (this.$route.params.id) {
                this.loadData();
            }
            this.loadAdditionalData();
        }
    }
</script>

<style scoped>

</style>