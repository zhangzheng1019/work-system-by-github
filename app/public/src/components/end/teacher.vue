<template>
    <div id="end-teacher">
        <h1 class="title-line">
            教师管理
            <add-teacher  v-on:addtea='getList'>
            </add-teacher>
        </h1>
        <div class="mtb20">
            <el-form :inline="true" :model="selectForm" class="demo-form-inline">
              <el-form-item label="姓名">
                <el-input v-model="selectForm.name" placeholder="姓名"></el-input>
              </el-form-item>
              <el-form-item label="手机号">
                <el-input v-model="selectForm.mobile" placeholder="手机号"></el-input>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="onSubmit()">查询</el-button>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="onBreak()">刷新</el-button>
              </el-form-item>
            </el-form>
            <template>
                <el-table :data="teacherList" style="width: 100%" border :stripe='true'>
                    <el-table-column prop="id" label="ID">
                    </el-table-column>
                    <el-table-column prop="realname" label="姓名">
                    </el-table-column>
                    <el-table-column prop="mail" label="邮箱">
                    </el-table-column>
                    <el-table-column prop="mobile" label="手机号">
                    </el-table-column>
                    <el-table-column prop="thumb" label="头像">
                        <template slot-scope="scope">
                            <div class="tea-thumb">
                                <img src="../../assets/github3.png" alt=""/>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="createtime" label="创建时间">
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" @click='resetPwd(scope.row.id)'>
                                重置密码
                            </el-button>
                        </template>
                    </el-table-column>
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
    import addTc from  "./teacher/addTea"

    export default {
        name: 'teacher',
        data () {
            return {
              teacherList: [],
              selectForm: { name:'', mobile:''},
              tclist:"",
              totalPage: 0,
              currentPage: 1,
            }
        },
        components: {
            "add-teacher" : addTc
        },
        methods: {
          getList() {
            let termData = {
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
          resetPwd(val) {
            fetch({
              url: '/teacher/resetPwd',
              data: {id:val},
              dataType: 'json',
              cb: (data,msg) => {
                this.$message.success(msg);
              }
            })
          },
          ajaxGetInfo(params) {
            fetch({
              url: '/teacher/getInfo',
              data: params,
              cb: (data, msg) => {
                this.teacherList = data.list
                this.totalPage   = data.total
                if (data.total == 0) {
                    this.$message.error("没有数据呢，亲！");
                }else{
                  this.$message.success(msg);
                }
              }
            });
          },
          onBreak() {
            window.location.reload()
          }


        },
        created() {
            this.getList();
        }
    }
</script>
<style scoped>
  .tea-thumb img{ width: 50px; border-radius: 100%; }
</style>
