<template>
    <div class="tag">
        <!-- 工具栏 -->
        <Toolbar @add="showDialog()" @del="del()"></Toolbar>
        
        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="selection" width="55" :selectable="checkSelectable"></el-table-column>
            <el-table-column prop="category_name" label="名称"></el-table-column>
            <el-table-column prop="jourbal_num" label="日志条数"></el-table-column>
            <el-table-column prop="push_num" label="好文推荐条数"></el-table-column>
            <el-table-column prop="micro_num" label="微码条数"></el-table-column>
            <el-table-column prop="last_update_time" label="最后修改时间" width="180"></el-table-column>
            <el-table-column label="操作" width="150">
                <template slot-scope="scope">
                    <el-button type="text" size="small" icon="el-icon-edit" @click="showDialog(scope.row)">编辑</el-button>
                    <el-button type="text" size="small" icon="el-icon-delete" @click="del(scope.row)" v-if="checkSelectable(scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!-- 弹框 -->
        <el-dialog :title="dislogTitle" :visible.sync="isShowDialog" width="400px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="80px" :model="formData" :rules="rules" label-suffix="：" ref='cateform'>
                <el-form-item label="名称" prop="category_name">
                    <el-input v-model="formData.category_name" placeholder="请输入名称"></el-input>
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
    name: "tag",
    components: {Toolbar},
    data() {
        return {
            list : [],// 数据
            selRowsData : [], //选中数据
            isShowDialog: false, //是否显示弹框
            dislogTitle: '', //弹框标题
            formData: {}, //表单数据对象
            curRowData: null, //缓存修改数据对象, 用于修改后直接页面更新
            rules: Common.getRequiredRuls({//校验规则
                category_name: '请输入标签名称'
            })
        };
    },
    methods: {
        //判断表格复选框是否可选
        checkSelectable : row => row.jourbal_num + row.push_num + row.micro_num == 0,

        //选中回调
        selChange(datas){
            this.selRowsData = datas;
        },
        //显示弹框
        showDialog(rowData){
            const isEdit = Boolean(rowData);
            this.dislogTitle = isEdit ? '编辑标签' : '新增标签';
            //清空数据和校验结果
            this.formData = {};
            this.$refs.cateform && this.$refs.cateform.resetFields();
            //数据填充
            isEdit && (this.formData = Object.assign({}, rowData), this.curRowData = rowData);
            //弹框显示
            this.isShowDialog = true;
        },
        //弹框内提交
        submit(){
            this.$refs.cateform.validate((isSuccess, column) => {
                isSuccess && (this.formData.id ? this.update() : this.add());
            });
        },
        //新增
        add(){
            Common.sendRequest({
                url: 'createCategory.do',
                type: 'POST',
                data: this.formData,
                success: (result) => {
                    this.list.unshift(result);
                    //关闭弹框
                    this.isShowDialog = false;
                }
            });
        },
        //修改
        update(){
            let formData = this.formData;
            Common.sendRequest({
                url: 'updateCategory.do',
                type: 'POST',
                data: {
                    id: formData.id,
                    category_name: formData.category_name
                },
                success: (result) => {
                    Object.assign(this.curRowData, formData, {last_update_time: result});
                    //关闭弹框
                    this.isShowDialog = false;
                }
            });
        },
        //删除
        del(rowData){
            rowData = rowData ? [rowData] : this.selRowsData;
            if(!rowData.length){
                return Common.message("请选择要删除的数据");
            }
            Common.confirm('此操作将删除选中数据, 是否继续?', ()=>{
                let ids = rowData.map(item=>item.id);
                Common.sendRequest({
                    url: 'delCategory.do',
                    type: 'POST',
                    data: {id: ids},
                    success: (result) => {
                        this.list = this.list.filter(item => !ids.includes(item.id));
                    }
                });
            });
        },
    },
    mounted() {
        //请求分类标签数据
        Common.getCategoryList(false).then((list)=>{
            this.list = list;
        });
    },
};
</script>