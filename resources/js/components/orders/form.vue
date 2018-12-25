<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group" v-if="users.length">
                    <label for="">Заказчик</label>
                    <select v-model="order.user_id" class="form-control">
                        <option :value="user.id" v-for="user in users">{{ user.last_name }} {{ user.first_name }}</option>
                    </select>
                </div>
                <div class="form-group" v-else>
                    <i>Не найдено ни одного фильма. Вы можете их добавить по <router-link to="/admin/user/add">ссылке</router-link>.</i>
                </div>
                <div class="form-group" v-if="movies.length">
                    <label for="">Фильм</label>
                    <select v-model="order.product_id" class="form-control">
                        <option :value="movie.id" v-for="movie in movies">{{ movie.name }} ({{ activity_format(movie.activity) }})</option>
                    </select>
                </div>
                <div class="form-group" v-else>
                    <i>Не найдено ни одного фильма. Вы можете их добавить по <router-link to="/admin/product/add">ссылке</router-link>.</i>
                </div>
                <div class="form-group">
                    <label for="">Услуга</label>
                    <select v-model="activity" class="form-control" disabled>
                        <option value="1">На прокат</option>
                        <option value="2">Покупка</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Стоимость</label>
                    <input type="text" class="form-control" disabled v-model="cost">
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/admin/orders'})">Отмена</button>
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
                order: {},
                users: [],
                movies: [],
                activity: 0,
                cost: ''
            }
        },
        methods: {
            activity_format(id) {
                switch (id) {
                    case "1": return 'На прокат';
                    case "2": return 'Покупка';
                }
            },
            validate() {
                if (this.order.user_id === null || this.order.user_id === undefined || !this.order.user_id) {
                    this.$swal('Ошибка!', 'Вы не укаазали покупателя', 'error');
                    return false;
                }
                if (this.order.product_id === null || this.order.product_id === undefined || !this.order.product_id) {
                    this.$swal('Ошибка!', 'Вы не указали фильм', 'error');
                    return false;
                }
                if (this.activity === null || this.activity === undefined || !this.activity) {
                    this.$swal('Ошибка!', 'Вы не указали вид услуги', 'error');
                    return false;
                }
                return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.order.id) {
                    const response = await axios.post('/orders/update/' + this.order.id, {...this.order});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись о заказе успешно обновлена', 'success');
                        this.$router.push('/admin/orders');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/orders/create', {...this.order});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись о заказе успешно добавлена', 'success');
                        this.$router.push('/admin/orders');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/orders/' + this.$route.params.id);
                if (! response.status) throw response;
                this.order = response.data.order;
                if (this.order.product !== undefined) {
                    this.cost = this.order.product.price;
                    this.activity = this.order.product.activity;
                }
            },
            async loadAdditionalData() {
                const response = await axios.get('/orders/form_info');
                if (! response.status) throw response;
                this.movies = response.data.movies;
                this.users = response.data.users;
            }
        },

        created() {
            if (this.$route.params.id) {
                this.loadData();
            }
            this.loadAdditionalData();
        },

        watch: {
            'order.product_id': function (newVal, oldVal) {
                this.movies.forEach(movie => {
                    if (movie.id === newVal) {
                        this.activity = movie.activity;
                        this.cost = movie.price;
                    }
                })
            },
        }
    }
</script>

<style scoped>

</style>