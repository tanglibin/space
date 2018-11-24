<template>
    <header class="header">
        <section class="main-cont">
            <nav class="topnav">
                <ul class="navbar" ref="navbar">
                    <li class="navitem" 
                        v-for="(item, index) in navList" 
                        :class="{'is-active': index == activeIndex}" 
                        :key="index" 
                        @click="changeRouter(index)">{{item.name}}</li>
                </ul>
                <a class="active-bar" ref="activebar"></a>
            </nav>

            <div class="searchbar">
                <el-select
                    v-model="searchParam"
                    filterable
                    remote
                    placeholder="站内搜索"
                    :remote-method="remoteMethod"
                    :loading="loading">
                    <el-option
                        v-for="item in searchList"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                    </el-option>
                </el-select>
            </div>
        </section>
    </header>
</template>

<script>
export default {
    name: 'headerBar',
    data() {
        return {
            activeIndex: 0, //默认选中菜单
            activebar: null, //选中标识下边框
            navList: [
                { name: '首页', route: '/'},
                { name: '前端日志', route: '/journal'},
                { name: '好文推荐', route: '/recommend'},
                { name: '我的微码', route: '/microcode'},
                { name: '我的足迹', route: '/footprint'}
            ],
            searchParam: '', //站内搜索当前搜索条件
            loading: false, //是否正在站内搜索
            searchList: []//站内搜索数据集合
        };
    },
    methods: {
        //路由跳转
        changeRouter(index){
            this.activeIndex = index;
            this.$router.push(this.navList[index].route);
            this.setBarPosition();
        },
        //设置下边框位置
        setBarPosition(){
            let refs = this.$refs,
                item = refs.navbar.children[this.activeIndex],
                activebar = this.activebar || refs.activebar,
                left = item.offsetLeft,
                width = item.clientWidth;
            activebar.style.transform = `translateX(${left}px)`;
            activebar.style.width = `${width}px`;
        },
        //站内搜索
        remoteMethod(query){
            if (query !== '') {
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                    this.searchList = [
                        {value: 1, label: '拿那就南京南京1'},
                        {value: 2, label: '拿那就南京南京2'},
                        {value: 3, label: '拿那就南京南京3'},
                        {value: 4, label: '拿那就南京南京4'},
                    ]
                }, 200);
            } else {
                this.searchList = [];
            }
        }
    },
    mounted(){
        let path = this.$route.path.match(/\/([a-z]+)/);
        if(path){
            path = path[0];
            this.activeIndex = this.navList.findIndex(item=> item.route == path);
        }
        //设置选中下边框
        this.setBarPosition();
    }
};
</script>