import Vue from 'vue'
import axios from 'axios';

const Common = {
    /**
     * Confirm 确认框
     * @Param msg {String} 消息内容
     * @Param callback {Function} 选择'确定'按钮回调
     */
    confirm: (msg, callback) => {
        Vue.prototype.$confirm(msg, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        }).then(callback).catch(() => {});
    },

    /**
     * 消息提示
     * 
     */
    message: (msg) => Vue.prototype.$message(msg),

    /**
     * 获取必填校验规则
     * @Param obj {Object}
     *      key: model 名称
     *      value： 提示内容
     * @return {Object}  Element 要求格式的校验规则
     */
    getRequiredRuls: (obj) => {
        let response = {};
        for (let key in obj) {
            response[key] = [{ required: true, message: obj[key].msg || obj[key], trigger: obj[key].type || 'blur' }];
        }
        return response;
    },

    /**
     * 服务端请求
     * @param op {Object} 请求配置参数
     */
    sendRequest: (op) => {
        let loading;
        let axiosParam = {
            url: op.url,
            method: op.type || 'get',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            //响应之前
            transformResponse: [function(data) {
                // 对 data 进行任意转换处理
                loading.close();
                return data;
            }]
        }

        //请求参数添加
        if (op.data) {
            var params = new URLSearchParams();
            for (let key in op.data) {
                params.append(key, op.data[key]);
            }
            if (axiosParam.method == 'get') {
                axiosParam.params = params;
            } else {
                axiosParam.data = params;
            }
        }

        new Promise(function(resolve, reject) {
            //显示loading
            loading = Vue.prototype.$loading.service({ background: "rgba(0,0,0,0.6)" });
            //请求发送
            axios(axiosParam).then(result => {
                result = JSON.parse(result.data);
                result.status ? resolve(result) : reject(result);
            }).catch(reject);

        }).then(({ data, msg }) => {
            msg && Common.message(msg);
            op.success(data);

        }).catch(({ data=true, msg='网络异常，请稍后再试~！'})=>{
            Common.message(msg);
            data == -1 && document.getElementById('app').__vue__.$router.push('/login');
            op.error && op.error();
        });
    },

    /**
     * 获取分类标签数据
     * @param simple {Boolean} 是否为简单模式， 简化模式只包含分类名称及id， 非简化模式则包含最后修改时间，日志、微码、好文等条目数
     */
    getCategoryList: (simple = true) => {
        return new Promise((resolve) => {
            let url = 'getCategoryList.do';
            simple && (url += '?simple=true');
            Common.sendRequest({
                url: url,
                success: resolve
            });
        });
    },

    /**
     * 校验用户名输入是否合法
     */
    validUserName: (username) => /[a-z0-9]+/.test(username)
};

export default Common;