import request from '../utils/request'

/**
 * 获取公众号列表
 * @param data
 */
export function getList(data) {
  return request({
    url: '/mps/get-list',
    method: 'post',
    data
  })
}

/**
 * 添加/编辑公众号
 * @param data
 */
export function update(data) {
  return request({
    url: '/mps/update',
    method: 'post',
    data
  })
}

/**
 * 删除公众号
 * @param data
 */
export function deleteMp(data) {
  return request({
    url: '/mps/delete',
    method: 'post',
    data
  })
}

/**
 * 验证公众号/小程序是否属于当前用户
 */
export function validateMp(data) {
  return request({
    url: '/mps/validate-mp',
    method: 'post',
    data
  })
}
