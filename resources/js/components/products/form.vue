<template>
    <div class="row">
        <form action="">
            <div class="form-group">
                <label for="">Обложка</label>
                <input type="text" class="form-control" v-model="product.cover">
            </div>
            <div class="form-group">
                <label for="">Название</label>
                <input type="text" class="form-control" v-model="product.name">
            </div>
            <div class="form-group">
                <label for="">Год</label>
                <input type="text" class="form-control" v-model="product.year">
            </div>
            <div class="form-group">
                <label for="">Описание</label>
                <input type="text" class="form-control" v-model="product.description">
            </div>
            <div class="form-group">
                <label for="">Цена</label>
                <input type="text" class="form-control" v-model="product.price">
            </div>
            <div class="form-group">
                <label for="">Услуга</label>
                <input type="text" class="form-control" v-model="product.activity">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "form",
        data() {
            return {
                product: {}
            }
        },
        methods: {
            validate() {
                if (this.product.name === null || this.product.name === undefined || !this.product.name) {
                    this.$swal('Ошибка!', 'Вы не указали название студии', 'error');
                    return false;
                }
                return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.product.id) {
                    const response = await axios.post('/products/update/' + this.product.id, {...this.product});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись об озвучке успешно обновлена', 'success');
                        this.$router.push('/audios');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/products/create', {...this.product});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись об озвучке успешно обновлена', 'success');
                        this.$router.push('/products');
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
            }
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