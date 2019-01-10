<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!--<div class="card-header">{{ __('Login') }}</div>-->
                    <div class="card-header">Регистрация</div>

                    <div class="card-body">
                        <form>
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
                                <button class="btn btn-outline-primary"
                                        @click="save()"
                                        type="button">
                                    Зарегистрироваться
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    export default {
        data() {
            return {
                user: {},
                roles: {}
            }
        },

        methods: {
            ...mapActions('auth', {
                setUser: 'setUser',
                setToken: 'setToken'
            }),
            validate() {
                if (this.user.email === undefined || this.user.email === null || !this.user.email) {
                    this.$swal('Ошибка!', 'Вы не указали email', 'error');
                    return false;
                }
                if (this.user.password === undefined || this.user.password === null || !this.user.password) {
                    this.$swal('Ошибка!', 'Вы не указали пароль', 'error');
                    return false;
                }
                if (this.user.first_name === undefined || this.user.first_name === null || !this.user.first_name) {
                    this.$swal('Ошибка!', 'Вы не указали имя', 'error');
                    return false;
                }
                if (this.user.last_name === undefined || this.user.last_name === null || !this.user.last_name) {
                    this.$swal('Ошибка!', 'Вы не указали фамилию', 'error');
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
                let emailRegExp = /([A-Za-z]{1,}[@][A-Za-z]{4,5}[.][A-Za-z]{1,3})/;
                let passwordRegExp = /([A-Za-z0-9]{6,50})/;
                let fnameRegExp = /([А-ЯЁа-яё]{3,50})/;
                let lnameRegExp = /([А-ЯЁа-яё]{3,50})/;
                let res = emailRegExp.exec(this.user.email);
                if (res === null) {
                    this.$swal('Ошибка!', 'Неверный формат email', 'error');
                    return false;
                } else {
                    if (res[0] !== this.user.email) {
                        this.$swal('Ошибка!', 'Неверный формат email', 'error');
                        return false;
                    }
                }
                res = passwordRegExp.exec(this.user.password);
                if (res === null) {
                    this.$swal('Ошибка!', 'Пароль должен состоять из латинских букв и цифр', 'error');
                    return false;
                } else {
                    if (res[0] !== this.user.password) {
                        this.$swal('Ошибка!', 'Пароль должен состоять из латинских букв и цифр', 'error');
                        return false;
                    }
                }
                res = fnameRegExp.exec(this.user.first_name);
                if (res === null) {
                    this.$swal('Ошибка!', 'Имя должно состоять из кириллистических букв', 'error');
                    return false;
                } else {
                    if (res[0] !== this.user.first_name) {
                        this.$swal('Ошибка!', 'Имя должно состоять из кириллистических букв', 'error');
                        return false;
                    }
                }
                res = lnameRegExp.exec(this.user.last_name);
                if (res === null) {
                    this.$swal('Ошибка!', 'Фамилия должна состоять из кириллистических букв', 'error');
                    return false;
                } else {
                    if (res[0] !== this.user.last_name) {
                        this.$swal('Ошибка!', 'Фамилия должна состоять из кириллистических букв', 'error');
                        return false;
                    }
                }
                return true;

            },
            // async loadAdditionalData() {
            //     const response = await axios.get('/users/form_info');
            //     if (! response.status) throw response;
            //     this.roles = response.data.roles;
            // },
            async save() {
                if (!this.validate()) return false;

                this.user.role_id = 1;

                const response = await axios.post('/registration', {...this.user});
                if  (response.data.message !== undefined) {
                    this.$swal('Ошибка', response.data.msg, 'error');
                    return false;
                }
                const token = response.data.token;
                const user = response.data.user;
                if (user === null || user === undefined) {
                    this.$swal('Ошибка', 'Неверный логин и пароль', 'error');
                    return false;
                }
                // if (user.role_id !== 2) {
                //     this.$swal('Ошибка', 'Неверный логин и пароль', 'error');
                //     return false;
                // }
                localStorage.setItem('token', token);
                this.setToken();
                this.setUser(user);
                if (user.role_id === 1) {
                    this.$router.push({ path: '/'});
                }
                if (user.role_id === 2) {
                    this.$router.push({ path: '/admin/products'});
                }
            }
        },
    }
</script>

<style scoped>

</style>