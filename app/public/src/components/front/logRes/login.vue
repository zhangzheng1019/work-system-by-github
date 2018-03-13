<template>
    <div id="login" class="clearfloat">
        <el-button type="primary" class="btn-public" @click.native="dialogSwitch('res')">
            注册
        </el-button>
        <el-button type="primary" class="btn-public" @click.native="dialogSwitch('login')">
            登录
        </el-button>
        <el-dialog :title="dialogStatus=='res' ? '注册' : '登录'" :visible.sync="isDialog">
            <template>
                <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane label="我是学生" name="first">
                        我是学生
                    </el-tab-pane>
                    <el-tab-pane label="我是教师" name="second">
                    	<template>
                        <el-form :model="loginForm" ref="loginForm" :rules="rulesForm" status-icon>
                            <el-form-item type="text" label="邮箱" :label-width="formLabelWidth" prop="email">
                                <el-input type="text" v-model="loginForm.email" auto-complete="off" :label-width="inputWidth">
                                </el-input>
                            </el-form-item>
                            <el-form-item type="text" label="密码" :label-width="formLabelWidth" prop="password">
                                <el-input type="text" v-model="loginForm.password" auto-complete="off">
                                </el-input>
                            </el-form-item>
                            <el-form-item v-if="dialogStatus=='res'" type="text" label="确认密码" :label-width="formLabelWidth" prop="respwd">
                                <el-input type="text" v-model="loginForm.respwd" auto-complete="off">
                                </el-input>
                            </el-form-item>
                            <el-form-item v-if="dialogStatus=='res'" type="text" label="验证码" :label-width="formLabelWidth" prop="code">
                                <el-input type="code" v-model="loginForm.code" auto-complete="off">
                                </el-input>
                                <el-button type="primary" v-if="dialogStatus=='res'">
                                    发送验证码
                                </el-button>
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
                <el-button type="primary" @click.native="closeDialog()">
                    确 定
                </el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
		import { check_email } from '../../../rules.js'
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
			  dialogStatus: '',
			  isDialog: false,  
				activeName: 'first',
				formLabelWidth: '80px',
                inputWidth: '100px',
				loginForm: { email:"", password:"", respwd:"",code:""},
				code: '',
				rulesForm: {
			  	email: [{
			  		required: true,
			  		validator: check_email,
			  		message: '请输入正确的邮箱',
			  		triggle: 'blur'
			  	}],
			  	password: [{
			  		required: true,
			  		message: '请输入密码',
			  		triggle: 'blur'
			  	}],
			  	respwd: [{
			  		required: true,
			  		validator: validateResPassword,
			  		triggle: 'blur'
			  	}],
			  }
			}
		},
		components: {

    },
		create() {

		},
		methods: {
			dialogSwitch(val) {
				this.dialogStatus = val;
				this.isDialog     = true;
			},
			closeDialog() {
				this.activeName   = 'first';
				this.isDialog     = false;
                this.$refs['loginForm'].resetFields();
			},
			handleClick(tab, event) {
        console.log(tab, event);
      }
		}

	}
</script>
<style scoped>
    .btn-public{ background: transparent; color: #fff; float: right; margin-left: 10px; }
</style>
