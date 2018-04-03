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
        if ($userInfo) {
            header('Location:/studentsel');
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
     * 获取用户信息
     * @return [type] [description]
     */
    public function getInfo()
    {
        $role     = $_COOKIE['userrole'];
        $id       = $_COOKIE['userid'];
        $isGithub = $_COOKIE['is_github'];

        if(!$id){
            ajax_fail(false,'',20000);
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
            $data['username'] = $userinfo['realname'];
            $data['mobile']   = $userinfo['mobile'];
            $data['email']    = $userinfo['mail'];
            $data['thumb']    = $userinfo['thumb'];
            $data['desc']     = $userinfo['desc'];
            $data['role']     = $role;
            ajax_success($data, 'teacher');
        } else if ($role == 'student') {
            if(!$isGithub){
                ajax_fail(false, '还没有授权GitHub');
            }
            $this->load->model('student_model');
            $where = array(
                'id' => $id,
            );
            $userinfo           = $this->student_model->getBasicInfo($where);
            $userinfo           = $userinfo['list'][0];
            $githubInfo         = json_decode($userinfo['github_info'], true);
            $data['id']         = $userinfo['id'];
            $data['username']   = $userinfo['realname'];
            $data['thumb']      = $userinfo['thumb'];
            $data['desc']       = $userinfo['desc'];
            $data['email']      = $githubInfo['email'];
            $data['github_url'] = $githubInfo['html_url'];
            $data['github_id']  = $githubInfo['id'];
            $data['role']       = $role;
            ajax_success($data, 'student',20002);
        }

    }

}
