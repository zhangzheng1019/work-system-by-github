<template>
  <div id="student" class="front-wrap">
    <el-row :gutter="20" >
        <person-info :userInfo="userInfo"></person-info>
        <!--课程内容区-->
        <el-col :span="19">
          <!--已发布的课程-->
          <el-row style="padding-top: 15px">
            <el-col v-if="courseList" :class="(idx%4==3) ? 'mr0 course-item' : 'course-item'" v-for="(val, idx) in courseList" :key="idx" >
              <el-card :body-style="{ padding: '0px' }">
                <div @click="courseLink(val.id)">
                  <div class="coursepic-box"><img :src="val.thumb" class="course-pic" v-if="val.thumb"/>
                  <img src="../../assets/course-default.png" class="course-pic" v-else/></div>
                  <div class="course-detail">
                    <p class="course-title moreline">{{ val.title }}</p>
                    <p class="course-desc moreline" :title="val.desc">{{ val.desc }}</p>
                  </div>
                </div>
                <div class="course-bottom bottom clearfix">
                  <p class="course-teacher oneline" :title="val.teacherInfo.realname">{{ val.teacherInfo.realname ? val.teacherInfo.realname : '' }}</p>
                  <span class="course-number">已有{{ val.stuNumber }}人加入</span>
                  <div class="add-status">
                    <el-button type="primary" size='mini' disabled v-if="val.addStatus==1">已加入</el-button>
                    <el-button type="primary" size='mini' v-else @click="studentAddCourse(val.id)">加入课程</el-button>
                  </div>
                </div>
              </el-card>
            </el-col>
            <el-col :span='8' :offset='8' v-else>
              <div class="courser-not">
                还没有你的课程
              </div>
            </el-col>
          </el-row>
          <div class="ptb10">
              <el-pagination v-if='totalPage>0'
                  layout="prev, pager, next"
                  background
                  :total="totalPage"
                  :current-page='currentPage'
                  @current-change='changePage'>
              </el-pagination>
          </div>
      </el-col>
    </el-row>
  </div>
</template>
<script>
  import { post } from '../../utils.js'
  import personInfo from './logRes/personinfo'

  export default {
    name: 'student',
    data () {
      return {
        courseList: [],
        totalPage: 0,
        currentPage: 1,
      }
    },
    props:['userInfo'],
    components: {
      'person-info' : personInfo
    },
    methods: {
      getList() {
        let termData = {
          id: this.userInfo.id,
          page: this.currentPage
        }
        post({
          url:'/course/getStudentList',
          data: termData,
          dataType: 'json',
          cb: (data,msg) =>{
            this.courseList = data.list
            this.totalPage  = data.total
          }
        })
      },
      changePage(val) {
        this.currentPage = val
        this.getList()
      },
      courseLink(id){
        window.location.href = "/#/course/"+id
      },
      studentAddCourse(courseId){
        let termData = {
          studentId: this.userInfo.id,
          courseId: courseId
        }
        post({
          url:'/student/studentAddCourse',
          data: termData,
          dataType: 'json',
          cb: (data,msg) =>{
            this.$message.success(msg);
            this.getList()
          },
          err: (data,msg) => {
            this.$message.error(msg);
            this.getList()
          }
        })
      }
    },
    mounted() {
      setTimeout(() => {
        this.getList()
      }, 200);
    }
  }
</script>
<style scoped>
  .mr0{ margin-right: 0!important; }
  /*课程*/
  .course-item{ width: 23%; height:15em;margin-right: 2%; margin-bottom:20px;transition: all 0.2s linear 0s; -webkit-transition: all 0.2s linear 0s; -moz-transition: all 0.2s linear 0s; -ms-transition: all 0.2s linear 0s; cursor: pointer; }
  .course-item:hover { box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -webkit-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -moz-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -ms-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); }
  .course-pic{ width: 100%; display: block; height: 100px;}
  .course-title{ color: #0366d6; font-size: 18px; font-weight: bold; }
  .course-desc{ margin-top: 5px;font-size: 14px; height: 2.2rem; }
  .course-detail{ margin: 14px; }
  .course-bottom{ margin: 0 14px 14px; }
  .course-number{ display: inline-block; font-size: 12px; color: #666; }
  .course-teacher:before{ content: "任课教师：";display: inline-block; color: #333;}
  .course-teacher{ font-size: 14px; color: #409eff; }
  .add-status{ display: inline-block;float: right; }
</style>