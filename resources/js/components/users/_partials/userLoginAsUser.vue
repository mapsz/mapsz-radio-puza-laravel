<template>
  <button @click="login()" class="btn btn-sm btn-primary">Login as {{data.username}}</button>
</template>

<script>
export default {
  props: ['data'],
  methods:{
    async login(){
      let r = await ax.fetch('/login/as', {'id':this.data.id}, 'post');
      if(r){location.reload();} 
    }
  },
}
</script>

<style>

</style>