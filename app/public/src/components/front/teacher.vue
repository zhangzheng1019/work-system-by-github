<template>
    <div id="teacher" class="front-wrap">
        <el-row :gutter="20">
            <el-col :span="4">
                <div class="grid-content">
                  <img src="../../assets/github3.png" alt="" class="person-pic"/>
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
                                <el-col class="course-item" :span="5" v-for="(val, idx) in courseList" :key="idx" :offset="idx%4> 0 ? 1 : 0">
                                    <el-card :body-style="{ padding: '0px' }">
                                    		<div @click="courseLink(val.id)">
	                                        <img :src="val.thumb" class="course-pic" v-if="val.thumb"/>
	                                        <img src="../../assets/course-default.png" class="course-pic" v-else/>
	                                        <div class="course-detail">
	                                            <p class="course-title more-line">
	                                                {{ val.title }}
	                                            </p>
	                                            <p class="course-desc more-line" :title="val.desc">
	                                                {{ val.desc }}
	                                            </p>
	                                        </div>
											                  </div>
                                        <div class="course-bottom bottom clearfix">
                                            <span class="course-number">
                                                已有{{ val.stuNumber }}人加入
                                            </span>
                                            <edit-course :gradeGroup="gradeGroup" :row="val" :userInfo="userInfo" v-on:editcou='getList'>
                                            </edit-course>
                                        </div>
                                    </el-card>
                                </el-col>
                            </el-row>
                        </el-tab-pane>
                    </el-tabs>
                    <add-course :activeName='activeName' :userInfo='userInfo' v-on:addcou='getList'>
                    </add-course>
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
		import editCourse from './course/editcou'

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
				'add-course' : addCourse,
				'edit-course' :editCourse
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
  /*用户*/
  .person-pub{ margin: 10px 0; width: 100%;font-size: 14px; }
  .person-pub i{ font-size: 16px; }
  .person-pic{ width: 100%;max-width:150px;height: 100%; display: inline-block;vertical-align: middle; border-radius: 6px;border: 1px solid #cdcdcd; }
  .person-name{ overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }
  .person-desc{ display: -webkit-box !important; overflow:hidden; text-overflow:ellipsis; word-break:break-all; -webkit-box-orient:vertical; -webkit-line-clamp:2;  }
  .person-email{ overflow:hidden; white-space:nowrap; text-overflow:ellipsis; }

  /*课程*/
  .course-item{ margin-bottom:20px;transition: all 0.2s linear 0s; -webkit-transition: all 0.2s linear 0s; -moz-transition: all 0.2s linear 0s; -ms-transition: all 0.2s linear 0s; cursor: pointer; }
  .course-item:hover { box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -webkit-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -moz-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -ms-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); }

	.course-pic{ width: 100%; display: inline-block;height: 100%; max-height: 100px; }
	.course-title{ min-height:50px; color: #0366d6; font-size: 18px; font-weight: bold; display: -webkit-box !important; overflow:hidden; text-overflow:ellipsis; word-break:break-all; -webkit-box-orient:vertical; -webkit-line-clamp:2;}
	.course-desc{ min-height: 42px; margin-top: 5px;font-size: 14px; display: -webkit-box !important; overflow:hidden; text-overflow:ellipsis; word-break:break-all; -webkit-box-orient:vertical; -webkit-line-clamp:2;}
	.course-detail{ margin: 14px; }
	.course-bottom{ margin: 0 14px 14px; }
	.course-number{ display: inline-block; font-size: 12px; color: #666; }
</style>
