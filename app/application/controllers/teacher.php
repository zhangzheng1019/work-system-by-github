<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("teacher_model");

    }

    public function getInfo()
    {
        $where         = [];
        $page          = $this->input->post('page') ? $this->input->post('page') : 1;
        $total         = $this->teacher_model->getTotalNum($where);
        $data          = $this->teacher_model->getBasicInfo($where, $page, 10);
        $data['total'] = $total;
        ajax_success($data, "加载成功");
    }

    public function addTea()
    {
        $data   = array();
        $id     = $this->input->post('id');
        $status = false;
        // 注册用
        if (!$id) {
            $data['mail']     = isset($_POST['email']) ? $_POST['email'] : '';
            $data['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $status           = $this->teacher_model->addTeacherInfo($data);
            $status           = $status['status'];
        } else {
            $teacherInfo      = $this->teacher_model->getBasicInfo(array('id' => $id));
            $data             = $teacherInfo['list'];
            $data['realname'] = isset($_POST['name']) ? $_POST['name'] : '';
            $data['mobile']   = isset($_POST['mobile']) ? $_POST['mobile'] : '';
            $data['thumb']    = isset($_POST['thumb']) ? $_POST['thumb'] : '';
            $status           = $this->teacher_model->updateTeacherInfo($id, $data);
        }

        if ($status) {
            ajax_success($status, '操作成功');
        } else {
            ajax_fail(false, '操作失败');
        }
    }

}
