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
                      <add-task :userInfo="userInfo" v-on:addtask='getTaskList'></add-task> 
                    </div>
                  </div>
                </div>
                <task-stu-list :userInfo="userInfo" :taskList="taskList" v-on:gettask="getTaskList"></task-stu-list>
                <div class="ptb10">
                  <el-pagination v-if='totalPage>0'
                      layout="prev, pager, next"
                      background
                      :total="totalPage"
                      :current-page='currentPage'
                      @current-change='changePage'>
                  </el-pagination>
                </div>
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
  import taskStuList from './task/studentList'

  export default {
    data () {
      return {
        courseInfo: [],
        gradeGroup: [],
        taskList: [],
        totalPage:0,
        currentPage:1,
      }
    },
    props: ['userInfo'],
    components: {
      'edit-course' : editcou,
      'add-task' : addTask,
      'task-stu-list' : taskStuList
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
          'course_id': this.$route.params.id,
          'page': this.currentPage
        }
        post({
          url: '/task/getTaskList',
          data: term,
          dataType: 'json',
          cb: (data, msg) => {
            this.taskList = data.list
            this.totalPage = data.total
          },
          err: (data, msg) => {
            this.$message.error(msg);
          }
        })
      },
      changePage(val){
        this.currentPage = val
        this.getTaskList()
      },
     

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
</style>
