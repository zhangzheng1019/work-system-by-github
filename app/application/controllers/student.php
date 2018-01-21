<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller 
{
	public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default",true);
        $this->load->model("student_model");
	}




}
