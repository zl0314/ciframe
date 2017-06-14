<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Author       : Aaron Zhang
 * createTime   : 2017/3/22 16:01
 * Description  : 站点首页
 *
 *   $this->cache->memcached->save('a', array(1,2,3,3,4,4,5,5));
 *   PR($this->cache->memcached->get('a'));
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