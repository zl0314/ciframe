<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Author       : Aaron Zhang
 * createTime   : 2017/3/7 12:00
 * Description  : 发送短信验证码
 *
 */
class Sendsms extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Sms_log_model');
        $this->load->model('User_model');
    }

    public function index() {
        $post = parsePostData();
        if(empty($post)){
            fail('参数错误');
        }
        $smstype = !empty($post['smstype']) ? $post['smstype'] : 'register';
        submitcheck('dosubmit');
        $mobile = !empty($post['mobile']) ? $post['mobile'] : '';

        if(empty($mobile)){
            fail('手机号不能为空');
        }
        $where = array('mobile_phone' => $mobile );
        $vo = $this->Result_model->getRow('users','user_id' , $where );

        if( $smstype == 'register' && !empty($vo) ){
            fail('手机号已经注册');
        }

        if( ( $smstype == 'findpwd' || $smstype =='modifypwd' ) && empty($vo) ){
            fail('手机号还没有注册');
        }

        //手机号是否已经注册
        if($smstype == 'update_mobile' && !empty($vo)){
            fail('手机已经注册，请重试');
        }

        $code = rand(100000 , 999999);
        $sms_content['findpwd'] = '尊敬的用户，您正在进行修改密码操作，验证码：%s（工作人员不会向您索取，请勿泄露）';
        $sms_content['modifypwd'] = '尊敬的用户，您正在进行修改密码操作，验证码：%s（工作人员不会向您索取，请勿泄露）';
        $sms_content['register'] = '尊敬的用户，感谢您注册成为家门活动会员，验证码：%s（10分钟内有效）';
        $sms_content['update_mobile'] = '尊敬的用户，您正在进行修改手机号操作，验证码：%s（10分钟内有效）';

        if(empty($mobile)){
            fail('手机号不能为空');
        }else if(!istelphone($mobile)){
            fail('请填写正确手机号');
        }

        //防刷短信， 一个IP一天只能发送5条
        $this->Sms_log_model->checkSendCount($mobile, $smstype);

        //$res = sendUserMessage($mobile, sprintf( $sms_content[$smstype] , $code ));
        $res = 1;
        if($res){
            $data = array(
                'code' => $code,
                'message' => sprintf( $sms_content[$smstype] , $code ),
                'mobile' => $mobile,
                'addtime' => time(),
                'source' => $smstype,
                'ip_address' => getonlineip()
            );
            $this->Result_model->save( 'sms_log', $data );
            $arr = array(
                'err' => 0,
//                'msg' => '验证码发送成功，请注意查收',
                'msg' => sprintf( $sms_content[$smstype] , $code )
            );
           success('验证码发送成功，请注意查收');
        }else{
           fail('验证码发送失败');
        }
    }

}