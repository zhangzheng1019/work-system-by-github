<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->DB = $this->load->database("default", true);
        $this->load->model("student_model");

        $this->GitHubOAuth = new \Yurun\OAuthLogin\GitHub\OAuth2($this->config->item('appid'), $this->config->item('appSecret'), $callback);
    }

    public function loginGit()
    {
        $this->config->load("github");

        $url                      = $this->GitHubOAuth->getAuthUrl();
        $_SESSION['GITHUB_STATE'] = $this->GitHubOAuth->state;
        dump($_SESSION);
        header('location:' . $url);
        // dump($url);
    }

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
}
