<template>
    <div id="hello">
    	<div class="main-bg">
       		<!-- <img src="../../assets/it.jpg" alt="" class="picb"> -->
            <p class="main-title">技术 · 共享 · 学习</p>
        </div>
    	<el-header class="clearfloat">
            <div class="pic">
                <img src="../../assets/github3-white.png" alt="默认头像" class="picb"/>
            </div>
            <login-front>
            </login-front>
        </el-header>
    </div>
</template>
<script>
    import indexHead  from './comment/indexHead'
	import Login from './logRes/login'
    export default {
    	name: 'hello',
		data () {
			return {
			  
			}
		},
		components: {
			'index-head': indexHead,
			'login-front': Login
        },
		create() {

		},
		methods: {
			
		}

	}
</script>
<style scoped>
    .main-bg{ position: fixed;top: 0;bottom: 0;left: 0;right: 0; width: 100%; background: url('../../assets/it.jpg') no-repeat; background-size: 100% 100%; -moz-background-size: 100% 100%; -webkit-background-size: 100% 100%;}
    #hello header{ position: relative; }
    .pic{ width: 40px;  float: left; }
    .main-title{ position: fixed; top: 50%; left: 50%; z-index: 1; -webkit-transform:translate(-50%, -50%); -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); height: auto; color: #fff; font-size: 4rem; text-align: center;}
    .el-header{ padding: 10px 40px; background-color: transparent; color: #333; text-align: center; line-height: 60px; }
</style>
