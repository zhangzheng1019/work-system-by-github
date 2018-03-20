<template>
    <div id="addCourse">
        <el-button type="primary" icon="el-icon-plus" size="medium" @click.navtive='dialogVisible=true'>
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
                    <el-upload class="upload-demo" :multiple='false' action="/upload" :on-preview="handlePreview" :on-remove="handleRemove" :on-success="handleSuccess" :before-upload="beforeAvatarUpload" :file-list="this.addCourseForm.thumb" list-type="picture">
                        <el-button size="small" type="primary">
                            点击上传
                        </el-button>
                        <div slot="tip" class="el-upload__tip">
                            只能上传jpg/png文件，且不超过1M
                        </div>
                    </el-upload>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">
                    取 消
                </el-button>
                <el-button type="primary" @click.navtive="openDialog">
                    确 定
                </el-button>
            </span>
        </el-dialog>
    </div>
</template>
<script>
    export default {
		data () {
			return {
				formLabelWidth: "80px",
				addCourseForm: { name:"", desc:"", thumb:[{name: 'food.jpeg', url: 'https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100'}]},
			  dialogVisible: true,
			  grade: '添加 '+2014+' 级课程',
			  rulesForm: {
			  	name: [{
			  		required: true,
			  		message: '请输入姓名',
			  		triggle: 'blur'
			  	}],
			  	desc: [{
			  		required: true,
			  		message: '请输入课程描述',
			  		triggle: 'blur'
			  	}],
			  	thumb: [{
			  		required: true,
			  		message: '请上传封面图',
			  		triggle: 'blur'
			  	}],
			  }
			}
		},
		props: ['activeName'],
		components: {

        },
		create() {

		},
		methods: {
			handleRemove(file, fileList) {
        console.log(file, fileList);
      },
      handlePreview(file) {
        console.log(file);
      },
      handleSuccess(file) {
      	// this.addCourseForm.thumb.push(file)
      	console.log(file);
      },
      beforeAvatarUpload(file){
      	const isJPG = (file.type === 'image/jpeg')|| (file.type === 'image/png');
      	const isLt2M = file.size / 1024 / 1024 < 1;
        if (!isJPG) {
          this.$message.error('上传头像图片只能是 JPG 格式!');
        }
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 1MB!');
        }
        return isJPG && isLt2M;
      },
		}

	}
</script>
<style scoped>
    #addCourse{ position: absolute;right: 0; top: 0; }
</style>
