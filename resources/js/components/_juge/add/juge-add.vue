<template>
<div v-if="inputs !== false" >

  <!-- Show button -->
  <button v-if="!addShow" @click="addShow=true" class="btn btn-success">{{buttonLabel ? buttonLabel : 'Добавить'}}</button>

  <!-- Add container -->
  <div v-if="addShow">
    <div class="add">
      <div>
        <h3 class="d-inline-block">Добавить</h3>
        <button class="btn btn-danger"  @click="addShow=false" style="float: right;">X</button>
      </div>
      <juge-form v-if="outInputs" v-model="data" :inputs="outInputs" :errors="errors" :refresh="refresh" @submit="put" />
    </div>
  </div>
  
      
</div>
</template>

<script>
export default {  
model: {
  event: 'blur'
},
props: ['model','buttonLabel','small','inputs'],
data(){return{
  data:[],
  addShow:0,
  refresh:0,
}},
computed:{
  rawInputs(){return this.$store.getters[this.model+'/getInputs'];},
  errors(){return this.$store.getters[this.model+'/getErrors'];},
  isInputsFetched(){return this.$store.getters[this.model+'/isInputsFetched'];},
  outInputs(){
    //Get inputs
    if(this.inputs != undefined){
      return this.inputs;
    }else{      
      return this.rawInputs;
    }
  }
},
watch:{  
  data: function () {this.$emit('blur', this.data)}
},
async mounted() {
  if(!this.isInputsFetched) this.$store.dispatch(this.model + '/fetchInputs');
},
methods:{
  async put(data){
    let put = await this.$store.dispatch(this.model + '/doPut',data);
    if(!put) return false;

    //Close add
    this.refresh++;
    this.addShow = false;
    
    //Emit
    this.$emit('add');
  }
},
}
</script>

<style scoped>
  .add {
    background-color: #e4f9e4;
    padding: 20px;
    border: 1px solid green;
    border-radius: 7px;
    margin: 10px 0px;
  }
</style>