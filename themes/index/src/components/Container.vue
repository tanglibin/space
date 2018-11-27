<template>
    <div class="container">
        <header-bar></header-bar>
        <router-view/>
        <footer-bar></footer-bar>
    </div>
</template>

<script>
//头部
import headerBar from '@/components/Header'
import footerBar from '@/components/Footer'

export default {
    name: "container",
    data(){
        return {
            
        }
    },
    mounted() {
        
    },
    components: { headerBar, footerBar},
};
</script>