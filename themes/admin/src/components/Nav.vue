<template>
    <div class="nav">
        <el-menu :default-active="$route.path" router>
            <el-menu-item index="/home" style="display:none"></el-menu-item>
            <el-menu-item v-for="(item, index) in navList" :key="index" :index="item.route">
                <i :class="item.icon"></i>
                <span slot="title">{{item.name}}</span>
            </el-menu-item>
        </el-menu>
    </div>
</template>

<script>
export default {
  name: "leftNav",
  data() {
    return {
      navList: [
        { name: "AK管理", route: "/ak", icon: "icon-ak" },
        { name: "用户管理", route: "/user", icon: "icon-user" },
        { name: "日志管理", route: "/journal", icon: "icon-journal" },
        { name: "微码管理", route: "/microcode", icon: "icon-microcode" },
        { name: "好文推荐", route: "/push", icon: "icon-push" },
        { name: "资源下载", route: "/download", icon: "icon-download" },
        { name: "建议反馈", route: "/propose", icon: "icon-propose" }
      ]
    };
  }
};
</script>