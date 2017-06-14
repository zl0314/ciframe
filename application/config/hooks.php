<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['pre_controller'] = array(
    'class'    => 'My_hook',
    'function' => 'system_start',
    'filename' => 'My_hook.php',
    'filepath' => 'hooks',
    'params'   => array()
);

$hook['post_system'] = array(
    'class'    => 'My_hook',
    'function' => 'system_end',
    'filename' => 'My_hook.php',
    'filepath' => 'hooks',
    'params'   => array()
);