<template>
    <div class="push">
        <!-- 工具栏 -->
        <Toolbar :search="true" @add="showDialog()" @del="del()" @search="getList"></Toolbar>
        
        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="index" :index="getIndex" width="50" label=" " align="center"></el-table-column>
            <el-table-column type="selection" width="35"></el-table-column>
            <el-table-column prop="title" label="标题" min-width="150" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="article" label="概要" min-width="155" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="url" label="地址" min-width="140" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="category_name" label="标签" width="100" align="center"></el-table-column>
            <el-table-column prop="issue_time" label="发布时间" min-width="155"></el-table-column>
            <el-table-column prop="status" label="状态" width="70">
                <template slot-scope="scope">
                    <span v-if="scope.row.status == 1" class="danger">待发布</span>
                    <span v-else class="success">已发布</span>
                </template>
            </el-table-column>
            <el-table-column label="操作" width="180">
                <template slot-scope="scope">
                    <el-button type="text" size="small" @click="issueToggle(scope.row)" v-if="scope.row.status == 1" icon="el-icon-upload2">发布</el-button>
                    <el-button type="text" size="small" @click="issueToggle(scope.row)" v-else icon="el-icon-download">下线</el-button>
                    <el-button type="text" size="small" icon="el-icon-delete" @click="del(scope.row)">删除</el-button>
                    <el-button type="text" size="small" icon="el-icon-edit" @click="showDialog(scope.row)">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination v-if="lastPage>1" @current-change="pageChange" :current-page.sync="pager.page" :page-size="pager.pageSize" :total="pager.total" layout="prev, pager, next, jumper"></el-pagination>

        <!-- 弹框 -->
        <el-dialog :title="dislogTitle" :visible.sync="isShowDialog" width="600px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="100px" :model="formData" :rules="rules" label-suffix="：" ref='pushform'>
                <el-form-item label="标题" prop="title">
                    <el-input v-model="formData.title" placeholder="请输入标题"></el-input>
                </el-form-item>
                <el-form-item label="概要" prop="article">
                    <el-input type="textarea" v-model="formData.article" placeholder="请输入概要内容"></el-input>
                </el-form-item>
                <el-form-item label="地址" prop="url">
                    <el-input v-model="formData.url" placeholder="请输入地址"></el-input>
                </el-form-item>
                <el-row>
                    <el-col :span="12">
                        <el-form-item label="分类标签" prop="category_id">
                            <el-select v-model="formData.category_id" placeholder="请选择分类标签">
                                <el-option v-for="(item, index) in categoryList" :key="index" :label="item.category_name" :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="发布状态">
                            <el-select v-model="formData.status" placeholder="请选择发布状态">
                                <el-option label="待发布" :value="1"></el-option>
                                <el-option label="发布" :value="2"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
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
            searchParam: {}, //表单搜索数据
            list : [],// 数据
            selRowsData : [], //选中数据
            pager:{
                page: 1, //当前页
                total: 0, //总数
                pageSize: 10, //每页显示数量
            },
            isShowDialog: false, //是否显示弹框
            dislogTitle: '', //弹框标题
            formData: {}, //表单数据对象
            curRowData: null, //缓存修改数据对象, 用于修改后直接页面更新
            rules: Common.getRequiredRuls({//校验规则
                title: '请输入标题内容',
                article: '请输入概要内容',
                url: '请输入地址内容',
                category_id: {msg: '请选择分类标签', type: 'change'}
            }),
            categoryList: [], //标签分类
        };
    },
    computed:{
        //最大页数
        lastPage(){
            return Math.ceil(this.pager.total / this.pager.pageSize);
        }
    },
    methods: {
        //选中回调
        selChange(datas){
            this.selRowsData = datas;
        },
        //获取索引值
        getIndex(index){
            return (this.pager.page - 1) *10 + index + 1;
        },
        //获取数据
        getList(param){
            let isRest = JSON.stringify(param) == '{}';
            this.searchParam = isRest ? Object.assign({}, this.pager) : Object.assign({}, this.searchParam, param, this.pager);
            Common.sendRequest({
                type: "POST",
                url: 'getPushList.do',
                data: this.searchParam, param,
                success: (result) => {
                    this.list = result.data;
                    this.pager.total = result.total;
                }
            });
        },
        //翻页
        pageChange(page){
             this.pager.page = page;
             this.getList();
        },
        //发布|下线
        issueToggle(rowData){
            let msg = rowData.status == 1 ? '发布' : '下线';
            let targetStatus = [,2,1][rowData.status];
            Common.confirm(`此操作将${msg}该推荐, 是否继续?`, ()=>{
                //解禁或禁用操作
                Common.sendRequest({
                    url: 'pushIssueToggle.do',
                    type: 'POST',
                    data: {
                        id: rowData.id,
                        status: targetStatus
                    },
                    success: (result) => {
                        Object.assign(rowData, result);
                    }
                });
            });
        },
        //删除
        del(rowData){
            rowData = rowData ? [rowData] : this.selRowsData;
            if(!rowData.length){
                return Common.message("请选择要删除的数据");
            }
            Common.confirm('此操作将删除选中数据, 是否继续?', ()=>{
                //删除操作
                let ids = rowData.map(item=>item.id);
                Common.sendRequest({
                    url: 'pushDel.do',
                    type: 'POST',
                    data: {id: ids},
                    success: (result) => {
                        let pagerObj = this.pager;
                        //当前最大页数大于1，且当前页为最后一页， 且当前总数减去删除的数据数量得出的最大页数小于原本最大页数时， 当前页减1
                        if(this.lastPage > 1 && pagerObj.page == this.lastPage && Math.ceil( (pagerObj.total - rowData.length) / pagerObj.pageSize ) < this.lastPage){
                            this.pager.page -= 1;
                        }
                        this.getList();
                    }
                });
            });
        },
        //显示弹框
        showDialog(rowData){
            const isEdit = Boolean(rowData);
            this.dislogTitle = isEdit ? '编辑' : '新增推荐';
            //清空数据和校验结果
            this.formData = { status: 2 };
            this.$refs.pushform && this.$refs.pushform.resetFields();
            //数据填充
            isEdit && (this.formData = Object.assign({}, rowData), this.curRowData = rowData);
            //弹框显示
            this.isShowDialog = true;
            //请求分类标签数据
            Common.getCategoryList().then((list)=>{
                this.categoryList = list;
            })
        },
        //获取分类名称
        getCateName(){
            //更新分类名称
            return {category_name: this.categoryList.find((item, index, arr) => { 
                return item.id == this.formData.category_id;
            }).category_name};
        },
        //弹框内提交
        submit(){
            this.$refs.pushform.validate((isSuccess, column) => {
                isSuccess && (this.formData.id ? this.update() : this.add());
            });
        },
        //新增
        add(){
            Common.sendRequest({
                url: 'createPush.do',
                type: 'POST',
                data: this.formData,
                success: (result) => {
                    this.list.unshift(Object.assign({}, this.formData, result, this.getCateName()));
                    this.lastPage > 1 && this.list.pop();
                    this.pager.total ++;
                    //关闭弹框
                    this.isShowDialog = false;
                }
            });
        },
        //修改
        update(){
            if(JSON.stringify(this.formData) == JSON.stringify(this.curRowData)){
                return Common.message("当前没有修改任何数据");
            }
            Common.sendRequest({
                url: 'updatePush.do',
                type: 'POST',
                data: this.formData,
                success: (result) => {
                    Object.assign(this.curRowData, this.formData, result, this.getCateName());
                    //关闭弹框
                    this.isShowDialog = false;
                }
            });
        },
    },
    mounted() {
        this.getList();
    },
};
</script>