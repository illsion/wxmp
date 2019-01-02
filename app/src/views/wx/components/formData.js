export const formData = {
  id: null,
  keywords: null,
  type: 'text',
  status: 1,
  mp_message: {
    id: null,
    title: null,
    description: null,
    content: null,
    url: null,
    media_url: null,
    full_media_url: null
  }
}

export const fieldsForm = {
  description: false,
  content: false,
  url: false,
  media_url: false
}

export const menuType = {
  click: '点击事件',
  view: '跳转网页',
  miniprogram: '小程序'
}

export const menuContent = {
  key: '关键字',
  url: '链接'
}

export const menuFieldsForm = {
  click: {
    key: true
  },
  view: {
    url: true
  },
  miniprogram: {
    url: true,
    appid: true,
    pagepath: true
  }
}

export const broadcastType = {
  1: '文本',
  2: '单图文'
}
