import store from '@/store'
import router from '@/router'

// 检查是否有选择微信公众号信息
export default function() {
  const info = store.getters.pickInfo

  if (Object.keys(info).length === 0) {
    router.push({ path: '/config/wx' })
  }
}
