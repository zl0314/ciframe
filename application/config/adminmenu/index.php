<?php
/**
 * Created by PhpStorm.
 * User: zhang
 * Date: 2017/6/21
 * Time: 16:57
 * 首页栏目权限节点
 */
$config = array(
    'id' => 'index',
    'name' => '首页',
    'status' => 1,
    'lists' => array(
        'Admin' => array(
            'name' => '首页',
            'status' => 1,
            'method' => array(
                'index' => array(
                    'name' => '首页',
                    'status' => 0
                ),
                'center' => array(
                    'name' => '系统信息',
                    'status' => 1
                ),
                'logout' => array(
                    'name' => '退出登陆',
                    'status' => 0
                )

            )
        ),
        'Tools_url' => array(
            'name' => '外链管理',
            'status' => 1,
            'method' => array(
                'index' => array(
                    'name' => '外链列表',
                    'status' => 1
                ),
                'add' => array(
                    'name' => '外链添加',
                    'status' => 1
                ),
                'edit' => array(
                    'name' => '外链编辑',
                    'status' => 0
                ),
                'delete' => array(
                    'name' => '删除',
                    'status' => 0
                ),
            ),
        ),
    ),

);