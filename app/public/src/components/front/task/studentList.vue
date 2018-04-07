<template>
   <div id="taskStuList">
    	<el-collapse accordion @change="changeTaskPannel">
        <el-collapse-item v-for="(val,k) in taskList" :key='k' :name="val.id">
          <template slot="title">
            <div class="task-name" v-html="val.name"></div>
          </template>
          <div class="task-desc" v-html="val.desc"></div>
				  <el-button type="success" size="mini" :disabled="receiveAbled" @click.native="receiveTask">领取</el-button>
				  <el-button type="primary" size="mini" :disabled="completeAbled" @click.native="completeTask">完成</el-button>
          <template>
            <el-tabs v-model="activeName" @tab-click="handleClick">
              <el-tab-pane v-for="(v,ko) in studentTypeList" :key='ko' :label="v.label" :name="v.name">
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
                    <el-table-column label="操作" v-if="userInfo.role=='teacher'">
                    	<template slot-scope="props">
                    		<see-content :userInfo="userInfo" :row="props.row"></see-content>
                    	</template>
                    </el-table-column>
                  </el-table>
                </template>
              </el-tab-pane>
            </el-tabs>
          </template>
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
			}
		},
		props:["userInfo","taskList","studentTypeList"],
		create() {

		},
		components:{
			'see-content': seeTask
		},
		methods: {
			getTaskStuList() {
        let term = {
          'course_id': this.$route.params.id,
          'task_id' : this.taskId,
          'active_name': this.activeName
        }
        post({
          url: '/task/getTaskStuList',
          data: term,
          dataType: 'json',
          cb: (data, msg) => {
            this.studentList = data.list
          },
          err: (data, msg) => {
            this.studentList = []
            this.$message.error(msg);
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
      receiveTask(){
      	this.receiveAbled = true
      },
      completeTask(){
      	this.$confirm('此操作不可逆, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$message({
            type: 'success',
            message: '确认成功'
          });
      		this.completeAbled = true
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消'
          });          
        });
      },
		}

	}
</script>

<style scoped>
  .github-url{ color: #409eff; text-decoration: none; }
</style>