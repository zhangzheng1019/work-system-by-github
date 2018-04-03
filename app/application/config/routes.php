<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller']   = 'main';
$route['404_override']         = '';
$route['translate_uri_dashes'] = false;

$route['login']      = 'main/front';
$route['studentsel'] = 'main/stuselect';
$route['admin']      = 'main/admin';
