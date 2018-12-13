// 当前选择管理
import { getPick, removePick, setPick } from '../../utils/cookie'
import { validateMp } from '../../api/mp'
import Toast from 'muse-ui-toast'

const typeIndex = {
  mp: '公众号',
  mini: '小程序'
}
const pick = {
  state: {
    info: {},
    type: undefined,
    status: false // 判断是否已进行操作
  },
  mutations: {
    SET_PICKINFO: (state, info) => {
      state.info = info
    },
    SET_PICKTYPE: (state, type) => {
      state.type = type
    },
    SET_PICKSTATUS: (state, status) => {
      state.status = status
    }
  },
  actions: {
    setPick({ commit }, data) {
      return new Promise((resolve, reject) => {
        const { info, type } = data
        validateMp({
          id: info.id,
          type: type
        }).then(response => {
          commit('SET_PICKINFO', info)
          commit('SET_PICKTYPE', {
            key: type,
            value: typeIndex[type]
          })
          commit('SET_PICKSTATUS', true)
          if (response.status === false) {
            Toast.message('当前' + typeIndex[type] + '未接入!')
          }
          setPick({ info, type: type })
          resolve()
        }).catch(error => {
          commit('SET_PICKINFO', {})
          commit('SET_PICKTYPE', undefined)
          commit('SET_PICKSTATUS', true)
          Toast.message('验证失败，请重新尝试')
          removePick()
          reject(error)
        })
      })
    },
    getPick({ commit }) {
      return new Promise((resolve, reject) => {
        const { info, type } = getPick()
        validateMp({
          id: info.id,
          type: type
        }).then(response => {
          commit('SET_PICKINFO', info)
          commit('SET_PICKTYPE', {
            key: type,
            value: typeIndex[type]
          })
          commit('SET_PICKSTATUS', true)
          if (response.status === false) {
            Toast.message('当前' + typeIndex[type] + '未接入!')
          }
          resolve()
        }).catch(error => {
          commit('SET_PICKINFO', {})
          commit('SET_PICKTYPE', undefined)
          commit('SET_PICKSTATUS', true)
          removePick()
          reject(error)
        })
      })
    },
    removePick({ commit }) {
      return new Promise(resolve => {
        commit('SET_PICKINFO', {})
        commit('SET_PICKTYPE', undefined)
        commit('SET_PICKSTATUS', true)
        removePick()
        resolve()
      })
    }
  }
}

export default pick
