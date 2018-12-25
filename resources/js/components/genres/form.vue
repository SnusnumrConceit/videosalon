<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Название жанра</label>
                    <input type="text" v-model.trim="genre.name" class="form-control">
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/genres'})">Отмена</button>
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
                genre: {}
            }
        },
        methods: {
            validate() {
                if (this.genre.name === null || this.genre.name === undefined || !this.genre.name) {
                    this.$swal('Ошибка!', 'Вы не указали название жанра', 'error');
                    return false;
                }
                if (this.genre.name.length > 50 || this.genre.name.length < 3)  {
                    this.$swal('Ошибка!', 'Название жанра должно быть от 3 до 50 символов', 'error');
                    return false;
                }
                return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.genre.id) {
                    const response = await axios.post('/genres/update/' + this.genre.id, {...this.genre});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Жанр успешно обновлен', 'success');
                        this.$router.push('/genres');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/genres/create', {...this.genre});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Жанр успешно добавлен', 'success');
                        this.$router.push('/genres');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/genres/' + this.$route.params.id);
                if (! response.status) throw response;
                this.genre = response.data.genre;
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