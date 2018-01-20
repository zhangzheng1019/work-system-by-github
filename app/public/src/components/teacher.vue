<template>
    <div id="teacher">
        <h1 class="title-line">
            教师管理
            <add-teacher></add-teacher>
        </h1>
        <div style="margin:20px 0px;">
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
              <el-table
                :data="teacherList"
                style="width: 100%"
                height="250"
                border>
                <el-table-column
                  prop="name"
                  label="姓名"
                  width="100">
                </el-table-column>
                <el-table-column
                  prop="mail"
                  label="邮箱"
                  width="200">
                </el-table-column>
                <el-table-column
                  prop="mobile"
                  label="手机号"
                  width="200">
                </el-table-column>
                <el-table-column
                  prop="thumb"
                  label="头像"
                  width="200">
                  <template>
                      <img :src="thumb" />
                  </template>
                </el-table-column>
                <el-table-column
                  prop="createtime"
                  label="创建时间"
                  width="200">
                </el-table-column>
                <el-table-column
                  label="操作"
                  width="180">
                  <template slot-scope="scope">
                    <el-button @click="handleClick(scope.row)" type="text">查看</el-button>
                    <el-button type="text">重置密码</el-button>
                  </template>
                </el-table-column>
              </el-table>
            </template>
        </div>
    </div>
</template>
<script>
    import addTc from  "./teacher/addTea.vue"
    export default {
        data () {
            return {
              teacherList: [{
                'id':1,
                'name':"张政",
                'mail': '447590461@qq.com',
                'mobile': 15533986116,
                'thumb': 15533986116,
                'createtime': '2018-01-14',
              },{
                'id':1,
                'name':"张政",
                'mail': '447590461@qq.com',
                'mobile': 15533986116,
                'thumb': 15533986116,
                'createtime': '2018-01-14',
              }],
              tclist:""
            }
        },
        components: {
            "add-teacher" : addTc
        },
        create() {
            
        },
        methods: {
            addtc(){

            }
        }
    }
</script>
<style type="text/css">
    
</style>