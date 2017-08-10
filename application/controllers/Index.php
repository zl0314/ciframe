<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Author       : Aaron Zhang
 * createTime   : 2017/3/22 16:01
 * Description  : 站点首页
 *
 */
class Index extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
    }

    public function index(){
    	$vars = array();
    	$this->tpl->assign($vars);
        $this->tpl->display();
    }
}
