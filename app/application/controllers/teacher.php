<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->DB = $this->load->database("default", true);
		$this->load->model("teacher_model");

	}

	public function getInfo() {
		$data = $this->teacher_model->getBasicInfo();
		ajax_success($data, "加载成功");
	}

	public function addTea() {
		$data = array();

		$data['realname'] = isset($_POST['name']) ? $_POST['name'] : '';
		$data['mobile'] = isset($_POST['mobile']) ? $_POST['mobile'] : '';
		$data['mail'] = isset($_POST['email']) ? $_POST['email'] : '';
		$data['password'] = isset($_POST['password']) ? $_POST['password'] : '';

		$data['password'] = md5($data['password']);

		$this->load->model("teacher_model");
		$addStatus = $this->teacher_model->addTeacherInfo($data);
		if ($addStatus) {
			ajax_success($addStatus, '添加成功');
		} else {
			ajax_fail(false, '添加失败');
		}
	}

}
