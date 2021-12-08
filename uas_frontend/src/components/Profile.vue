<template>
    <v-main class="list">
        <h3 class="text-h3" font-weight-medium mb-5> User Page </h3>
        <v-card>
            <v-card-title>
                <span class="headline">User</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-text-field v-model="form.fullname" label="Name" required></v-text-field>
                    <v-text-field v-model="form.email" label="Email" required></v-text-field>
                    <v-text-field v-model="form.password" type="password" min="8" label="Password" required></v-text-field>
                    <v-text-field v-model="form.phonenumber" label="Phone Number" required></v-text-field>
                    <v-text-field v-model="form.address" label="Address" required></v-text-field>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-space></v-space>
                <v-btn color="blue darken-1" text @click="dialogConfirm = true"> delete </v-btn>
                <v-btn color="blue darken-1" text @click="update">Save</v-btn>
            </v-card-actions>
        </v-card>
        <v-dialog v-model="dialogConfirm" persistent max-width="400px">
            <v-card>
                <v-card-title>
                    <span class="headline">Warning!</span>
                </v-card-title>
                <v-card-text>Anda Yakin ingin menghapus user ini?</v-card-text>
                <v-card-action>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialogConfirm = false">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="deleteData">Delete</v-btn>
                </v-card-action>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom> {{ error_message }}</v-snackbar>
    </v-main>
</template>

<script>
    export default {
        name: "List",
        data() {
            return {
                load: false,
                snackbar: false,
                error_message: '',
                color: '',
                dialogConfirm: false,
                user: new FormData,
                users: [],
                form: {
                    fullname: null,
                    email: null,
                    password: null,
                    phonenumber: null,
                    address: null,
                }
            };
        },
        methods: {
            // Get Data Users
            readData() {
                var url = this.$api + '/user/' + localStorage.getItem('id');
                this.$http.get(url, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    this.form = response.data.data;
                    this.form.password = '';
                })
            },
            // ubah data User
            update() {
                let newData = {
                    fullname : this.form.fullname,
                    email : this.form.email,
                    password : this.form.password,
                    phonenumber : this.form.phonenumber,
                    address : this.form.address
                };
                var url = this.$api + '/user/' + localStorage.getItem('id');
                this.load = true;
                this.$http.put(url, newData, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    this.error_message = response.data.message;
                    this.color = "green";
                    this.snackbar = true;
                    this.load = false;
                    this.readData(); // baca data
                }).catch(error => {
                    this.error_message = error.response.data.message;
                    this.color = 'red';
                    this.snackbar = true;
                    this.load = false;
                });
            },
            // Hapus data user
            deleteData() {
                // menghapus data
                var url = this.$api + '/user/' + localStorage.getItem('id');
                this.load = true;
                this.$http.delete(url, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    localStorage.removeItem('id');
                    localStorage.removeItem('token');
                    this.error_message = response.data.message;
                    this.color = "green";
                    this.snackbar = true;
                    this.load = false;
                    this.dialogConfirm = false;
                    this.resetForm;
                    this.$router.push({
                        name: 'Login',
                    });
                }).catch(error => {
                    this.error_message = error.response.data.message;
                    this.color = "red";
                    this.snackbar = true;
                    this.load = false;
                });
            },
            resetForm() {
                this.form = {
                    fullname : null,
                    email : null,
                    password : null,
                    phonenumber : null,
                    address : null
                };
            },
        },
        mounted() {
            this.readData();
        },
    };
</script>