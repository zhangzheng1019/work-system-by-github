<template>
	<div id="addTeacher" style="display:inline-block;float:right;">
		<el-button type="primary" @click="dialogFormVisible = true">添加</el-button>

		<el-dialog title="添加教师(初始密码：123456)" :visible.sync="dialogFormVisible">
		  <el-form :model="form">
		    <el-form-item label="姓名" :label-width="formLabelWidth">
		      <el-input v-model="form.name" auto-complete="off"></el-input>
		    </el-form-item>
		    <el-form-item label="手机号" :label-width="formLabelWidth">
		      <el-input v-model="form.mobile" auto-complete="off"></el-input>
		    </el-form-item>
		    <el-form-item label="邮箱" :label-width="formLabelWidth">
		      <el-input v-model="form.mail" auto-complete="off"></el-input>
		    </el-form-item>
		  </el-form>
		  <div slot="footer" class="dialog-footer">
		    <el-button @click="dialogFormVisible = false">取 消</el-button>
		    <el-button type="primary" @click.native="teacherAdd()">确 定</el-button>
		  </div>
		</el-dialog>

	</div>
</template>
<script>
	import { post } from '../../utils.js'
	export default {
		data () {
			return {
			  formLabelWidth: "120px",
			  dialogFormVisible: false,
			  form: { name:"", mobile:"", mail:"",}
			}
		},
		components: {
			
		},
		create() {

		},
		methods: {
			teacherAdd() {
				post({
					url: ""
				})
			}
		}
	}
</script>
<style type="text/css">
	
</style>