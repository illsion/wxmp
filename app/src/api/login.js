import request from '../utils/request'

/**
 * 获取用户信息
 */
export function getUserInfo(token) {
  return request({
    url: '/users/info',
    method: 'post',
    data: {
      token
    }
  })
}
/**
 * 登录
 * @param username
 * @param password
 */
export function login(username, password) {
  const data = {
    username,
    password
  }
  return request({
    url: '/users/login',
    method: 'post',
    data
  })
}

/**
 * 登出
 */
export function logout() {
  return request({
    url: '/users/logout',
    method: 'post'
  })
}
