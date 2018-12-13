/**
 * cookie 处理模块
 */
import Cookie from 'js-cookie'

const TokenKey = 'pm-Token'
const PickKey = 'pickInfo'

export function getToken() {
  return Cookie.get(TokenKey)
}

export function setToken(token) {
  return Cookie.set(TokenKey, token)
}

export function removeToken() {
  return Cookie.remove(TokenKey)
}

export function setPick(value) {
  return Cookie.set(PickKey, value)
}

export function getPick() {
  return Cookie.getJSON(PickKey)
}

export function removePick() {
  return Cookie.remove(PickKey)
}
