<template>
    <div class="push">
        <!-- 工具栏 -->
        <Toolbar @add="showDialog()" @del="del()"></Toolbar>
        
        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="selection" width="35"></el-table-column>
            <el-table-column prop="title" label="标题" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="article" label="概要" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="url" label="地址" width="200" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="category" label="标签类型" width="80"></el-table-column>
            <el-table-column prop="release_time" label="发布时间" width="160"></el-table-column>
            <el-table-column prop="status" label="状态" width="70">
                <template slot-scope="scope">
                    <span v-if="scope.row.status == 1" class="success">已发布</span>
                    <span v-else class="danger">待发布</span>
                </template>
            </el-table-column>
            <el-table-column label="操作" width="150">
                <template slot-scope="scope">
                    <el-button type="text" size="small" icon="el-icon-delete" @click="del(scope.row)">删除</el-button>
                    <el-button type="text" size="small" icon="el-icon-edit" @click="showDialog(scope.row)">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!-- 弹框 -->
        <el-dialog :title="dislogTitle" :visible.sync="isShowDialog" width="400px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="80px" :model="formData" :rules="rules" label-suffix="：" ref='akform'>
                <el-form-item label="Key" prop="key">
                    <el-input v-model="formData.key"></el-input>
                </el-form-item>
                <el-form-item label="Value" prop="value">
                    <el-input v-model="formData.value"></el-input>
                </el-form-item>
                <el-form-item label="名称" prop="name">
                    <el-input v-model="formData.name"></el-input>
                </el-form-item>
            </el-form>
            <el-row type="flex" justify="end">
                <el-button type="primary" size="medium" @click="submit">提 交</el-button>
            </el-row>
        </el-dialog>
    </div>
</template>

<script>
import Toolbar from '@/components/Toolbar'
import Common from '../assets/js/common';

export default {
    name: "push",
    components: {Toolbar},
    data() {
        return {
            list : [],// 数据
            selRowsData : [], //选中数据
            isShowDialog: false, //是否显示弹框
            dislogTitle: '', //弹框标题
            formData: {}, //表单数据对象
            rules: {//校验规则
                key: [{ required: true, message: '请输入key', trigger: 'blur' }],
                value: [{ required: true, message: '请输入value', trigger: 'blur' }],
                name: [{ required: true, message: '请输入名称', trigger: 'blur' }]
            }
        };
    },
    methods: {
        //选中回调
        selChange(datas){
            this.selRowsData = datas;
        },
        //显示弹框
        showDialog(rowData){
            const isEdit = Boolean(rowData);
            this.dislogTitle = isEdit ? '编辑' : '新增AK';
            //清空数据和校验结果
            this.formData = {};
            this.$refs.akform && this.$refs.akform.resetFields();
            //数据填充
            isEdit && (this.formData = Object.assign({}, rowData));
            //弹框显示
            this.isShowDialog = true;
        },
        //弹框内提交
        submit(){
            this.$refs.akform.validate((isSuccess, column) => {
                isSuccess && (this.formData.id ? this.update() : this.add());
            });
        },
        //新增
        add(){
            alert("新增操作");
            //关闭弹框
            this.isShowDialog = false;
        },
        //修改
        update(){
            alert("修改操作")
            //关闭弹框
            this.isShowDialog = false;
        },
        //删除
        del(rowData){
            rowData = rowData ? [rowData] : this.selRowsData;
            if(!rowData.length){
                return Common.message("请选择要删除的数据");
            }
            Common.confirm('此操作将删除选中数据, 是否继续?', ()=>{
                //删除操作
                alert("删除操作")
            });
        },
    },
    mounted() {
        this.list = [
                {id: 1, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1},
                {id: 2, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1},
                {id: 3, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1},
                {id: 4, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 2},
                {id: 5, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1},
                {id: 6, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1},
                {id: 7, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 2},
                {id: 8, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1},
                {id: 9, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1},
                {id: 10, title: "我如何零基础转行成为一个自信的前端", article: "我从 2017 年年初开始高强度学习，去年十一假期之后开始我的第一份前端工作，到今天刚好工作一年时间。接下来我将我的学习路径，学习方法，和学习资源整理分享出来，希望可以帮到更多人。", url: "https://juejin.im/post/5bb9aed1e51d451a3f4c3923", category:"综合", release_time: "2018-10-17 20:37:54", status: 1}
        ];
    },
};
</script>