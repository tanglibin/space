<template>
    <div class="download">
        <!-- 工具栏 -->
        <Toolbar @add="showDialog()" @del="del()"></Toolbar>
        
        <!-- 正文内容表格 -->
        <el-table :data="list" @selection-change="selChange" border style="width: 100%">
            <el-table-column type="selection" width="55"></el-table-column>
            <el-table-column prop="name" label="名称" min-width="100" :show-overflow-tooltip="true"></el-table-column>
            <el-table-column prop="logo_name" label="图标" width="80">
                <template slot-scope="scope">
                    <el-upload :show-file-list="false" :on-success="uploaded" :action="`/themes/admin/dist/static/Upload/controller.php?id=${scope.row.id}&filename=${scope.row.logo_name}`">
                        <i v-if="scope.row.logo_name" class='logo_name' :style="`background-image:url('/upload/download/${scope.row.logo_name + '?' + scope.row.imgRandom}')`"></i>
                        <a class="link" v-else>上传图标</a>
                    </el-upload>
                </template>
            </el-table-column>
            <el-table-column prop="file_name" label="文件" min-width="100">
                <template slot-scope="scope">
                    <el-upload :show-file-list="false" :on-success="uploaded" :action="`/themes/admin/dist/static/Upload/controller.php?id=${scope.row.id}&filename=${scope.row.file_name}`">
                        <a class="link">{{scope.row.file_name || '上传文件'}}</a>
                    </el-upload>
                </template>
            </el-table-column>
            <el-table-column prop="version" label="版本" min-width="80"></el-table-column>
            <el-table-column prop="size" label="大小" min-width="90"></el-table-column>
            <el-table-column prop="url" label="外部地址" min-width="220" :show-overflow-tooltip="true">
                <template slot-scope="scope">
                    <a class="link" :href="scope.row.url" target="_blank">{{scope.row.url}}</a>
                </template>
            </el-table-column>
            <el-table-column prop="last_update_time" label="最后更新时间" width="180"></el-table-column>
            <el-table-column label="操作" width="140">
                <template slot-scope="scope">
                    <el-button type="text" size="small" icon="el-icon-delete" @click="del(scope.row)">删除</el-button>
                    <el-button type="text" size="small" icon="el-icon-edit" @click="showDialog(scope.row)">编辑</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!-- 弹框 -->
        <el-dialog :title="dislogTitle" :visible.sync="isShowDialog" width="400px" :close-on-click-modal="false">
            <el-form label-position="right" label-width="100px" :model="formData" :rules="rules" label-suffix="：" ref='downform'>
                <el-form-item label="名称" prop="name">
                    <el-input v-model="formData.name" placeholder="请输入名称"></el-input>
                </el-form-item>
                <el-form-item label="版本" prop="version">
                    <el-input v-model="formData.version" placeholder="请输入版本"></el-input>
                </el-form-item>
                <el-form-item label="外部地址">
                    <el-input v-model="formData.url" placeholder="请输入外部地址"></el-input>
                </el-form-item>
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
    name: "download",
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
                name: '请输入名称',
                version: '请输入版本'
            })
      };
    },
    methods: {
        //选中回调
        selChange(datas){
            this.selRowsData = datas;
        },
        //文件大小单位转换
        getSize(bytes){
            if (bytes === 0) return '0b';
            var k = 1024,
                sizes = ['b', 'kb', 'mb'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.floor((bytes / Math.pow(k, i))*100)/100 + sizes[i];
        },
        //上传文件成功
        uploaded(response, file, fileList){
            let dataId = response.id,
                isUpLogo = ['.png', '.jpg'].includes(response.type),
                moduleName = isUpLogo ? 'logo_name' : 'file_name',
                param = {id: dataId};

            this.curRowData = this.list.find((item)=>{
                return item.id == dataId;
            });

            isUpLogo ? (this.curRowData.imgRandom = +new Date()) : (param.size = this.getSize(response.size));
            param[moduleName] = response.title;
            this.update(param);
        },
        //显示弹框
        showDialog(rowData){
            const isEdit = Boolean(rowData);
            this.dislogTitle = isEdit ? '编辑资源信息' : '新增资源';
            //清空数据和校验结果
            this.formData = {};
            this.$refs.downform && this.$refs.downform.resetFields();
            //数据填充
            isEdit && (this.formData = Object.assign({}, rowData), this.curRowData = rowData);
            //弹框显示
            this.isShowDialog = true;
        },
        //弹框内提交
        submit(){
            this.$refs.downform.validate((isSuccess, column) => {
                isSuccess && (this.formData.id ? this.update() : this.add());
            });
        },
        //新增
        add(){
            Common.sendRequest({
                url: 'createDown.do',
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
        update(data){
            if(!data){
                let formData = this.formData;
                if(JSON.stringify(formData) == JSON.stringify(this.curRowData)){
                    return Common.message("当前没有修改任何数据");
                }
                data = {
                    id: formData.id,
                    name: formData.name,
                    version: formData.version,
                    url: formData.url
                }
            }
            Common.sendRequest({
                url: 'updateDown.do',
                type: 'POST',
                data: data,
                success: (result) => {
                    Object.assign(this.curRowData, data, {last_update_time: result});
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
                let ids = [], files = [];
                rowData.forEach(item => {
                    ids.push(item.id);
                    item.logo_name && files.push(item.logo_name);
                    item.file_name && files.push(item.file_name);
                });
                Common.sendRequest({
                    url: 'delDown.do',
                    type: 'POST',
                    data: {ids: ids, files: files},
                    success: (result) => {
                        this.list = this.list.filter(item => !ids.includes(item.id));
                    }
                });
            });
        },
    },
    mounted() {
        Common.sendRequest({
            url: 'getDownList.do',
            success: (result) => {
                this.list = result;
            }
        });
    },
};
</script>