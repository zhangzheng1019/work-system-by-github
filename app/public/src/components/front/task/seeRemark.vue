<template>
    <div id="seeRemark">
    	<div class="remark" v-if="userInfo.role=='teacher'">
        <h3 class="remark-title">教师评语：</h3>
        <el-input class="remark-tea" type="textarea" name="res" v-model="remark"></el-input>
        <el-button type="primary" size="mini" @click.native="onSubmit()">提交评价</el-button>
      </div>
      <div v-else-if="userInfo.role=='student'">
      	<h3 class="remark-title">other Repositories：</h3>
      	<a class="gh-project" v-for="(val,key) in ghRepos" :href="val.gh_url">{{val.gh_name}}</a>
      </div>
    </div>
</template>

<script>
	import { fetch, post } from '../../../utils.js'
  export default {
		data () {
			return {
			  remark: this.row.tea_response,
			  ghRepos:[]
			}
		},
		mounted() {
			this.getTaskContent()
		},
		props: ['userInfo', 'row', 'repos'],
		methods: {
			getTaskContent() {
				let term = {
					'gh_name': this.row.studentInfo.github_info.login,
					'cur_repos': this.repos
				}
				fetch({
					url: '/github/getUserAllRepos',
					data: term,
					dataType: 'json',
					cb: ( data,msg ) => {
						this.ghRepos = data.repos
					}
				})
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
						this.$emit("stuList")
						this.$message.success(msg)
					},
					err: (data,msg) => {
						this.$confirm(msg, '提示', {
		          confirmButtonText: '确定',
		          cancelButtonText: '取消',
		          type: 'warning'
		        }).then(() => {
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
  .remark-tea{ margin: 20px 0 10px; }
  .remark-content{ padding: 15px 20px; background: #f1f1f1; border-radius: 4px;margin-top: 10px; color: #333;line-height: 25px;}
  .gh-project{ line-height: 15px; margin: 4px; font-size: 15px; color: #333; display: inline-block; padding: 10px 10px 8px; border: 1px solid #9292AF; border-radius: 48px;text-decoration: none; }
</style>