<template>
    <div class="container">
        <HeaderBar></HeaderBar>
        <router-view/>
    </div>
</template>

<script>
//头部
import HeaderBar from '@/components/Header'

export default {
    name: "container",
    data(){
        return {
            
        }
    },
    mounted() {
        
    },
    components: { HeaderBar},
};
</script>