import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import testPage from './components/pages/testPage'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'

import home from './components/pages/home'
import tags from './components/admin/pages/tags'
import category from './components/admin/pages/category'

const routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/tags',
        component: tags
    },
    {
        path: '/category',
        component: category
    },

    
    {
        path: '/test-route',
        component: testPage
    },
    {
        path: '/hooks',
        component: hooks
    },
    {
        path: '/methods',
        component: methods
    },
]

export default new Router({
    mode: 'history',
    routes
})