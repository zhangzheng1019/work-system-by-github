<template>
    <div id="app">
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
      userInfo:{
        id:1
      },
    }
  },
  created() {
    
   }
}
</script>
<style>
  body{ overflow-y: scroll; }
  #app, .wrapper, body, html{ width: 100%; height: 100%; overflow: hidden;}
  *{ margin: 0;padding: 0; }
  .picb{ width: 100%;display: block; }
  .clearfloat{ zoom: 1; }
  .clearfloat:after{ content:"."; display:block; height:0; clear:both; visibility:hidden; }
  .relative{ position: relative; }
  .title-line{font-size: 18px;font-weight: normal;line-height: 50px;border-bottom:1px solid #ccc;margin-bottom: 20px;}
  .add{ display:inline-block;float:right; }
  .mtb20{ margin: 20px 0; }
  .ptb10{ padding: 10px 0; }
</style>
