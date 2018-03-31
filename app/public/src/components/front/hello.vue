<template>
    <div id="hello">
    	<div class="main-bg">
            <p class="main-title">技术 · 共享 · 学习</p>
        </div>
    	<el-header class="clearfloat">
            <div class="header-left">
                <img src="../../assets/github3-white.png" alt="默认头像" class="picb header-logo"/><span class="header-title">基于GitHub作业统计系统</span>
            </div>
            <login-front :userInfo="userInfo">
            </login-front>
        </el-header>
    </div>
</template>
<script>
	import Login from './logRes/login'
    export default {
    	name: 'hello',
		data () {
			return {
			  
			}
		},
        props:["userInfo"],
		components: {
			'login-front': Login
        },
		create() {
		},
        mounted() {
            
        },
		methods: {
			
		}

	}
</script>
<style scoped>
    .main-bg{ position: fixed;top: 0;bottom: 0;left: 0;right: 0; width: 100%; background: url('../../assets/it.jpg') no-repeat; background-size: 100% 100%; -moz-background-size: 100% 100%; -webkit-background-size: 100% 100%;}
    .main-title{ position: fixed; top: 50%; left: 50%; z-index: 1; -webkit-transform:translate(-50%, -50%); -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); height: auto; color: #fff; font-size: 3rem; text-align: center;}
    /*头部*/
    #hello header{ position: relative; }
    .header-logo{ width: 40px;  float: left; }
    .header-left{ position: absolute; color: #fff; font-size: 1.5rem; line-height: 2.5rem;vertical-align: middle;}
    .header-title{ margin-left: 20px; }
</style>
