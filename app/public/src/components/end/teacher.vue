<template>
    <div id="end-teacher">
        <h1 class="title-line">
            教师管理
            <add-teacher  v-on:addtea='getList'>
            </add-teacher>
        </h1>
        <div class="mtb20">
            <!-- <el-form :inline="true" :model="formInline" class="demo-form-inline">
              <el-form-item label="审批人">
                <el-input v-model="formInline.user" placeholder="审批人"></el-input>
              </el-form-item>
              <el-form-item label="活动区域">
                <el-select v-model="formInline.region" placeholder="活动区域">
                  <el-option label="区域一" value="shanghai"></el-option>
                  <el-option label="区域二" value="beijing"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="onSubmit">查询</el-button>
              </el-form-item>
            </el-form> -->
            <template>
                <el-table :data="teacherList" style="width: 100%" border>
                    <el-table-column prop="id" label="ID" width="100">
                    </el-table-column>
                    <el-table-column prop="realname" label="姓名" width="100">
                    </el-table-column>
                    <el-table-column prop="mail" label="邮箱" width="200">
                    </el-table-column>
                    <el-table-column prop="mobile" label="手机号" width="200">
                    </el-table-column>
                    <el-table-column prop="thumb" label="头像" width="100">
                        <template slot-scope="scope">
                            <div class="tea-thumb">
                                <img src="../../assets/github3.png" alt=""/>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="createtime" label="创建时间" width="200">
                    </el-table-column>
                    <el-table-column label="操作" width="180">
                        <template slot-scope="scope">
                            <el-button @click="handleClick(row)" type="text">
                                查看
                            </el-button>
                            <el-button type="text">
                                重置密码
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </div>
        <div class="ptb10">
            <el-pagination v-if='totalPage>
                0'
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
            fetch({
              url: '/teacher/getInfo',
              data: termData,
              cb: (data, msg) => {
                this.teacherList = data.list
                this.totalPage   = data.total
                if (data.total == 0) {
                    this.$message.success("没有数据呢，亲！");
                }else{

                  this.$message.success(msg);
                }
              }
            });
          },
          addtc(){

          },
          handleClick(){

          },
          changePage(val) {
            this.currentPage = val
            this.getList()
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
