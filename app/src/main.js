import Vue from 'vue'
import App from './App.vue'
import store from './store'

import MuseUI from 'muse-ui'
import Toast from 'muse-ui-toast'
import Loading from 'muse-ui-loading'
import Message from 'muse-ui-message'
import VueClipboard from 'vue-clipboard2'

import 'typeface-roboto'
import 'muse-ui/dist/muse-ui.css'
import 'muse-ui-loading/dist/muse-ui-loading.css'
import '@/styles/common.less'
import 'muse-ui-message/dist/muse-ui-message.css'

import router from './router'
import './auth'

Vue.config.productionTip = false

Vue.use(MuseUI)
Vue.use(Loading, {
  size: 24
})
Vue.use(Toast, {
  position: 'top'
})
Vue.use(Message)

// VueClipboard.config.autoSetContainer = true
Vue.use(VueClipboard)

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
