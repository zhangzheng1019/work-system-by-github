<template>
  <div id="student" class="front-wrap">
    <el-row :gutter="18" >
      <!--课程标题和描述卡片-->
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>Html5与移动互联网开发</span>
          <el-button style="float: right; padding: 3px 0" type="text">操作按钮</el-button>
        </div>
        <div v-for="o in 1" :key="o" class="text item">
          课程描述：这是关于html5与移动互联网开发课程的一条描述。
        </div>
      </el-card>

    </el-row>
    <el-row :gutter="18" >
      <!--tab任务完成情况-->
      <template>
        <el-tabs v-model="activeName" @tab-click="handleClick">
          <el-tab-pane label="已完成" name="first">

          </el-tab-pane>
          <el-tab-pane label="未完成" name="second">
            <!--树级已选课程展示-->
            <el-tree :data="data" :props="defaultProps" @node-click="handleNodeClick"></el-tree>
          </el-tab-pane>
          <el-tab-pane label="已过期" name="third">角色管理</el-tab-pane>

        </el-tabs>
      </template>







    </el-row>




  </div>
</template>
<script>
  export default {
    name: 'student',
    data () {
      return {
        course:{
          name: 'JavaScript'
        }
      }
    },
    components: {

    },
    create() {

    },
    methods: {

    }

  }
</script>
<style scoped>

</style>
<!--分区的样式-->
<style>
  .el-header, .el-footer {
    background-color: #B3C0D1;
    color: #333;
    text-align: center;
    line-height: 60px;
  }

  .el-aside {
    background-color: #D3DCE6;
    color: #333;
    text-align: center;
    line-height: 200px;
  }

  .el-main {
    background-color: #E9EEF3;
    color: #333;
    text-align: center;
    line-height: 160px;
  }

  body > .el-container {
    margin-bottom: 40px;
  }

  .el-container:nth-child(5) .el-aside,
  .el-container:nth-child(6) .el-aside {
    line-height: 260px;
  }

  .el-container:nth-child(7) .el-aside {
    line-height: 320px;
  }
</style>
<!--分级样式-->
<script>
  export default {
    data() {
      return {
        data: [{
          label: 'HTML5课程',
          children: [{
            label: '任务一',
            children: [{
              label: '完成html5的代码编写任务'
            }]
          }, {
            label: '任务二',
            children: [{
              label: '完成初级教程的学习问题'
            }]
          }]
        }, {
          label: 'web课程',
          children: [{
            label: '任务一',
            children: [{
              label: '三级 2-1-1'
            }]
          }, {
            label: '任务二',
            children: [{
              label: '三级 2-2-1'
            }]
          }]
        }, {
          label: 'C语言课程',
          children: [{
            label: '二级 3-1',
            children: [{
              label: '三级 3-1-1'
            }]
          }, {
            label: '二级 3-2',
            children: [{
              label: '三级 3-2-1'
            }]
          }]
        }],
        defaultProps: {
          children: 'children',
          label: 'label'
        }
      };
    },
    methods: {
      handleNodeClick(data) {
        console.log(data);
      }
    }
  };
</script>

<!--卡片-->
<style>
  .text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 8px;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  .box-card {
    width: 1180px;
  }
</style>
<!--&lt;!&ndash;tab样式&ndash;&gt;-->
<!--<script>-->
  <!--export default {-->
    <!--data() {-->
      <!--return {-->
        <!--activeName: 'second'-->
      <!--};-->
    <!--},-->
    <!--methods: {-->
      <!--handleClick(tab, event) {-->
        <!--console.log(tab, event);-->
      <!--}-->
    <!--}-->
  <!--};-->
<!--</script>-->



