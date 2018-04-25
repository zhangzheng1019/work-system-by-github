<template>
    <div id="seeTask">
    	<el-button type="text" icon="el-icon-view" size="medium" @click.native='getTaskContent'>作业情况</el-button>
			<el-dialog title="查看作业" :visible.sync="dialogTask" width="60%">
					<!-- pre标签：按原样输出，保留所有的空格和换行符。 xmp: 把内部html片段当做字符串输出 -->
					<pre class="remark-content">{{row}}</pre>
					<!-- 教师 -->
					<div class="remark" v-if="userInfo.role=='teacher'">
						<h3 class="remark-title">教师评语：</h3>
						<el-input class="remark-tea" type="textarea" name="res" v-model="remark" :value="afreshRemrk"></el-input>
						<el-button type="primary" size="mini" @click.native="onSubmit">提交评价</el-button>
					</div>
					<!-- 学生 -->
					<div class="remark" v-else-if="(userInfo.role=='student') && (row.student_id==userInfo.id)">
						<h3 class="remark-title">教师评语：</h3>
						<p class="remark-content" v-html="afreshRemrk"></p>
					</div>
			</el-dialog>
    </div>
</template>

<script>
	import { post } from '../../../utils.js'
  export default {
		data () {
			return {
			  dialogTask: false,
			  remark: '',
			}
		},
		props: ['userInfo', 'row'],
		computed: {
			afreshRemrk: function() {
				return this.row.tea_response ? this.row.tea_response : '暂未做出评价'
			}
    },
		create() {

		},
		methods: {
			getTaskContent() {
				let term = {

				}
				// post({
				// 	url: '/task/submitRemark',
				// 	data: term,
				// 	dataType: 'json',
				// 	cb: ( data,msg ) => {
				// 		this.dialogTask = false
				// 		this.$emit("stuList")
				// 		this.$message.success(msg)
				// 	}
				// })
				this.dialogTask = true
			},
			onSubmit() {
				let term = {
					id: this.row.id,
					content: this.remark
				}
				post({
					url: '/task/submitRemark',
					data: term,
					dataType: 'json',
					cb: ( data,msg ) => {
						this.dialogTask = false
						this.$emit("stuList")
						this.$message.success(msg)
					},
					err: (data,msg) => {
						this.$confirm(msg, '提示', {
		          confirmButtonText: '确定',
		          cancelButtonText: '取消',
		          type: 'warning'
		        }).then(() => {
							this.dialogTask = false
							this.$emit("stuList")
		        }).catch(() => {
		          this.$message.info('已取消');          
		        });
					}
				})
			}
		}

	}
</script>

<style scoped>
	.remark{ margin-top: 20px; }
	.remark-title{ margin-bottom: 20px; }
  .remark-tea{ margin: 20px 0 10px; }
  .remark-content{ padding: 15px 20px; background: #f1f1f1; border-radius: 4px;margin-top: 10px; color: #333;line-height: 25px;}
</style>