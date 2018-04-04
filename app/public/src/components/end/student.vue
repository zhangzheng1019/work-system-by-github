<template>
    <div id="end-student">
        <h1 class="title-line">
            学生名单
            <!-- <add-student  v-on:addstu='getList'></add-student> -->
        </h1>
        <div class="mtb20">
            <el-form :inline="true" :model="selectForm" class="demo-form-inline">
              <el-form-item label="姓名">
                <el-input v-model="selectForm.name" placeholder="姓名"></el-input>
              </el-form-item>
              <el-form-item label="年级">
                <template>
								  <el-select v-model="selectForm.grade" placeholder="请选择"  @change="getClassArr">
								    <el-option
								      v-for="item in grade"
								      :key="item.key"
								      :label="item.value"
								      :value="item.value">
								    </el-option>
								  </el-select>
								</template>
              </el-form-item>
              <el-form-item label="班级" v-if="selectForm.grade">
                <template>
								  <el-select v-model="selectForm.class" placeholder="请选择">
								    <el-option
								      v-for="item in classes"
								      :key="item.key"
								      :label="item.value"
								      :value="item.value">
								    </el-option>
								  </el-select>
								</template>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="onSubmit()">查询</el-button>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="onBreak()">刷新</el-button>
              </el-form-item>
            </el-form>
            <template>
                <el-table :data="studentList" style="width: 100%" border :stripe='true'>
                    <el-table-column prop="id" label="ID">
                    </el-table-column>
                    <el-table-column prop="realname" label="姓名">
                    </el-table-column>
                    <el-table-column prop="grade" label="年级">
                    </el-table-column>
                    <el-table-column prop="class" label="班级">
                    </el-table-column>
                    <el-table-column prop="mail" label="邮箱">
                    </el-table-column>
                    <el-table-column label="GitHub" >
                      <template slot-scope="scope">
                        <a :href="scope.row.github_info.html_url" target="_blank" title="查看GitHub">{{scope.row.github_info.login}}</a>
                      </template>
                    </el-table-column>
                    <el-table-column label="头像"> 
                        <template slot-scope="scope">
                            <div class="stu-thumb">
                                <img :src="scope.row.thumb" v-if="scope.row.thumb"/>
                                <img src="../../assets/github3.png" v-else/>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="createtime" label="创建时间">
                    </el-table-column>
                    <el-table-column prop="modifytime" label="修改时间">
                    </el-table-column>
                    <!-- <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" @click='resetPwd(scope.row.id)'>
                                重置密码
                            </el-button>
                        </template>
                    </el-table-column> -->
                </el-table>
            </template>
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
    </div>
</template>
<script>
    import {fetch, post} from '../../utils'
    import addStu from  "./student/addStu"

    export default {
        name: 'student',
        data () {
            return {
              studentList: [],
              selectForm: { name:'',grade:'',class:''},
              tclist:"",
              totalPage: 0,
              currentPage: 1,
              grade: [],
              classes: []
            }
        },
        components: {
            "add-student" : addStu
        },
        props:['userInfo'],
        methods: {
          getList() {
            let termData = {
              select: this.selectForm,
              page: this.currentPage,
            };
            this.ajaxGetInfo(termData)
          },
          changePage(val) {
            this.currentPage = val
            this.getList()
          },
          onSubmit() {
            let termData = {
              select: this.selectForm,
              page: this.currentPage
            }
            this.ajaxGetInfo(termData)
          },
          ajaxGetInfo(params) {
          	params['teacherId'] = this.userInfo['id'];
            fetch({
              url: '/student/getInfo',
              data: params,
              cb: (data, msg) => {
                this.studentList = data.list
                this.grade = data.grade
                this.totalPage   = data.total
                if (data.total == 0) {
                   this.$message.error("没有数据呢，亲！");
                }else{
                  this.$message.success(msg);
                }
              }
            });
          },
          getClassArr() {
            post({
              url: '/student/getClassArr',
              data: { grade:this.selectForm.grade },
              dataType: 'json',
              cb: (data,msg) => {
                this.classes = data;
              }
            })
          },
          onBreak() {
          	window.location.reload()
          }

        },
        mounted() {
            this.getList();
        }
    }
</script>
<style scoped>
  .stu-thumb img{ width: 50px;height: 50px; border-radius: 100%; }
</style>
