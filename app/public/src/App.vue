<template>
    <div class="app">
        <router-view :userInfo='userInfo'>
        </router-view>
    </div>
</template>
<script>

import { fetch } from './utils'
export default {
  name:'app',
  data () {
    return {
      userInfo:'',
    }
  },
  created() {
    
   }
}
</script>
<style>
    @import "../static/css/main.css";
    @import "../static/css/color-dark.css";
    body{ overflow-y: scroll; }
    .picb{ width: 100%;display: block; }
    .clearfloat{ zoom: 1; }
    .clearfloat:after{ content:"."; display:block; height:0; clear:both; visibility:hidden; }

</style>
