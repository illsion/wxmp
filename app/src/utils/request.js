import axios from 'axios'
import store from '../store'
import { getToken } from './cookie'
import Toast from 'muse-ui-toast'
import Message from 'muse-ui-message'

const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API,
  timeout: 5000
})

// 请求拦截器
service.interceptors.request.use(
  config => {
    if (store.getters.token) {
      // 让每个请求携带token
      config.headers['X-Token'] = getToken()
    }
    // if (store.getters.pickType && store.getters.pickInfo.id) {
    //   const pick = getPick()
    //   config.params = Object.assign({}, config.params, {
    //     pickId: pick.info.id,
    //     pickType: pick.type
    //   })
    // }
    return config
  },
  error => {
    Promise.reject(error)
  }
)

// 响应 拦截器
service.interceptors.response.use(
  // response => response
  // 自定义拦截器
  response => {
    const res = response.data
    // 无权限
    if (res.code === 403) {
      Message.alert(res.message, '提示', {
        okLabel: '确定'
      }).then(() => {
        store.dispatch('FedOut').then(() => {
          location.reload()
        })
      })
      return Promise.reject(res.message)
    }
    if (res.code === 200) {
      if (res.tipType === true) {
        Toast.success(res.message)
      }
      return res.data
    } else {
      if (res.tipType === false) {
        return Promise.reject(res.message)
      } else if (res.tipType === true) {
        Toast.error(res.message)
        return Promise.reject(res.message)
      }
      return res
    }
  },
  error => {
    Toast.error('接口调用失败')
    return Promise.reject(error)
  }
)

export default service
