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
      userInfo:[]
    }
  },
  mounted(){
    $('html').css('fontSize','16px');
  },
  methods: {
    ajaxGetUsr() {
      fetch({
        url: '/login/getInfo',
        dataType: 'json',
        cb:(data, msg) => {
          this.userInfo = data
          console.log(this.userInfo)
        },
        err:(data,msg) => {
          this.$message.error(msg)
        }
      })
    },
  },
  created() {
    this.ajaxGetUsr()
  }
}
</script>
<style>
  @import '../static/css/font-awesome.min.css'
  #app, .wrapper, body, html{ width: 100%; height: 100%; }
  html{ font-family: "Microsoft YaHei","微软雅黑","Hiragino Sans GB",Arial,sans-serif; }
  *{ margin: 0;padding: 0; }
  [class^="el-icon-fa"], [class*=" el-icon-fa"] { display: inline-block; font: normal normal normal 14px/1 FontAwesome!important; font-size: inherit; text-rendering: auto; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
  div,span{ outline: none; }
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
