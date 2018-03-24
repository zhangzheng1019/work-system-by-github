<template>
    <div id="editperson">
    		<el-button type="text" class="editper-btn" icon="el-icon-edit-outline" size="mini" @click='editDialog=true'>
            编辑
        </el-button>
        <el-dialog title="编辑信息" :visible.sync="editDialog">
            <el-form :model="personFrom" ref="personFrom" :rules="rulesForm" status-icon>
                <el-form-item label="姓名" :label-width="formLabelWidth" prop="username">
                    <el-input type="text" v-model="personFrom.username" pla auto-complete="off">
                    </el-input>
                </el-form-item>
                <el-form-item label="个人描述" :label-width="formLabelWidth" prop="desc">
                    <el-input type="textarea" v-model="personFrom.desc">
                    </el-input>
                </el-form-item>
                <el-form-item label="头像" :label-width="formLabelWidth" prop="thumb">
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
                        <img :src="personFrom.thumb" class="preview-pic" v-else/>
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
                <el-button type="primary" @click.native="submitPerson">
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
          username: [{
            required: true,
            message: '输入您的姓名，方便沟通呦',
            triggle: 'blur'
          }]
        }
			}
		},
		props: ['userInfo'],
		computed: {
			personFrom() {
				return this.userInfo
			}
		},
		components: {

    },
		create() {

		},
		methods: {
			handleSuccess(file) {
        if(file.data){
          this.uploadImage.path   = file.data.url
          this.uploadImage.name   = file.data.origin_name
          this.uploadImage.width  = file.data.image_width
          this.uploadImage.height = file.data.image_height
          this.userInfo.thumb          = file.data.url
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
              'id' : this.userInfo.id,
              'username' : this.userInfo.username,
              'desc' : this.userInfo.desc,
              'thumb' : this.userInfo.thumb,
              'role' : this.userInfo.role
            }
            post({
              url:'/main/editUserInfo',
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
		#editperson{ display: inline-block; float: right;}
    .preview-pic{ width: 10%; display: inline-block; margin-right: 10px; }
</style>