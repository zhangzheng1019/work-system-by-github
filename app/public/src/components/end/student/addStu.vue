<template>
    <div id="end-addTeacher" class="add">
        <el-button type="primary" @click.native="openDialog()">
            添加
        </el-button>
        <el-dialog title="添加教师" :visible.sync="dialogFormVisible">
            <el-form :model="addTeaForm" ref="addTeaForm" :rules="rulesForm" status-icon>
                <el-form-item type="text" label="姓名" :label-width="formLabelWidth" prop="name">
                    <el-input type="text" v-model="addTeaForm.name" auto-complete="off">
                    </el-input>
                </el-form-item>
                <el-form-item type="number" label="手机号" :label-width="formLabelWidth" prop="mobile">
                    <el-input type="number" v-model="addTeaForm.mobile" auto-complete="off">
                    </el-input>
                </el-form-item>
                <el-form-item label="邮箱" :label-width="formLabelWidth" prop="email">
                    <el-input type="text" v-model="addTeaForm.email" auto-complete="off">
                    </el-input>
                </el-form-item>
                <el-form-item label="密码" :label-width="formLabelWidth" prop="password">
                    <el-input type="password" v-model="addTeaForm.password" auto-complete="off">
                    </el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">
                    取 消
                </el-button>
                <el-button type="primary" @click.native="teacherAdd()">
                    确 定
                </el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
	import { post } from '../../../utils.js'
	import { check_email, check_phone, check_pwdlen } from '../../../rules.js'
	export default {
		data () {
			return {
			  formLabelWidth: "80px",
			  dialogFormVisible: false,
			  addTeaForm: { name:"", mobile:"", email:"",password:""},
			  rulesForm: {
			  	name: [{
			  		required: true,
			  		message: '请输入姓名',
			  		triggle: 'blur'
			  	}],
			  	mobile: [{
			  		required: true,
			  		validator: check_phone,
			  		message: '请输入正确的手机号',
			  		triggle: 'blur'
			  	}],
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
			  }
			}
		},
		create() {

		},
		methods: {
			openDialog() {
				if (this.$refs['addTeaForm']!==undefined) {
					this.$refs['addTeaForm'].resetFields();
				}
				this.dialogFormVisible = true;
			},
			teacherAdd() {
				this.$refs['addTeaForm'].validate((valid) => {
					if(valid){
						post({
							url: "/teacher/addTea",
							data: this.addTeaForm,
							dataType: 'json',
							cb: (data, msg) => {
								this.dialogFormVisible = false
								this.$message.success(msg)
								this.$refs['addTeaForm'].resetFields()
								this.$emit('addtea')
		          },
							err: (data, msg) => {
								this.$message.error(msg);
							}
						})
					}
				})
			}
		}
	}
</script>
<style scoped>

</style>
