<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Author       : Aaron Zhang
 * createTime   : 2017/3/7 14:10
 * Description  : 短信发送管理模型
 *
 */

class Sms_log_model extends CI_Model{
    function __construct() {
        parent::__construct() ;
    }

    /**
     * @param $mobile   发送短信的手机号
     * @param $smstype  发送短信类型
     * @output JSON
     */
    public function checkSendCount($mobile, $smstype){
        $ip = getonlineip();
        $time_start  = strtotime(date('Y-m-d'));
        $time_end = strtotime(date('Y-m-d 23:59:59'));
        $where = array(
            'ip_address' => $ip,
            'addtime >=' => $time_start,
            'addtime <=' => $time_end,
            'mobile' => $mobile,
            'source' => $smstype
        );
        $count = $this->Result_model->getOne('sms_log','count(id) as cnt', $where);
        if($count > ALLOW_SEND_SMS_COUNT){
            fail('每天只能发送' . ALLOW_SEND_SMS_COUNT . '次短信');
        }
    }

    /**
     * @param $mobile   手机号
     * @param $smstype  短信类型
     * @param $code     用户输入的验证码
     */
    public function checkSmsCode($mobile, $smstype){
        $where = array(
            'mobile' => $mobile,
            'source' => $smstype
        );
        $post = parsePostData();
        $code = $post['smscode'];
        if(empty($code)){
            fail('验证码不能为空');
        }
        $row = $this->Result_model->getRow('sms_log', 'id,code,addtime,mobile', $where, 'id desc');
        if(!empty($row)){
            if( $row['code'] !=  $code){
               fail('手机验证码错误');
            }
            if(time() > ( $row['addtime'] + 600 ) ){
                fail('手机验证码已过期');
            }
        }else{
            fail('验证码错误');
        }
    }

}