<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    const EXPIRES_TIME = 7 *24 * 60 * 60; //设置cookie保存时间为 7天

    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->helper('cookie');
    }

    /**
     * 教师注册
     * @return [type] [description]
     */
    public function teacherResigter()
    {
        // $email = $this->input->get('email') ? $this->input->get('email') : '';
        // $email = $_GET['email'];
        // set_cookie("email", $email, self::EXPIRES_TIME);
    }

}
