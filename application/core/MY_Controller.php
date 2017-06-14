<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends Common_Controller {

	function __construct(){
		parent::__construct();
		//获取站点设置
		$system_setting = $this->cache->file->get('system_setting');
		$this->data['webset'] = $system_setting;

        $this->data['header'] = 'header';
        $this->data['footer'] = 'footer';
	}

	/**
	 * 前台提示信息
	 * @param string $err   输出信息
	 * @param string $url  跳转到URL
	 * @param int $sec  跳转秒数
	 */
	public function message( $err ='', $url='', $sec = '1' ){
		if( $err ){
			$this->data['sec'] = $sec*1000;
			$this->data['url'] = reMoveXss($url);
			$this->data['err'] = reMoveXss($err);
			if($this->is_mobile){
				mobileMessage($err, 1);exit;
			}else{
				$this->load->view('message', $this->data);
			}
		}
	}
	//设置页面标题
	public function set_page_title($title){
		if($title){
			$this->data['pagetitle'] = $title;
		}
	}

	//设置顶部导航标题
	public function set_top_nav_title($title){
		if($title){
			$this->data['nav_title'] = $title;
		}
	}


}