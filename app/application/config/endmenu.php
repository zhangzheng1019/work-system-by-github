<?php
defined('BASEPATH') or exit('No direct script access allowed');

//0：管理员，1：教师
$config['menu'] = array(
    array(
        "icon"     => 'el-icon-menu',
        "index"    => '/end/teacher',
        "title"    => '教师管理',
        "is_admin" => '0',
    ),
    array(
        "icon"     => 'el-icon-menu',
        "index"    => '2',
        "title"    => '学生管理',
        "subs"     => array(
            array(
                "index" => '/end/student',
                "title" => '名单',
            ),
        ),
        "is_admin" => '0,1',
    ),
);
