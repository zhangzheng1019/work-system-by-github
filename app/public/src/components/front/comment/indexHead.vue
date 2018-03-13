<template>
    <div id="indexHead">
        <el-header>
            <div class="pic">
                <img src="../../../assets/github3-white.png"alt="默认头像" class="picb"/>
            </div>
            <login-front>
            </login-front>
        </el-header>
    </div>
</template>
<script>
	import Login from '../logRes/login'
    export default {
		data () {
			return {
			  
			}
		},
		components: {
			'login-front': Login
        },
		create() {

		},
		methods: {
			
		}

	}
</script>
<style scoped>
	#indexHead header{ position: relative; }
    .pic{ width: 45px;  display: inline-block; float: left; }
    .el-header{ padding: 10px 40px 5px; background-color: transparent; color: #333; text-align: center; line-height: 60px; }
    .el-main{  }

</style>
