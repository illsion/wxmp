const getters = {
  token: state => state.user.token,
  userInfo: state => state.user.userInfo,
  routers: state => state.auth.routers,
  pickInfo: state => state.pick.info,
  pickType: state => state.pick.type,
  pickStatus: state => state.pick.status,
  serveInfo: state => state.common.serveInfo
}

export default getters
