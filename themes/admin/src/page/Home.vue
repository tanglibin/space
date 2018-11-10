<template>
    <div class="home">
        <!-- 面包屑 -->
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>{{ $route.name }}</el-breadcrumb-item>
        </el-breadcrumb>

        <!-- 统计部分 -->
        <el-row :gutter="20">
            <el-col :span="6">
                <div class="statistics sta-1">
                    <i class="icon-journal"></i>
                    <div class="sta-item">
                        <h5>1350</h5>
                        <h6>日志条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/journal')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="statistics sta-2">
                    <i class="icon-microcode"></i>
                    <div class="sta-item">
                        <h5>1350</h5>
                        <h6>微码条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/push')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="statistics sta-3">
                    <i class="icon-push"></i>
                    <div class="sta-item">
                        <h5>1350</h5>
                        <h6>好文推荐条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/microcode')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="statistics sta-4">
                    <i class="icon-download"></i>
                    <div class="sta-item">
                        <h5>1350</h5>
                        <h6>资源下载条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/download')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
        </el-row>

        <!-- 图表部分 -->
        <section class="sta-chart" :style="{height: height+'px'}">
            <el-select v-model="year" size="mini" placeholder="请选择年份">
                <el-option v-for="item in years" :key="item" :label="item" :value="item"></el-option>
            </el-select>
            <canvas id="myChart" :width="width" :height="height"></canvas>
        </section>
    </div>
</template>

<script>
import Common from '../assets/js/common';
export default {
    name: "home",
    data() {
        return {
            height: document.body.clientHeight - 280,
            width: document.body.clientWidth - 290,
            year: null, //已选择年份
            years: [2018], //年份集合
        };
    },
    methods:{
        //初始化图表
        initChart(){
            new Chart(document.getElementById("myChart"), {
                "type": "line",
                "data": {
                    "labels": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    "datasets": [{
                        "label": "日志",
                        "data": [5, 1, 3, 2, 6, 0, 9],
                        "fill": false,
                        "borderColor": "#27a9e3",
                        "lineTension": 0.1
                    }, {
                        "label": "微码",
                        "data": [1, 2, 0, 0, 1, 0, 4],
                        "fill": false,
                        "borderColor": "#28b779",
                        "lineTension": 0.1
                    }, {
                        "label": "推荐",
                        "data": [1, 7, 0, 2, 1, 0, 4],
                        "fill": false,
                        "borderColor": "#842a9b",
                        "lineTension": 0.1
                    }]
                },
                "options": {}
            });
        }
    },
    mounted() {
        this.initChart();
    },
};
</script>