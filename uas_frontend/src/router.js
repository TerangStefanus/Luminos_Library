import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);
function importComponent(path){
    return()=> import(`./components/${path}.vue`);
}

const router = new VueRouter({
    mode: 'history',
    routes:[
        {
            path: '/main',
            component: importComponent("DashboardLayout"),
            children: [
                //Dashboard
                {
                    path:'/dashboard',
                    name: 'Dashboard',
                    meta:{title: 'Dashboard'},
                    component: importComponent('Dashboard'),
                },
                {
                    path: '/book',
                    name: 'Book',
                    meta: { title: 'Book' },
                    component: importComponent('DataMaster/Book'),
                },

                {
                    path: '/peminjaman',
                    name: 'Peminjaman',
                    meta: { title: 'Peminjaman' },
                    component: importComponent('DataMaster/Peminjaman'),
                },
                {
                    path: '/pengembalian',
                    name: 'Pengembalian',
                    meta: { title: 'Pengambilan' },
                    component: importComponent('DataMaster/Pengembalian'),
                },
                {
                    path: '/user',
                    name: 'User',
                    meta:{title: 'User'},
                    component: importComponent('User'),
                },
                
                {
                    path: '/profile',
                    name: 'Profile',
                    meta:{title: 'Profile'},
                    component: importComponent('Profile'),
                },
            ],
        },
        //LOgin
        {
            path: '/login',
            name: 'Login',
            meta: { title: 'Login' },
            component: importComponent ('Login'),
        },
        {
            path: '/register',
            name: 'Register',
            meta: { title: 'Register' },
            component: importComponent ('Register'),
        },
        {
            path: '/',
            component: importComponent('HomeNavbar'),
            children:[
                {
                    //Home
                    path: '/',
                    name: 'Home',
                    meta: {title: 'Home'},
                    component: importComponent('Home'),
                },
            ]
        },
        {
            path:'*',
            redirect: '/'
        },
    ],
});

//set Judul
router.beforeEach( (to, from, next) => {
    document.title = to.meta.title;
    next();
});
export default router;