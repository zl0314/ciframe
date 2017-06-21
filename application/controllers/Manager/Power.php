<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author       : Aaron Zhang
 * createTime   : 2017-06-21 10:52:52
 * Description  : File description here
 *
 */

class Power extends Base_Controller {

    public function __construct()  {

        //表名
        $this->tb = 'power';

        //主键
        $this->primary = 'id';

        //是否有添加时间字段[ture|false]
        $this->has_addtime = false;

        $this->status = array('1' => '开启', '0' => '关闭');
        $this->tree = array();

        //表单属性
        $data = array (
            'id' =>
                array (
                    'field' => 'Id',
                    'is_primary' => true,
                    'type' => 'hidden',
                ),
            'name' =>
                array (
                    'field' => '名称',
                    'is_require' => true,
                    'type' => 'text',
                    'show_in_table' => true,
                    'show_in_search' => true,
                ),
            'action' =>
                array (
                    'field' => '动作',
                    'is_require' => true,
                    'type' => 'text',
                ),
            'pid' =>
                array (
                    'field' => '上级权限',
                    'type' => 'select',
                    'data' => $this->tree
                ),
            'status' =>
                array (
                    'field' => '状态',
                    'type' => 'radio',
                    'data' => $this->status,
                ),
        );

        $this->form_data = $data;

        parent::__construct();

        $this->checkAdminLogin();
    }

    public function index(){
        $data = get_page('power');
        $list_html = get_list_html($this->form_data, $data);
        $vars = array(
            'list_html' => $list_html,
        );

        $this->tpl->assign($vars);
        $this->tpl->display();
    }

    /**
     * [添加记录]
     **/
    public function add(){
        $this->saveData();
        $vars = array(

        );

        $this->tpl->assign($vars);
        $this->tpl->display();
    }

    /**
     * [编辑记录]
     */
    public function edit($id= 0){
        $id = intval($id);
        $row = $this->getRow($id);

        $this->saveData($row);

        $vars = array(

        );
        $this->tpl->assign($vars);
        $this->tpl->display();
    }

    //排序
    public function public_listorder(){
        $this->commonListOrder('rank');
    }

    //删除
    public function delete(){
        $this->commonDelete();
    }
}