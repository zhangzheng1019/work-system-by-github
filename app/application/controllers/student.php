<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{
    const OFFSET = 10;
    const EXPIRES = 604800; //7天
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("student_model");
        $this->load->library('session');
    }
    
    /**
     * 获取学生信息
     * @return [type] [description]
     */
    public function getInfo()
    {
        $where  = [];
        $page   = $this->input->get('page') ? $this->input->get('page') : 1;
        $select = $this->input->get('select') ? $this->input->get('select') : '';
        if ($select) {
            $select['name'] && $where['realname'] = $select['name'];
            $select['grade'] && $where['grade']   = $select['grade'];
            $select['class'] && $where['class']   = $select['class'];
        }
        $teacherId = $this->input->get('teacherId') ? $this->input->get('teacherId') : 0;
        if (!$teacherId) {
            ajax_fail(false, '请登录', 10000);
        }
        $limit           = ($page - 1) * self::OFFSET;
        $data            = $this->student_model->getBasicInfo($where, $limit, self::OFFSET);
        $list            = filterData($data['list'], $teacherId, 'teacher_id');
        $grade           = $this->student_model->studentGroupBy('grade');
        $class           = $this->student_model->studentGroupBy('class');
        $result['total'] = count($list);
        $result['grade'] = $grade;
        $result['class'] = $class;
        $result['list']  = $list;
        ajax_success($result, "加载成功");
    }

    /**
     * 学生加入课程
     * @return [type] [description]
     */
    public function studentAddCourse()
    {
        $studentId = $this->input->post('studentId');
        $courseId  = $this->input->post('courseId');
        if (!$studentId) {
            ajax_fail(false, '请登录', 20000);
        }
        if (!$courseId) {
            ajax_fail(false, '无效的课程id');
        }
        $status = $this->student_model->updateStuByStuIdAndCourseId($studentId, $courseId);
        $status ? ajax_success($status, '加入成功，开启你的学习之路吧！') : ajax_fail(false, '加入失败了呀！');
    }

    /**
     * 添加学生信息
     */
    public function addStu()
    {
        $data   = array();
        $id     = $this->input->post('id');
        $status = false;
        // if ($id) {
        //     // 修改
        //     $teacherInfo      = $this->student_model->getBasicInfo(array('id' => $id));
        //     $data             = $teacherInfo['list'];
        //     $data['realname'] = isset($_POST['name']) ? $_POST['name'] : $data['realname'];
        //     $data['mobile']   = isset($_POST['mobile']) ? $_POST['mobile'] : $data['mobile'];
        //     $data['thumb']    = isset($_POST['thumb']) ? $_POST['thumb'] : $data['thumb'];
        //     $status           = $this->student_model->updateTeacherInfo($id, $data);
        //     $status ? ajax_success($status, '操作成功') : ajax_fail(false, '你没有修改呦！');
        // } else {
        //添加
        $data['realname']    = isset($_POST['username']) ? $_POST['username'] : '';
        $data['github_info'] = json_encode($this->session->userdata('gbinfo'));
        $data['grade']       = isset($_POST['grade']) ? $_POST['grade'] : '';
        $data['class']       = isset($_POST['class']) ? $_POST['class'] : '';
        $data['thumb']       = isset($_POST['thumb']) ? $_POST['thumb'] : '';
        $status              = $this->student_model->addStudentInfo($data);
        $status['status'] ? ajax_success($status['insertId'], '操作成功') : ajax_fail(false, '操作失败');
        // }

    }

    /**
     * 获取年级
     * @return [type] [description]
     */
    public function getGrade()
    {
        $this->config->load("classids", true);
        $ids   = $this->config->item('classids');
        $i     = 0;
        $grade = [];
        foreach ($ids as $key => $value) {
            $grade['grade'][$i] = $key;
            $i++;
        }
        ajax_success($grade);
    }
    /**
     * 获取班级
     * @return [type] [description]
     */
    public function getClass()
    {
        $grade = $this->input->post('grade');
        if (!$grade) {
            ajax_fail(false, '无效的年级' . $grade);
        }
        $this->config->load("classids", true);
        $ids = $this->config->item('classids');

        ajax_success($ids[$grade]);
    }

}
