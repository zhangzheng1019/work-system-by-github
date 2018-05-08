<template>
    <div id="teacher" class="front-wrap">
        <el-row :gutter="20" >
        		<person-info :userInfo="userInfo"></person-info>
            <el-col :span="19">
                <div class="grid-content relative">
                    <el-tabs v-model="activeName" @tab-click="handleClick">
                        <el-tab-pane v-for="(item, index) in gradeGroup" :key="index" :label="item.value" :name="item.value">
                            <el-row style="padding-top: 15px" v-if="courseList">
                                <el-col :class="(idx%4==3) ? 'mr0 course-item' : 'course-item'" v-for="(val, idx) in courseList" :key="idx">
                                    <el-card :body-style="{ padding: '0px' }">
                                    		<div @click="courseLink(val.id)">
	                                        <div class="coursepic-box"><img :src="val.thumb" class="course-pic" v-if="val.thumb"/>
	                                        <img src="../../assets/course-default.png" class="course-pic" v-else/></div>
	                                        <div class="course-detail">
	                                            <p class="course-title oneline">
	                                                {{ val.title }}
	                                            </p>
	                                            <p class="course-desc moreline" :title="val.desc">
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
                            <el-row v-else>
                                <el-col :span='8' :offset='8'>
									                <div class="courser-not">
											              	您还没有发布课程
											            </div>
                                </el-col>
                            </el-row>
                        </el-tab-pane>
                    </el-tabs>

                    <add-course :userInfo='userInfo' :currentTab='activeName' v-on:addcou='getList'>
                    </add-course>
                </div>
                <div class="ptb10">
                    <el-pagination v-if='totalPage>0'
                        layout="prev, pager, next"
                        background
                        :total="totalPage"
                        :page-size="pageSize"
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
		import personInfo from './logRes/personinfo'

    export default {
    	name: 'teacher',
			data () {
				return {
					courseList: [],
					gradeGroup: [],
				  activeName: '',
				  totalPage: 0,
				  pageSize: 10,
	        currentPage: 1,
				}
			},
			props:['userInfo'],
			components: {
				'add-course' : addCourse,
				'edit-course' :editCourse,
				'person-info' : personInfo
	    },
			methods: {
				handleClick(tab, event) {
					this.activeName = tab.label
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
						url:'/course/getTeacherList',
						data: termData,
						dataType: 'json',
						cb: (data,msg) =>{
							this.activeName = data.currentGrade
							this.gradeGroup = data.gradeGroup
							this.courseList = data.list
							this.totalPage = data.total
							this.pageSize = data.pageSize
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
				setTimeout(() => {
          this.getList()
        }, 250);
				
			}

	}
</script>
<style scoped>
	.mr0{ margin-right: 0!important; }
  /*课程*/
	.course-item{ width: 23%;height:14.5em;margin-right: 2%; margin-bottom:20px;transition: all 0.2s linear 0s; -webkit-transition: all 0.2s linear 0s; -moz-transition: all 0.2s linear 0s; -ms-transition: all 0.2s linear 0s; cursor: pointer; }
  .course-item:hover { box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -webkit-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -moz-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -ms-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); }
	.course-pic{ width: 100%; display: block; height: 100px;}
	.course-title{ color: #0366d6; font-size: 18px; font-weight: bold; }
	.course-desc{ margin-top: 5px;font-size: 14px; height: 2.2rem; }
	.course-detail{ margin: 14px; }
	.course-bottom{ margin: 0 14px 14px; }
	.course-number{ display: inline-block; font-size: 12px; color: #666; }
</style>
