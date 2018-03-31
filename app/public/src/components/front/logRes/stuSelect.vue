<template>
    <div id="stuSelect">
        <div class="main-bg">
            <p class="main-title">
                技术 · 共享 · 学习
            </p>
        </div>
        <el-header class="clearfloat">
            <div class="header-left">
                <img src="../../../assets/github3-white.png" alt="默认头像" class="picb header-logo"/>
                <span class="header-title">
                    基于GitHub作业统计系统
                </span>
            </div>
        </el-header>
        <el-dialog title="请填写必要的信息" :visible.sync="stuDialog">
            <el-form :model="personFrom" ref="personFrom" :rules="rulesForm" status-icon>
                <el-form-item label="姓名" :label-width="formLabelWidth" prop="username">
                    <el-input type="text" v-model="personFrom.username" pla auto-complete="off">
                    </el-input>
                </el-form-item>
                <el-form-item label="个人描述" :label-width="formLabelWidth" prop="desc">
                    <el-input type="textarea" v-model="personFrom.desc">
                    </el-input>
                </el-form-item>
                <el-form-item label="年级" required>
                    <template>
                        <el-select v-model="personFrom.grade" placeholder="请选择" @change="getClass">
                            <el-option v-for="item in grades" :key="item" :label="item" :value="item">
                            </el-option>
                        </el-select>
                    </template>
                </el-form-item>
                <el-form-item label="班级" required v-if="this.personFrom.grade">
                    <template>
                        <el-select v-model="personFrom.class" placeholder="请选择">
                            <el-option v-for="item in classes" :key="item" :label="item" :value="item">
                            </el-option>
                        </el-select>
                    </template>
                </el-form-item>
                <el-form-item label="头像" :label-width="formLabelWidth" prop="thumb">
                    <el-upload class="upload-demo" :multiple='false' action="/upload" :on-success="handleSuccess" :before-upload="beforeUpload" list-type="picture" :show-file-list="false">
                        <el-button size="small" type="primary">
                            点击上传
                        </el-button>
                        <div slot="tip" class="el-upload__tip">
                            只能上传jpg/png文件，且不超过1M
                            <br/>
                            <font color="red">
                                注：默认使用GitHub头像
                            </font>
                        </div>
                    </el-upload>
                    <template>
                        <img :src="uploadImage.path" class="preview-pic" v-if='uploadImage.path'/>
                        <img :src="personFrom.thumb" class="preview-pic" v-else/>
                        <span class="preview-pic-desc">
                            {{ uploadImage.name }}
                        </span>
                    </template>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="stuDialog = false">
                    取 消
                </el-button>
                <el-button type="primary" @click.native="submitPerson">
                    确 定
                </el-button>
            </span>
        </el-dialog>
    </div>
</template>
<script>
  import { fetch, post } from '../../../utils.js'
  export default {
    data () {
      return {
        formLabelWidth: "80px",
        personFrom: { username: '', desc:'', thumb:'',grade: '', class:''},
        grades: [],
        classes: [],
        uploadImage: { name: '', path:'', width: '', height: ''},
        stuDialog: true,
        rulesForm: {
          username: [{
            required: true,
            message: '输入您的姓名，方便沟通呦',
            triggle: 'blur'
          }],
        }
      }
    },
    components: {

    },
    mounted() {
      this.getGrade()
    },
    create() {

    },
    methods: {
      getGrade() {
        fetch({
          url: '/student/getGrade',
          dataType: 'json',
          cb:(data, msg)=>{
            this.grades = data.grade;
          }
        })
      },
      getClass() {
        post({
          url: '/student/getClass',
          data: { grade:this.personFrom.grade },
          dataType: 'json',
          cb: (data,msg) => {
            this.classes = data;
          }
        })
      },
      handleSuccess(file) {
        if(file.data){
          this.uploadImage.path   = file.data.url
          this.uploadImage.name   = file.data.origin_name
          this.uploadImage.width  = file.data.image_width
          this.uploadImage.height = file.data.image_height
          this.personFrom.thumb   = file.data.url
          this.$message.success(file.msg)
        }else{
          this.$message.error(file.msg)
        }
      },
      beforeUpload(file){
        const isJPG = (file.type === 'image/jpeg') || (file.type === 'image/png')|| (file.type === 'image/jpg');
        const isLt2M = file.size / 1024 / 1024 < 1;
        if (!isJPG) {
          this.$message.error('上传图片只能是 JPG/JPEG/PNG 格式!');
        }
        if (!isLt2M) {
          this.$message.error('上传图片大小不能超过 1MB!');
        }
        return isJPG && isLt2M;
      },
      submitPerson() {
        this.$refs['personFrom'].validate((valid) => {
          if(valid){
            let postData = {
              'username' : this.personFrom.username,
              'desc' : this.personFrom.desc,
              'thumb' : this.personFrom.thumb,
              'grade' : this.personFrom.grade,
              'class' : this.personFrom.class,
              'role' : 'student'
            }
            post({
              url:'/student/addStu',
              data: postData,
              dataType: 'json',
              cb: (data,msg) => {
                this.$message.success(msg)
                window.location.href="/#/student"
              },
              err:(data,msg) => {
                this.$message.error(msg)
                this.stuDialog = false;
              }
            })
          }
        })
      }
    },

  }
</script>
<style scoped>
    #indexHead{ height: 0!important; }
    .main-bg{ position: fixed;top: 0;bottom: 0;left: 0;right: 0; width: 100%; background: url('../../../assets/it.jpg') no-repeat; background-size: 100% 100%; -moz-background-size: 100% 100%; -webkit-background-size: 100% 100%;}
    .main-title{ position: fixed; top: 50%; left: 50%; z-index: 1; -webkit-transform:translate(-50%, -50%); -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%); height: auto; color: #fff; font-size: 3rem; text-align: center;}
    /*头部*/
    #stuSelect header{ padding: 20px 40px 0; }
    .header-logo{ width: 40px;  float: left; }
    .header-left{ position: absolute; color: #fff; font-size: 1.5rem; line-height: 2.5rem;vertical-align: middle;}
    .header-title{ margin-left: 20px; }
</style>
