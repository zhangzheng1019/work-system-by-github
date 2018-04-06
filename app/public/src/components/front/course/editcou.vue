<template>
    <div id="editCourse">
        <el-button style="padding: 0" type="text" icon="el-icon-edit-outline" size="mini" @click='editDialog=true'>
            编辑
        </el-button>
        <el-dialog title="编辑课程" :visible.sync="editDialog">
            <el-form :model="row" ref="row" :rules="rulesForm" status-icon>
                <el-form-item label="所属年级" :label-width="formLabelWidth" prop="grade">
                    <el-select v-model="row.grade_id" placeholder="请选择年级" disabled>
                        <el-option v-for="item in gradeGroup" :key="item.key" :label="item.value" :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="课程名称" :label-width="formLabelWidth" prop="title">
                    <el-input type="text" v-model="row.title" pla auto-complete="off">
                    </el-input>
                </el-form-item>
                <el-form-item label="课程描述" :label-width="formLabelWidth" prop="desc">
                    <el-input type="textarea" v-model="row.desc">
                    </el-input>
                </el-form-item>
                <el-form-item label="封面图" :label-width="formLabelWidth" prop="thumb">
                    <el-upload class="upload-demo" :multiple='false' action="/upload" :on-success="handleSuccess" :before-upload="beforeUpload" list-type="picture" :show-file-list="false">
                        <el-button size="small" type="primary">
                            点击上传
                        </el-button>
                        <div slot="tip" class="el-upload__tip">
                            只能上传jpg/png文件，且不超过1M
                        </div>
                    </el-upload>
                    <template>
                        <img :src="uploadImage.path" class="preview-pic" v-if='uploadImage.path'/>
                        <img :src="row.thumb" class="preview-pic" v-else/>
                        <span class="preview-pic-desc">
                            {{ uploadImage.name }}
                        </span>
                    </template>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editDialog = false">
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
        uploadImage: { name: '', path:'', width: '', height: ''},
        editDialog: false,
        rulesForm: {
          title: [{
            required: true,
            message: '请输入课程名称',
            triggle: 'blur'
          }],
          desc: [{
            required: true,
            message: '请输入课程描述',
            triggle: 'blur'
          }],
        }
      }
    },
    props: ['gradeGroup','row','userInfo'],
    create() {

    },
    mounted() {

    },
    methods: {
      handleSuccess(file) {
        if(file.data){
          this.uploadImage.path   = file.data.url
          this.uploadImage.name   = file.data.origin_name
          this.uploadImage.width  = file.data.image_width
          this.uploadImage.height = file.data.image_height
          this.row.thumb          = file.data.url
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
      submitCourse() {
        this.$refs['row'].validate((valid) => {
          if(valid){
            let postData = {
              'id' : this.row.id,
              'name' : this.row.title,
              'desc' : this.row.desc,
              'thumb' : this.row.thumb,
              'teacher_id' : this.userInfo.id,
              'grade_id' : this.row.grade_id
            }
            post({
              url:'/course/addCourse',
              data: postData,
              dataType: 'json',
              cb: (data,msg) => {
                this.$message.success(msg)
                this.editDialog = false;
                this.$emit('editcou')
              },
              err:(data,msg) => {
                this.$message.error(msg)
                this.editDialog = false;
              }
            })
          }
        })
      }
    }

  }
</script>
<style scoped>
    #editCourse{ display: inline-block;float: right;}
    .preview-pic{ width: 10%; display: inline-block; margin-right: 10px; }
</style>
