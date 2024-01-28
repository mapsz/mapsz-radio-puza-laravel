<template>
  <juge-layout>

    <div class="container-fluid">
      <div class="row mt-3">

        <div class="col-12 col-md-6">
          <h1>Users</h1>
        </div>

        <div class="col-12 col-md-6 d-flex justify-content-end">
          <juge-add :model="'user'" />
        </div>       

      </div>

      <juge-list :data="'user'" :edit="true" :delete="true" :pages="true" />
    </div>
  </juge-layout>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
export default {

}
</script>

<style>

</style>