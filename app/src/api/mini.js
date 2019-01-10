import request from '../utils/request'

/**
 * 获取小程序列表
 * @param data
 */
export function getList(data) {
  return request({
    url: '/miniapps/get-list',
    method: 'post',
    data
  })
}

/**
 * 更新
 * @param data
 */
export function update(data) {
  return request({
    url: '/miniapps/update',
    method: 'post',
    data
  })
}

/**
 * 删除
 * @param data
 */
export function deleteMini(data) {
  return request({
    url: '/miniapps/delete',
    method: 'post',
    data
  })
}

export function getInfo(data) {
  return request({
    url: '/miniapps/get-stats',
    method: 'post',
    data
  })
}
