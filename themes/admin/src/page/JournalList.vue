<template>
    <div class="journal">
        <!-- 工具栏 -->
        <Toolbar :search="true" @add="$router.push('/journal/add')" @del="del()" @search="getList"></Toolbar>
        
        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="index" :index="getIndex" width="50" label=" " align="center"></el-table-column>
            <el-table-column type="selection" width="40" align="center"></el-table-column>
            <el-table-column prop="title" label="标题" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="article" label="概要" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="category_name" label="标签" width="100"></el-table-column>
            <el-table-column prop="issue_time" label="发布时间" width="160"></el-table-column>
            <el-table-column prop="status" label="状态" width="80">
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
                    <el-button type="text" size="small" icon="el-icon-edit" @click="update(scope.row)" v-if="scope.row.status == 1">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination v-if="lastPage>1" @current-change="pageChange" :current-page.sync="pager.page" :page-size="pager.pageSize" :total="pager.total" layout="prev, pager, next, jumper">
    </el-pagination>
    </div>
</template>

<script>
import Toolbar from '@/components/Toolbar'
import Common from '../assets/js/common';

export default {
    name: "journal",
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
            }
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
                url: 'getJournalList.do',
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
            Common.confirm(`此操作将${msg}该日志, 是否继续?`, ()=>{
                //解禁或禁用操作
                Common.sendRequest({
                    url: 'journalIssueToggle.do',
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
                    url: 'journalDel.do',
                    type: 'POST',
                    data: {id: ids},
                    success: (result) => {
                        let pagerObj = this.pager;
                        if(pagerObj.page == this.lastPage && Math.ceil( (pagerObj.total - rowData.length) / pagerObj.pageSize ) < this.lastPage){
                            this.pager.page -= 1;
                        }
                        this.getList();
                    }
                });
            });
        },
        //修改
        update(rowData){
            this.$router.push('/journal/edit/'+rowData.id);
        }
    },
    mounted() {
        this.getList();
    },
};
</script>