import request from '../utils/request'

// 获取服务器信息
export function getServerInfo() {
  return request({
    url: '/common/get-server-info',
    method: 'get'
  })
}
