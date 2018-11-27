<template>
    <footer class="footer">
        <!-- 右侧栏 -->
        <section class="right-bar">
            <div class="feedback">意见反馈</div>
            <div class="to-up" v-show="showToUp" @click="toTop"><i class="el-icon-arrow-up"></i></div>
        </section>
    </footer>
</template>

<script>
export default {
    name: 'footerBar',
    data() {
        return {
            body: null, //body容器
            scrollTop: 0, //窗口滚动高度
        };
    },
    computed: {
        //是否显示置顶按钮
        showToUp(){
            return this.scrollTop >= 500;
        }
    },
    methods: {
        //滚动事件
        scroll(){
            this.scrollTop = window.pageYOffset;
        },
        //跳转到顶部
        toTop(){
            let timer = 50, //执行时长
                _top = this.scrollTop, //当前滚动所在高度
                scrollPart = Math.ceil(_top / timer * 100) / 100, //300毫秒滚动段高度
                interval;
            //定时滚动
            interval = setInterval(()=>{
                _top -= scrollPart;
                window.scrollTo(0, _top);
                if(_top <= 0){
                    this.scrollTop = 0;
                    return clearInterval(interval);
                }
            }, 1);
        }
    },
    mounted(){
        this.scrollTop = window.pageYOffset;
        window.addEventListener('scroll', ()=>{this.scroll()})
    }
};
</script>