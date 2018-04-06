<template>
    <div id="course" class="front-wrap">
        <el-row :gutter="18">
          <el-col :span="20" :offset="2">
            <!--课程标题和描述卡片-->
            <el-card class="box-card">
                <div slot="header" class="clearfloat">
                  <div class="course-thumb">
                    <img :src="courseInfo.thumb" alt="课程封面图" v-if="courseInfo.thumb" class="picb">
                    <img src="../../assets/course-default.png" alt="课程封面图" v-else class="picb">
                  </div>
                  <div class="course-right">
                    <span class="course-title oneline" v-html="courseInfo.title"></span>
                    <span class="course-desc moreline" v-html="courseInfo.desc"></span>
                    <span class="course-grade">{{ courseInfo.grade_id }}</span>
                    <div class="course-edit" v-if="userInfo.role=='teacher'">
                      <edit-course :gradeGroup="gradeGroup" :row="courseInfo" :userInfo="userInfo" v-on:editcou='getCourseInfo'></edit-course>
                    </div>
                    <div class="task-add" v-if="userInfo.role=='teacher'">
                      <add-task :userInfo="userInfo" v-on:addtask='getCourseInfo'></add-task> 
                    </div>
                  </div>
                </div>
                <el-collapse accordion>
                  <el-collapse-item v-for="(val,k) in taskList" :key='k'>
                    <template slot="title">
                      <div class="task-name" v-html="val.name"></div>
                    </template>
                      <div class="task-desc" v-html="val.desc"></div>
                      <template>
                        <el-tabs v-model="activeName" @tab-click="handleClick">
                            <el-tab-pane label="已完成" name="finish">
                                已完成------Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </el-tab-pane>
                            <el-tab-pane label="未完成" name="nofinish">
                                未完成------Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </el-tab-pane>
                            <el-tab-pane label="已过期" name="outdate">
                                已完成------Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </el-tab-pane>
                        </el-tabs>
                      </template>
                  </el-collapse-item>
                </el-collapse>
                
            </el-card>
          </el-col>
          <!-- <el-col :span="8" :offset="1">
            <el-card class="box-card">
                <div slot="header" class="clearfloat">
                  <div class="course-thumb">
                    <img :src="courseInfo.thumb" alt="课程封面图" v-if="courseInfo.thumb" class="picb">
                    <img src="../../assets/course-default.png" alt="课程封面图" v-else class="picb">
                  </div>
                  <div class="course-right">
                    <span class="course-title oneline" v-html="courseInfo.title"></span>
                    <span class="course-desc moreline" v-html="courseInfo.desc"></span>
                    <span class="course-grade">{{ courseInfo.grade_id }}</span>
                    <div class="course-edit" v-if="userInfo.role=='teacher'">
                      <edit-course :gradeGroup="gradeGroup" :row="courseInfo" :userInfo="userInfo" v-on:editcou='getCourseInfo'></edit-course>
                    </div>
                  </div>
                </div>
            </el-card>
          </el-col> -->
        </el-row>
    </div>
</template>
<script>
  import { fetch, post } from '../../utils.js'
  import editcou from './course/editcou'
  import addTask from './task/add'

  export default {
    data () {
      return {
        courseInfo: [],
        gradeGroup: [],
        activeName:'finish',
        taskList: [],
      }
    },
    props: ['userInfo'],
    components: {
      'edit-course' : editcou,
      'add-task' : addTask
    },
    mounted() {
      setTimeout(()=>{
        this.getCourseInfo()
        this.getTaskList()
      },200)
    },
    methods: {
      getCourseInfo() {
        let term = {
          'course_id': this.$route.params.id,
          'teacher_id': this.userInfo.id
        }
        fetch({
          url: '/course/getCourseInfo',
          data: term,
          dataType: 'json',
          cb: (data, msg) => {
            this.courseInfo = data.course
            this.gradeGroup = data.grade
          },
          err: (data, msg) => {
            this.$message.error(msg);
            history.back();
          }  
        })
      },
      getTaskList() {
        let term = {
          // 'role': this.userInfo.role,
          // 'userid': this.userInfo.id,
          'course_id': this.$route.params.id,
          // 'taskType': this.activeName,
        }
        post({
          url: '/task/getTaskList',
          data: term,
          dataType: 'json',
          cb: (data, msg) => {
            this.taskList = data.list
          },
          err: (data, msg) => {
            this.$message.error(msg);
            history.back();
          }
        })
      },
      handleClick(val){
        console.log(val)
      }

    }
  }
</script>
<style scoped>
  .course-thumb{ width: 30%;height: 10rem; border: 1px solid #e4e7ed; border-radius: 10px;padding: 10px; float: left;}
  .course-thumb img{ height: 100%; }
  .course-right{float: left; margin-left: 3%; width: 60%;}
  .course-title, .course-desc, .course-grade{ display: block;margin: 15px 0 10px; }
  .course-title::before{ content: '课程名称：';display: inline-block; }
  .course-desc::before{ content: '课程描述：';display: inline-block; }
  .course-grade::before{ content: '所属年级：';display: inline-block; }
  .course-edit{ float: left; }
  .task-add{ float: right; }
  .task-desc p{ font-size: 16px; }
  .el-collapse-item__content img{ width: 30%!important; display: block; }
</style>
