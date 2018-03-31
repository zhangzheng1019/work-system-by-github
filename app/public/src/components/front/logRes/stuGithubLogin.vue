<template>
    <div id="stuGithubLogin">
        <a class="btn-github" href="/student/loginGit">
            <el-button type="primary">
                <i class="el-icon-fa-github" aria-hidden="true"></i>
                关联 GitHub 
            </el-button>
        </a>
    </div>
</template>
<script>
		import { fetch } from "../../../utils.js"
    export default {
		data () {
			return {
			  version: '1.0.0',
				urlParams: {},
			}
		},
		props: ["userInfo"],
		components: {

    },
		create() {
			
		},
		methods: {
			
		}

	}
</script>
<style scoped>
    #stuGithubLogin{ text-align: center; margin-top: 30px; }
    .btn-github-login{ padding-left: 10%;padding-right: 10%; }
</style>
