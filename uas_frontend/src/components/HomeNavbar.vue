<template>

<v-app>
    <v-toolbar
    class="mainHeader"
    max-height="50px"
    flat
    >
      <v-toolbar-title>
          <v-img :src="require('@/assets/luminos.png')" width="53px" height="53px"/>
      </v-toolbar-title>

      <v-toolbar-items>  
        <v-btn text href="/">
            Home
        </v-btn>
        <v-btn text href="/login">
            Login
        </v-btn>
        <v-btn text href="/register">
            Register
        </v-btn>
      </v-toolbar-items>

      <v-spacer></v-spacer>

      <v-text-field 
        hide-details 
        label="Search" 
        placeholder="Search" 
        filled 
        rounded 
        dense 
        single-line 
        prepend-inner-icon="mdi-magnify" class="shrink">
      </v-text-field>

    </v-toolbar>
    <router-view/>
    <v-footer
      :padless="true"
      absolute
    >
      <v-card
        flat
        tile
        width="100%"
        class="secondary white--text text-center"
      >
        <v-card-text class="white--text">
          {{ new Date().getFullYear() }} — <strong>Luminos Library</strong>
        </v-card-text>
      </v-card>
    
    </v-footer>
</v-app>    
</template>

<script>
export default {
  name: "HomeNavbar",
  data(){
    return{

    }
  },
  methods:{
    logout(){
      localStorage.removeItem('token');
      this.$router.replace('/login');
    }
  }
}
</script>