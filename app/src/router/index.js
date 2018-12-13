import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Layout from '@/views/layout/Layout'

export const defaultRouterMap = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/errorPage/404'),
    hidden: true
  }
]

export const constantRouterMap = [
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    hidden: false,
    children: [
      {
        name: 'Dashboard',
        path: 'dashboard',
        meta: { title: '主页' },
        component: () => import('@/views/dashboard/index')
      }
    ]
  },
  {
    path: '/wx',
    component: Layout,
    redirect: '/wx/message',
    hidden: false,
    meta: { title: '公众号管理' },
    children: [
      {
        name: 'MpEvent',
        path: 'event',
        meta: { title: '回复规则' },
        component: () => import('@/views/wx/event')
      },
      {
        path: 'message',
        meta: { title: '自动回复' },
        component: () => import('@/views/wx/message')
      }
    ]
  },
  {
    path: '/config',
    component: Layout,
    redirect: '/config/wx',
    meta: { title: '系统管理' },
    hidden: false,
    children: [
      {
        path: 'wx',
        meta: { title: '微信管理' },
        component: () => import('@/views/config/wx')
      },
      {
        path: 'mini',
        meta: { title: '小程序管理' },
        component: () => import('@/views/config/mini')
      },
      {
        path: 'user',
        meta: { title: '用户管理' },
        component: () => import('@/views/config/user')
      }
    ]
  },
  { path: '*', redirect: '/404', hidden: true }
]

export const RouterMap = [
  ...defaultRouterMap,
  ...constantRouterMap
]

export default new Router({
  scrollBehavior: () => ({ y: 0 }),
  routes: defaultRouterMap
})
