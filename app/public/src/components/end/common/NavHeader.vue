<template>
    <div class="app-header">
        <div class="logo">
            GitHub作业统计后台系统
        </div>
        <div class="user-info">
            <el-dropdown trigger="click">
                <span class="el-dropdown-link">
                    {{ userInfo.username }}
                </span>
                <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item>
                        <a href="login/loginoutEnd">退出</a>
                    </el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
        </div>
    </div>
</template>
<script>
import { post } from '../../../utils.js'
export default {
  data() {
      return {
          name: 'linxin'
      }
  },
  props: ['userInfo'],
  methods:{

  },
  created(){
    
  }

}
</script>
<style>
    .app-header {
        position: relative;
        box-sizing: border-box;
        width: 100%;
        height: 70px;
        font-size: 22px;
        line-height: 70px;
        color: #fff;
        background-color: #242f42;
    }
    .app-header .logo{
        float: left;
        width:250px;
        text-align: center;
    }
    .user-info {
        float: right;
        padding-right: 50px;
        font-size: 16px;
        color: #fff;
    }
    .user-info .el-dropdown-link{
        position: relative;
        display: inline-block;
        padding-left: 50px;
        color: #fff;
        cursor: pointer;
        vertical-align: middle;
    }
    .user-info .user-logo{
        position: absolute;
        left:0;
        top:15px;
        width:40px;
        height:40px;
        border-radius: 50%;
    }
    .el-dropdown-menu{
      padding: 0;
    }
    .el-dropdown-menu__item{
        text-align: center;
    }
</style>
