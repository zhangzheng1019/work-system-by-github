<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    const EXPIRES = 604800; //7天
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);

        $this->config->load("github");
        $this->GitHubOAuth = new \Yurun\OAuthLogin\GitHub\OAuth2($this->config->item('appid'), $this->config->item('appSecret'), $callback);
        $this->load->library('session');
        $this->load->helper('cookie');
    }
    /**
     * 关联登录git,
     * @return [type] [description]
     */
    public function loginGit()
    {
        $url                      = $this->GitHubOAuth->getAuthUrl();
        $_SESSION['GITHUB_STATE'] = $this->GitHubOAuth->state;

        header('Location:' . $url);
    }
    /**
     * 登录返回
     * @return [type] [description]
     */
    public function loginCallBack()
    {
        // 获取accessToken
        $accessToken = $this->GitHubOAuth->getAccessToken($_SESSION['GITHUB_STATE']);
        // 用户资料
        $userInfo = $this->GitHubOAuth->getUserInfo($accessToken);
        // 用户唯一标识
        $openid = $this->GitHubOAuth->openid;
        $this->session->set_userdata('gbinfo', $userInfo);
        set_cookie('is_github', true, self::EXPIRES);
        $where = array(
            'github_id' => $userInfo['id'],
        );
        $this->load->model("student_model");
        $stuInfo = $this->student_model->getBasicInfo($where);
        if (!$stuInfo['list'][0]) {
            header('Location:/studentsel');
        } else {
            set_cookie('userid', $stuInfo['list'][0]['id'], self::EXPIRES);
            set_cookie('userrole', 'student', self::EXPIRES);
            header('Location:/');
        }
    }
    /**
     * 教师注册
     * @return [type] [description]
     */
    public function teacherResigter()
    {
        $email = $this->input->get('email') ? $this->input->get('email') : '';
        set_cookie('userid', $email, self::EXPIRES);
        set_cookie('userrole', 'teacher', self::EXPIRES);
        ajax_success(true, 'teacher');
    }

    /**
     * 学生登录设置session
     * @return [type] [description]
     */
    public function studentLogin()
    {
        $id = $this->input->get('id') ? $this->input->get('id') : '';
        set_cookie('userid', $id, self::EXPIRES);
        set_cookie('userrole', 'student', self::EXPIRES);
        ajax_success(true, 'student');
    }

    /**
     * 后台登录入口
     * @return [type] [description]
     */
    public function endlogin()
    {
        $data        = array();
        $data['id']  = $this->input->post('username') ? $this->input->post('username') : '';
        $data['pwd'] = $this->input->post('password') ? $this->input->post('password') : '';

        $this->load->model("admin_model");

        $where = array(
            'name'     => $data['id'],
            'password' => $data['pwd'],
        );
        $adminRes = $this->admin_model->getInfo($where);
        if ($adminRes) {
            set_cookie('userid', $data['id'], 3600); //保留一小时
            set_cookie('userrole', 'admin', 3600); //保留一小时
            ajax_success(true,'admin');
        }
    }
    /**
     * 获取用户信息
     * @return [type] [description]
     */
    public function getInfo()
    {
        $role     = $_COOKIE['userrole'];
        $id       = $_COOKIE['userid'];
        $isGithub = $_COOKIE['is_github'];
        if ($role == 'admin') {
            $this->adminInfo($role, $id);
        } else {
            $this->frontInfo($role, $id, $isGithub);

        }
    }

    /**
     * 前台用户信息
     * @param  [type] $role     [description]
     * @param  [type] $id       [description]
     * @param  [type] $isGithub [description]
     * @return [type]           [description]
     */
    public function frontInfo($role, $id, $isGithub)
    {
        if (!$id) {
            redirect('/login');
        }
        $data = array();
        if ($role == 'teacher') {
            $this->load->model("teacher_model");
            $where = array(
                'mail' => $id,
            );
            $userinfo         = $this->teacher_model->getBasicInfo($where);
            $userinfo         = $userinfo['list'][0];
            $data['id']       = $userinfo['id'];
            $data['username'] = $userinfo['realname'] ? $userinfo['realname'] : '你的姓名好像丢了呀';
            $data['mobile']   = $userinfo['mobile'] ? $userinfo['mobile'] : '快来填写手机号吧';
            $data['email']    = $userinfo['mail'];
            $data['thumb']    = $userinfo['thumb'];
            $data['desc']     = $userinfo['desc'] ? $userinfo['desc'] : '快来补充你的个人描述吧';
            $data['role']     = $role;
        } else if ($role == 'student') {
            if (!$isGithub) {
                ajax_fail(false, '还没有授权GitHub');
            }
            $this->load->model('student_model');
            $where = array(
                'id' => $id,
            );
            $userinfo            = $this->student_model->getBasicInfo($where);
            $userinfo            = $userinfo['list'][0];
            $githubInfo          = json_decode($userinfo['github_info'], true);
            $data['id']          = $userinfo['id'];
            $data['username']    = $userinfo['realname'] ? $userinfo['realname'] : '你的姓名好像丢了呀';
            $data['thumb']       = $userinfo['thumb'];
            $data['desc']        = $userinfo['desc'] ? $userinfo['desc'] : '快来补充你的个人描述吧';
            $data['email']       = $githubInfo['email'];
            $data['github_url']  = $githubInfo['html_url'];
            $data['github_id']   = $githubInfo['id'];
            $data['github_name'] = $githubInfo['login'];
            $data['role']        = $role;
        }

        ajax_success($data, $role);
    }

    /**
     * 获取后台用户信息
     * @param  [type] $role [description]
     * @param  [type] $id   [description]
     * @return [type]       [description]
     */
    public function adminInfo($role, $id)
    {
        $data = array();
        if (!$id) {
            redirect('/admin');
        }
        $where = array(
            'name' => $id,
        );
        $this->load->model("admin_model");

        $adminRes = $this->admin_model->getInfo($where);
        if (1 == $adminRes[0]['is_admin']) {
            $this->load->model("teacher_model");
            $where = array(
                'mail' => $id,
            );
            $userinfo         = $this->teacher_model->getBasicInfo($where);
            $userinfo         = $userinfo['list'][0];
            $data['id']       = $userinfo['id'];
            $data['username'] = $userinfo['realname'] ? $userinfo['realname'] : $userinfo['mail'];
            $data['mobile']   = $userinfo['mobile'];
            $data['email']    = $userinfo['mail'];
            $data['thumb']    = $userinfo['thumb'];
            $data['role']     = 'teacher';
        } else {
            $data['id']       = 0;
            $data['username'] = '管理员';
        }
        ajax_success($data, $role);
    }

    /**
     * 后台退出登录
     * @return [type] [description]
     */
    public function loginoutEnd()
    {
        delete_cookie('userid');
        delete_cookie('userrole');
        delete_cookie('is_github');
        header('Location: /admin');
    }

    /**
     * 退出登录
     * @return [type] [description]
     */
    public function loginout()
    {
        delete_cookie('userid');
        delete_cookie('userrole');
        delete_cookie('is_github');
        header('Location: /login');
    }

}
