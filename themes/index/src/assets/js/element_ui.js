// 所需Element-ui 的组件配置
import { Message, Select, Option, Loading, Pagination} from 'element-ui'
const element = {
    install: function (Vue) {
        Vue.use(Select)//下拉框
        Vue.use(Option)//下拉框选项
        Vue.use(Pagination)//分页控件
        
        //Loading
        Vue.prototype.$loading = Loading;

        //普通消息提示
        Vue.component(Message.name, Message);
        Vue.prototype.$message = Message;
    }
}
export default element;