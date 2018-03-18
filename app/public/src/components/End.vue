<template>
    <div class="end-app">
            <side-nav :levelBar="levelBar">
            </side-nav>
            <nav-header :userInfo='userInfo'>
            </nav-header>
        <div class="content">
            <transition name="move" mode="out-in"><router-view></router-view></transition>
        </div>
    </div>
</template>
<script>

import NavHeader from './end/common/NavHeader.vue'
import SideNav from './end/common/SideNav.vue'
import { fetch } from '../utils'
export default {
  components: {
    'nav-header':NavHeader, 
    'side-nav':SideNav
  },
  name:'end',
  data () {
    return {
      userInfo:'',
      levelBar: [],
    }
  },
  created() {
   var cururl = window.location.href;
   this.levelBar = [
      {
        icon: 'el-icon-menu',
        index: '/end/teacher',
        title: '教师管理'
      },
      {
        icon: 'el-icon-menu',
        index: '2',
        title: '学生管理',
        subs: [
            {index: '/end/student',title: '名单' }
        ]
      },
      {
        icon: 'el-icon-menu',
        index: '3',
        title: '作业统计',
        subs: [
            {index: '/end/course',title: '名单' },
            {index: '/end/course',title: '' }
        ]
      }
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
    @import "../../static/css/main.css";
    @import "../../static/css/color-dark.css";
	*{
	  margin: 0;padding: 0;
	}
	.app-content {
	  margin-left: 224px;
	  min-width: 640px;
	  overflow: scroll;
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
 .mtb20{ margin: 20px 0; }
 .ptb10{ padding: 10px 0; }
</style>
