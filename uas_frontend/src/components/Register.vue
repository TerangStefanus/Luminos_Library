<template>
    <main>
        <v-container fluid fill-height class="posisinya">
            <v-layout flex align-center justify-center>
                <v-flex xs12 sm6 elevation-6>
                    <v-toolbar class="grey darken-3">
                        <v-toolbar-title class="grey--text">
                            <h1>Register to Luminos Library</h1>
                        </v-toolbar-title>
                    </v-toolbar>
                    <v-card>
                        <v-card-text class="pt-4">
                            <div>
                                <v-form v-model="valid" ref="form">
                                    <v-text-field label="Nama" v-model="nama" :rules="namaRules" required></v-text-field>
                                    <v-text-field label="E-mail" v-model="email" :rules="emailRules" required></v-text-field>
                                    <v-text-field label="Password" v-model="password" type="password" min="8" :rules="passwordRules" required></v-text-field>
                                    <v-text-field label="No HP" v-model="noHP" required></v-text-field>
                                    <v-text-field label="Alamat" v-model="address" required></v-text-field>
                                    <v-layout align-start>
                                        <v-btn @click="login" class="mr-5 grey darken-3 white--text">Login</v-btn>
                                        <v-layout justify-end>
                                            <v-btn class="mr-2" @click="submit" :class=" {'grey darken-1 white--text' : valid, disabled: !valid }">Register</v-btn>
                                            <v-btn @click="clear" class="grey darken-3 white--text">Clear</v-btn>
                                        </v-layout>
                                    </v-layout>
                                </v-form>
                            </div>
                        </v-card-text>
                    </v-card>
                    <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar>
                </v-flex>
            </v-layout>
        </v-container>
    </main>
</template>

<style>
    @import url("https://fonts.googleapis.com/css?family=Jolly%20Lodger");

    .posisinya{
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
    }
</style>

<script>
    export default {
        name: "Register",
        data() {
            return {
                load: false,
                snackbar: false,
                error_message: '',
                color: '',
                valid: false,
                nama: '',
                namaRules: [
                    (v) => !!v || 'Nama tidak boleh kosong :(',
                ],
                password: '',
                passwordRules: [
                    (v) => !!v || 'Password tidak boleh kosong :(',
                ],
                email: '',
                emailRules: [
                    (v) => !!v || 'E-mail tidak boleh kosong :(',
                ],
                noHP: '',
                address: '',
            };
        },
        methods: {
            submit(){
                if(this.$refs.form.validate()) {
                    //cek validasi data yg terkirim
                    this.load = true;
                    this.$http.post(this.$api + '/register', {
                        fullname: this.nama,
                        email: this.email,
                        password: this.password,
                        phonenumber: this.noHP,
                        address: this.address
                    }).then(response => {
                        //simpan data id user yg diinput
                        localStorage.setItem('id', response.data.user.id);
                        localStorage.setItem('token', response.data.access_token);
                        this.error_message = response.data.message;
                        this.color = "green";
                        this.snackbar = true;
                        this.load = false;
                        this.clear();
                        this.$router.push({
                            name: 'Login',
                        });
                    }).catch(error => {
                        this.error_message = error.response.data.message;
                        this.color = "red";
                        this.snackbar = true;
                        localStorage.removeItem('token');
                        this.load = false;
                    })
                }
            },

            clear() {
                this.$refs.form.reset() //clear form login
            },

            login(){
                this.$router.push({
                    name: 'Login',
                });
            }
        },
    };
</script>