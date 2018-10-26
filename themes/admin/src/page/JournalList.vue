<template>
    <div class="journal">
        <!-- 工具栏 -->
        <Toolbar :search="true" @add="add" @del="del()" @search="search"></Toolbar>
        
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
            selRowsData : [] //选中数据
        };
    },
    methods: {
        //选中回调
        selChange(datas){
            this.selRowsData = datas;
        },
        //过滤数据
        search(searchParam){
            alert("过滤数据>"+JSON.stringify(searchParam));
        },
        //新增
        add(){
            this.$router.push('/ak');
        },
        //修改
        update(){
            alert("修改操作")
            //关闭弹框
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
                {key: "baidu-ak1", value: "81HNEmUTP0jBYS6saW5HDj2ZTD0ldE2i", name: "百度天气", id:1},
                {key: "baidu-ak2", value: "81HNEmUTP0jBYS6saW5HDj2ZTD0ldE3i", name: "百度天气", id:2},
                {key: "baidu-ak3", value: "81HNEmUTP0jBYS6saW5HDj2ZTD0ldE4i", name: "百度天气", id:3}
        ];
    },
};
</script>