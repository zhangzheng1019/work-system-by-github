<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends CI_Controller
{
    const LIMIT = 10;

    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("teacher_model");

    }
    /**
     * 获取教师信息列表
     * @return [type] [description]
     */
    public function getInfo()
    {
        $where  = [];
        $page   = $this->input->get('page') ? $this->input->get('page') : 1;
        $select = $this->input->get('select') ? $this->input->get('select') : '';
        if ($select) {
            $select['name'] && $where['realname'] = $select['name'];
            $select['mobile'] && $where['mobile'] = $select['mobile'];
        }
        $offset        = ($page - 1) * self::LIMIT;
        $total         = $this->teacher_model->getTotalNum($where);
        $data          = $this->teacher_model->getBasicInfo($where, $offset, self::LIMIT);
        $data['total'] = $total;
        ajax_success($data, "加载成功");
    }
    /**
     * 添加、修改教师信息
     */
    public function addTea()
    {
        $data   = array();
        $id     = $this->input->post('id');
        $status = false;
        $this->load->model('admin_model');
        if ($id) {
            // 修改教师信息
            $teacherInfo      = $this->teacher_model->getBasicInfo(array('id' => $id));
            $data             = $teacherInfo['list'];
            $data['realname'] = isset($_POST['name']) ? $_POST['name'] : $data['realname'];
            $data['mobile']   = isset($_POST['mobile']) ? $_POST['mobile'] : $data['mobile'];
            $data['thumb']    = isset($_POST['thumb']) ? $_POST['thumb'] : $data['thumb'];
            $status           = $this->teacher_model->updateTeacherInfo($id, $data);
            !$status && ajax_fail(false, '你没有修改呦！');
        } else {
            //添加
            $data['mail']     = isset($_POST['email']) ? $_POST['email'] : '';
            $data['password'] = isset($_POST['password']) ? $_POST['password'] : '';
            $data['realname'] = isset($_POST['name']) ? $_POST['name'] : '';
            $data['mobile']   = isset($_POST['mobile']) ? $_POST['mobile'] : '';
            $status           = $this->teacher_model->addTeacherInfo($data);
            $endStatus        = $this->admin_model->addEndUser('teacher', $data);
            $status           = $status['status'] && $endStatus['status'];
            !$status && ajax_fail(false, '操作失败');
        }

        $status && ajax_success($status, '操作成功');
    }
    /**
     * 重置密码
     * @return [type] [description]
     */
    public function resetPwd()
    {
        $id = $this->input->get('id');
        if (!$id) {
            ajax_fail(false, '无效的id');
        }
        $teacherInfo  = $this->teacher_model->getBasicInfo(['id' => $id]);
        $emailAddress = $teacherInfo['list'][0]['mail'];
        if (!emailAddress) {
            ajax_fail(false, '无效的邮箱地址');
        }

        $this->load->model("email_model");

        $code         = '123456';
        $subject      = 'github作业统计系统-重置密码';
        $body         = '<p style="font-size:24px">您的新密码为：' . '<font color="#409EFF">' . $code . '</font><p>';
        $sendStatus   = $this->email_model->sendEmail($emailAddress, $subject, $body);
        $updateStatus = false;
        if ($sendStatus) {
            $updateStatus = $this->teacher_model->updateTeacherInfo($id, ['password' => md5($code)]);
            ajax_success($updateStatus, '重置成功');
        }
    }
    /**
     * 验证邮箱注册时，邮箱唯一性
     * @return [type] [description]
     */
    public function uniqueEmail()
    {
        $email = $this->input->get("email") ? $this->input->get("email") : '';
        if (!$email) {
            ajax_success(false, '请输入邮箱地址');
        }
        $where = array(
            'mail' => $email,
        );
        $total = $this->teacher_model->getTotalNum($where);
        ($total > 0) ? ajax_success(false, '该邮箱已经注册过了') : ajax_success(true);
    }

    public function veriInfo()
    {
        $data          = array();
        $data['email'] = $this->input->post('email') ? $this->input->post('email') : '';
        $data['pwd']   = $this->input->post('pwd') ? $this->input->post('pwd') : '';

        $this->load->model("teacher_model");

        $status = false;
        $where  = array(
            'mail'     => $data['email'],
            'password' => $data['pwd'],
        );
        $teacherInfo = $this->teacher_model->getBasicInfo($where);

        $teacherInfo['list'][0] ? ajax_success(true) : ajax_success(false, '登录失败');
    }
}
