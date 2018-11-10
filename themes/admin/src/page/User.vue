<template>
    <div class="user">
        <!-- 工具栏 -->
        <Toolbar @add="showDialog()" @del="del()"></Toolbar>

        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="selection" width="55" :selectable="checkSelectable"></el-table-column>
            <el-table-column prop="username" label="用户名" min-width="120"></el-table-column>
            <el-table-column prop="user_type" label="类型" min-width="100">
                <template slot-scope="scope">{{scope.row.user_type == 1 ? '管理用户' : '普通用户'}}</template>
            </el-table-column>
            <el-table-column prop="status" label="状态" min-width="100">
                <template slot-scope="scope">
                    <span v-if="scope.row.status == 1" class="success">正常</span>
                    <span v-else class="danger">禁用</span>
                </template>
            </el-table-column>
            <el-table-column prop="access_times" label="访问次数" min-width="80"></el-table-column>
            <el-table-column prop="last_login_time" label="最后登录时间" width="180"></el-table-column>
            <el-table-column prop="create_time" label="创建时间" width="180"></el-table-column>
            <el-table-column label="操作" width="220">
                <template slot-scope="scope">
                    <el-button type="text" size="small" icon="el-icon-edit" @click="showDialog(scope.row)">修改密码</el-button>
                    <template v-if="checkSelectable(scope.row)">
                        <el-button type="text" size="small" @click="blockToggle(scope.row)" v-if="scope.row.status == 1" icon="el-icon-goods">禁用</el-button>
                        <el-button type="text" size="small" @click="blockToggle(scope.row)" v-else icon="el-icon-sold-out">解禁</el-button>
                        <el-button type="text" size="small" icon="el-icon-delete" @click="del(scope.row)">删除</el-button>
                    </template>
                </template>
            </el-table-column>
        </el-table>

        <!-- 新增弹框 -->
        <el-dialog title="新增用户" :visible.sync="isShowAddDialog" width="400px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="100px" :model="addFormData" :rules="rules" label-suffix="：" ref='addform'>
                <el-form-item label="用户名" prop="username">
                    <el-input v-model="addFormData.username" placeholder="请输入用户名"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input v-model="addFormData.password" type="password" placeholder="请输入密码"></el-input>
                </el-form-item>
            </el-form>
            <el-row type="flex" justify="end">
                <el-button type="primary" size="medium" @click="add">提 交</el-button>
            </el-row>
        </el-dialog>

        <!-- 修改密码弹框 -->
        <el-dialog title="修改密码" :visible.sync="isShowEditDialog" width="400px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="100px" :model="editFormData" :rules="rules" label-suffix="：" ref='editform'>
                <el-form-item label="新密码" prop="password">
                    <el-input v-model="editFormData.password" type="password" placeholder="请输入新密码"></el-input>
                </el-form-item>
            </el-form>
            <el-row type="flex" justify="end">
                <el-button type="primary" size="medium" @click="update">提 交</el-button>
            </el-row>
        </el-dialog>
    </div>
</template>

<script>
import Toolbar from '@/components/Toolbar'
import Common from '../assets/js/common';
export default {
    name: "user",
    components: {Toolbar},
    data() {
        return {
            list : [],// 数据
            selRowsData : [], //选中数据
            isShowAddDialog: false, //是否显示新增弹框
            addFormData: {}, //新增表单数据对象
            isShowEditDialog : false, //是否显示修改密码弹框
            editFormData : {}, //修改密码表单数据对象
            rules: Common.getRequiredRuls({//校验规则
                username: '请输入用户名',
                password: '请输入密码'
            })
        };
    },
    methods: {
        //判断表格复选框是否可选
        checkSelectable : row => row.user_type == 2,
        
        //选中回调
        selChange(datas){
            this.selRowsData = datas;
        },
        //显示弹框
        showDialog(rowData){
            const isEdit = Boolean(rowData);
            if(isEdit){
                this.editFormData = {
                    id : rowData.id
                };
                this.$refs.editform && this.$refs.editform.resetFields();
                //显示修改弹框
                this.isShowEditDialog = true;
            }else{
                //清空数据和校验结果
                this.addFormData = {user_type: "2"};
                this.$refs.addform && this.$refs.addform.resetFields();
                //显示新增弹框
                this.isShowAddDialog = true;
            }
        },
        //新增
        add(){
            this.$refs.addform.validate((isSuccess, column) => {
                if(isSuccess){
                    Common.sendRequest({
                        url: 'createUser.do',
                        type: 'POST',
                        data: this.addFormData,
                        success: (result) => {
                            this.list.unshift(result);
                            //关闭新增弹框
                            this.isShowAddDialog = false;
                        }
                    });
                }
            });
        },
        //修改
        update(){
            this.$refs.editform.validate((isSuccess, column) => {
                if(isSuccess){
                    Common.sendRequest({
                        url: 'updatePassword.do',
                        type: 'POST',
                        data: this.editFormData,
                        success: (result) => {
                            //关闭修改弹框
                            this.isShowEditDialog = false;
                        }
                    });
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
                    url: 'delUser.do',
                    type: 'POST',
                    data: {id: ids},
                    success: (result) => {
                        this.list = this.list.filter(item => !ids.includes(item.id));
                    }
                });
            });
        },
        //禁用|解禁
        blockToggle(rowData){
            let msg = rowData.status == 1 ? '禁用' : '解禁';
            let targetStatus = [,2,1][rowData.status];
            Common.confirm(`此操作将${msg}该用户, 是否继续?`, ()=>{
                //解禁或禁用操作
                Common.sendRequest({
                    url: 'updateUserStatus.do',
                    type: 'POST',
                    data: {
                        id: rowData.id,
                        status: targetStatus
                    },
                    success: (result) => {
                        rowData.status = targetStatus;
                    }
                });
            });
        }
    },
    mounted() {
        Common.sendRequest({
            url: 'getUserList.do',
            success: (result) => {
                this.list = result;
            }
        });
    },
};
</script>