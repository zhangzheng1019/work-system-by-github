<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("task_model");
        $this->taskType = array(
            'outdate'  => -1,
            'nofinish' => 1,
            'finish'   => 2,
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

        $addRes['status'] ? ajax_success($addRes, "添加成功") : ajax_fail($addRes['status'], '添加失败');
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
        $data['list'] ? ajax_success($data, '加载成功') : ajax_fail(false, '暂无数据');

    }

}
