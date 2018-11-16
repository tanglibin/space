<template>
    <div class="login-page">
        <div class="content">
            <h2>管理后台登录</h2>
            <div class="enter-item">
                <i class="icon-user"></i>
                <input v-model.trim="username" placeholder="请输入用户名">
            </div>
            <div class="enter-item">
                <i class="icon-password"></i>
                <input v-model.trim="password" placeholder="请输入密码" type="password">
            </div>
            <div class="login-btn" @click="login">登 录</div>
        </div>
    </div>
</template>

<script>
import Common from '../assets/js/common';

export default {
    name: "login",
    data() {
        return {
            username: '',
            password: ''
        };
    },
    methods: {
        login(){
            let username = this.username, 
                password = this.password;
            if(!username){
                return Common.message('请输入用户名');
            }
            if(!password){
                return Common.message('请输入密码');
            }
            if(!Common.validUserName(username)){
                return Common.message('用户名或密码输入错误，请重新输入');
            }
            let md5 = require('md5');
            Common.sendRequest({
                url: 'loginAdmin.do',
                type: 'POST',
                data: {
                    username: username,
                    password: md5(password),
                },
                success: (result) => {
                    this.$router.push('/');
                }
            });
        }
    },
};
</script>