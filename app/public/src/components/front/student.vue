<template>
  <div id="student" class="front-wrap">
    <el-row :gutter="20" >
      <!--上传头像-->
      <el-col :span="5">
          <el-upload
            class="avatar-uploader"
            action="https://jsonplaceholder.typicode.com/posts/"
            :show-file-list="false"
            :on-success="handleAvatarSuccess"
            :before-upload="beforeAvatarUpload">
            <img v-if="imageUrl" :src="imageUrl" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
          </el-upload>

          <!--个人信息展示-->
          <person-info :userInfo="userInfo"></person-info>

           <!--左侧导航-->
        <!--<h5>默认颜色</h5>-->
        <el-menu
          default-active="2"
          class="el-menu-vertical-demo"
          @open="handleOpen"
          @close="handleClose">
          <el-submenu index="1">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>导航一</span>
            </template>
            <el-menu-item-group>
              <template slot="title">分组一</template>
              <el-menu-item index="1-1">选项1</el-menu-item>
              <el-menu-item index="1-2">选项2</el-menu-item>
            </el-menu-item-group>
            <el-menu-item-group title="分组2">
              <el-menu-item index="1-3">选项3</el-menu-item>
            </el-menu-item-group>
            <el-submenu index="1-4">
              <template slot="title">选项4</template>
              <el-menu-item index="1-4-1">选项1</el-menu-item>
            </el-submenu>
          </el-submenu>
          <el-menu-item index="2">
            <i class="el-icon-menu"></i>
            <span slot="title">导航二</span>
          </el-menu-item>
          <el-menu-item index="3" disabled>
            <i class="el-icon-document"></i>
            <span slot="title">导航三</span>
          </el-menu-item>
          <el-menu-item index="4">
            <i class="el-icon-setting"></i>
            <span slot="title">导航四</span>
          </el-menu-item>
        </el-menu>
      </el-col>
      <!--左侧菜单-->




      <!--课程内容区-->
      <el-col :span="19">
        <!--已发布的课程-->
        <el-row>
          <el-col :span="6" v-for="(o, index) in 3" :key="o" :offset="index > 0 ? 2: 0">
            <el-card :body-style="{ padding: '0px' }">
              <img src="./html.jpg" class="image">
              <div style="padding: 14px;">
                <span>HTML5课程</span>
                <div class="bottom clearfix">
                  <time class="time">{{ currentDate }}</time>
                  <el-button type="text" class="button" @click="dialogVisible = true">加入课程</el-button>
                        <!--添加课程弹出提示框-->
                        <el-dialog
                          title="提示"
                          :visible.sync="dialogVisible"
                          width="30%"
                          :before-close="handleClose">
                          <span>您已成功添加该课程</span>
                          <span slot="footer" class="dialog-footer">
                            <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
                            <el-button @click="dialogVisible = false">取 消</el-button>

                          </span>
                        </el-dialog>
                </div>
              </div>
            </el-card>
          </el-col>

          <el-col :span="6" v-for="(o, index) in 3" :key="o" :offset="index > 0 ? 2: 0">
            <el-card :body-style="{ padding: '0px' }">
              <img src="./bhtml5.jpg" class="image">
              <div style="padding: 14px;">
                <span>web课程</span>
                <div class="bottom clearfix">
                  <time class="time">{{ currentDate }}</time>
                  <el-button type="text" class="button">加入课程</el-button>
                </div>
              </div>
            </el-card>
          </el-col>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>
<script>
  import {  post } from '../../utils.js'
  import addCourse from './course/add'
  import editCourse from './course/editcou'
  import personInfo from './logRes/personinfo'

  export default {
    name: 'student',
    data () {
      return {
        courseList: [],
        gradeGroup: [],
        activeName: '',
        totalPage: 0,
        currentPage: 1,
      }
    },
    props:['userInfo'],
    components: {
      'add-course' : addCourse,
      'edit-course' :editCourse,
      'person-info' : personInfo
    },
    methods: {
      handleClick(tab, event) {
        this.activeName = tab.label
        this.currentPage = 1;
        this.getList();
      },
      getList() {
        let termData = {
          id: this.userInfo.id,
          page: this.currentPage,
          grade: this.activeName
        }
        post({
          url:'/course/getList',
          data: termData,
          dataType: 'json',
          cb: (data,msg) =>{
          this.activeName = data['currentGrade']
        this.gradeGroup = data['gradeGroup']
        this.courseList = data['list']
        this.totalPage = data['total']
      }
      })
      },
      changePage(val) {
        this.currentPage = val
        this.getList()
      },
      courseLink(id){
        window.location.href = "/#/course/"+id
      },
    },
    mounted() {
      this.getList()
    }

  }
</script>
<style scoped>
  .mr0{ margin-right: 0!important; }
  /*课程*/
  .course-item{ width: 23%;margin-right: 2%; margin-bottom:20px;transition: all 0.2s linear 0s; -webkit-transition: all 0.2s linear 0s; -moz-transition: all 0.2s linear 0s; -ms-transition: all 0.2s linear 0s; cursor: pointer; }
  .course-item:hover { box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -webkit-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -moz-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); -ms-box-shadow: 0 5px 10px 3px rgba(0,0,0,.1); }
  .course-pic{ width: 100%; display: block; height: 100px;}
  .course-title{ color: #0366d6; font-size: 18px; font-weight: bold; }
  .course-desc{ margin-top: 5px;font-size: 14px; }
  .course-detail{ margin: 14px; }
  .course-bottom{ margin: 0 14px 14px; }
  .course-number{ display: inline-block; font-size: 12px; color: #666; }
</style>

<style>
  #app{
    margin-height:10px;

  }
</style>


















<script>
    export default {
    	name: 'student',
		data () {
			return {
        course:{
          name: 'JavaScript'
        }
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
<!--&lt;!&ndash;分区的样式&ndash;&gt;-->
<!--<style>-->
  <!--.el-header, .el-footer {-->
    <!--background-color: #B3C0D1;-->
    <!--color: #333;-->
    <!--text-align: center;-->
    <!--line-height: 60px;-->
  <!--}-->

  <!--.el-aside {-->
    <!--background-color: #D3DCE6;-->
    <!--color: #333;-->
    <!--text-align: center;-->
    <!--line-height: 200px;-->
  <!--}-->

  <!--.el-main {-->
    <!--background-color: #E9EEF3;-->
    <!--color: #333;-->
    <!--text-align: center;-->
    <!--line-height: 160px;-->
  <!--}-->

  <!--body > .el-container {-->
    <!--margin-bottom: 40px;-->
  <!--}-->

  <!--.el-container:nth-child(5) .el-aside,-->
  <!--.el-container:nth-child(6) .el-aside {-->
    <!--line-height: 260px;-->
  <!--}-->

  <!--.el-container:nth-child(7) .el-aside {-->
    <!--line-height: 320px;-->
  <!--}-->
<!--</style>-->
<!--&lt;!&ndash;分级样式&ndash;&gt;-->
<!--<script>-->
  <!--export default {-->
    <!--data() {-->
      <!--return {-->
        <!--data: [{-->
          <!--label: 'HTML5课程',-->
          <!--children: [{-->
            <!--label: '任务一',-->
            <!--children: [{-->
              <!--label: '完成html5的代码编写任务'-->
            <!--}]-->
          <!--}, {-->
            <!--label: '任务二',-->
            <!--children: [{-->
              <!--label: '完成初级教程的学习问题'-->
            <!--}]-->
          <!--}]-->
        <!--}, {-->
          <!--label: 'web课程',-->
          <!--children: [{-->
            <!--label: '任务一',-->
            <!--children: [{-->
              <!--label: '三级 2-1-1'-->
            <!--}]-->
          <!--}, {-->
            <!--label: '任务二',-->
            <!--children: [{-->
              <!--label: '三级 2-2-1'-->
            <!--}]-->
          <!--}]-->
        <!--}, {-->
          <!--label: 'C语言课程',-->
          <!--children: [{-->
            <!--label: '二级 3-1',-->
            <!--children: [{-->
              <!--label: '三级 3-1-1'-->
            <!--}]-->
          <!--}, {-->
            <!--label: '二级 3-2',-->
            <!--children: [{-->
              <!--label: '三级 3-2-1'-->
            <!--}]-->
          <!--}]-->
        <!--}],-->
        <!--defaultProps: {-->
          <!--children: 'children',-->
          <!--label: 'label'-->
        <!--}-->
      <!--};-->
    <!--},-->
    <!--methods: {-->
      <!--handleNodeClick(data) {-->
        <!--console.log(data);-->
      <!--}-->
    <!--}-->
  <!--};-->
<!--</script>-->

<!--上传头像样式-->
<style>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
</style>

<script>
  export default {
    data() {
      return {
        imageUrl: ''
      };
    },
    methods: {
      handleAvatarSuccess(res, file) {
        this.imageUrl = URL.createObjectURL(file.raw);
      },
      beforeAvatarUpload(file) {
        const isJPG = file.type === 'image/jpeg';
        const isLt2M = file.size / 1024 / 1024 < 2;

        if (!isJPG) {
          this.$message.error('上传头像图片只能是 JPG 格式!');
        }
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 2MB!');
        }
        return isJPG && isLt2M;
      }
    }
  }
</script>

<!--已发布的课程样式-->
<style>
  .time {
    font-size: 13px;
    color: #999;
  }

  .bottom {
    margin-top: 13px;
    line-height: 12px;
  }

  .button {
    padding: 0;
    float: right;
  }

  .image {
    width: 100%;
    display: block;
  }

  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }

  .clearfix:after {
    clear: both
  }
</style>

<script>
  export default {
    data() {
      return {
        currentDate: new Date()
      };
    }
  }
</script>.
<!--添加课程成功-->
<script>
  export default {
    data() {
      return {
        dialogVisible: false
      };
    },
    methods: {
      handleClose(done) {
        this.$confirm('确认关闭？')
          .then(_ => {
          done();
      })
      .catch(_ => {});
      }
    }
  };
</script>

<!--左侧菜单样式-->
<script>
  export default {
    methods: {
      handleOpen(key, keyPath) {
        console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
        console.log(key, keyPath);
      }
    }
  }
</script>
