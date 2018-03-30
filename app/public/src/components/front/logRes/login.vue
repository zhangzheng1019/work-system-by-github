<template>
    <div id="login" class="clearfloat">
        <div class="login-two">
            <el-button type="primary" class="btn-public" @click.native="dialogSwitch('res')">
                注册
            </el-button>
            <el-button type="primary" class="btn-public" @click.native="dialogSwitch('login')">
                登录
            </el-button>
        </div>
        <div class="relative">
            <el-dialog :title="dialogStatus=='res' ? '注册' : '登录'" :visible.sync="isDialog" center>
                <template>
                    <el-tabs v-model="activeName" @tab-click="handleClick">
                        <el-tab-pane label="我是学生" name="student">
                            <stu-github :userInfo="userInfo"></stu-github>
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
    </div>
</template>
<script>
    import { fetch, post } from '../../../utils.js'
    import stuGitHub from '../logRes/stuGithubLogin'
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
      var validateCode = (rule, value, callback) => {
          if (value === '') {
              callback(new Error('请输入验证码'));
          } else if (value !== this.returnCode) {
              callback(new Error('验证码不正确'));
          } else {
              callback();
          }
      }
      var check_email = (rule, value, callback) => {
          var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          if (rule.required === true) {
              if (!value) {
                  callback(new Error('请输入邮箱地址'));
              } else {
                  setTimeout(() => {
                      if (!filter.test(value)) {
                          callback(new Error('请输入正确的邮箱地址'));
                      } else {
                          fetch({
                            url: '/teacher/uniqueEmail',
                            data: { email:this.loginForm.email },
                            dataType: 'json',
                            cb: (data, msg) => {
                              data ? callback() : callback(new Error('该邮箱已经注册过了'));
                            }
                          })
                      }
                  }, 200);
              }
          } 
      }
      return {
        dialogStatus: 'res',
        isDialog: true,  
        activeName: 'student',
        formLabelWidth: '100px',
        loginForm: { email:"", password:"", respwd:"",code:""},
        returnCode: '',
        rulesForm: {
          email: [{
            required: true,
            validator: check_email,
            triggle: 'blur'
          }],
          password: [
            { required: true, message: '请输入密码', trigger: 'blur' },
            { min: 6, max: 20, message: '长度在 6 到 20 个字符', trigger: 'blur' }
          ],
          respwd: [{
            required: true,
            validator: validateResPassword,
            triggle: 'blur'
          }],
          code: [{
            required: true,
            validator: validateCode,
            triggle: 'blur'
          }]
        }
      }
    },
    props: ["userInfo"],
    components: {
      "stu-github" : stuGitHub
    },
    create() {

    },
    methods: {
      dialogSwitch(val) {
        if (this.$refs['loginForm']!==undefined) {
          this.$refs['loginForm'].resetFields();
        }
        this.activeName   = 'student';
        this.dialogStatus = val;
        this.isDialog     = true;
      },
      closeDialog() {
        this.isDialog     = false;
      },
      handleClick(tab, event) {
        if (this.$refs['loginForm']!==undefined) {
          this.$refs['loginForm'].resetFields();
        }
      },
      sendCode() {
        post({
          url: '/email/ajaxSend',
          dataType: 'json',
          data: { email: this.loginForm.email },
          cb: (data, msg) =>  { 
            if(data){
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
            this.$refs['loginForm'].validate((valid) => {
              if(!valid){
                this.$message.error("邮箱或者密码不正确");
                return false
              }
            })
          }else if('student' == userRole){

          }
      },
      register() {
          let userRole = this.activeName
          if('teacher' == userRole){
            this.$refs['loginForm'].validate((valid) => {
              if(!valid){
                this.$message.error("请填写注册信息");
                return false
              }
              post({
                url:'/teacher/addTea',
                dataType: 'json',
                data: this.loginForm,
                cb: (data,msg) => {
                    this.$message.success("恭喜你，注册成功");
                    this.ajaxSetCookie();
                    // window.location.href="/#/student"
                },
                err:(data ,msg) => {
                  this.$message.error(msg);
                }
              })
            })
          }else if('student' == userRole){
            console.log('student')
          }
      },
      ajaxSetCookie(){
         post({
            url:'/login/teacherResigter',
            dataType: 'json',
            data: {email: this.loginForm.email},
            cb: (data,msg) => {
              alert(msg)
            }
        })
      },
    }

  }
</script>
<style scoped>
    .login-two{ position: relative; right: 0; }
    .btn-public{  color: #fff; float: right; margin-left: 10px; background: transparent; }
    .btn-public:hover{ background-color: #409EFF; }
    .el-input{  width: 60%; }
    .send-code{ margin-left: 15px; }
    .dialog-footer{ text-align: right; }
</style>
