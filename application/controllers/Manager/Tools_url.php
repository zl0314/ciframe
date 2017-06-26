<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author       : Aaron Zhang
 * createTime   : 2017-06-26 13:42:02
 * Description  : File description here
 *
 */

class Tools_url extends Base_Controller {

    public function __construct()  {

        //表名
        $this->tb = 'tools_url';

        //主键
        $this->primary = 'id';

        //是否有添加时间字段[ture|false]
        $this->has_addtime = true;

        //表单属性
        $data = array (
            'id' =>
                array (
                    'field' => 'Id',
                    'is_primary' => true,
                    'type' => 'hidden',
                ),
            'title' =>
                array (
                    'field' => 'Title',
                    'is_require' => true,
                    'type' => 'text',
                ),
            'link_url' =>
                array (
                    'field' => 'Link_url',
                    'is_require' => true,
                    'type' => 'text',
                ),
            'addtime' =>
                array (
                    'field' => 'Addtime',
                    'is_require' => true,
                    'type' => 'time',
                ),
            'hook' =>
                array (
                    'field' => 'Hook',
                    'is_require' => true,
                    'type' => 'text',
                ),
            'is_index' =>
                array (
                    'field' => 'Is_index',
                    'type' => 'text',
                ),
            'content' =>
                array (
                    'field' => 'Content',
                    'type' => 'text',
                    'editor' => true,
                ),
            'atime' =>
                array (
                    'field' => 'Atime',
                    'type' => 'time',
                ),
        );

        $this->form_data = $data;

        parent::__construct();

        $this->checkAdminLogin();
    }

    public function index(){
        $data = get_page($this->tb);
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
        $this->commonListOrder('listorder');
    }

    //删除
    public function delete(){
        $this->commonDelete();
    }
}