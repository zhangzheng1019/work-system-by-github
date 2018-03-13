<template>
    <div id="Front">
        <el-container direction="vertical">
            
            <el-main>
                <router-view :userInfo='userInfo'>
                </router-view>
            </el-main>
            <el-footer>
                Footer
            </el-footer>
        </el-container>
    </div>
</template>
<script>
    import indexHead  from './front/comment/indexHead'
    export default {
        name: 'front',
        data () {
            return {
              userInfo: ''
            }
        },
        components: {
          'index-head': indexHead
        },
        create() {

        },
        methods: {
            
        }

    }
</script>
<style scoped>
    
    .el-footer { background-color: #B3C0D1; color: #333; text-align: center; line-height: 60px; }
  
    .el-aside { background-color: #D3DCE6; color: #333; text-align: center; line-height: 200px; }
  
    .el-main { color: #333; text-align: center; }
  
    body > .el-container { margin-bottom: 40px; }
  
</style>
