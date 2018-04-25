<template>
   <div id="taskStuList">
    	<el-collapse accordion @change="changeTaskPannel">
        <el-collapse-item v-for="(val,k) in taskList" :key='k' :name="val.id">
          <template slot="title">
            <div class="task-name">{{val.name}}&nbsp;&nbsp;&nbsp;&nbsp; 时间：<strong> {{val.startime}} —— {{val.endtime}}</strong></div>
          </template>
          <div class="task-desc" v-html="val.desc"></div>
				  <el-button type="success" size="mini" :disabled="receiveAbled ? receiveAbled : (val.flag >=1)" @click.native="receiveTask(val.id)" v-if="userInfo.role=='student'">领取</el-button>
				  <el-button type="primary" size="mini" :disabled="completeAbled ? completeAbled : (val.flag >=2)" @click.native="completeTask(val.id)" v-if="userInfo.role=='student'">完成</el-button>
          <template>
            <el-tabs v-model="activeName" @tab-click="handleClick">
              <el-tab-pane v-for="(v,ko) in val.typeNum" :key='ko' :label="v.label" :name="v.name">
                <template>
                  <el-table :data="studentList" style="width: 100%">
                    <el-table-column type="expand">
                      <template slot-scope="props">
                        <el-form label-position="left" inline class="demo-table-expand">
                          <el-form-item label="学生名称">
                            <span>{{ props.row.studentInfo.github_info.html_url }}</span>
                          </el-form-item>
                        </el-form>
                      </template>
                    </el-table-column>
                    <el-table-column label="姓名" prop="studentInfo.realname"></el-table-column>
                    <el-table-column label="GitHub">
                      <template slot-scope="props">
                        <a class="github-url" :href="props.row.studentInfo.github_info.html_url" target="_blank" title="查看GitHub">{{props.row.studentInfo.github_info.login}}</a>
                      </template>
                    </el-table-column>
                    <el-table-column prop="studentInfo.grade" label="年级"></el-table-column>
                    <el-table-column prop="studentInfo.class" label="班级"></el-table-column>
                    <el-table-column label="操作">
                    	<template slot-scope="props">
                    		<see-content :userInfo="userInfo" :row="props.row" v-on:stuList="getTaskStuList"></see-content>
                    	</template>
                    </el-table-column>
                  </el-table>
                </template>
              </el-tab-pane>
            </el-tabs>
          </template>
          <div class="ptb10">
              <el-pagination v-if='totalPage>0'
                  layout="prev, pager, next"
                  small
                  :total="totalPage"
                  :current-page='currentPage'
                  @current-change='changePage'>
              </el-pagination>
          </div>
        </el-collapse-item>
      </el-collapse>
   </div>
</template>

<script>
	import { post } from '../../../utils.js'
	import seeTask from './seeTask'
  export default {
		data () {
			return {
        activeName:'finish',
        studentList:[],
        taskId: 0,
        receiveAbled: false,
        completeAbled: false,
        totalPage:0,
        currentPage:1,
			}
		},
		props:["userInfo","taskList"],
		create() {

		},
    mounted() {
      
    },
		components:{
			'see-content': seeTask
		},
		methods: {
			getTaskStuList() {
        let term = {
          'course_id': this.$route.params.id,
          'task_id' : this.taskId,
          'active_name': this.activeName,
          'page': this.currentPage
        }
        post({
          url: '/task/getTaskStuList',
          data: term,
          dataType: 'json',
          cb: (data, msg) => {
            this.studentList = data.list
            this.totalPage = data.total
          },
          err: (data, msg) => {
            this.studentList = []
            this.totalPage = 0
          }
        })
      },
      changeTaskPannel(val) {
        this.taskId = val
        this.activeName = 'finish'
        this.getTaskStuList()
      },
      handleClick(val){
        this.activeName = val.name
        this.getTaskStuList()
      },
      changePage(val){
        this.currentPage = val
        this.getTaskStuList()
      },
      receiveTask(task_id){
        let term = {
          'sid': this.userInfo.id,
          'cid': this.$route.params.id,
          'tid': task_id
        }
        post({
          url: 'task/receiveTask',
          data: term,
          dataType: 'json',
          cb: (data, msg) => {
            this.$message.success('领取成功');
      	    this.receiveAbled = true
            this.getTaskStuList()
            this.$emit('gettask')
          },
          err: (data, msg) => {
            this.$message.warning(msg)
          }
        })
      },
      completeTask(task_id){
        let term = {
          'sid': this.userInfo.id,
          'cid': this.$route.params.id,
          'tid': task_id
        }
      	this.$confirm('此操作不可逆, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          post({
            url: 'task/completeTask',
            data: term,
            dataType: 'json',
            cb: (data, msg) => {
              this.$message.success('确认成功');
      		    this.completeAbled = true
              this.getTaskStuList()
              this.$emit('gettask')
            },
            err: (data, msg) => {
              this.$message.warning(msg)
            }
          })
        }).catch(() => {
          this.$message.info('已取消');          
        });
      },
		}

	}
</script>

<style scoped>
  .github-url{ color: #409eff; text-decoration: none; }
  .task-desc{ margin-bottom: 15px; }
</style>