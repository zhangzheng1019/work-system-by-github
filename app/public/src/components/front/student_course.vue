<template>
    <div id="student" class="front-wrap">
        <el-row :gutter="18">
            <!--课程标题和描述卡片-->
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>
                        Html5与移动互联网开发
                    </span>
                    <el-button style="float: right; padding: 3px 0" type="text">
                        操作按钮
                    </el-button>
                </div>
                <div v-for="o in 1" :key="o" class="text item">
                    课程描述：这是关于html5与移动互联网开发课程的一条描述。
                </div>
            </el-card>
        </el-row>
        <el-row :gutter="18">
            <!--tab任务完成情况-->
            <template>
                <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane label="已完成" name="first">
                    </el-tab-pane>
                    <el-tab-pane label="未完成" name="second">
                        <!--树级已选课程展示-->
                        <el-tree :data="data" :props="defaultProps" @node-click="handleNodeClick">
                        </el-tree>
                    </el-tab-pane>
                    <el-tab-pane label="已过期" name="third">
                        角色管理
                    </el-tab-pane>
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
