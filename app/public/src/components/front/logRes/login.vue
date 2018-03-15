<template>
    <div id="login" class="clearfloat">
        <el-button type="primary" class="btn-public" @click.native="dialogSwitch('res')">
            注册
        </el-button>
        <el-button type="primary" class="btn-public" @click.native="dialogSwitch('login')">
            登录
        </el-button>
        <el-dialog :title="dialogStatus=='res' ? '注册' : '登录'" :visible.sync="isDialog" center>
            <template>
                <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane label="我是学生" name="student">
                        我是学生
                    </el-tab-pane>
                    <el-tab-pane label="我是教师" name="teacher">
                        <template>
                            <el-form :model="loginForm" ref="loginForm" :rules="rulesForm" status-icon>
                                <el-form-item type="text" label="邮箱" :label-width="formLabelWidth" prop="email">
                                    <el-input type="text" v-model="loginForm.email" auto-complete="off">
                                    </el-input>
                                </el-form-item>
                                <el-form-item type="text" label="验证码" :label-width="formLabelWidth" prop="code" v-if="dialogStatus=='res'">
                                    <el-input type="code" v-model="loginForm.code" auto-complete="off">
                                    </el-input>
                                    <el-button type="primary" v-if="dialogStatus=='res'" class="send-code" @click.native="sendCode()">
                                        发送验证码
                                    </el-button>
                                </el-form-item>
                                <el-form-item type="password" label="密码" :label-width="formLabelWidth" prop="password">
                                    <el-input type="password" v-model="loginForm.password" auto-complete="off">
                                    </el-input>
                                </el-form-item>
                                <el-form-item type="password" label="确认密码" :label-width="formLabelWidth" prop="respwd" v-if="dialogStatus=='res'">
                                    <el-input type="password" v-model="loginForm.respwd" auto-complete="off">
                                    </el-input>
                                </el-form-item>
                            </el-form>
                        </template>
                    </el-tab-pane>
                </el-tabs>
            </template>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="closeDialog()">
                    取 消
                </el-button>
                <el-button type="primary" @click.native="dialogStatus=='res' ? register() : login()">
                    确 定
                </el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    import { fetch, post } from '../../../utils.js'
    import { check_email,check_pwdlen } from '../../../rules.js'
    export default {
    data () {

      var validateResPassword = (rule, value, callback) => {
          if (value === '') {
              callback(new Error('请再次输入密码'));
          } else if (value !== this.loginForm.password) {
              callback(new Error('两次输入密码不一致!'));
          } else {
              callback();
          }
      }
      return {
        dialogStatus: 'res',
        isDialog: true,  
        activeName: 'teacher',
        formLabelWidth: '80px',
        loginForm: { email:"", password:"", respwd:"",code:""},
        returnCode: '',
        rulesForm: {
          email: [{
            required: true,
            validator: check_email,
            message: '请输入正确的邮箱',
            triggle: 'blur'
          }],
          password: [{
            required: true,
            validator: check_pwdlen,
            triggle: 'blur'
          }],
          respwd: [{
            required: true,
            validator: validateResPassword,
            triggle: 'blur'
          }]
        }
      }
    },
    components: {

    },
    create() {

    },
    methods: {
      dialogSwitch(val) {
        this.$refs['loginForm'].resetFields();
        this.activeName   = 'student';
        this.dialogStatus = val;
        this.isDialog     = true;
      },
      closeDialog() {
        this.isDialog     = false;
      },
      handleClick(tab, event) {
        this.$refs['loginForm'].resetFields();

        // console.log(tab, event);
      },
      sendCode() {
        // 问题： 如何判断邮箱验证通过，，发送按钮才可点击
        post({
          url: '/email/ajaxSend',
          dataType: 'json',
          data: {email: this.loginForm.email},
          cb: (data, msg, errcode) =>  { 
            if(errcode){
              this.returnCode = data
            }else{
              this.$message.error(msg)
            }
          }
        })
      },
      login() {
        let userRole = this.activeName
        if('teacher' == userRole){
          console.log('teacher')
        }else if('student' == userRole){
          console.log('student')
        }
      },
      register() {
        let userRole = this.activeName
        if('teacher' == userRole){
          console.log('teacher')
          // if(this.returnCode !== this.loginForm.code){
          //   this.$message.error("验证码不正确，请确认验证码")
          //   return false
          // }
          post({
            url:'/teacher/addTea',
            dataType: 'json',
            data: this.loginForm,
            cb: (data,msg) => {
                this.$message.success("恭喜你，注册成功");
            },
            err:(data ,msg) => {
                this.$message.error(msg);
              }
          })
        }else if('student' == userRole){
          console.log('student')
        }
      }
    }

  }
</script>
<style scoped>
    .btn-public{ background: transparent; color: #fff; float: right; margin-left: 10px; }
    .el-input{  width: 75%; }
    .send-code{ margin-left: 15px; }
    .dialog-footer{ text-align: right; }
</style>
