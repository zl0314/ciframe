<?php
/********************************************************
 *      @author Aaron
 *      @link http://mp.weixin.qq.com/wiki/home/index.html
 *      @uses $wxApi = new WxApi();
 ********************************************************/
class Wxapi{
	    const APPID = WECHAT_APPID;
        const APPSEC = WECHAT_APPSECRET;
        const MCHID = WECHAT_MCHID; //商户号
        const PKEY = WECHAT_KEY; //私钥
        const SSLCERT_PATH = WECHAT_SSLCERT_PATH;
        const SSLKEY_PATH = WECHAT_SSLKEY_PATH;
//        const WECHAT_XCX_APPID = WECHAT_XCX_APPID;
//        const WECHAT_XCX_APPSECRET = WECHAT_XCX_APPSECRET;

        public $parameters  = array();
 		public $errorMsg = '';
 		public $CI = null;
        public function __construct(){
 			$this->CI = &get_instance();
        }


	/**
	 * 
	 * 通过跳转获取用户的openid，跳转流程如下：
	 * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
	 * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
	 * 
	 * @return 用户的openid
	 */
	public function GetOpenid($baseUrl = '', $is_redirect = true){
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$urlObj = array();
			if(empty($baseUrl)){
				$baseUrl = $this->getBaseUrl();
			}
			$urlObj["appid"] = self::APPID;
			$urlObj["redirect_uri"] = urlencode($baseUrl);
			$urlObj["response_type"] = 'code';
			$urlObj["scope"] = 'snsapi_userinfo';
			$urlObj["state"] = 'STATE#wechat_redirect';
			$bizString = $this->ToUrlParams($urlObj);
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
			if($is_redirect){
				Header("Location: $url");
				exit();
			}else{
				$arr = array(
					'url' => $url,
					'towechat' => 1
				);
				return $arr;
			}
		} else {
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$openid = $this->getOpenidFromMp($code);
			return $openid;
		}
	}

		/**
         * 统一下单
         * 调用该接口在微信支付服务后台生成预支付交易单，返回正确的预支付交易回话标识后再按扫码、JSAPI、APP等不同场景生成交易串调起支付。
         * @$from  1网页版， 2小程序
         * @return  prepay_id
         */
	public function unifiedOrder($data, $timeOut = 6){
		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		//检测必填参数
		if(empty($data['out_trade_no'])) {
			$this->setErrorMsg('缺少统一支付接口必填参数out_trade_no！');
		}else if(empty($data['body'])){
			$this->setErrorMsg('缺少统一支付接口必填参数body！');
		}else if(empty($data['total_fee'])) {
			$this->setErrorMsg('缺少统一支付接口必填参数total_fee！');
		}else if(empty($data['trade_type'])) {
			$this->setErrorMsg('缺少统一支付接口必填参数trade_type！');
		}
		
		//关联参数
		if($data['trade_type'] == 'JSAPI' && empty($data['openid'])){
			$this->setErrorMsg('统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！');
		}
		if( $data['trade_type'] == 'NATIVE' && empty($data['product_id'])){
			$this->setErrorMsg('统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！');
		}
		
		$data['appid'] = self::APPID;//公众账号ID
		$data['mch_id'] = self::MCHID;//商户号
		// $data['bill_create_ip'] = getonlineip();//终端ip	  
		//$inputObj->SetSpbill_create_ip("1.1.1.1");  	    
		$data['nonce_str'] = $this->getNonceStr();//随机字符串
		
		//签名
		$sign = $this->MakeSign($data);
		$data['sign'] = $sign;
		$xml = $this->ToXml($data);
		
		$response = $this->postXmlCurl($xml, $url, false, $timeOut);
		$response = $this->FromXml($response);
		if(!empty($response) && $response['return_code'] == 'FAIL'){
			$this->setErrorMsg($response['return_msg']);
		}
		
		return $response;
	}
	
	/**
	 * 获取jsapi支付的参数
	 * @param array $UnifiedOrderResult 统一支付接口返回的数据
	 * @return json数据，可直接填入js函数作为参数
	 */
	public function GetJsApiParameters($UnifiedOrderResult){
		if(!array_key_exists("appid", $UnifiedOrderResult) )		{
			fail("参数错误");
		}
		if(!array_key_exists("prepay_id", $UnifiedOrderResult) || $UnifiedOrderResult['prepay_id'] == "" ){
			fail('prepay_id不能为空');
		}
		$timeStamp = time();
		$data = array(
			'appId' => $UnifiedOrderResult['appid'],
			'timeStamp' => "$timeStamp",
			'nonceStr' => $this->getNonceStr(),
			'package' => 'prepay_id='.$UnifiedOrderResult['prepay_id'],
			'signType' => 'MD5',
		);
		$sign = $this->MakeSign($data);
		$data['paySign'] = $sign;
		$parameters = json_encode($data);
		return $parameters;
	}

	//微信获取AccessToken 返回指定微信公众号的at信息
    public function wxAccessToken($appId = NULL , $appSecret = NULL){
            $appId          = is_null($appId) ? self::APPID : $appId;
            $appSecret      = is_null($appSecret) ? self::APPSEC : $appSecret;
           
            $sql = "select * from ".tname('jssdk').' where 1';
            $data = $row = getRow($sql, 'jssdk');

            if ($data['token_time'] < time()) {
                $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
                $result = $this->postXmlCurl('', $url);
                //print_r($result);
                $jsoninfo       = json_decode($result, true);
                $access_token   = $jsoninfo["access_token"];
                if ($access_token) {
                    $update_data['token_time'] = time() + 7000;
                    $update_data['token'] = $access_token;
                    $update_data['addtime'] = time();
                    if(!empty($row['id'])){
                        $where = array('id' => $row['id']);
                        updatetable('jssdk', $where, $update_data);
                    }else{
                        $update_data['addtime'] = time();
                        inserttable('jssdk', $update_data);
                    }
                }
            }
            else {
                $access_token = $data['token'];
            }
            return $access_token;
    }

    //微信获取AccessToken 返回指定微信公众号的at信息
    public function wxJsApiTicket($appId = NULL , $appSecret = NULL){
            $appId          = is_null($appId) ? self::APPID : $appId;
            $appSecret      = is_null($appSecret) ? self::APPSEC : $appSecret;
            $sql = "select * from ".tname('jssdk').' where 1';
            $data = $row = getRow($sql, 'jssdk');
            if ($data['ticket_time'] < time()) {                
                $url        = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$this->wxAccessToken();
                $result         = $this->postXmlCurl('', $url);
                $jsoninfo       = json_decode($result, true);
                $ticket = $jsoninfo['ticket'];
                if ($ticket) {
                    $update_data['ticket_time'] = time() + 7000;
                    $update_data['ticket'] = $ticket;
                    if(!empty($row['id'])){
                        $where = array('id' => $row['id']);
                        updatetable('jssdk', $where, $update_data);
                    }else{
                        $update_data['addtime'] = time();
                        inserttable('jssdk', $update_data);
                    }
                }
            }else {
                $ticket = $data['ticket'];
            }
            return $ticket;
    }

    /**
	 * 
	 * 查询订单，WxPayOrderQuery中out_trade_no、transaction_id至少填一个
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 * @param 订单信息 
	 * @param int $timeOut
	 * * @$from  1网页版， 2小程序
	 */
	public function orderQuery($data, $timeOut = 6){
		$url = "https://api.mch.weixin.qq.com/pay/orderquery";
		$data['appid'] = self::APPID;

		$data['mch_id'] = self::MCHID;
		$data['nonce_str'] = self::getNonceStr();
		$sign = $this->MakeSign($data);
		$data['sign'] = $sign;
		$xml = $this->ToXml($data);

		$response = $this->postXmlCurl($xml, $url, false, $timeOut);
		$response = $this->FromXml($response);
		if($this->checkSign($response)){
			return $response;
		}
		return null;
	}

	/**
	 * 申请退款，WxPayRefund 中out_trade_no、transaction_id 至少填一个且
	 * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
	 * appid、mchid、spbill_create_ip、nonce_str不需要填入
	 */
	public function refoundQuery($data, $timeOut = 10){
		$url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
		$data['appid'] = self::APPID;
		$data['mch_id'] = self::MCHID;
		$data['nonce_str'] = self::getNonceStr();
		$sign = $this->MakeSign($data);
		$data['sign'] = $sign;
		$xml = $this->ToXml($data);

		$response = $this->postXmlCurl($xml, $url, 1, $timeOut);
		$response = $this->FromXml($response);
		return $response;
	}

    //微信JSAPI分享等接口 签名包
    public function getSignPackage() {
	    $jsapiTicket = $this->wxJsApiTicket();
	    $url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'];
	    $timestamp = time();
	    $nonceStr = $this->getNonceStr();

	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	    $signature = sha1($string);
	    $signPackage = array(
	      "appId"     => self::APPID,
	      "nonceStr"  => $nonceStr,
	      "timestamp" => $timestamp,
	      "url"       => $url,
	      "signature" => $signature,
	      "rawString" => $string
	    );
	    return $signPackage; 
	}

	//验证签名
	public function checkSign($data){
		$source_sign = $data['sign'];
		unset($data['sign']);
		$make_sign = $this->MakeSign($data);
		if($source_sign == $make_sign){
			return true;
		}
		return false;
	}
	
	/**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
	public function FromXml($xml){
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($xmlObj), true);		
		return $data;
	}
     /**
	 * 生成签名
	 * @return 签名
	 */
	public function MakeSign($orderData){
		//签名步骤一：按字典序排序参数
		ksort($orderData);
		$string = $this->ToUrlParams($orderData);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".self::PKEY;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}
	
	/**
	 * 
	 * 产生随机字符串，不长于32位
	 * @param int $length
	 * @return 产生的随机字符串
	 */
	public static function getNonceStr($length = 32){
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}

	/**
	 * 格式化参数格式化成url参数
	 */
	public function ToUrlParams($orderData){
		$buff = "";
		foreach ($orderData as $k => $v){
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		$buff = trim($buff, "&");
		return $buff;
	}

	/**
	 * 组合xml字符
	**/
	public function ToXml($data){
		if(!is_array($data) || count($data) <= 0){
    		$this->setErrorMsg('数组数据异常！', 1);
    	}
    	
    	$xml = "<xml>";
    	foreach ($data as $key=>$val)
    	{
    		if (is_numeric($val)){
    			$xml.="<".$key.">".$val."</".$key.">";
    		}else{
    			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    		}
        }
        $xml.="</xml>";
        return $xml; 
	}

	//设置错误提示
	public function setErrorMsg($msg, $code = 1){
		$this->errorMsg = $msg;
		mobileMessage($this->errorMsg, 1);
		exit;
	}

	/**
	 * 以post方式提交xml到对应的接口url
	 * 
	 * @param string $xml  需要post的xml数据
	 * @param string $url  url
	 * @param bool $useCert 是否需要证书，默认不需要
	 * @param int $second   url执行超时时间，默认30s
	 * @throws WxPayException
	 */
	private function postXmlCurl($xml, $url, $useCert = false, $second = 30) {
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//严格校验
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($useCert == true){
			//设置证书
			//使用证书：cert 与 key 分别属于两个.pem文件
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, self::SSLCERT_PATH);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, self::SSLKEY_PATH);
		}

		//post提交方式
		if($xml){
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		}
		
		//运行curl
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			$this->setErrorMsg('curl出错，错误码:'.$error, 2);
		}
	}

	/**
	 * 
	 * 通过code从工作平台获取openid机器access_token
	 * @param string $code 微信跳转回来带上的code
	 * 
	 * @return openid
	 */
	public function GetOpenidFromMp($code){
		$urlObj = array();
		$urlObj["appid"] = self::APPID;
		$urlObj["secret"] = self::APPSEC;
		$urlObj["code"] = $code;
		$urlObj["grant_type"] = 'authorization_code';
		
		$bizString = $this->ToUrlParams($urlObj);
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;

		$data = curlgetInfo($url);
		$data = json_decode($data,true);
		if(!empty($data['errcode']) && $data['errcode'] != 0){
			$this->setErrorMsg($data['errmsg']);
		}
		$openid = $data['openid'];
		return $openid;
	}

	//得到回调地址
	public function getBaseUrl(){
		$class_method = $this->CI->uri->uri_string;
		$url = site_url('/').''.$class_method.'?'.$_SERVER['QUERY_STRING'];
		return $url;
	}
}