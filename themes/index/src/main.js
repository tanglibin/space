import Vue from 'vue'
import router from './router'

//按需加载Element-ui
import 'element-ui/lib/theme-chalk/index.css';
import element from '@/assets/js/element_ui'
Vue.use(element);

//加载全局样式文件
import less from './assets/less/main.less';

//加载入口组件
import App from './App'

//启动时是否生成生产提示
Vue.config.productionTip = false


/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    components: { App },
    template: '<App/>',
    methods: {
        getStyle: (bgColor = '#222', fontColor = '#fadfa3') => {
            return `color:${fontColor};background-color:${bgColor};line-height:32px;padding: 0 8px;font-size:14px;`;
        }
    },
    mounted() {
        console.log('%c联系邮箱%ctony@tlbin.com', this.getStyle(), this.getStyle('#fadfa3', '#333'));
        console.log('%c网站地址%chttps://www.tlbin.com, https://www.c3tt.com', this.getStyle(), this.getStyle('#fadfa3', '#333'));
    },
})
