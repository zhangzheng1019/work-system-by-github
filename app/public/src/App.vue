<template>
<div class="app">
  <div class="app-side">
    <side-nav :levelBar="levelBar"></side-nav>
  </div>
  <div class="app-header">
      <nav-header :userInfo='userInfo'></nav-header>
    </div>
  <div class="app-content"> 
      <router-view :userInfo='userInfo'></router-view>
  </div>
</div>
</template>

<script>

import NavHeader from './components/common/NavHeader.vue'
import SideNav from './components/common/SideNav.vue'
import { fetch } from './utils'
export default {
  components: {
    'nav-header':NavHeader, 
    'side-nav':SideNav
  },
  name:'app',
  data () {
    return {
      userInfo:'',
      levelBar:[],
    }
  },
  created() {
   var cururl = window.location.href;
   this.levelBar = [
      {label:'教师管理',url:'/teacher',labelIndex:"0"},
      {label:'学生管理',url:'/student',labelIndex:"1"}
   ];
  //  fetch({
  //       url: '/user/menu',
  //       dataType: 'json',
  //       data: {menufromurl: cururl},
  //       cb: (data,msg) =>  { 
  //         this.userInfo = data;
  //         for (let [k, mainlevel] of Object.entries(data)) {
  //            if(k == 'menu_control'){
  //                  for (let [key, level] of Object.entries(mainlevel)) {
  //                       var inLevel = [];
  //                       for(let[child,childlevel] of Object.entries(level.list)){
  //                           inLevel.push({
  //                                        'name':childlevel.name,
  //                                        'index':childlevel.index,
  //                                        'url':childlevel.url
  //                                      });
  //                           inLevel.concat(inLevel);
  //                       }
  //                       this.levelBar.push({
  //                           'label':level.name,
  //                           'labelIndex':level.index,
  //                           'level':inLevel
  //                       })
  //                  }
  //             }
  //         }
  //         },
  //         err: (data,msg) => {
  //              this.$message.error(msg) 
  //         }
  //     })
    
   }
}
</script>
<style>
*{
  margin: 0;padding: 0;
}
.app {
  height: 100vh;
  position: relative;
  width: 100%;
}
.app-content {
  margin-left: 224px;
  min-width: 640px;
  overflow: scroll;
}
.app-header {
  position: fixed;
  left:224px;
  top: 0;
  right: 0;
  z-index:99;
  background: #2db7f5;
  height: 64px;
  border-bottom: 1px solid #e9e9e9;
  z-index: 99
}
.app-side {
  position: fixed;
  width: 224px;
  left: 0;
  top: 0;
  height: 100vh;
  background: #404040;
  z-index: 99;
}
.app  .app-content{
     position: relative;
     left: 0px;top: 64px;
     padding-bottom: 40px;padding-right: 40px;padding-left: 40px;padding-top: 20px;
}
.header {
  border-bottom: 1px solid rgb(233, 233, 233);
}
.title {
  font-weight: 500;
  margin: 20px;
}
 .title-line{font-size: 18px;font-weight: normal;line-height: 50px;border-bottom:1px solid #ccc;margin-bottom: 20px;}
 .add{ display:inline-block;float:right; }
</style>
