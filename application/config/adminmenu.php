<?php
/**
* @author		ZhangLong
* @since		version - 2015-7-4
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| 管理员导航菜单、权限
|--------------------------------------------------------------------------
*/

$ADMIN_MENU = array(
	'menu' => array(
		/*===========TOP菜单 首页 ===========*/
		array(
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
			
),

/*===========在这上面添加一级TOP菜单 ===========*/
/**
* 模板
*
* array(
'id' => '元素ID ',
'name' => 'TOP菜单名称',
'status' => 1,
'lists' => array(
'tradecp' => array(
'name' => '栏目名',
'status' => 1,
	'method' => array(
		'index' => array(
			'name' => '方法名',
			'status' => 1 //1显示   0 不显示
			),
		),
	),
)
)
*/

),
);