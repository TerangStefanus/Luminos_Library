<template>

<div
    class="ma-3 pa-10"
    margin-top="20px"
    :style="bgcolor"
>
    <v-carousel hide-delimiters>
      <v-carousel-item
        :src="require('@/assets/slider1.png')"
        contain
      >
        <v-row
          class="fill-height"
          align="center"
          justify="center"
        >
        </v-row>
      </v-carousel-item>
      <v-carousel-item
        :src="require('@/assets/slider2.png')"
        contain
      >
        <v-row
          class="fill-height"
          align="center"
          justify="center"
        >
        </v-row>
      </v-carousel-item>
      <v-carousel-item
        :src="require('@/assets/slider3.jpg')"
        contain
      >
        <v-row
          class="fill-height"
          align="center"
          justify="center"
        >
        </v-row>
      </v-carousel-item>
    </v-carousel>

    <v-divider></v-divider>
</div>
</template>
<style scoped>
  .v-card--reveal {
    align-items: center;
    bottom: 0;
    justify-content: center;
    opacity: .8;
    position: absolute;
    width: 100%;
  }
</style>
<script>
export default {
  name: "Home",
    data: () => ({
        bgcolor:{
          backgroundColor:"#000000"
        }
    }),
    methods:{
        //Route::get('feed/{id}', 'Api\FeedController@show');
      readData(){
        var url = this.$api + '/feed/'+localStorage.getItem('id');
        this.$http.get(url, {
          headers:{
              'Authorization': 'Bearer ' + localStorage.getItem('token')
          }
        }).then(response => {
          this.products = response.data.data
          // this.dialog = false
        })
      },
      
    },
    mounted(){
        this.readData();
    }
}
</script>