<template>
    <div class="row">
        <div class="col-md-12">
            <div class="user-contrainer">
                <h2>{{ user.last_name }} {{ user.first_name }}</h2>
            </div>
            <div class="orders-container" v-if="user.products.length">
                <table>
                    <thead>
                        <th>Номер заказа</th>
                        <th>Фильм</th>
                        <th>Стоимость</th>
                        <th>Услуга</th>
                    </thead>
                    <tbody>
                        <tr v-for="(product, index) in user.products">
                            <td>{{ product.id }}</td>
                            <td>{{ product.product.name }}</td>
                            <td>{{ product.product.price }}</td>
                            <td>{{ activity_format(product.product.activity) }}</td>
                            <td>
                                <button class="btn btn-outline-danger" @click="remove(product.id, index)">
                                    Удалить
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h3>Итого: {{ user.total_price }} ₽</h3>
                <button class="btn btn-outline-primary" type="button" @click="buyMovies">Оплатить</button>
            </div>
            <h3 v-else>
               Заказы отсутствуют
            </h3>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        name: "cabinet",

        computed: {
            ...mapGetters('auth', {
                token: 'isAuthenticated',
                user: 'getUser'
            }),
        },

        methods: {
            activity_format(id) {
                switch (id) {
                    case '1': return 'На прокат';
                    case '2': return 'Покупка';
                }
            },

            async remove(id, index) {
                const response = await axios.post('/orders/delete/' + id);
                if (response.status === 200) {
                    this.user.total_price -= this.user.products[index].product.price;
                    this.user.products.splice(index, 1);
                    this.$swal('Успешно', 'Заказ успешно удалён', 'success');
                    return true;
                } else {
                    this.$swal('Ошибка', 'Возникла ошибка при удалении заказа', 'error');
                    return false;
                }
            },

            async buyMovies() {

                const response = await axios.post('/orders/buy', { token: localStorage.getItem('token')});
                if (response.status === 200) {
                    this.user.products.splice(0, Object.keys(this.user.products).length);
                    this.$swal('Успешно', 'Заказы успешно оплачены.', 'success');
                    return true;
                } else {
                    this.$swal('Ошибка', response.data.msg, 'error');
                    return false;
                }
            }
        },

        created() {

        }
    }
</script>

<style scoped>

</style>