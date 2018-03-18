<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends CI_Controller
{
    const OFFSET = 10;
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("teacher_model");

    }

    public function getInfo()
    {
        $where  = [];
        $page   = $this->input->get('page') ? $this->input->get('page') : 1;
        $select = $this->input->get('select') ? $this->input->get('select') : '';
        if ($select) {
            $select['name'] && $where['realname'] = $select['name'];
            $select['mobile'] && $where['mobile'] = $select['mobile'];
        }
        $limit         = ($page - 1) * self::OFFSET;
        $total         = $this->teacher_model->getTotalNum($where);
        $data          = $this->teacher_model->getBasicInfo($where, $limit, self::OFFSET);
        $data['total'] = $total;
        ajax_success($data, "加载成功");
    }

    public function addTea()
    {
        $data   = array();
        $id     = $this->input->post('id');
        $status = false;
        // 修改教师信息
        if ($id) {
            $teacherInfo      = $this->teacher_model->getBasicInfo(array('id' => $id));
            $data             = $teacherInfo['list'];
            $data['realname'] = isset($_POST['name']) ? $_POST['name'] : $data['realname'];
            $data['mobile']   = isset($_POST['mobile']) ? $_POST['mobile'] : $data['mobile'];
            $data['thumb']    = isset($_POST['thumb']) ? $_POST['thumb'] : $data['thumb'];
            $status           = $this->teacher_model->updateTeacherInfo($id, $data);
        } else {
//添加
            $data['mail']     = isset($_POST['email']) ? $_POST['email'] : '';
            $data['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $data['realname'] = isset($_POST['name']) ? $_POST['name'] : '';
            $data['mobile']   = isset($_POST['mobile']) ? $_POST['mobile'] : '';
            $status           = $this->teacher_model->addTeacherInfo($data);
            $status           = $status['status'];
        }

        if ($status) {
            ajax_success($status, '操作成功');
        } else {
            ajax_fail(false, '操作失败');
        }
    }

}
