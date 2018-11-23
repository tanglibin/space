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
    template: '<App/>'
})
