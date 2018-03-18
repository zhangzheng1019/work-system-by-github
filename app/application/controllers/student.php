<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{
    const OFFSET = 10;
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("student_model");

        $this->GitHubOAuth = new \Yurun\OAuthLogin\GitHub\OAuth2($this->config->item('appid'), $this->config->item('appSecret'), $callback);
    }
    /**
     * 关联登录git,
     * @return [type] [description]
     */
    public function loginGit()
    {
        $this->config->load("github");

        $url                      = $this->GitHubOAuth->getAuthUrl();
        $_SESSION['GITHUB_STATE'] = $this->GitHubOAuth->state;
        dump($_SESSION);
        header('location:' . $url);
        // dump($url);
    }
    /**
     * 登录返回
     * @return [type] [description]
     */
    public function loginCallBack()
    {
        // 获取accessToken
        $accessToken = $this->GitHubOAuth->getAccessToken($_SESSION['GITHUB_STATE']);
        dump($accessToken);
        // 调用过getAccessToken方法后也可这么获取
        // $accessToken = $this->GitHubOAuth->accessToken;
        // 这是getAccessToken的api请求返回结果
        // $result = $this->GitHubOAuth->result;

        // 用户资料
        $userInfo = $this->GitHubOAuth->getUserInfo();

        // 这是getAccessToken的api请求返回结果
        // $result = $this->GitHubOAuth->result;

        // 用户唯一标识
        $openid = $this->GitHubOAuth->openid;
    }
    /**
     * 获取信息
     * @return [type] [description]
     */
    public function getInfo()
    {
        $where  = [];
        $page   = $this->input->get('page') ? $this->input->get('page') : 1;
        $select = $this->input->get('select') ? $this->input->get('select') : '';
        if ($select) {
            $select['name'] && $where['realname'] = $select['name'];
            $select['grade'] && $where['grade']   = $select['grade'];
            $select['class'] && $where['class']   = $select['class'];
        }
        $teacherId = $this->input->get('teacherId') ? $this->input->get('teacherId') : 0;
        if(!$teacherId){
            ajax_fail(false,'请登录',10000);
        }
        $limit           = ($page - 1) * self::OFFSET;
        $data            = $this->student_model->getBasicInfo($where, $limit, self::OFFSET);
        $list            = filterData($data['list'], $teacherId, 'teacher_id');
        $grade           = $this->student_model->studentGroupBy('grade');
        $class           = $this->student_model->studentGroupBy('class');
        $result['total'] = count($list);
        $result['grade'] = $grade;
        $result['class'] = $class;
        $result['list']  = $list;
        ajax_success($result, "加载成功");
    }
}
