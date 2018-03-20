<template>
    <div id="teacher" class="front-wrap">
        <el-row :gutter="20">
            <el-col :span="5">
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
								    <el-tab-pane label="用户管理" name="first">
								    	<el-row style="padding-top: 15px">
											  <el-col class="course-item" :span="5" v-for="(val, index) in 10" :key="val" :offset="index%4 > 0 ? 1 : 0">
											    <el-card :body-style="{ padding: '0px' }">
											      <img src="../../assets/logo.png" class="course-pic">
											      <div class="course-detail">
											        <span class="course-title more-line">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
											        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
											        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
											        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
											        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
											        <div class="bottom clearfix">
											          <span class="course-number">已有300人加入</span>
											        </div>
											      </div>
											    </el-card>
											  </el-col>
											</el-row>
								    </el-tab-pane>
								    <el-tab-pane label="配置管理" name="second">配置管理</el-tab-pane>
								    <el-tab-pane label="角色管理" name="third">角色管理</el-tab-pane>
								    <el-tab-pane label="定时任务补偿" name="fourth">定时任务补偿</el-tab-pane>
								  </el-tabs>
									<add-course :activeName='activeName'></add-course>
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
		import { fetch } from '../../utils.js'
		import addCourse from './course/add'

    export default {
    	name: 'teacher',
		data () {
			return {
				gradeGroup: [],
			  activeName: 'first',
			  totalPage: 40,
        currentPage: 1,
			}
		},
		props:['userInfo'],
		components: {
			'add-course' : addCourse
    },
		create() {
			this.getGradeGroup()
		},
		methods: {
			handleClick(tab, event) {
				console.log(tab,event)
			},
			getGradeGroup() {
				fetch({
					url:'/course/getGradeGroup',
					data: {id: this.userInfo.id },
					dataType: 'json',
					cb: (data,msg) =>{
						this.gradeGroup = data;
					}
				})
			},
      changePage(val) {
        this.currentPage = val
        this.getList()
      },
		}

	}
</script>
<style scoped>

	.grid-content { }

  /*用户*/
  .person-pub{ margin: 10px 0; width: 100%;font-size: 14px; }
  .person-pub i{ font-size: 16px; }
  .person-pic{ width: 100%;max-width:200px;height: 100%; display: inline-block;vertical-align: middle; border-radius: 6px;border: 1px solid #cdcdcd; }
  .person-name{  }
  .person-desc{  }
  .person-email{  }

  /*课程*/
  .course-item{ margin-bottom:20px;transition: all 0.2s linear 0s; -webkit-transition: all 0.2s linear 0s; -moz-transition: all 0.2s linear 0s; -ms-transition: all 0.2s linear 0s; }
  .course-item:hover { box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -webkit-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -moz-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -ms-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); }

	.course-pic{ width: 100%; display: inline-block;height: 100%; max-height: 150px; }
	.course-detail{ padding: 14px; }
	.course-number{ display: inline-block; margin: 10px 0; font-size: 12px; color: #666; }
</style>
