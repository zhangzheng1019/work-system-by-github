<template>
    <div class="end-app">
        <side-nav :levelBar="levelBar">
        </side-nav>
        <nav-header :userInfo='userInfo'>
        </nav-header>
        <div class="content">
            <transition name="move" mode="out-in"><router-view :userInfo='userInfo'></router-view></transition>
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
      levelBar: [],
    }
  },
  props:['userInfo'],
  created() {
    this.getSideBar()
  },
  methods: {
      getSideBar() {
        fetch({
          url: '/main/endPurview',
          dataType: 'json',
          cb: (data,msg) =>  { 
            this.levelBar = data;
          },
          err: (data,msg) => {
            this.$message.error(msg) 
          }
        })
      }
   }
}
</script>
<style>
    @import "../../static/css/main.css";
    @import "../../static/css/color-dark.css";
  
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

 
</style>
