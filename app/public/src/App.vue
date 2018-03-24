<template>
    <div id="app">
        <router-view :userInfo='userInfo'>
        </router-view>
    </div>
</template>
<script>
import $ from 'jquery'
import { fetch } from './utils'
export default {
  name:'app',
  data () {
    return {
      userInfo:{
        id:1,
        username: '张政',
        desc: '快去介绍一下自己吧',
        email: '447590461@qq.com',
        thumb: './uploads/20180321100316.jpg',
        role: 'teacher'
      },
    }
  },
  mounted(){
    $('html').css('fontSize','16px');
  },
  created() {
    
   }
}
</script>
<style>
  #app, .wrapper, body, html{ width: 100%; height: 100%; }
  html{ font-family: "Microsoft YaHei","微软雅黑","Hiragino Sans GB",Arial,sans-serif; }
  *{ margin: 0;padding: 0; }
  div{ outline: none; }
  .picb{ width: 100%;display: block; }
  .clearfloat{ zoom: 1; }
  .clearfloat:after{ content:"."; display:block; height:0; clear:both; visibility:hidden; }
  .relative{ position: relative; }
  .absolute{ position: absolute; }
  .oneline { overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
  .moreline { overflow:hidden;text-overflow:ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient: vertical; }
  .title-line{font-size: 18px;font-weight: normal;line-height: 50px;border-bottom:1px solid #ccc;margin-bottom: 20px;}
  .add{ display:inline-block;float:right; }
  .mtb20{ margin: 20px 0; }
  .ptb10{ padding: 10px 0; }
</style>
