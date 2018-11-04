<template>
    <div class="toolbar">
        <!-- 面包屑 -->
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>{{ $route.name }}</el-breadcrumb-item>
        </el-breadcrumb>

        <!-- 工具栏 -->
        <el-row class="tool-bar" type="flex" justify="start">
            <el-button type="warning" icon="el-icon-delete" size="mini" @click="$emit('del')">删除选中</el-button>
            <el-button type="primary" icon="el-icon-circle-plus-outline" size="mini" @click="$emit('add')">新增</el-button>
            <el-button type="success" icon="el-icon-search" v-if="search" size="mini" @click="showDialog">数据过滤</el-button>
        </el-row>

        <!-- 数据过滤弹框 -->
        <el-dialog title="数据过滤" :visible.sync="isShowDialog" width="600px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="100px" :model="searchData" :rules="rules" label-suffix="：" ref='searchform'>
                <el-form-item label="标题">
                    <el-input v-model="searchData.title" placeholder="输入标题内容过滤"></el-input>
                </el-form-item>
                <el-form-item label="概要">
                    <el-input v-model="searchData.article" placeholder="输入概要内容过滤"></el-input>
                </el-form-item>
                <el-form-item label="发布时间">
                    <el-col :span="11">
                        <el-form-item prop="stardate">
                            <el-date-picker v-model="searchData.start" type="date" placeholder="请选择开始日期" value-format="yyyy-MM-dd"></el-date-picker>
                        </el-form-item>
                    </el-col>
                    <el-col class="t-center" :span="2">-</el-col>
                    <el-col :span="11">
                        <el-form-item prop="enddate">
                            <el-date-picker v-model="searchData.end" type="date" placeholder="请选择介绍日期" value-format="yyyy-MM-dd"></el-date-picker>
                        </el-form-item>
                    </el-col>
                </el-form-item>
                <el-form-item label="标签分类">
                    <el-col :span="9">
                        <el-form-item>
                            <el-select v-model="searchData.category_id" placeholder="请选择标签分类">
                                <el-option v-for="(item, index) in categoryList" :key="index" :label="item.category_name" :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="15">
                        <el-form-item label="发布状态">
                            <el-select v-model="searchData.status" placeholder="请选择发布状态">
                                <el-option label="待发布" value="1"></el-option>
                                <el-option label="已发布" value="2"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-form-item>
            </el-form>
            <el-row type="flex" justify="end">
                <el-button type="warning" size="medium" @click="searchData={}">重  置</el-button>
                <el-button type="primary" size="medium" @click="searchSub">搜  索</el-button>
            </el-row>
        </el-dialog>
    </div>
</template>

<script>
import Common from '../assets/js/common';
export default {
    name: "toolbar",
    props: ['search'],
    data(){
        return {
            categoryList: [],//分类标签列表
            requestEd: false, //是否已经请求过分类标签集合
            isShowDialog:false,
            searchData:  {}, //搜索表单数据
            rules: {
                stardate: [
                    { validator: (rule, value, callback) => {
                        this.$refs.searchform.validateField('enddate');
                        callback();
                    }, trigger: 'change' }
                ],
                enddate: [
                    { validator: (rule, value, callback) => {
                        let start = this.searchData.start,
                            end = this.searchData.end;
                        if( (!start && end) || (start && end && new Date(end) < new Date(start) )){
                            return callback(new Error('结束时间不能小于开始时间'));
                        }else if(start && !end){
                            return callback(new Error('请选择结束日期'));
                        }
                        callback();
                    }, trigger: 'change' }
                ]
            }
        }
    },
    methods:{
        //打开弹框
        showDialog(){
            //校验结果
            this.$refs.searchform && this.$refs.searchform.resetFields();
            //显示弹框
            this.isShowDialog = true;

            //请求分类标签数据
            if(!this.requestEd){
                this.requestEd = true;
                //请求分类标签数据
                Common.getCategoryList().then((list)=>{
                    this.categoryList = list;
                })
            }
        },
        //搜索
        searchSub(){
            this.$refs.searchform.validate((isSuccess, column) => {
                if(isSuccess){
                    this.$emit('search', this.searchData);
                    this.isShowDialog = false;
                }
            });
        }
    },
};
</script>