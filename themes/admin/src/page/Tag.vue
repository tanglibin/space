<template>
    <div class="tag">
        <!-- 工具栏 -->
        <Toolbar @add="showDialog()" @del="del()"></Toolbar>
        
        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="selection" width="55"></el-table-column>
            <el-table-column prop="category_name" label="名称"></el-table-column>
            <el-table-column prop="jourbal_num" label="日志条数"></el-table-column>
            <el-table-column prop="push_num" label="好文推荐条数"></el-table-column>
            <el-table-column prop="micro_num" label="微码条数"></el-table-column>
            <el-table-column prop="last_update_time" label="最后修改时间" width="180"></el-table-column>
            <el-table-column label="操作" width="150">
                <template slot-scope="scope">
                    <el-button type="text" size="small" icon="el-icon-delete" @click="del(scope.row)">删除</el-button>
                    <el-button type="text" size="small" icon="el-icon-edit" @click="showDialog(scope.row)">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>
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
            isEdit && (this.formData = Object.assign({}, rowData), this.curRowData = rowData);
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
            Common.sendRequest({
                url: 'createAk.do',
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
                url: 'updateAk.do',
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
                    url: 'delAk.do',
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
        this.list = [
            {id: 1, category_name: "js", jourbal_num: 20, push_num: 2, micro_num: 0, last_update_time: "2018-12-15 15:12:02"},
            {id: 2, category_name: "css", jourbal_num: 0, push_num: 0, micro_num: 0, last_update_time: "2018-02-15 15:12:04"},
            {id: 3, category_name: "html", jourbal_num: 20, push_num: 2, micro_num: 0, last_update_time: "2018-03-15 15:12:02"},
        ];

        //请求分类标签数据
        Common.getCategoryList(false).then((list)=>{
            this.list = list;
        });
    },
};
</script>