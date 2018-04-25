<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends CI_Controller
{
    const LIMIT = 10;
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

        $data['task_id']   = $taskId;
        $data['course_id'] = $courseId;
        foreach ($studentList as $key => $value) {
            $data['student_id'] = $value['id'];
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
        $courseId = $this->input->post('course_id');
        $page     = $this->input->post('page');
        $where    = array(
            'course_id' => $courseId,
        );
        $taskList = $this->task_model->getBasicInfo($where);
        foreach ($taskList as $k => $v) {
            $taskList[$k]['startime'] = date('Y-m-d', strtotime($v['startime']));
            $taskList[$k]['endtime']  = date('Y-m-d', strtotime($v['endtime']));
            $taskList[$k]['typeNum']  = $this->getStuTaskNum($v['course_id'], $v['id']);
        }
        // 处理当前学生的任务状态
        if ($_COOKIE['userrole'] == 'student') {
            $studentId = $_COOKIE['userid'];
            foreach ($taskList as $key => $value) {
                $studentTaskList        = $this->stutask_model->getBasicInfo(array('task_id' => $value['id'], 'student_id' => $studentId));
                $taskList[$key]['flag'] = $studentTaskList[0]['flag']; //代表学生该任务的状态
            }
        }
        $offset        = ($page - 1) * self::LIMIT;
        $data['total'] = count($taskList);
        $data['list']  = array_slice($taskList, $offset, self::LIMIT);
        $data['list'] ? ajax_success($data, '加载成功') : ajax_fail(false, '暂无任务');

    }

    /**
     * 统计作业类型人数
     * @return [type] [description]
     */
    public function getStuTaskNum($courseId, $taskId)
    {
        $where = array(
            'course_id' => $courseId,
            'task_id'   => $taskId,
        );
        $data = $this->taskType;
        foreach ($this->taskType as $key => $value) {
            $where['flag']       = $value['flag'];
            $data[$key]['label'] = $value['label'] . '（' . $this->stutask_model->getTotalNum($where) . '）';
        }
        return $data;
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
        $page        = $this->input->post('page') ? $this->input->post('page') : 1;
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

        $offset = ($page - 1) * self::LIMIT;

        $stuList = $this->stutask_model->getBasicInfo($where, $offset, $limit);
        // 关联学生
        $this->load->model('student_model');
        foreach ($stuList as $key => $value) {
            $studentInfo                  = $this->student_model->getBasicInfo(array('id' => $value['student_id']));
            $stuInfo                      = $studentInfo['list'][0];
            $stuInfo['github_info']       = json_decode($stuInfo['github_info'], true);
            $stuList[$key]['studentInfo'] = $stuInfo;
        }

        $data['total'] = count($stuList);
        $data['list']  = array_slice($stuList, $offset, self::LIMIT);

        $data['list'] ? ajax_success($data) : ajax_fail(false, '暂无学生名单');
    }

    /**
     * 学生领取任务
     * @return [type] [description]
     */
    public function receiveTask()
    {
        $sid = $this->input->post('sid');
        $cid = $this->input->post('cid');
        $tid = $this->input->post('tid');

        // 判断任务时间
        $where = array(
            'id' => $tid,
        );
        $taskInfo   = $this->task_model->getBasicInfo($where);
        $now        = date('Y-m-d H:m:s');
        $taskStatus = diff_date($now, $taskInfo[0]['startime'], $taskInfo[0]['endtime']);
        if (!$taskStatus) {
            ajax_fail(false, '任务已经结束,不能再领取了');
        }

        $data['flag'] = 1;
        $status       = $this->stutask_model->modifyTaskStatus($sid, $cid, $tid, $data);
        ajax_success($status);
    }

    /**
     * 学生完成任务
     * @return [type] [description]
     */
    public function completeTask()
    {
        $sid = $this->input->post('sid');
        $cid = $this->input->post('cid');
        $tid = $this->input->post('tid');

        // 判断任务时间
        $where = array(
            'id' => $tid,
        );
        $taskInfo   = $this->task_model->getBasicInfo($where);
        $now        = date('Y-m-d H:m:s');
        $taskStatus = diff_date($now, $taskInfo[0]['startime'], $taskInfo[0]['endtime']);
        if (!$taskStatus) {
            ajax_fail(false, '任务已经结束了');
        }

        $data['flag'] = 2;
        $status       = $this->stutask_model->modifyTaskStatus($sid, $cid, $tid, $data);
        ajax_success($status);
    }

    /**
     * 提交评语
     * @return [type] [description]
     */
    public function submitRemark()
    {
        $id      = $this->input->post('id');
        $content = $this->input->post('content') ? $this->input->post('content') : '';

        $data = array(
            'tea_response' => $content,
        );
        $status = $this->stutask_model->updateStuTaskInfo($id, $data);

        $status ? ajax_success($status, '感谢您的评判') : ajax_fail($status, '您未做修改');
    }
}
