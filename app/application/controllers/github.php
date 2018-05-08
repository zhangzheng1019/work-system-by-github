<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Github extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->config->load("github");
        $this->GitHubOAuth = new \Yurun\OAuthLogin\GitHub\OAuth2($this->config->item('appid'), $this->config->item('appSecret'), $callback);
    }

    /**
     * 获取用户所有的仓库
     * @return [type] [description]
     */
    public function getUserAllRepos()
    {
        $ghName   = $this->input->get('gh_name');
        $curRepos = $this->input->get('cur_repos');

        // $ghName   = $_GET['gh_name'];
        $reposArr = $this->GitHubOAuth->getAllRepos($ghName);

        $formatData = null;
        foreach ($reposArr as $key => $value) {
            if ($value['name'] != $curRepos) {
                $formatData[$key]['gh_name'] = $value['name'];
                $formatData[$key]['gh_url']  = $value['html_url'];
            }
        }
        $data['repos'] = $formatData;
        ajax_success($data);
    }

    /**
     * 获取当前仓库的信息
     * @return [type] [description]
     */
    public function getGithubCode()
    {
        // $ghName  = $this->input->get('gh_name');
        // $ghRepos = $this->input->get('gh_repos');
        $ghName   = $_GET['gh_name'];
        $ghRepos  = $_GET['gh_repos'];
        $reposArr = $this->GitHubOAuth->getRepos($ghName, $ghRepos);
        echo "<pre>";
        var_dump($reposArr);

    }
}
