<template>
    <div id="addTask">
        <el-button type="primary" icon="el-icon-plus" size="medium" @click.native='dialogVisible=true'>
            发布任务
        </el-button>
        <el-dialog title="发布任务" :visible.sync="dialogVisible" width="60%">
            <el-form :model="addTaskForm" ref="addTaskForm" :rules="rulesForm" status-icon>
                <el-form-item label="任务主题" :label-width="formLabelWidth" prop="name">
                    <el-input type="text" v-model="addTaskForm.name">
                    </el-input>
                </el-form-item>
                <el-form-item label="任务描述" :label-width="formLabelWidth" prop="desc">
                    <quill-editor class="task-desc" ref="myTextEditor" v-model="addTaskForm.desc" :options="editorOption">
                    </quill-editor>
                </el-form-item>
                <el-form-item label="日期" :label-width="formLabelWidth" prop="dates">
                	<div class="task-block">
								    <el-date-picker
                      type="date"
                      placeholder="选择开始时间"
                      v-model='addTaskForm.start_period'
                      size='large'
                      format='yyyy-MM-dd 00:00:00'
                    >
                    </el-date-picker>
                    至
                    <el-date-picker
                      type="date"
                      placeholder="选择结束时间"
                      v-model='addTaskForm.end_period'
                      size='large'
                      format='yyyy-MM-dd 00:00:00'
                    >
                    </el-date-picker>
								  </div>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">
                    取 消
                </el-button>
                <el-button type="primary" @click.native="submitTask">
                    确 定
                </el-button>
            </span>
        </el-dialog>
    </div>
</template>
<script>
  import { post } from '../../../utils.js'
  import { formattime } from '../../../rules.js'
  import { quillEditor } from 'vue-quill-editor'
  export default {
    data () {
      return {
        formLabelWidth: "80px",
        addTaskForm: { name:"", desc:"", start_period:"", end_period:""},
        gradeGroup :[],
        dialogVisible: false,
        rulesForm: {
          name: [{
            required: true,
            message: '请输入任务名称',
            triggle: 'blur'
          }],
          desc: [{
            required: true,
            message: '请输入任务内容',
            triggle: 'blur'
          }],
        },
        editorOption: {
          modules: {
            toolbar: [
							['bold', 'italic'],
							[{ 'align': [] }], 
							[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
							[{ 'color': [] }, { 'background': [] }],
							[{ 'list': 'ordered'}, { 'list': 'bullet' }],
							['link', 'image'],
							['clean']
            ],
          }
        }
      }
    },
    props: ['userInfo'],
    components: {
    	'quill-editor': quillEditor
    },
    mounted() {
      this.getGrade()
    },
    methods: {
    	onEditorChange({ editor, html, text }) {
        this.addTaskForm.desc = html;
      },
      changeGrade(val){
        this.addTaskForm.grade_id = val; 
      },
      submitTask() {
        this.$refs['addTaskForm'].validate((valid) => {
          if(valid){
          	this.addTaskForm.start_period = this.addTaskForm.start_period ? formattime(this.addTaskForm.start_period) : ''
            this.addTaskForm.end_period = this.addTaskForm.end_period ? formattime(this.addTaskForm.end_period) : ''
           
            let postData = {
              'name' : this.addTaskForm.name,
              'desc' : this.addTaskForm.desc,
              'start_period' : this.addTaskForm.start_period,
              'end_period' : this.addTaskForm.end_period,
              'teacher_id' : this.userInfo.id,
              'course_id' : this.$route.params.id,
            }
            post({
              url:'/task/addTask',
              data: postData,
              dataType: 'json',
              cb: (data,msg) => {
                this.$message.success(msg)
                this.dialogVisible = false;
                this.$refs['addTaskForm'].resetFields()
                this.$emit('addtask')
              },
              err:(data,msg) => {
                this.$message.error(msg)
              }
            })
          }
        });
      },
      getGrade(){
        post({
          url: '/course/getGrade',
          dataType:'json',
          cb: (data, msg) => {
            this.gradeGroup = data
          }
        })
      }
    },

  }
</script>
<style scoped>
    .preview-pic{ width: 10%; display: inline-block; margin-right: 10px; }
</style>
