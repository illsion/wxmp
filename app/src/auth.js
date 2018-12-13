// 路由权限
import 'muse-ui-progress/dist/muse-ui-progress.css'
import NProgress from 'muse-ui-progress'
import store from './store'
import router from './router'
import { constantRouterMap } from './router'
import { getToken } from './utils/cookie'

NProgress.config({
  zIndex: 2000, // progress z-index
  top: 0, // position fixed top
  speed: 300, // progress speed
  color: 'red', // color
  size: 2 // progress size
})

// 白名单
const whiteList = ['/login']

router.beforeEach((to, from, next) => {
  NProgress.start()
  if (getToken()) {
    if (to.path === '/login') {
      next({ path: '/' })
      NProgress.done()
    } else {
      if (store.getters.pickStatus === false) {
        store.dispatch('getPick').catch(() => {
          store.dispatch('removePick')
        })
      }
      if (store.getters.userInfo === null) { // 判断是否拉取用户信息
        store.dispatch('GetUserInfo').then(response => {
          if (store.getters.routers.length === 0) {
            store.commit('SET_ROUTERS', constantRouterMap)
            router.addRoutes(store.getters.routers) // 添加路由
            next({ ...to, replace: true })
          } else {
            next()
          }
        }).catch(() => {
          store.dispatch('FedOut').then(() => {
            next({ path: '/' })
          })
        })
      } else {
        if (store.getters.routers.length === 0) {
          store.commit('SET_ROUTERS', constantRouterMap)
          router.addRoutes(store.getters.routers) // 添加路由
          next({ ...to, replace: true })
        } else {
          next()
        }
      }
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1) { // 白名单内，直接进入
      next()
    } else {
      next('/login')
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  NProgress.done()
})
