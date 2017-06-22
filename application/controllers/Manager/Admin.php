<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Admin extends Base_Controller {

	public function __construct(){
		parent::__construct();
		if($this->sitemethod != 'login' && $this->sitemethod != 'logout'){
			$this->checkAdminLogin();
		}
	}

	public function index(){
        $this->data['header'] = '';
        $this->data['footer'] = '';

		$this->tpl->display();		
	}
	
	public function center(){
	    $admin_info = $this->admin_info;

		$this->lang->load('common');
		//echo '<pre>';print_r( $this->session->all_userdata());exit;
		$_LANG['yes'] = '是';
		$_LANG['no'] = '否';
	
		/* 系统信息 */
		$sys_info['os']            = PHP_OS;
		$sys_info['ip']            = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['REMOTE_ADDR'];
		$sys_info['web_server']    = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['php_ver']       = PHP_VERSION;
		//$sys_info['mysql_ver']     = $mysql_ver;
		$sys_info['zlib']          = function_exists('gzclose') ? $_LANG['yes']:$_LANG['no'];
		$sys_info['safe_mode']     = (boolean) ini_get('safe_mode') ?  $_LANG['yes']:$_LANG['no'];
		$sys_info['safe_mode_gid'] = (boolean) ini_get('safe_mode_gid') ? $_LANG['yes'] : $_LANG['no'];
		$sys_info['timezone']      = function_exists("date_default_timezone_get") ? date_default_timezone_get() : '无时区';
		$sys_info['curr_time']    = date('Y-m-d H:i:s' , time());
		$sys_info['socket']        = function_exists('fsockopen') ? $_LANG['yes'] : $_LANG['no'];
	
		/* 检查系统支持的图片类型 */
		$sys_info['gd'] = '';
		if ( (imagetypes() & IMG_JPG) > 0) {
			$sys_info['gd'] .= ' JPEG';
		}
	
		if ( (imagetypes() & IMG_GIF) > 0) {
			$sys_info['gd'] .= ' GIF';
		}
	
		if ( (imagetypes() & IMG_PNG) > 0) {
			$sys_info['gd'] .= ' PNG';
		}
	
		/* 允许上传的最大文件大小 */
		$sys_info['max_filesize'] = ini_get('upload_max_filesize');
		$sys_info['last_login'] = $admin_info['last_login_time'];
		$this->data['sys_info'] = $sys_info;
		$this->tpl->display();
	}
	
	public function login(){
        $this->data['header'] = '';
        $this->data['footer'] = '';
		
		if( empty($_POST)){
			$this->tpl->display();
		}else{
			$username = _post('username');
			$password = _post('password');
			$captcha = _post('captcha');

			if(strtolower($this->session->userdata('captcha')) != strtolower($captcha)){
                $this->message('验证码不正确', site_url(MANAGER_PATH.'/'.$this->siteclass.'/'.$this->sitemethod));
            }
			if( empty( $username) ) {
				$this->message('用户名不能为空', site_url(MANAGER_PATH.'/'.$this->siteclass.'/'.$this->sitemethod));
			}
			if(empty( $password)){
				$this->message('密码不能为空', site_url(MANAGER_PATH.'/'.$this->siteclass.'/'.$this->sitemethod));
			}
			$userInfo = $this->Result_model->getRow(''.$this->siteclass.'_user', '*' , array('user_name' => $username));
				
			if( empty($userInfo) ) {
				$this->message('用户名不存在', site_url(MANAGER_PATH.'/'.$this->siteclass.'/'.$this->sitemethod));
			}
	
			$check_res = check_password($password, $userInfo['salt'], $userInfo['password']);
			if( !$check_res) {
				$this->message('密码错误', site_url(MANAGER_PATH.'/'.$this->siteclass.'/'.$this->sitemethod));
			}

            if($userInfo['status'] == 0){
                $this->message('用户被禁用，请联系管理员');
            }

            //更新最后登录时间
            $where = array(
                'user_id' => $userInfo['user_id']
            );
            $data = array(
                'last_login_time' => SITE_DATETIME
            );
            $userInfo['last_login_time'] = $data['last_login_time'];
            $this->Result_model->update('admin_user', $where, $data);

            $this->session->set_userdata('admin_info', $userInfo);
			$this->message('登录成功', site_url(MANAGER_PATH.'/'.$this->siteclass.'/index'));
		}
	
	}
	
	public function logout(){
		$this->session->set_userdata(''.$this->siteclass.'_id', '');
		$this->session->set_userdata('user_name', '');
		$this->session->set_userdata('nick_name', '');
		$this->session->set_userdata('is_root', '');
		$this->message('退出成功', site_url(MANAGER_PATH.'/'.$this->siteclass.'/login'));
	}
	
}