<template>
    <div>
        <div class="logo">
            <span>
                基于GitHub后台管理系统
            </span>
            <!-- <img src=""> -->
        </div>
        <el-menu class="app-menu" theme="dark" :default-openeds='active' v-if='levelBar' :unique-opened='true'>
            <el-menu-item v-for="(levelBar, index) in levelBar" :index='levelBar.labelIndex' :key="levelBar.labelIndex">
                <router-link :to='levelBar.url'>
                    <i class="el-icon-menu">
                    </i>
                    {{ levelBar.label }}
                </router-link>
            </el-menu-item>
            <!-- <el-submenu v-for="(levelBar,index) in levelBar" :index='levelBar.labelIndex' :key="levelBar.labelIndex" >
                    <template slot="title"><i class="el-icon-menu"></i>{{levelBar.label}}</template>
                      <el-menu-item v-for="(levelIn,index) in levelBar.level" :index='levelIn.index' :key="levelIn.index">
                        <router-link :to='levelIn.url'>
                            {{levelIn.name}}
                        </router-link> 
                      </el-menu-item>
                  </el-submenu> -->
        </el-menu>
    </div>
</template>
<script>
import { fetch } from '../../../utils'
export default {
  data () {
    return {
      active: [],
    }
  },
  props:['levelBar'],
  watch: {
    '$route': function (to, from) {
      this.activeMenu(to.path)
    },
  },
  created () {
    this.activeMenu(this.$route.path);
  },
  methods: {
    activeMenu(path) {
      console.log(path);
        if (path && path.search(/\/adv\/*/) >= 0) {
       //   this.active = ["1"]
        }
    }
  }
}
</script>
<style type="text/css">
.app-menu {
  background: #404040;
  color: #999;
}
.logo {
  width: 100%;
    height: 64px;
    background: #2db7f5;
    text-align: center;
}
.logo > img {
  margin-top: 12px;
}
.logo span {
  color: #fff;
  line-height: 64px;
}
.el-submenu .el-menu-item{
  padding: 0;
  cursor: auto;
}
.el-menu-item > a{
  display: block
}
.el-menu-item > a:not(.router-link-active)  {
  color: #c0ccda;
  text-decoration: none;
}
.router-link-active {
  color: white;
  text-decoration: none;
}
</style>
