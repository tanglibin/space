<template>
    <div class="download">
        <el-upload
            :show-file-list="false"
            action="/themes/admin/dist/static/Upload/controller.php?filename=123.rar">
            <el-button size="small" type="primary">点击上传</el-button>
        </el-upload>
        <el-upload
            :show-file-list="false"
            action="/themes/admin/dist/static/Upload/controller.php?filename=123.jpg">
            <el-button size="small" type="primary">点击上传</el-button>
        </el-upload>

        <button @click="rename">修改名称</button>
        <button @click="delfile">删除文件</button>
    </div>
</template>

<script>
import Toolbar from '@/components/Toolbar'
import Common from '../assets/js/common';

export default {
    name: "download",
    data() {
      return {

      };
    },
    methods: {
        rename(){
            Common.sendRequest({
                url: 'xx.do',
                type: 'POST',
                data: {
                    imgName_o: '123.jpg',
                    imgName_n: '456.jpg',
                    fileName_o: '123.rar',
                    fileName_n: '456.rar'
                },
                success: (result) => {
                    
                }
            });
        },
        delfile(){
            Common.sendRequest({
                url: 'dd.do',
                data: {
                    imgName: '456.jpg',
                    fileName: '456.rar'
                },
                success: (result) => {
                    
                }
            });
        }
    }
};
</script>