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
        $this->has_addtime = false;

        //工具类型
        $this->hook = array(
            'gjhl' => '国际汇率',
            'fdjs' => '房贷计算',
            'shop' => '微店',
        );
        $this->is_index = array(
            '0' => '否',
            '1' => '是'
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
                'rule' => 'trim',
                'is_require' => 'false',
                'type' => 'text',
                'show_in_search' => true,
                'search_type' => 'like'
            ),
            'atime' => array(
                'field' => '时间',
                'type' => 'time',
                'is_primary' => false,
                'show_in_search' => false,
                'search_type' => 'like',
                'readonly' => true,
			    'format' => 'yyyy-mm-dd HH:mm:ss'
            ),
            'is_index' => array(
                'field' => '推荐首页',
                'type' => 'radio',
                'is_primary' => false,
                'show_in_search' => true,
                'default' => 0,
                'data' => $this->is_index
            ),
            'hook' => array(
                'field' => '分类',
                'type' => 'select',
                'is_primary' => false,
                'show_in_search' => true,
                'data' => $this->hook
            ),
            'content' => array(
                'field' => '详情',
                'is_primary' => false,
//                'show_in_table' => false,
                'type' => 'textarea',
                'editor' => false,
            ),
            'img' => array(
                'type' => 'image',
                'field' => '缩略图',
                'is_primary' => false,
                'readonly' => true,
                'tip' => '宽100, 高100'
            ),
            'interesting' => array(
                'field' => '兴趣',
                'type' => 'checkbox',
                'is_require' => 'false',
//                'show_in_table' => true,
                'data' => array('1' => '打球', '2' => '读书', '3' => '音乐', '4' => '跑步')
            ),
        );
        $this->form_data = $data;
        parent::__construct();
        $this->checkAdminLogin();
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
//        PR($_POST);die;
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