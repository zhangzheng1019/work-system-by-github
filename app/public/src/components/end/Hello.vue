<template>
    <div class="container">
        <div class="hello-header">
            <h2>
                欢迎使用{{ msg }}
            </h2>
        </div>
        <div class="hello-info">
            使用说明：
            为了保证使用者的体验，本项目使用的大量的浏览器新特性，故不再支持比较旧的浏览器。
            <br/>
            其中明确不再支持的浏览器有：
            <br/>
            <ul>
                <li>
                    IE任何一个版本
                </li>
                <li>
                    Mac safari 9以及以下版本
                </li>
            </ul>
            明确支持的浏览器有：
            <ul>
                <li>
                    chrome 55以及以上版本
                    <span style='color:red;'>
                        (推荐)
                    </span>
                </li>
                <li>
                    window Edge 14以及以上版本
                </li>
                <li>
                    Mac safari 10以及以上版本
                </li>
            </ul>
            其他浏览器如360，猎豹等请及时更新，并在极速模式下使用。
        </div>
    </div>
</template>
<script>
export default {
  name: 'hello',
  data:function() {
    return {
      msg: 'GitHub 作业统计后台管理系统'
    }
  }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
.container{padding:50px;}
.hello-header{padding-bottom: 20px;}
.hello-info ul{padding-left: 50px;}
.hello-info{line-height: 34px;}
</style>
