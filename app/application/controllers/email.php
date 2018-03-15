<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Email extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ajaxSend()
    {
        $emailAddress = $this->input->post('email');
        if (!$emailAddress) {
            return [];
        }

        // 对邮箱做唯一性校验
        $this->load->model('teacher_model');
        $where = array(
            'mail' => $emailAddress
        );
        $count = $this->teacher_model->getTotalNum($where);
        if($count > 0){
            ajax_fail($count,"该邮箱已经注册了哈！",false);
        }
        $this->load->model("email_model");

        $code       = rand_string(6, 2); //生成长度为6的，纯数字 验证码
        $subject    = '欢迎使用github作业统计系统';
        $body       = '<p style="font-size:24px">您的验证码为：' . '<font color="#409EFF">' . $code . '</font><p>';
        $sendStatus = $this->email_model->sendEmail($emailAddress, $subject, $body);
        ajax_success($code, true);
    }
}
