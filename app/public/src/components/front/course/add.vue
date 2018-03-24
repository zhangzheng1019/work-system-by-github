<template>
    <div id="addCourse">
        <el-button type="primary" icon="el-icon-plus" size="medium" @click.native='dialogVisible=true'>
            发布课程
        </el-button>
        <el-dialog :title="grade" :visible.sync="dialogVisible">
            <el-form :model="addCourseForm" ref="addCourseForm" :rules="rulesForm" status-icon>
                <el-form-item label="课程名称" :label-width="formLabelWidth" prop="name">
                    <el-input type="text" v-model="addCourseForm.name" pla auto-complete="off">
                    </el-input>
                </el-form-item>
                <el-form-item label="课程描述" :label-width="formLabelWidth" prop="desc">
                    <el-input type="textarea" v-model="addCourseForm.desc">
                    </el-input>
                </el-form-item>
                <el-form-item label="封面图" :label-width="formLabelWidth" prop="thumb">
                    <el-upload class="upload-demo" :multiple='false' action="/upload" :on-preview="handlePreview" :on-remove="handleRemove" :on-success="handleSuccess" :before-upload="beforeAvatarUpload" list-type="picture" :show-file-list="false">
                        <el-button size="small" type="primary">
                            点击上传
                        </el-button>
                        <div slot="tip" class="el-upload__tip">
                            只能上传jpg/png文件，且不超过1M
                        </div>
                    </el-upload>
                    <template>
                        <img :src="uploadImage.path" class="preview-pic"/>
                        <span class="preview-pic-desc">
                            {{ uploadImage.name }}
                        </span>
                    </template>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">
                    取 消
                </el-button>
                <el-button type="primary" @click.native="submitCourse">
                    确 定
                </el-button>
            </span>
        </el-dialog>
    </div>
</template>
<script>
    import { post } from '../../../utils.js'
    export default {
    data () {
      return {
        formLabelWidth: "80px",
        addCourseForm: { name:"", desc:"", thumb:"" },
        uploadImage: { name: '', path:'', width: '', height: ''},
        dialogVisible: false,
        rulesForm: {
          name: [{
            required: true,
            message: '请输入课程名称',
            triggle: 'blur'
          }],
          desc: [{
            required: true,
            message: '请输入课程描述',
            triggle: 'blur'
          }]
        }
      }
    },
    props: ['activeName','userInfo'],
    create() {

    },
    computed: {
      grade: function() {
        return '添加 '+this.activeName+' 级课程'
      }
    },
    methods: {
      handleRemove(file, fileList) {
        // 移除上传图片
        console.log(file, fileList);
      },
      handlePreview(file) {
        console.log(file);
      },
      handleSuccess(file) {
        if(file.data){
          this.uploadImage.path   = file.data.url
          this.uploadImage.name   = file.data.origin_name
          this.uploadImage.width  = file.data.image_width
          this.uploadImage.height = file.data.image_height
          this.$message.success(file.msg)
        }else{
          this.$message.error(file.msg)
        }
      },
      beforeAvatarUpload(file){
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
      submitCourse() {
        this.$refs['addCourseForm'].validate((valid) => {
          if(valid){
            let postData = {
              'name' : this.addCourseForm.name,
              'desc' : this.addCourseForm.desc,
              'thumb' : this.uploadImage.path,
              'teacher_id' : this.userInfo.id,
              'grade_id' : this.activeName
            }
            post({
              url:'/course/addCourse',
              data: postData,
              dataType: 'json',
              cb: (data,msg) => {
                this.$message.success(msg)
                this.dialogVisible = false;
                this.$refs['addCourseForm'].resetFields()
                this.$emit('addcou')
              },
              err:(data,msg) => {
                this.$message.error(msg)
              }
            })
          }
        });
      }
    },


  }
</script>
<style scoped>
    #addCourse{ position: absolute;right: 0; top: 0; }
    .preview-pic{ width: 10%; display: inline-block; margin-right: 10px; }
</style>
