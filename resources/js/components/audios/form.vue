<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Студия озвучки</label>
                    <input type="text" v-model.trim="audio.name" class="form-control">
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/audios'})">Отмена</button>
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
                audio: {}
            }
        },
        methods: {
            validate() {
                if (this.audio.name === null || this.audio.name === undefined || !this.audio.name) {
                    this.$swal('Ошибка!', 'Вы не указали название студии', 'error');
                    return false;
                }
                if (this.audio.name.length > 50 || this.audio.name.length < 3)  {
                    this.$swal('Ошибка!', 'Название студии озвучки должно быть от 3 до 50 символов', 'error');
                    return false;
                }
                return true;
            },
            async save() {
                if (! this.validate()) return false;
                if (this.audio.id) {
                    const response = await axios.post('/audios/update/' + this.audio.id, {...this.audio});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись об озвучке успешно обновлена', 'success');
                        this.$router.push('/audios');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                } else {
                    const response = await axios.post('/audios/create', {...this.audio});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно', 'Запись об озвучке успешно обновлена', 'success');
                        this.$router.push('/audios');
                        return false;
                    } else if (response.data.status === 'error') {
                        this.$swal('Ошибка', 'Произошла ошибка. Повторите позднее.', 'error');
                        return false;
                    }
                }
            },
            async loadData() {
                const response = await axios.get('/audios/edit/' + this.$route.params.id);
                if (! response.status) throw response;
                this.audio = response.data.audio;
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