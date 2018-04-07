<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("task_model");
        $this->load->model('stutask_model');
        $this->taskType = array(
            array(
                'label' => '已完成',
                'name'  => 'finish',
                'flag'  => 2,
            ),
            array(
                'label' => '未完成',
                'name'  => 'nofinish',
                'flag'  => 1,
            ),
            array(
                'label' => '未领取',
                'name'  => 'nopull',
                'flag'  => -1,
            ),
        );
    }

    /**
     * 添加任务
     */
    public function addTask()
    {
        $teacherId   = $this->input->post("teacher_id");
        $courseId    = $this->input->post("course_id");
        $taskname    = $this->input->post("name");
        $desc        = $this->input->post("desc");
        $startPeriod = $this->input->post("start_period");
        $endPeriod   = $this->input->post("end_period");

        !$teacherId && redirect('/login');
        empty($taskname) && ajax_fail([], "请填写任务名称");
        empty($desc) && ajax_fail([], "请填写任务详细内容");
        empty($startPeriod) && ajax_fail([], "请选择开始时间");
        empty($endPeriod) && ajax_fail([], "请选择结束时间");

        if ($startPeriod > $endPeriod) {
            ajax_fail([], "开始时间不能大于结束时间");
        }

        $data = array(
            'name'      => $taskname,
            'desc'      => $desc,
            'startime'  => $startPeriod,
            'endtime'   => $endPeriod,
            'course_id' => $courseId,
        );
        $addRes = $this->task_model->addTaskInfo($data);
        $this->addStudentTask($courseId, $addRes['insertId']);
        $addRes['status'] ? ajax_success($addRes, "添加成功") : ajax_fail($addRes['status'], '添加失败');
    }

    /**
     * 根据课程id、任务id，将student和task存入wg_stu_task表中
     */
    public function addStudentTask($courseId, $taskId)
    {
        // 根据courseid获取年级
        $this->load->model('course_model');
        $courseInfo    = $this->course_model->getBasicInfo(array("id" => $courseId));
        $courseGrade   = $courseInfo[0]['grade_id'];
        $courseTeacher = $courseInfo[0]['teacher_id'];
        // 根据grade获取学生列表
        $this->load->model('student_model');
        $students    = $this->student_model->getBasicInfo(array('grade' => $courseGrade, 'flag' => 1));
        $studentList = filterData($students['list'], $courseId, 'course_id');
        // 将学生存入wg_stu_task
        $addStatus = null;
        foreach ($studentList as $key => $value) {
            $data['student_id'] = $value['id'];
            $data['task_id']    = $taskId;
            $data['course_id']  = $courseId;
            $addStatus[$key]    = $this->stutask_model->addStuTaskInfo($data);
        }
        return $addStatus;
    }

    /**
     * 得到列表
     * @return [type] [description]
     */
    public function getTaskList()
    {
        // $role     = $this->input->post('role');
        // $userId   = $this->input->post('userid');
        $courseId = $this->input->post('course_id');
        // $taskType = $this->input->post('taskType');

        // !$userId && redirect('/login');

        // $taskFlag = $this->taskType[$taskType];
        $where = array(
            'course_id' => $courseId,
        );
        $taskList     = $this->task_model->getBasicInfo($where);
        $data['list'] = $taskList;
        $data['tabs'] = $this->taskType;
        $data['list'] ? ajax_success($data, '加载成功') : ajax_fail(false, '暂无任务');

    }

    /**
     * 获取任务学生名单
     * @return [type] [description]
     */
    public function getTaskStuList()
    {
        $courseId    = $this->input->post('course_id');
        $taskId      = $this->input->post('task_id');
        $currentName = $this->input->post('active_name');
        $flag        = 0;

        foreach ($this->taskType as $key => $value) {
            if ($value['name'] == $currentName) {
                $flag = $value['flag'];
            }
        }
        $where = array(
            'course_id' => $courseId,
            'task_id'   => $taskId,
            'flag'      => $flag,
        );
        $stuList = $this->stutask_model->getBasicInfo($where);
        $this->load->model('student_model');
        foreach ($stuList as $key => $value) {
            $studentInfo                  = $this->student_model->getBasicInfo(array('id' => $value['student_id']));
            $stuInfo                      = $studentInfo['list'][0];
            $stuInfo['github_info']       = json_decode($stuInfo['github_info'], true);
            $stuList[$key]['studentInfo'] = $stuInfo;
        }
        $data['list'] = $stuList;

        $data['list'] ? ajax_success($data) : ajax_fail(false, '暂无学生名单');
    }
}
