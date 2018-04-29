<template>
    <span id="myTask">
    	<a :href="row.gh_url" target="_blank"><el-button type="primary" icon="el-icon-view" size="mini">我的作业情况</el-button></a>
    	<el-tooltip placement="top">
			  <div slot="content">{{tea_response}}</div>
			  <el-button type="primary" icon="el-icon-view" size="mini">我的作业评语</el-button>
			</el-tooltip>
    </span>
</template>

<script>
	import { post } from '../../../utils.js'
  export default {
		data () {
			return {

			}
		},
		props: ['userInfo', 'row'],
		computed: {
			tea_response: function(){
				return this.row.tea_response ? this.row.tea_response : '教师暂未做出评价'
			}
		}

	}
</script>

<style scoped>
	#myTask{ margin-left: 20px; }
	.remark{ margin-top: 20px; }
	.remark-title{ margin-bottom: 20px; }
  .remark-content{ padding: 15px 20px; background: #f1f1f1; border-radius: 4px;margin-top: 10px; color: #333;line-height: 25px;}
</style>