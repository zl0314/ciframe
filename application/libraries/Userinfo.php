<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户信息
 * @author 
 */
class Userinfo{
	static $CI;
	static $uid;
	static $member;

	public function __construct(){
		self::$CI = &get_instance();
		self::$uid = 0;
		self::$member = array();
		self::checkauth();
	}

	//得到所有会员信息
	public static function getUserinfo($field = '*', $id = ''){
		$id = $id ? $id : self::$uid;
		$sql = "select $field from ".tname( USER_TABLE )." where user_id = '$id'";
		if($field == '*' || strpos($field, ',') !== false){
			return getRow($sql);
		}else{
			return getOne($sql);
		}
	}
	
	//会员登录
	public static function userLogin($setarr, $cookietime = 0){
		$data = array();
		$data['last_time'] = date('Y-m-d H:i:s', SITE_TIME);
		$data['last_ip'] = getonlineip();
		
		if(empty($setarr['authpwd'])){
			$setarr['authpwd'] = md5(SITE_TIME);
			$data['authpwd'] = $setarr['authpwd'];
		}
		//更新信息
		updatetable(USER_TABLE, $data, array('user_id'=>$setarr['user_id']));
		//设置在线Cookie
		set_cookie('userinfo', self::authcode("{$setarr['authpwd']}|{$setarr['user_id']}|{$setarr['mobile_phone']}", 'ENCODE'), $cookietime);
		set_cookie('loginuser', $setarr['user_name'], 31536000);
	}

	//从加密Cookie(userinfo)中获取信息
	public static function checkauth(){
		$cookieInfo = get_cookie('userinfo');
		$cookieInfo = self::authcode($cookieInfo , 'DECODE', '', 3600*24*7);

		if($cookieInfo){
			@list($authpwd, $user_id, $mobile) = explode('|', $cookieInfo);
			if($user_id){
				$sql = "select * from ".tname( USER_TABLE )." where user_id = '$user_id'";
				$userinfo = getRow($sql);
				if( $userinfo['authpwd'] == $authpwd ){
					self::$uid = $user_id;
					self::$member = $userinfo;
				}else{
					set_cookie('userinfo', '', time() - 86400 );
				}
			}
		}
	}
	//用户cookie信息加密
	public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

		$ckey_length = 4;
		// 随机密钥长度 取值 0-32;
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方。
		// 当此值为 0 时，则不产生随机密钥，也就是用户每次登录的加密值都一样，反之则每次登录产生的加密值都不一样。

		$key = md5($key ? $key : SITEKEY);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
	
	
}
