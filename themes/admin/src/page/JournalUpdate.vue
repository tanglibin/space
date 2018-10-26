<template>
    <div class="journalupdate">
        <!-- 面包屑 -->
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item :to="{ path: '/journal' }">日志管理</el-breadcrumb-item>
            <el-breadcrumb-item>{{ $route.name }}</el-breadcrumb-item>
        </el-breadcrumb>

        <!-- 正文表单 -->
        <vue-ueditor-wrap v-model="msg" :config="myConfig"></vue-ueditor-wrap>
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
            msg: "",
            myConfig: {
                UEDITOR_HOME_URL: '/themes/admin/dist/static/UEditor/',
                // 编辑器不自动被内容撑高
                autoHeightEnabled: false,
                // 初始容器高度
                initialFrameHeight: 300,
                // 初始容器宽度
                initialFrameWidth: '100%'
            }
        };
    },
    methods: {
        
    }
};
</script>