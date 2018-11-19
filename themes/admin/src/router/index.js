import Vue from 'vue'
import Router from 'vue-router'

//错误页面
import Error from '@/components/Error'
//管理后台容器页面
import Container from '@/components/Container'
//登录
import Login from '@/page/Login'
//首页
import Home from '@/page/Home'
//AK管理
import Ak from '@/page/Ak'
//用户管理
import User from '@/page/User'
//标签管理
import Category from '@/page/Category'
//日志管理-入口
import Journal from '@/page/Journal'
//日志管理-数据展示
import JournalList from '@/page/JournalList'
//日志管理-新增修改
import JournalUpdate from '@/page/JournalUpdate'
//微码管理
import Microcode from '@/page/Microcode'
//好文推荐
import Push from '@/page/Push'
//资源下载
import Download from '@/page/Download'


Vue.use(Router)
let newRouter = new Router({
    routes: [{
        path: '/',
        component: Container,
        redirect: '/home',
        children: [
            { path: 'home', component: Home, name: "首页" },
            { path: 'ak', component: Ak, name: "AK管理" },
            { path: 'user', component: User, name: "用户管理" },
            { path: 'category', component: Category, name: "标签管理" },
            { path: 'journal', component: Journal, redirect: '/',
                children: [
                    { path: '/', component: JournalList, name: "日志管理" },
                    { path: 'add', component: JournalUpdate, name: "新增日志" },
                    { path: 'edit/:id', component: JournalUpdate, name: "编辑日志" }
                ]
            },
            { path: 'microcode', component: Microcode, name: "微码管理" },
            { path: 'push', component: Push, name: "好文推荐" },
            { path: 'download', component: Download, name: "资源下载" }
        ]},
        {
            path: '/login',
            component: Login,
            name: "登录",
            meta: { needLogin: false }
        },
        {
            path: '*',
            component: Error,
            meta: { needLogin: false }
        }
    ]
})

export default newRouter;