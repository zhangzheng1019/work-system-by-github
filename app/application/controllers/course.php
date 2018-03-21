<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Course extends CI_Controller
{
    const LIMIT = 10;

    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("course_model");
    }

    /**
     * 获取课程列表
     * @return [type] [description]
     */
    public function getList()
    {
        $where = [];

        $teacherId = $this->input->post('id') ? $this->input->post('id') : 0;
        if (!$teacherId) {
            ajax_fail(false, '请登录', 20000);
        }
        $gradeGroup = $this->getGradeGroup($teacherId);
        $grade      = $this->input->post('grade') ? $this->input->post('grade') : $gradeGroup[0]['value'];

        $where = array(
            'teacher_id' => $teacherId,
            'grade_id'   => $grade,
        );
        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $offset = ($page - 1) * self::LIMIT;

        $total = $this->course_model->getTotalNum($where);
        $data  = $this->course_model->getBasicInfo($where, $offset, self::LIMIT);
        
        $this->load->model("student_model");
        $allStudent = $this->student_model->getBasicInfo();
        foreach ($data as $key => $value) {
            $data[$key]['stuNumber'] = count(filterData($allStudent['list'], $value['id'], 'course_id'));
        }

        $result['total']        = $total;
        $result['gradeGroup']   = $gradeGroup;
        $result['currentGrade'] = $grade;
        $result['list']         = $data;
        ajax_success($result, "加载成功");
    }
    /**
     * 添加课程
     */
    public function addCourse()
    {
        $data   = array();
        $id     = $this->input->post('id');
        $status = false;
        if ($id) {
            // 修改
            $courseInfo    = $this->course_model->getBasicInfo(array('id' => $id));
            $data          = $courseInfo['list'];
            $data['title'] = isset($_POST['name']) ? $_POST['name'] : $data['title'];
            $data['desc']  = isset($_POST['desc']) ? $_POST['desc'] : $data['desc'];
            $data['thumb'] = isset($_POST['thumb']) ? $_POST['thumb'] : $data['thumb'];
            $status        = $this->course_model->updateCourseInfo($id, $data);
        } else {
            //添加
            $data['title']      = isset($_POST['name']) ? $_POST['name'] : '';
            $data['desc']       = isset($_POST['desc']) ? $_POST['desc'] : '';
            $data['thumb']      = isset($_POST['thumb']) ? $_POST['thumb'] : '';
            $data['teacher_id'] = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : '';
            $data['grade_id']   = isset($_POST['grade_id']) ? $_POST['grade_id'] : '';
            $status             = $this->course_model->addCourseInfo($data);
            $status             = $status['status'];
        }

        if ($status) {
            ajax_success($status, '操作成功');
        } else {
            ajax_fail(false, '操作失败');
        }
    }

    /**
     * 获取年级
     * @return [type] [description]
     */
    public function getGradeGroup($teacher_id)
    {
        if (!$teacher_id) {
            return [];
        }
        $where = array(
            'teacher_id' => $teacher_id,
        );
        $gradeGroup = $this->course_model->courseGroupBy('grade_id', $where, 4, 'grade_id desc');
        return $gradeGroup;
    }
}
