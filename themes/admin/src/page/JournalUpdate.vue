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
            <el-form label-position="right" label-width="100px" :model="formData" :rules="rules" label-suffix="：" ref='form'>
                <el-form-item label="标题" prop="title">
                    <el-input v-model="formData.title" type="text" placeholder="请输入概要标题"></el-input>
                </el-form-item>
                <el-form-item label="概要" prop="article">
                    <el-input type="textarea" v-model="formData.article" placeholder="请输入概要内容"></el-input>
                </el-form-item>
                <el-row>
                    <el-col :span="colspan">
                        <el-form-item label="分类标签" prop="category_id">
                            <el-select v-model="formData.category_id" filterable allow-create placeholder="请选择分类标签">
                                <el-option v-for="(item, index) in categoryList" :key="index" :label="item.category_name" :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="colspan">
                        <el-form-item label="文章类型" prop="type">
                            <el-select v-model="formData.type" placeholder="请选择文章类型" :disabled="formData.chapter_title.length > 1">
                                <el-option label="单节" value="1"></el-option>
                                <el-option label="多节" value="2"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="colspan" v-if="formData.type==2">
                        <el-form-item label="章节标题" prop="chapter_title">
                            <el-select v-model="formData.chapter_title" filterable allow-create placeholder="请输入章节标题">
                                <el-option v-for="(item, index) in chapter_title_list" :key="index" :label="item" :value="item"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="内容" prop="content">
                    <vue-ueditor-wrap v-model="formData.content" :config="myConfig"></vue-ueditor-wrap>
                </el-form-item>
                <el-row type="flex" justify="end">
                    <el-button type="warning" size="medium" @click="resetForm">重  置</el-button>
                    <el-button type="primary" size="medium" @click="submit()">保存章节</el-button>
                    <el-button type="primary" size="medium" @click="submit(1)">完成编辑</el-button>
                </el-row>
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
            formData: {content: '', chapter_title: []},// 表单对象
            rules: Common.getRequiredRuls({//校验规则
                title: '请输入标题',
                article: '请输入概要',
                category_id: {msg: '请选择分类标签', type: 'change'},
                type: {msg: '请选择章节类型', type: 'change'},
                content: '请输入内容'
            }),
            rule2: Common.getRequiredRuls({//校验规则, 多选时，章节标题
                chapter_title: {msg: '请输入章节标题', type: 'change'}
            }),
            categoryList: [], //标签分类
            chapter_title_list : [], //章节标题下拉选项数据
            myConfig: {//富文本编辑器配置参数
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
    watch: {
        //监听文章类型选择， 控制校验规则是否包含章节标题
        'formData.type'(value){
            value == 2 ? Object.assign(this.rules, this.rule2) : delete this.rules.chapter_title;
        },
        //监听内容改变，刷新校验结果
        'formData.content'(value){
            this.$refs.form.validate('content');
        }
    },
    computed:{
        //文章类型为单节时， 两块平分，多节时则三分
        colspan(){
            return this.formData.type == 2 ? 8 : 12;
        }
    },
    methods: {
        //获取标签分类
        getCategory(){
            Common.sendRequest({
                url: 'getCategoryList.do',
                success: (result) => {
                    this.categoryList = result;
                }
            });
        },
        //重置表单
        resetForm(){

        },
        /**
         * 提交
         * @param flg {Boolean} 是否完成编辑
         */
        submit(flg){
            var a = this;
            var b = this.formData.category_id;
            this.$refs.form.validate((isSuccess, column) => {

            });
        }
    },
    mounted() {
        //获取标签分类
        this.getCategory();

        //编辑状态获取数据
        let editId = this.$route.params.id;
        if(editId){
            Common.sendRequest({
                url: 'getJournalById.do',
                data: {id: editId},
                success: (result) => {
                    console.log(JSON.stringify(result));
                }
            });
        }
    },
};
</script>