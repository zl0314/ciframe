<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends Common_Controller {
	public $is_wechat = 0;
	public $is_mobile = 0;
	function __construct(){
		parent::__construct();

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			$is_wechat = 1;
			$this->is_wechat = $is_wechat;
		}

		$ua = new CI_User_agent();
		$this->is_mobile = $ua->is_mobile;

		$system_setting = $this->cache->file->get('system_setting');
		

		//消息未读通知红点和未读条数
        $arr = array('Index','Member','Interesting','Master');
        $site_class = $this->router->class;
		$this->uid = Userinfo::$uid;
        if(in_array($site_class,$arr)){
			$this->data['num'] = '0';
            if($this->uid){
                $user_id = $this->uid;
                $this->data['num'] = $this->Result_model->getOneBySql("select count(id) from ".tname('notice_user')." where user_id = $user_id and is_read=0");
            }
        }

		$this->data['webset'] = $system_setting;
        $this->data['header'] = 'header';
        $this->data['footer'] = 'footer';
	}
	/**
	 * 前台提示信息
	 * @param string $err   输出信息
	 * @param string $url  跳转到URL
	 * @param int $sec  跳转秒数
	 * @param int $is_right 是否是正确的时候显示的信息
	 */
	public function message( $err ='', $url='', $sec = '1' , $is_right = 0){
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