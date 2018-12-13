import Vue from 'vue'
import Vuex from 'vuex'
import getters from './getters'
import user from './modules/user'
import auth from './modules/auth'
import pick from './modules/pick'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    user,
    auth,
    pick
  },
  getters
})

export default store
