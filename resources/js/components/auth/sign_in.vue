<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!--<div class="card-header">{{ __('Login') }}</div>-->
                    <div class="card-header">Авторизация</div>

                    <div class="card-body">
                        <form>

                            <div class="form-group">
                                <!--<label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>-->
                                <label for="email" class="col-form-label">Email</label>
                                <input id="email"
                                       type="email"
                                       class="form-control"
                                       name="email"
                                       v-model="email"
                                       required
                                       placeholder="Введите email">

                            </div>

                            <div class="form-group">
                                <!--<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>-->
                                <label for="password" class="col-form-label">Пароль</label>
                                <input id="password"
                                       type="password"
                                       class="form-control"
                                       v-model="pass"
                                       required
                                       placeholder="Введите пароль">
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-2">
                                    <button @click.prevent="signIn()" class="btn btn-primary" type="button">
                                        <!--{{ __('Login') }}-->
                                        Войти
                                    </button>
                                </div>
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
                email: '',
                pass: ''
            }
        },

        methods: {
            ...mapActions('auth', {
                setUser: 'setUser',
                setToken: 'setToken'
            }),

            validate() {
                if (! this.email.length || this.email === null || this.email === undefined) {
                    this.$swal('Ошибка!', 'Вы не ввели email', 'error');
                    return false;
                }
                if (! this.pass.length || this.pass === null || this.pass === undefined) {
                    this.$swal('Ошибка!', 'Вы не ввели пароль', 'error');
                    return false;
                }
                if (this.email.length < 10 || this.email.length > 50) {
                    this.$swal('Ошибка!', 'Длина email должна быть от 10 до 50 символов', 'error');
                    return false;
                }
                if (this.pass.length < 6 || this.pass.length > 50) {
                    this.$swal('Ошибка!', 'Длина пароля должна быть от 6 до 50 символов', 'error');
                    return false;
                }
                let emailRegExp = /([A-Za-z]{1,}[@][A-Za-z]{4,5}[.][A-Za-z]{1,3})/;
                let passwordRegExp = /([A-Za-z0-9]{6,50})/;

                let res = emailRegExp.exec(this.email);
                if (res === null) {
                    this.$swal('Ошибка!', 'Неверный формат email', 'error');
                    return false;
                } else {
                    if (res[0] !== this.email) {
                        this.$swal('Ошибка!', 'Неверный формат email', 'error');
                        return false;
                    }
                }
                res = passwordRegExp.exec(this.pass);
                if (res === null) {
                    this.$swal('Ошибка!', 'Пароль должен состоять из латинских букв и цифр', 'error');
                    return false;
                } else {
                    console.log(res);
                    if (res[0] !== this.pass) {
                        this.$swal('Ошибка!', 'Пароль должен состоять из латинских букв и цифр', 'error');
                        return false;
                    }
                }
                return true;
            },

            async signIn() {
                if (! this.validate()) return false;
                const response = await axios.post('/login', { email: this.email, password: this.pass});
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
        }
    }
</script>

<style scoped>

</style>