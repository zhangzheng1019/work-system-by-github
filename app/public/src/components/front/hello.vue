<template>
    <div id="hello">
    	<div class="main-bg">
       		<img src="../../assets/mac3.jpg" alt="" class="picb">
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
    .main-bg img{ position: fixed;top: 0;left: 0; width: 100%; }
    #hello header{ position: relative; }
    .pic{ width: 40px;  float: left; }
    .el-header{ padding: 10px 40px; background-color: transparent; color: #333; text-align: center; line-height: 60px; }
    .el-main{  }
</style>
