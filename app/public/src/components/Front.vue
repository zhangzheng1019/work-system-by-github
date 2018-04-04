<template>
    <div id="Front">
        <el-container direction="vertical">
            <index-head :userInfo='userInfo'>
            </index-head>
            <el-main>
                <transition name="move" mode="out-in"><router-view :userInfo='userInfo'>
                </router-view></transition>
            </el-main>
            <el-footer>
                <p class="copyright">
                    Copyright © 2018 · 河北师范大学软件学院2014级GitHub三人组毕业设计 · All Rights Reserved
                </p>
            </el-footer>
        </el-container>
    </div>
</template>
<script>
    import $ from 'jquery'
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
            
        },
        mounted() {
            
        }


    }
</script>
<style scoped>
    body > .el-container { margin-bottom: 40px; }
    .el-main { color: #333;  height: 100%;}
    .el-footer { background-color: #c1c7cf; color: #333; text-align: center; line-height: 60px; }

    /*Front 子页面公共样式*/
    .front-wrap { padding: 10px 5%; }
</style>
