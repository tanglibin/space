import Vue from 'vue'
import Router from 'vue-router'

//错误页面
import Error from '@/components/Error'
//管理后台容器页面
import Container from '@/components/Container'
//首页
import Index from '@/page/Index'
//前端日志
import Journal from '@/page/Journal'
//日志详情
import JournalInfo from '@/page/JournalInfo'
//好文推荐
import Recommend from '@/page/Recommend'
//我的微码
import Microcode from '@/page/Microcode'
//我的足迹
import Footprint from '@/page/Footprint'

Vue.use(Router)
export default new Router({
    mode: "history",
    routes: [{
        path: '/',
        component: Container,
        children: [
            { path: '', alias:'index', component: Index, name: "index" },
            { path: 'journal', component: Journal, name: "journal" },
            { path: 'journal/:id(\\d+)', component: JournalInfo, name: "journal_info" },
            { path: 'recommend', component: Recommend, name: "recommend" },
            { path: 'microcode', component: Microcode, name: "microcode" },
            { path: 'footprint', component: Footprint, name: "footprint" },
        ]},
        {
            path: '*',
            component: Error
        }
    ]
})
