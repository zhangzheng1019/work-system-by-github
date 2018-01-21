<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends CI_Controller 
{
	public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default",true);
        $this->load->model("course_model");

	}


	


	
}
