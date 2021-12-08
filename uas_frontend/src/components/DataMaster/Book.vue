<template>
    <v-main class="list">
        <h3 class="text-h3" font-weight-medium mb-5> Book </h3>
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
             <v-data-table :headers="headers" :items="books" :search="search">
                <template v-slot:[`item.actions`]="{ item }">
                      <v-btn small class="mr-2" @click="editHandler(item)">edit</v-btn>
                     <v-btn small class="red darken-1 white--text" @click="deleteHandler(item.id)">Delete</v-btn>
                </template>
            </v-data-table>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                        <span class="headline">{{ formTitle }} Books</span>
                </v-card-title>
                <v-card-text>
                        <v-container>
                            <v-text-field v-model="form.judul" label="Judul" required></v-text-field>
                            <v-text-field v-model="form.quantity" label="Quantity" required></v-text-field>
                            <v-text-field v-model="form.halaman" label="Halaman" required></v-text-field>
                            <v-text-field v-model="form.penulis" label="Penulis" required></v-text-field>
                            <v-text-field v-model="form.category" label="Category" required></v-text-field>
                            <v-text-field v-model="form.genre" label="Genre" required></v-text-field>
                            <v-text-field v-model="form.description" label="Description" required></v-text-field>
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
                    <v-card-text>Anda Yakin ingin menghapus kelas ini?</v-card-text>
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
                     { text: "Judul", align: "start", sortable: true, value: "judul"},
                     { text: "Quantity", value:'quantity'},
                      { text: "Halaman", value: 'halaman'},
                     { text: "Penulis", value:'penulis'},
                     { text: "Category", value: 'category'},
                     { text: "Genre", value:'genre'},
                     { text: "Description", value:'description'},
                      { text: "Actions", value:'actions'},
                ],
                book: new FormData,
                books: [],
                form: {
                    judul: null,
                    quantity: null,
                    halaman: null,
                    penulis: null,
                    category: null,
                    genre: null,
                    description: null,
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
                var url = this.$api + '/book';
                this.$http.get(url, {
                    headers: {
                        'Authorization' : 'Bearer ' + localStorage.getItem('token')
                    }
                }).then(response => {
                    this.books = response.data.data;
                })
            },
            // simpan data Books
            save() {
                this.book.append ('judul', this.form.judul);
                this.book.append ('quantity', this.form.quantity);
                this.book.append ('halaman', this.form.halaman);
                this.book.append ('penulis', this.form.penulis);
                this.book.append ('category', this.form.category);
                this.book.append ('genre', this.form.genre);
                this.book.append ('description', this.form.description);
                var url = this.$api + '/book/'
                this.load = true;
                this.$http.post(url, this.book, {
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
            // ubah data Books
            update() {
                let newData = {
                    judul : this.form.judul,
                    quantity : this.form.quantity,
                    halaman : this.form.halaman,
                    penulis : this.form.penulis,
                    category : this.form.category,
                    genre : this.form.genre,
                    description : this.form.description
                };
                var url = this.$api + '/book/' + this.editId;
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
                
                var url =this.$api + '/book/' + this.deleteId;
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
                this.form.judul = item.judul;
                this.form.quantity = item.quantity;
                this.form.halaman = item.halaman;
                this.form.penulis = item.penulis;
                this.form.category = item.category;
                this.form.genre = item.genre;
                this.form.description = item.description;
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
                    judul: null,
                    quantity: null,
                    halaman: null,
                    penulis: null,
                    category: null,
                    genre: null,
                    description: null,
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