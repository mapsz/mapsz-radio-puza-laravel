import jugeVuex from './juge-vuex.vuex.js'

let store = {  
  modules:{
    user: require('./modules/user.vuex').default,

  }
};

export default store;