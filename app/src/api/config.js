import request from '../utils/request'

/**
 * 用户列表
 * @param data
 */
export function getUserList(data) {
  return request({
    url: '/users/get-list',
    method: 'post',
    data
  })
}

export function updateUser(data) {
  return request({
    url: '/users/update',
    method: 'post',
    data
  })
}

