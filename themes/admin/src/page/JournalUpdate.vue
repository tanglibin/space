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
                <el-form-item label="标题" prop="info.title">
                    <el-input v-model="formData.info.title" type="text" placeholder="请输入概要标题"></el-input>
                </el-form-item>
                <el-form-item label="概要" prop="info.article">
                    <el-input type="textarea" v-model="formData.info.article" placeholder="请输入概要内容"></el-input>
                </el-form-item>
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="分类标签" prop="info.category_id">
                            <el-select v-model="formData.info.category_id" filterable placeholder="请选择分类标签">
                                <el-option v-for="(item, index) in categoryList" :key="index" :label="item.category_name" :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="发布状态">
                            <el-select v-model="formData.info.status" placeholder="请选择发布状态">
                                <el-option label="待发布" :value="1"></el-option>
                                <el-option label="发布" :value="2"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="文章类型">
                            <el-select v-model="formData.info.type" placeholder="请选择文章类型" :disabled="chapter_title_list.length > 1">
                                <el-option label="单节" :value="1"></el-option>
                                <el-option label="多节" :value="2"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="章节标题" prop="detail.chapter_title" v-if="formData.info.type==2">
                    <input class="chapter-title-enter" type="text" v-model="formData.detail.chapter_title">
                    <el-select v-model="formData.detail.title" clearable placeholder="请选择章节标题" @change="changeChapTitle">
                        <el-option v-for="(item, index) in chapter_title_list" :key="index" :label="item.chapter_title" :value="index"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="内容" prop="detail.content">
                    <vue-ueditor-wrap v-model="formData.detail.content" :config="myConfig"></vue-ueditor-wrap>
                </el-form-item>
                <el-row type="flex" justify="end">
                    <el-button type="warning" size="medium" @click="deleteChap">删除章节</el-button>
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
            editId: null, //日志id，编辑时传递
            editData: {}, //编辑原始数据
            formData: {  // 表单对象
                info: {  //概要部分
                    status: 1,
                    type: 1,
                },
                detail: {//详情部分
                    content: ''
                }
            },
            contentValid: true, //内容校验状态默认设定
            rules: Common.getRequiredRuls({//校验规则
                'info.title': '请输入标题',
                'info.article': '请输入概要',
                'info.category_id': {msg: '请选择分类标签', type: 'change'},
                'detail.content': '请输入内容'
            }),
            rule2: Common.getRequiredRuls({//校验规则, 多选时，章节标题
                'detail.chapter_title': '请输入章节标题'
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
        'formData.info.type'(value){
            value == 2 ? Object.assign(this.rules, this.rule2) : delete this.rules['detail.chapter_title'];
        },
        //监听内容改变，刷新校验结果
        'formData.detail.content'(value){
            !this.contentValid && this.$refs.form.validate('detail.content');
        }
    },
    methods: {
        /*获取标签分类*/
        getCategory(){
            //请求分类标签数据
            Common.getCategoryList().then((list)=>{
                this.categoryList = list;
            })
        },
        /*切换章节标题*/
        changeChapTitle(val){
            let detailData_1 = this.editData.detail,
                detailData_2 = this.formData.detail,
                select = detailData_1[val] || {};
            //页面更新填充标题及内容
            detailData_2.chapter_title = select.chapter_title || '';
            detailData_2.content = select.content || '';
        },
        /*获取日志数据*/
        getDataById(){
            Common.sendRequest({
                url: 'getJournalById.do',
                data: {id: this.editId},
                success: (result) => {
                    this.chapter_title_list = result.detail;
                    this.editData = Object.assign({}, result);
                    this.formData = {info: Object.assign({}, result.info), detail:{}};
                    //延迟处理，规避富文本总是填充不到值的问题
                    setTimeout(()=>{
                        this.formData.detail = Object.assign({title: 0}, result.detail[0]);
                    }, 100);
                },
                error: ()=> {
                    this.$router.go(-1);
                }
            });
        },
        /**
         * 提交
         * @param flg {Boolean} 是否完成编辑
         */
        submit(flg){
            this.$refs.form.validate((isSuccess, column) => {
                if(isSuccess){
                    this.editId ? this.update(flg) : this.add(flg);
                }else{
                    this.contentValid = !Boolean(column['detail.content']);
                }
            });
        },
        /**
         * 修改, 不管是否为多章节类型，一次修改提交只能修改一章
         * @param flg {Boolean} 是否完成编辑
         */
        update(flg){
            let formData = this.formData, 
                editData = this.editData,
                param = {};

            //先确定概要部分是否有改动，如果没改动则不传递修改
            if(JSON.stringify(editData.info) != JSON.stringify(formData.info)){
                param.info = JSON.stringify(formData.info);
            }

            //判断当前选中章节有无修改即可
            let detail = editData.detail[formData.detail.title], 
                curDetail = formData.detail;
            
            //detail 没值则为新增章节
            if(!detail){
                delete curDetail.id;
                param.detail = JSON.stringify(curDetail);
            }else if(detail.chapter_title != curDetail.chapter_title || detail.content != curDetail.content){
                param.detail = JSON.stringify(curDetail);
            }

            //判断当前是否有改动，若无则提交
            if(JSON.stringify(param) == '{}'){
                return Common.message('当前没有改动！');
            }

            //请求发送
            Common.sendRequest({
                url: 'journalUpdate.do',
                type: 'POST',
                data: param,
                success: (result) => {
                    if(flg){
                        return this.$router.push('/journal');
                    }
                    detail ? Object.assign(detail, curDetail) : editData.detail.push(Object.assign({}, curDetail, {id: result}));
                }
            });
        },
        /**
         * 新增提交
         * @param flg {Boolean} 是否完成编辑  
         */
        add(flg){
            let formData = this.formData;
            //请求发送
            Common.sendRequest({
                url: 'journalAdd.do',
                type: 'POST',
                data: {info: JSON.stringify(formData.info), detail: JSON.stringify(formData.detail)},
                success: (result) => {
                    let route = flg ? '/journal' : '/journal/edit/' + result;
                    this.$router.push(route);
                }
            });
        },
        /*删除章节*/
        deleteChap(){

        },
    },
    mounted() {
        //获取标签分类
        this.getCategory();

        //编辑状态获取数据
        this.editId = this.$route.params.id;
        this.editId && this.getDataById();
    },
};
</script>