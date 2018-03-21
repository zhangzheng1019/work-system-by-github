<template>
    <div id="teacher" class="front-wrap">
        <el-row :gutter="20">
            <el-col :span="3" :offset="1">
                <div class="grid-content">
                	<img src="../../assets/github3.png" alt="" class="person-pic">
                	<p class="person-pub person-name one-line"><i class="el-icon-news">&nbsp;</i>{{ userInfo.username }}</p>
                	<p class="person-pub person-desc more-line" :title="userInfo.desc"><i class="el-icon-edit-outline">&nbsp;</i>{{ userInfo.desc }}</p>
                	<p class="person-pub person-email one-line"><i class="el-icon-message">&nbsp;</i>{{ userInfo.email }}</p>
                </div>
            </el-col>
            <el-col :span="18" :offset="1">
                <div class="grid-content relative">
									<el-tabs v-model="activeName" @tab-click="handleClick">
								    <el-tab-pane v-for="(item, index) in gradeGroup" :key="index" :label="item.value" :name="item.value">
								    	<el-row style="padding-top: 15px">
											  <el-col class="course-item" :span="5" v-for="(val, idx) in courseList" :key="idx" :offset="idx%4 > 0 ? 1 : 0">
												    <el-card :body-style="{ padding: '0px' }" @click.native="courseLink(val.id)">
												      <img :src="val.thumb" class="course-pic" v-if="val.thumb">
												      <img src="../../assets/course-default.png" class="course-pic" v-else>
												      <div class="course-detail">
												        <span class="course-title more-line">{{val.title}}</span>
												        <span class="course-desc more-line" :title="val.desc">{{val.desc}}</span>
												        <div class="bottom clearfix">
												          <span class="course-number">已有{{val.stuNumber}}人加入</span>
												        </div>
												      </div>
												    </el-card>
											  </el-col>
											</el-row>
								    </el-tab-pane>
								  </el-tabs>
									<add-course :activeName='activeName' :userInfo='userInfo' v-on:addcou='getList'></add-course>
                </div>
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
		import {  post } from '../../utils.js'
		import addCourse from './course/add'

    export default {
    	name: 'teacher',
		data () {
			return {
				courseList: [],
				gradeGroup: [],
			  activeName: '',
			  totalPage: 0,
        currentPage: 1,
			}
		},
		props:['userInfo'],
		components: {
			'add-course' : addCourse
    },
		methods: {
			handleClick(tab, event) {
				this.currentPage = 1;
				this.getList();
			},
			getList() {
				let termData = {
					id: this.userInfo.id,
					page: this.currentPage,
					grade: this.activeName
				}
				post({
					url:'/course/getList',
					data: termData,
					dataType: 'json',
					cb: (data,msg) =>{
						this.activeName = data['currentGrade']
						this.gradeGroup = data['gradeGroup']
						this.courseList = data['list']
						this.totalPage = data['total']
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
		},
		mounted() {
			this.getList()
		}

	}
</script>
<style scoped>

	.grid-content { }

  /*用户*/
  .person-pub{ margin: 10px 0; width: 100%;font-size: 14px; }
  .person-pub i{ font-size: 16px; }
  .person-pic{ width: 100%;max-width:150px;height: 100%; display: inline-block;vertical-align: middle; border-radius: 6px;border: 1px solid #cdcdcd; }
  .person-name{  }
  .person-desc{  }
  .person-email{  }

  /*课程*/
  .course-item{ margin-bottom:20px;transition: all 0.2s linear 0s; -webkit-transition: all 0.2s linear 0s; -moz-transition: all 0.2s linear 0s; -ms-transition: all 0.2s linear 0s; cursor: pointer; }
  .course-item:hover { box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -webkit-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -moz-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -ms-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); }

	.course-pic{ width: 100%; display: inline-block;height: 100%; max-height: 100px; }
	.course-title{ min-height:50px; color: #0366d6; font-size: 18px; font-weight: bold;}
	.course-desc{ min-height: 42px; margin: 5px 0 10px 0;font-size: 14px;}
	.course-detail{ padding: 14px; }
	.course-number{ display: inline-block; font-size: 12px; color: #666; }
</style>
