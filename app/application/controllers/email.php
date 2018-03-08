<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Email extends CI_Controller
{
    public function index()
    {
        $this->load->model("email_model");
        $this->email_model->sendEmail('447590461@qq.com', '122226');
    }
}
