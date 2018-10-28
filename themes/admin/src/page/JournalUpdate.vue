<template>
    <div class="journalupdate">
        <!-- 面包屑 -->
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item :to="{ path: '/journal' }">日志管理</el-breadcrumb-item>
            <el-breadcrumb-item>{{ $route.name }}</el-breadcrumb-item>
        </el-breadcrumb>

        <!-- 正文表单 -->
        <section class="form-wrap">
            <el-form label-position="right" label-width="100px" :model="formData" :rules="rules" label-suffix="：" ref='editform'>
                <el-form-item label="标题">
                    <el-input v-model="formData.title" type="text" placeholder="请输入概要标题"></el-input>
                </el-form-item>
                <el-form-item label="概要">
                    <el-input type="textarea" v-model="formData.article" placeholder="请输入概要内容"></el-input>
                </el-form-item>
                <el-row>
                    <el-col :span="colspan">
                        <el-form-item label="分类标签">
                            <el-select v-model="formData.category_id" placeholder="请选择分类标签">
                                <el-option label="区域一" value="shanghai"></el-option>
                                <el-option label="区域二" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="colspan">
                        <el-form-item label="文章类型">
                            <el-select v-model="formData.type" placeholder="请选择文章类型">
                                <el-option label="单节" value="1"></el-option>
                                <el-option label="多节" value="2"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="colspan" v-if="formData.type==2">
                        <el-form-item label="章节标题">
                            <el-input v-model="formData.chapter_title" type="text" placeholder="请输入章节标题"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="内容">
                    <vue-ueditor-wrap v-model="msg" :config="myConfig"></vue-ueditor-wrap>
                </el-form-item>
            </el-form>
        </section>
    </div>
</template>

<script>
import VueUeditorWrap from 'vue-ueditor-wrap'
import Common from '../assets/js/common';

export default {
    name: "journalupdate",
    components: {VueUeditorWrap},
    data() {
        return {
            formData: {},// 表单对象
            rules: {},
            myConfig: {//富文本编辑器配置参数
                UEDITOR_HOME_URL: '/themes/admin/dist/static/UEditor/',
                // 编辑器不自动被内容撑高
                autoHeightEnabled: false,
                // 初始容器高度
                initialFrameHeight: 300,
                // 初始容器宽度
                initialFrameWidth: '100%'
            },
            msg: "",
        };
    },
    computed:{
        //文章类型为单节时， 两块平分，多节时则三分
        colspan(){
            return this.formData.type == 2 ? 8 : 12;
        }
    },
    methods: {
        
    }
};
</script>