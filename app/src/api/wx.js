import request from '../utils/request'

/**
 * 获取公众号回复规则
 * @param data
 */
export function getRuleList(data) {
  return request({
    url: '/mp-rules/get-list',
    method: 'post',
    data
  })
}

/**
 * 更新/添加公众号回复规则
 * @param data
 */
export function updateRule(data) {
  return request({
    url: '/mp-rules/update',
    method: 'post',
    data
  })
}

/**
 * 删除公众号回复规则
 * @param data
 */
export function deleteRule(data) {
  return request({
    url: '/mp-rules/delete',
    method: 'post',
    data
  })
}

/**
 * 获取公众号事件列表
 */
export function getEventList() {
  return request({
    url: '/mp-events/get-list',
    method: 'post'
  })
}

/**
 * 更新/添加事件
 * @param data
 */
export function updateEvent(data) {
  return request({
    url: '/mp-events/update',
    method: 'post',
    data
  })
}
