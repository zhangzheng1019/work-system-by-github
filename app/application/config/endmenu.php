<?php
defined('BASEPATH') or exit('No direct script access allowed');

//1：管理员，0：教师
$menu = [
    [
        "icon"     => 'el-icon-menu',
        "index"    => '/end/teacher',
        "title"    => '教师管理',
        "is_admin" => '1',
    ],
    [
        "icon"     => 'el-icon-menu',
        "index"    => '2',
        "title"    => '学生管理',
        "subs"     => [
            "index" => '/end/student',
            "title" => '名单',
        ],
        "is_admin" => '1,0',
    ],
];
