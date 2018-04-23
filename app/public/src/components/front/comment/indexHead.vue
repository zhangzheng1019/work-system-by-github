<template>
    <div id="indexHead">
      <el-header class="front-head clearfloat">
          <div class="header-left">
              <img src="../../../assets/github3.png" alt="logo" class="picb header-logo"/><span class="header-title">基于GitHub作业统计系统</span>
          </div>
          <div class="user-info">
              <el-dropdown >
                  <span class="el-dropdown-link">
                      <img :src="userInfo.thumb" class="user-logo picb" v-if="userInfo.thumb">
                      <img src="../../../assets/github3.png" class="user-logo picb" v-else>
                      {{ userInfo.username }}<i class="el-icon-arrow-down el-icon--right"></i>
                  </span>
                  <el-dropdown-menu slot="dropdown" class="my-dropdown-menu">
                      <el-dropdown-item v-if="userInfo.role=='student'">
                          <a href="/#/student" title="">我的主页</a>
                      </el-dropdown-item>
                      <el-dropdown-item v-if="userInfo.role=='teacher'">
                          <a href="/#/teacher" title="">我的主页</a>
                      </el-dropdown-item>
                      <el-dropdown-item v-if="userInfo.role=='teacher'">
                          <a href="/admin" title="">后台管理</a>
                      </el-dropdown-item>
                      <el-dropdown-item>
                          <a href="/login/loginout" title="">退出</a>
                      </el-dropdown-item>
                  </el-dropdown-menu>
              </el-dropdown>
          </div>
      </el-header>
    </div>
</template>
<script>
  import { post } from '../../../utils.js'
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
			currentPath(){
          return this.$route.path;
      }
		}

	}
</script>
<style scoped>
  .header-left{ position: absolute; color: #333; font-size: 1.5rem; line-height: 2.5rem;vertical-align: middle;}
	.header-logo{ width: 40px;  float: left; }
  .header-title{ margin-left: 20px; }
  .front-head{ padding: 10px 40px; background: #c1c7cf;}

  /*用户*/
  .user-info {float: right;font-size: 16px;color: #333;}
  .user-info .el-dropdown-link{position: relative;display: inline-block;padding-left: 50px;color: #333;font-size:16px;cursor: pointer;vertical-align: middle;}
  .user-info .user-logo{position: absolute;left:0;top:0;width:40px;height:40px;border-radius: 50%; }
  .el-dropdown{ line-height: 2.5rem; }
  .el-dropdown-menu{ padding: 0; top: 40px; }
  .el-dropdown-menu__item{text-align: center; }
  .my-dropdown-menu a{ text-decoration: none;color: #0379e0; }
</style>
