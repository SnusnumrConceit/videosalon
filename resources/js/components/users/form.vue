<template>
    <div class="row">
        <div class="col-md-6">
            <form action="">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text"
                           class="form-control"
                           v-model.trim="user.email">
                </div>
                <div class="form-group">
                    <label for="">Пароль</label>
                    <input type="password"
                           class="form-control"
                           v-model.trim="user.password">
                </div>
                <div class="form-group">
                    <label for="">Имя</label>
                    <input type="text"
                           class="form-control"
                           v-model.trim="user.first_name">
                </div>
                <div class="form-group">
                    <label for="">Фамилия</label>
                    <input type="text"
                           class="form-control"
                           v-model.trim ="user.last_name">
                </div>
                <div class="form-group">
                    <label for="">Роль</label>
                    <select class="form-control"
                            v-model="user.role_id">
                        <option value="1">Пользователь</option>
                        <option value="2">Администратор</option>
                        <!--<option :value="role.id" v-for="role in roles">{{ role.name }}</option>-->
                    </select>
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
                    <button class="btn btn-outline-default" @click="$router.push({ path: '/users' })">
                        Отменить
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {},
                roles: {}
            }
        },

        methods: {
            validate() {
                if (this.user.email === undefined || this.user.email === null || ! this.user.email) {
                    this.$swal('Ошибка!', 'Вы не указали email', 'error');
                    return false;
                }
                if (this.user.password === undefined || this.user.password === null || ! this.user.password) {
                    this.$swal('Ошибка!', 'Вы не указали пароль', 'error');
                    return false;
                }
                if (this.user.first_name === undefined || this.user.first_name === null || ! this.user.first_name) {
                    this.$swal('Ошибка!', 'Вы не указали имя', 'error');
                    return false;
                }
                if (this.user.last_name === undefined || this.user.last_name === null || ! this.user.last_name) {
                    this.$swal('Ошибка!', 'Вы не указали фамилию', 'error');
                    return false;
                }
                if (this.user.role_id === undefined || this.user.role_id === null || ! this.user.role_id) {
                    this.$swal('Ошибка!', 'Вы не выбрали назначение для данного пользователя', 'error');
                    return false;
                }
                if (this.user.email.length > 50 || this.user.email.length < 10) {
                    this.$swal('Ошибка', 'Длина email должна быть от 10 до 50 символов', 'error');
                    return false;
                }
                if (this.user.password.length > 50 || this.user.password.length < 6) {
                    this.$swal('Ошибка', 'Длина пароля должна быть от 6 до 50 символов', 'error');
                    return false;
                }
                if (this.user.first_name.length > 20 || this.user.first_name.length < 2) {
                    this.$swal('Ошибка', 'Длина имени должна быть от 2 до 20 символов', 'error');
                    return false;
                }
                if (this.user.last_name.length > 30 || this.user.last_name.length < 4) {
                    this.$swal('Ошибка', 'Длина фамилии должна быть от 4 до 30 символов', 'error');
                    return false;
                }
                return true;

            },
            async loadData() {
                const response = await axios.get('/users/' + this.$route.params.id);
                if (! response.status) throw response;
                this.user = response.data.user;
                this.roles = response.data.roles;
            },
            // async loadAdditionalData() {
            //     const response = await axios.get('/users/form_info');
            //     if (! response.status) throw response;
            //     this.roles = response.data.roles;
            // },
            async save() {
                if (! this.validate()) return false;
                if (this.$route.params.id) {
                    const response = await axios.post('/users/update/' + this.$route.params.id, {...this.user});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно!', 'Запись о пользователе успешно добавлена', 'success');
                        this.$router.push({ path: '/users' });
                        return true;
                    } else {
                        this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее', 'error');
                        return false;
                    }

                } else {
                    const response = await axios.post('/users/create', {...this.user});
                    if (response.data.status === 'success') {
                        this.$swal('Успешно!', 'Запись о пользователе успешно добавлена', 'success');
                        this.$router.push({ path: '/users' });
                        return true;
                    } else {
                        this.$swal('Ошибка!', 'Произошла ошибка. Повторите позднее', 'error');
                        return false;
                    }

                }
            }
        },

        mounted() {
            if (this.$route.params.id) {
                this.loadData();
            }
            // this.loadAdditionalData();
        }
    }
</script>

<style scoped>
    .row {
        justify-content: center;
    }
</style>