<template>
    <v-main class="list">
        <h3 class="text-h3" font-weight-medium mb-5> Pengembalian </h3>
        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details>
                </v-text-field>
                <v-spacer></v-spacer>
                <v-btn color="success" dark @click="dialog =true"> Tambah </v-btn>
            </v-card-title>
             <v-data-table :headers="headers" :items="pengembalians" :search="search">
                <template v-slot:[`item.actions`]="{ item }">
                      <v-btn small class="mr-2" @click="editHandler(item)">edit</v-btn>
                     <v-btn small class="red darken-1 white--text" @click="deleteHandler(item.id)">Delete</v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                        <span class="headline">{{ formTitle }} Pengembalian</span>
                </v-card-title>
                <v-card-text>
                        <v-container>
                            <v-text-field v-model="form.idUser" label="ID User" required></v-text-field>
                            <v-text-field v-model="form.idBuku" label="ID Buku" required></v-text-field>
                        </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="setForm">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
            <v-dialog v-model="dialogConfirm" persistent max-width="400px">
            <v-card>
                <v-card-title>
                        <span class="headline">Warning!</span>
                </v-card-title>
                    <v-card-text>Anda Yakin ingin menghapus pengembalian ini?</v-card-text>
                <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="dialogConfirm = false">Cancel</v-btn>
                        <v-btn color="blue darken-1" text @click="deleteData">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar>
    </v-main>
</template>

<script>
    export default {
        name: "List",
        data() {
            return {
                inputType: 'Tambah',
                load: false,
                snackbar: false,
                error_message:'',
                color: '',
                search: null,
                dialog: false,
                dialogConfirm: false,
                headers: [
                     { text: "ID User", align: "start", sortable: true, value: "idUser"},
                      { text: "email", value: 'email' },
                      { text: "ID Buku", value:'idBuku'},
                     { text: "Judul", value:'judul'},
                     { text: "Tanggal Pengembalian", value:'created_at'},
                      { text: "Actions", value:'actions'},
                ],
                pengembalian: new FormData,
                pengembalians: [],
                form: {
                    idUser: null,
                    idBuku: null,
                },
                deleteId:'',
                editId:''
            };
        },
        methods: {
            setForm(){
                if(this.inputType !== 'Tambah'){
                    this.update();
                }else{
                    this.save();
                }
            },
            // Read Data Courses
            readData() {
                var url = this.$api + '/pengembalian';
                this.$http.get(url, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    this.pengembalians = response.data.data;
                })
            },
            // simpan data Pengembalians
            save() {
                this.pengembalian.append ('idUser', this.form.idUser);
                this.pengembalian.append ('idBuku', this.form.idBuku);
                this.pengembalian.append ('judul', '');
                this.pengembalian.append ('email', '');
                var url = this.$api + '/pengembalian'
                this.load = true;
                this.$http.post(url, this.pengembalian, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token'),
                    }
            }).then(response => {
                this.error_message=response.data.message;
                this.color = "green";
                         
                this.snackbar =true;
                this.load = true; 
                this.close();
                this.readData(); // baca data
                this.resetForm();
            }).catch(error => {
                this.error_message=error.response.data.message;
                this.color = "red";
                this.snackbar = true;
                this.load = false;
                        
                });
            },
            // ubah data Pengembalians
            update() {
                let newData = {
                    idUser : this.form.idUser,
                    email : '',
                    idBuku :this.form.idBuku,
                    judul : ''
                };
                var url = this.$api + '/pengembalian/' + this.editId;
                this.load = true;
                this.$http.put(url, newData, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    this.error_message = response.data.message;
                    this.color = 'green';
                    this.snackbar =true;
                    this.load = false;     
                    this.close();
                    this.readData(); // baca data
                    this.resetForm();
                    this.inputType = 'Tambah';
                }).catch(error => {
                    this.error_message =error.response.data.message;
                    this.color = 'red';
                    this.snackbar = true;
                    this.load = false;
                });
            },
            // Hapus data produk
            deleteData() {
                
                var url =this.$api + '/pengembalian/' + this.deleteId;
                this.load = true;
                this.$http.delete(url, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    this.error_message=response.data.message;
                    this.color = "green";          
                    this.snackbar= true;
                    this.load = false;
                    this.close();
                    this.readData();
                    this.resetForm();
                    this.inputType = "Tambah";
                }).catch(error => {
                    this.error_message= error.response.data.message;
                    this.color = "red";
                    this.snackbar = true;
                    this.load = false;
                });
            },
            editHandler(item) {
                this.inputType = 'Ubah';
                this.editId = item.id;           
                this.form.idUser = item.idUser;
                this.form.idBuku = item.idBuku;
                this.dialog= true;
            },
            deleteHandler(id) {
                this.deleteId = id;
                this.dialogConfirm = true;
            },
            close() {
                this.dialog = false;
                this.inputType = 'Tambah';
                this.dialogConfirm = false;
                this.readData();
            },
            cancel () {
                this.resetForm();
                this.readData();
                this.dialog = false;
                this.dialogConfirm = false;
                this.inputType ='Tambah';
            },
            resetForm() {    
                this.form = {
                    idUser: null,
                    idBuku: null
                };
            },
        },
        computed: {
            formTitle() {
                return this.inputType;
            },
        },
        mounted () {
            this.readData();
        },
    };
</script>