<?php
defined('BASEPATH') or exit('No direct script access allowed');

class main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
    }

    public function index()
    {
        $role = $_COOKIE['userrole'];
        if ("" == $role) {
            $this->front();
        } else {
            $this->load->view('main/index.html');
        }
    }
    public function error()
    {
        $this->load->view('errors/html/error_404');
    }
    public function front()
    {
        $this->load->view('main/header.html');
        $this->load->view('main/front.html');
        $this->load->view('main/footer.html');
    }
    public function stuselect()
    {
        $role = $_COOKIE['userrole'];
        if ($role == 'student') {
            redirect("/#/student");
        }
        $this->load->view('main/header.html');
        $this->load->view('main/stuselect.html');
        $this->load->view('main/footer.html');
    }
    public function admin()
    {
        $this->load->view('main/header.html');
        $this->load->view('main/admin.html');
        $this->load->view('main/footer.html');
    }

    public function editUserInfo()
    {
        $role = $this->input->post("role");
        if (!$role) {
            redirect('/login', '', 302);
        }
        $param = $_POST;

        if ("teacher" == $role) {
            $status = $this->editTeaInfo($param);
        } else if ("student" == $role) {
            $status = $this->editStuInfo($param);
        }

        $status ? ajax_success(true, "操作成功") : ajax_fail(false, "你没有修改呦！");

    }
    /**
     * 修改教师个人信息
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    public function editTeaInfo($post)
    {
        $data             = array();
        $data['id']       = $post['id'];
        $data['realname'] = $post['username'];
        $data['desc']     = $post['desc'];
        $data['thumb']    = $post['thumb'];

        $this->load->model("teacher_model");
        $editStatus = $this->teacher_model->updateTeacherInfo($data['id'], $data);

        return $editStatus;
    }
    /**
     * 修改学生个人信息
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    public function editStuInfo($post)
    {
        $data             = array();
        $data['id']       = $post['id'];
        $data['realname'] = $post['username'];
        $data['desc']     = $post['desc'];
        $data['thumb']    = $post['thumb'];

        $this->load->model("student_model");
        $editStatus = $this->student_model->updateStudentInfo($data['id'], $data);

        return $editStatus;
    }

    /**
     * 后台菜单权限
     * @return [type] [description]
     */
    public function endPurview()
    {
        $role = $_COOKIE['userrole'];
        $id   = $_COOKIE['userid'];
        if($role != 'admin'){
            redirect('/login');
        }
        if (!$id) {
            redirect('/admin');
        }

        $this->load->model("admin_model");
        $where = [
            "name" => $id,
        ];
        $adminRes = $this->admin_model->getInfo($where);
        $isAdmin  = $adminRes[0]['is_admin'];

        $this->config->load('endmenu', true);
        $menu = $this->config->item('endmenu');
        $menu = $menu['menu'];
        foreach ($menu as $key => $value) {
            $menu[$key]['is_admin'] = explode(',', $value['is_admin']);
            if (!inArray($isAdmin, $menu[$key]['is_admin'])) {
                unset($menu[$key]);
            }
        }
        ajax_success(array_values($menu));
    }

}
