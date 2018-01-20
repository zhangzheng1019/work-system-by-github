<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class main extends CI_Controller 
{
	public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default",true);
	}


	public function index()
	{
		$this->load->model("teacher_model","teacher");
		$this->teacher->getBasicInfoById(1);
		// $this->load->view('home/home.html');
	}
	public function error()
	{
		$this->load->view('errors/html/error_404');
	}
}
