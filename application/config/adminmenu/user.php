<?php
/**
 * Created by PhpStorm.
 * User: zhang
 * Date: 2017/6/21
 * Time: 17:17
 * 管理员管理权限节点
 */
$config = array(
    'id' => 'member_center',
    'name' => '用户管理',
    'status' => 1,
    'lists' => array(
        'Adminuser' => array(
            'name' => '管理员管理',
            'status' => 1,
            'method' => array(
                'index' => array(
                    'name' => '管理员列表',
                    'status' => 1
                ),
                'add' => array(
                    'name' => '添加管理员',
                    'status' => 1
                ),
                'updpass' => array(
                    'name' => '修改密码',
                    'status' => 1
                ),
                'del' => array(
                    'name' => '删除',
                    'status' => 0
                ),
                'right' => array(
                    'name' => '权限设置',
                    'status' => 0
                )
            )
        ),

    ),
);