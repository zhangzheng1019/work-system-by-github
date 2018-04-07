<template>
    <div id="seeTask">
    	<el-button type="text" icon="el-icon-view" size="medium" @click.native='dialogVisible=true'>作业情况</el-button>
			<el-dialog title="查看作业" :visible.sync="dialogVisible" width="60%">
					{{row}}
			</el-dialog>
    </div>
</template>

<script>
	import { post } from '../../../utils.js'
  export default {
		data () {
			return {
			  dialogVisible: false
			}
		},
		props: ['userInfo', 'row'],
		components: {

    },
		create() {

		},
		methods: {
			
		}

	}
</script>

<style scoped>
    
</style>