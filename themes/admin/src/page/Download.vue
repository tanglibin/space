<template>
    <div class="download">
        <!-- 工具栏 -->
        <Toolbar @add="showDialog()" @del="del()"></Toolbar>
        
        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="selection" width="55"></el-table-column>
            <el-table-column prop="name" label="名称" min-width="100"></el-table-column>
            <el-table-column prop="logo_name" label="图标" min-width="100"></el-table-column>
            <el-table-column prop="file_name" label="文件" min-width="100"></el-table-column>
            <el-table-column prop="version" label="版本" min-width="60"></el-table-column>
            <el-table-column prop="size" label="大小" min-width="60"></el-table-column>
            <el-table-column prop="url" label="外部地址" min-width="200"></el-table-column>
            <el-table-column prop="last_update_time" label="最后更新时间" width="180"></el-table-column>
            <el-table-column label="操作" width="140">
                <template slot-scope="scope">
                    <el-button type="text" size="small" icon="el-icon-delete" @click="del(scope.row)">删除</el-button>
                    <el-button type="text" size="small" icon="el-icon-edit" @click="showDialog(scope.row)">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!-- 弹框 -->
        <el-dialog :title="dislogTitle" :visible.sync="isShowDialog" width="400px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="100px" :model="formData" :rules="rules" label-suffix="：" ref='downform'>
                <el-form-item label="名称" prop="name">
                    <el-input v-model="formData.name" placeholder="请输入名称"></el-input>
                </el-form-item>
                <el-form-item label="版本" prop="version">
                    <el-input v-model="formData.version" placeholder="请输入版本"></el-input>
                </el-form-item>
                <el-form-item label="外部地址" prop="url">
                    <el-input v-model="formData.url" placeholder="请输入外部地址"></el-input>
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
    name: "download",
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
                name: '请输入名称',
                version: '请输入版本',
                url: '请输入外部地址'
            })
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
            this.dislogTitle = isEdit ? '编辑资源信息' : '新增资源';
            //清空数据和校验结果
            this.formData = {};
            this.$refs.downform && this.$refs.downform.resetFields();
            //数据填充
            isEdit && (this.formData = Object.assign({}, rowData), this.curRowData = rowData);
            //弹框显示
            this.isShowDialog = true;
        },
        //弹框内提交
        submit(){
            this.$refs.downform.validate((isSuccess, column) => {
                isSuccess && (this.formData.id ? this.update() : this.add());
            });
        },
        //新增
        add(){
            Common.sendRequest({
                url: 'createDown.do',
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
            Common.sendRequest({
                url: 'updateDown.do',
                type: 'POST',
                data: this.formData,
                success: (result) => {
                    Object.assign(this.curRowData, this.formData);
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
                    url: 'delDown.do',
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
        Common.sendRequest({
            url: 'getDownList.do',
            success: (result) => {
                this.list = result;
            }
        });
    },
};
</script>