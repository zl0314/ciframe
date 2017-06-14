<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author       : Aaron Zhang
 * createTime   : 2017-06-02 19:43:44
 * Description  : File description here
 *
 */

class Url extends Base_Controller {

    public function __construct()  {

        //表名
        $this->tb = 'url';

        //主键
        $this->primary = 'id';

        //是否有添加时间字段[ture|false]
        $this->has_addtime = false;

        //表单属性
        $data = array (
            'id' =>
                array (
                    'field' => 'Id',
                    'is_primary' => true,
                    'type' => 'hidden',
                ),
            'url' =>
                array (
                    'field' => 'Url',
                    'is_require' => true,
                    'type' => 'text',
                ),
            'short' =>
                array (
                    'field' => 'Short',
                    'is_require' => true,
                    'type' => 'text',
                ),
        );

        $this->form_data = $data;

        parent::__construct();

        $this->checkAdminLogin();
    }

    public function index(){
        $data = get_page('url');
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