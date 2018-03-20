<template>
    <div id="indexHead">
        <el-header class="clearfloat">
            <div class="header-left">
                <img src="../../../assets/github3.png" alt="logo" class="picb logo"/><span class="web-title">基于GitHub作业统计系统</span>
            </div>

						<div class="user-info">
		            <el-dropdown trigger="click" @command="handleCommand">
		            		<img src="../../../assets/github3.png" alt="默认头像"  class="user-logo picb">
		                <span class="el-dropdown-link">
		                    {{ userInfo.username }}
		                </span>
		                <el-dropdown-menu slot="dropdown">
		                    <!-- <el-dropdown-item command="person">
		                        个人信息
		                    </el-dropdown-item> -->
		                    <el-dropdown-item command="loginout">
		                        退出
		                    </el-dropdown-item>
		                </el-dropdown-menu>
		            </el-dropdown>
		        </div>
        </el-header>
    </div>
</template>
<script>

  export default {
		data () {
			return {

			}
		},
		props: ['userInfo'],
		components: {

    },
		create() {

		},
		methods: {
			 handleCommand(command) {
			 	console.log(command)
        if(command == 'loginout'){
          localStorage.removeItem('ms_username')
          this.$router.push('/login');
        }
        else if(command == 'person'){
        	window.location.href = "/#/personinfo"	
        }
      }
		}

	}
</script>
<style scoped>
	#hello header{ position: relative; }
  .logo{ width: 40px;  float: left; }
  .header-left{ position: absolute; color: #333; font-size: 1.5rem; line-height: 2.5rem;vertical-align: middle;}
  .web-title{ margin-left: 20px; }
  .el-header{ padding: 10px 40px; background-color: transparent; color: #333; text-align: center; }

	/*用户*/
	.user-info {float: right;font-size: 16px;color: #333;}
  .user-info .el-dropdown-link{position: relative;display: inline-block;padding-left: 50px;color: #333;font-size:16px;cursor: pointer;vertical-align: middle;}
  .user-info .user-logo{position: absolute;left:0;top:0;width:40px;height:40px;border-radius: 50%; }
  .el-dropdown{ line-height: 2.5rem; }
  .el-dropdown-menu{ padding: 0; top: 40px; }
  .el-dropdown-menu__item{text-align: center; }
</style>
