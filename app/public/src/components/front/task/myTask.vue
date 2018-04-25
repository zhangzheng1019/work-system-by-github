<template>
    <span id="myTask">
    	<el-button type="primary" icon="el-icon-view" size="mini" round @click.native='getTaskContent'>作业情况</el-button>
			<el-dialog title="查看作业" :visible.sync="dialogTask" width="60%">
					<!-- pre标签：按原样输出，保留所有的空格和换行符。 xmp: 把内部html片段当做字符串输出 -->
					<pre class="remark-content">{{row}}</pre>
					
					<div class="remark">
						<h3 class="remark-title">教师评语：</h3>
						<p class="remark-content" v-html="mytask.tea_response"></p>
					</div>
			</el-dialog>
    </span>
</template>

<script>
	import { post } from '../../../utils.js'
  export default {
		data () {
			return {
			  dialogTask: false,
			  remark: '',
			  mytask: [],
			}
		},
		props: ['userInfo', 'row'],
		methods: {
			getTaskContent() {
				let term = {
					sid: this.userInfo.id,
					cid: this.row.course_id,
					tid: this.row.id
				}
				post({
					url: '/task/getMyRemark',
					data: term,
					dataType: 'json',
					cb: ( data,msg ) => {
						this.dialogTask = true
						this.mytask = data.mytask
					}
				})
			},
		}

	}
</script>

<style scoped>
	#myTask{ margin-left: 20px; }
	.remark{ margin-top: 20px; }
	.remark-title{ margin-bottom: 20px; }
  .remark-content{ padding: 15px 20px; background: #f1f1f1; border-radius: 4px;margin-top: 10px; color: #333;line-height: 25px;}
</style>