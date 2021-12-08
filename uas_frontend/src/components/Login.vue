<template>
    <main>
        <v-container fluid fill-height class="posisinya">
            <v-layout flex align-center justify-center>
                  <v-flex xs12 sm6 elevation-6>
                     <v-toolbar class="grey darken-3">
                         <v-toolbar-title class="grey--text">
                             <h1>Login to Luminos Library</h1>
                         </v-toolbar-title>
                    </v-toolbar>
                    <v-card>
                         <v-card-text class="pt-4">
                            <div>
                                 <v-form v-model="valid" ref="form">
                                     <v-text-field label="E-mail" v-model="email" :rules="emailRules" required></v-text-field>
                                     <v-text-field label="Password" v-model="password" type="password" min="8" :rules="passwordRules" counter required></v-text-field>
                                     <v-layout align-start>
                                        <v-btn @click="register" class="mr-5 grey darken-3 white--text">Register</v-btn>
                                        <v-layout justify-end>
                                            <v-btn class="mr-2" @click="submit" :class=" {'grey darken-1 white--text' : valid, disabled: !valid }">Login</v-btn>
                                            <v-btn @click="clear" class="grey darken-3 white--text">Clear</v-btn>
                                        </v-layout>
                                    </v-layout>
                                 </v-form>
                             </div>
                         </v-card-text>
                    </v-card>
                    <v-dialog v-model="dialog" persistent max-width="600px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Verify Your Account</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-text-field v-model="form.verifyToken" label="Token" required></v-text-field>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="cancel">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="verify">Verify</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                     <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar>
                </v-flex>
            </v-layout>
        </v-container>
    </main>
</template>
<style>
    .posisinya{
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
    }
</style>

<script>
    export default {
        name: "Login",
        data() {
            return {
                load: false,
                snackbar: false,
                error_message:'',
                dialog: false,
                form: {
                    verifyToken: null,
                },
                color: '',
                valid: false,
                password:'',
                passwordRules: [
                     (v) => !!v || 'Password tidak boleh kosong :(',
                ],
                email: '',
                emailRules: [
                     (v) => !!v || 'E-mail tidak boleh kosong :(',
                ]
            };
        },
        methods: {
            submit() {
                if(this.$refs.form.validate()) {
                    //cek validasi data yang terkirim
                    this.load = true;
                    this.$http.post(this.$api + '/login', {
                        email: this.email,
                        password: this.password
                    }).then(response => {
                        // simpan data id user yang diinput
                        localStorage.setItem('id', response.data.user.id);
                        localStorage.setItem('token', response.data.access_token);
                        this.error_message = response.data.message;
                        this.color = "green";
                        this.snackbar = true;
                        this.load = false;
                        this.clear();
                        if(response.data.user.token==1){
                            this.$router.push({
                                name: 'Book',
                            });
                        }else{
                            this.dialog = true;
                        }
                        
                    }).catch(error => {
                        this.error_message = error.response.data.message;
                        this.color = "red";
                        this.snackbar = true;
                        localStorage.removeItem('token');
                        this.load = false;
                    })
                }
            },
            clear(){
                this.$refs.form.reset()
            },
            register(){
                this.$router.push({
                    name: 'Register',
                });
            },
            verify() {
                let newData = {
                    token : this.form.verifyToken,
                };
                var url = this.$api + '/verify';
                this.load = true;
                this.$http.post(url, newData, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    this.error_message = response.data.message;
                    this.color = 'green';
                    this.snackbar =true;
                    this.load = false;     
                    this.close();
                    this.resetForm();
                }).catch(error => {
                    this.error_message =error.response.data.message;
                    this.color = 'red';
                    this.snackbar = true;
                    this.load = false;
                });
            },
            close() {
                this.dialog = false;
            },
            resetForm() {
            this.form = {
                verifyToken: null,
            };
        },
        },
    };
</script>