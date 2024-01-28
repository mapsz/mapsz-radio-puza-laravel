<template>
<b-navbar toggleable="lg" type="light" variant="light">
  <b-navbar-brand href="/">🏠</b-navbar-brand>
  <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

  <b-collapse id="nav-collapse" is-nav>
    <!-- Left -->
    <b-navbar-nav v-if="links">
      <b-nav-item v-for='(link,i) in links' :key='i' :href="link.link" :class="current == link.link ? 'active' : ''">{{link.caption}}</b-nav-item>
    </b-navbar-nav>

    <!-- Right -->
    <b-navbar-nav class="ml-auto">
      <!-- Auth -->
      <div v-if="user" class="w-100 d-flex" style="justify-content:flex-end">
        <!-- Profile -->
        <div class="align-self-center mx-2">
          <span>{{user.name}}</span>
        </div>
        <!-- Logout -->
        <div>
          <button @click="logout()" class="btn btn-sm btn-outline-danger" type="button">Выход</button>
        </div>
      </div>
    </b-navbar-nav>

  </b-collapse>

</b-navbar>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import {navbar as navbar} from './../../router.js';
export default {
  data(){return{
    current:this.$route.path,
    adminPrefix:'/admin',
  }},
  computed:{
    ...mapGetters({user:'user/getAuth'}),
    links(){
      if (navbar == undefined) return false;

      console.log(this.user);

      let links = [];

      //Roles
      navbar.forEach(link => {
        let add = true;

        if (link.roles != undefined && Array.isArray(link.roles)){ //link got roles
          add = false;
          if(this.user.roles != undefined && Array.isArray(this.user.roles)){ //user got roles
            link.roles.forEach(linkRole => {
              this.user.roles.forEach(userRole => {
                if(linkRole == userRole.name) add = true;
              });
            });
          }
        }

        if(add) links.push(link);

      });

      return links;
    },
  },
  mounted(){
    // this.links = navbar;
  },
  methods:{
    ...mapActions({logout:'user/logout'}),
  },
}
</script>

<style scoped>
.navbar-nav{
  font-size: 9pt;
}
</style>