<template>
    <div id="Front">
        <!-- <el-container>
        		  <el-header>Header</el-header>
        		  <el-main><router-view :userInfo='userInfo'></router-view></el-main>
        		  <el-footer>Footer</el-footer>
        		</el-container> -->
        <el-button>
            Front Header
        </el-button>
        <router-view :userInfo='userInfo'>
        </router-view>
        <el-button>
            Front Footer
        </el-button>
    </div>
</template>
<script>
    export default {
    	name: 'front',
		data () {
			return {
			  userInfo: ''
			}
		},
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
