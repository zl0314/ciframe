<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Author       : Aaron Zhang
 * createTime   : 2017/4/17 17:47
 * Description  : 工具链接管理
 *
 */

class Tools_url extends Base_Controller {

    public function __construct()  {
        //表名
        $this->tb = 'tools_url';
        //主键
        $this->primary = 'id';
        //添加时间字段
        $this->has_addtime = true;

        //工具类型
        $this->hook = array(
            'gjhl' => '国际汇率',
            'fdjs' => '房贷计算',
            'shop' => '微店',
        );

        //表单属性
        $data = array(
            'id' => array(
                'field' => 'ID',
                'is_primary' => true,
                'type' => 'hidden',
            ),
            'title' => array(
                'field' => '标题',
                'type' => 'text',
                'show_in_search' => true,
                'search_type' => 'like'
            ),
            'link_url' => array(
                'field' => '链接地址',
                'type' => 'text'
            ),
            'hook' => array(
                'field' => '分类',
                'type' => 'select',
                'show_in_search' => true,
                'data' => $this->hook
            ),
        );

        $this->form_data = $data;
        
        parent::__construct();

        $this->checkAdminLogin();
        //加载Model
        $this->load->model('Tools_url_model');
    }

    public function index(){
        $data = get_page('tools_url');
        $list_html = get_list_html($this->form_data, $data);
        $vars = array(
            'list_html' => $list_html,
        );

        $this->tpl->assign($vars);
        $this->tpl->display();
    }

    /**
     * [添加]
     **/
    public function add($id = 0){
        $this->saveData();
        $vars = array(

        );

        $this->tpl->assign($vars);
        $this->tpl->display();
    }

    /**
     * [编辑]
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

    //删除
    public function delete(){
        $this->commonDelete();
    }
}