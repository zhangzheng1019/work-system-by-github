<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller 
{
	public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default",true);
		$this->load->model("teacher_model");

	}

	public function getInfo()
	{
		$data = $this->teacher_model->getBasicInfo();
		ajax_success($data,"加载成功");
	}




}
