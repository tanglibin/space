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
                        <h5 v-text="sum.journal"></h5>
                        <h6>日志条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/journal')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="statistics sta-2">
                    <i class="icon-microcode"></i>
                    <div class="sta-item">
                        <h5 v-text="sum.microcode"></h5>
                        <h6>微码条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/push')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="statistics sta-3">
                    <i class="icon-push"></i>
                    <div class="sta-item">
                        <h5 v-text="sum.push"></h5>
                        <h6>好文推荐条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/microcode')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
            <el-col :span="6">
                <div class="statistics sta-4">
                    <i class="icon-download"></i>
                    <div class="sta-item">
                        <h5 v-text="sum.download"></h5>
                        <h6>资源下载条数</h6>
                    </div>
                    <div class="view-more" @click="$router.push('/download')">VIEW MORE<i class="icon-more"></i></div>
                </div>
            </el-col>
        </el-row>

        <!-- 图表部分 -->
        <section class="sta-chart" :style="{height: height+'px'}">
            <el-select v-model="year" @change="changeYear" size="mini" placeholder="请选择年份">
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
            sum: {}, //条目数对象
            height: document.body.clientHeight - 280,
            width: document.body.clientWidth - 290,
            year: new Date().getFullYear(), //已选择年份
            years: []  //年份列表
        };
    },
    methods:{
        //初始化图表
        initChart({journal, microcode, push}){
            new Chart(document.getElementById("myChart"), {
                type: "line",
                data: {
                    labels: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    datasets: [{
                        label: "日志",
                        data: journal,
                        fill: false,
                        borderColor: "#27a9e3",
                        lineTension: 0.1
                    }, {
                        label: "微码",
                        data: microcode,
                        fill: false,
                        borderColor: "#28b779",
                        lineTension: 0.1
                    }, {
                        label: "推荐",
                        data: push,
                        fill: false,
                        borderColor: "#842a9b",
                        lineTension: 0.1
                    }]
                },
                options: {}
            });
        },
        //年份更改
        changeYear(val){
            this.year = val;
            this.getMonthSum();
        },
        //获取数据统计
        getSum(){
            Common.sendRequest({
                url: 'getSum.do',
                success: (result) => {
                    this.sum = result;
                }
            });
        },
        //获取当前选择年份的日志，微码，推荐月条目数
        getMonthSum(){
            Common.sendRequest({
                url: 'getSumMonth.do',
                data: {year: this.year},
                success: (result) => {
                    this.initChart({
                        journal: Object.values(result.journal),
                        microcode: Object.values(result.microcode),
                        push: Object.values(result.push)
                    });
                }
            });
        }
    },
    mounted() {
        let year = this.year, years = [], startYear = 2018;
        for(let i=year; i>= startYear; i--){ years.push(i); }
        this.years = years;

        //获取数据统计
        this.getSum();
        //获取当前选择年份的日志，微码，推荐月条目数
        this.getMonthSum();
    },
};
</script>