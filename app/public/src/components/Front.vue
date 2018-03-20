<template>
    <div id="Front">
        <el-container direction="vertical">
            <el-main>
                <router-view :userInfo='userInfo'>
                </router-view>
            </el-main>
            <el-footer>
                <p class="copyright">Copyright © 2018 · 河北师范大学软件学院2014级GitHub三人组毕业设计 · All Rights Reserved</p>
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
                
            }
        },
        props: ['userInfo'],
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
    .el-footer { background-color: #c1c7cf; color: #333; text-align: center; line-height: 60px; }
    .el-aside { background-color: #D3DCE6; color: #333; text-align: center; line-height: 200px; }
    .el-main { color: #333; text-align: center; }
  
    body > .el-container { margin-bottom: 40px; }

</style>
