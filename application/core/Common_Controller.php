<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_Controller extends CI_Controller {
    //是否是微信端
    public $is_wechat = 0;
    //是否是手机端
    public $is_mobile = 0;

    //控制器
    public $siteclass;
    //方法
    public $sitemethod;

    function __construct() {
        parent::__construct();

        $this->siteclass = $this->router->class;
        $this->sitemethod = $this->router->method;

        $this->data['siteclass'] = $this->router->class;
        $this->data['sitemethod'] = $this->router->method;

        //判断是否是微信
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            $this->is_wechat = true;
        }
        //判断是否是手机端
        $ua = new CI_User_agent();
        $this->is_mobile = $ua->is_mobile;

    }

    //前台，检查数据， 并ajax返回错误
    /**
     * @param $data 要检查的数据
     * DEMO:
     *
     $data = array(

        'user_name' => '您的姓名',
        'mobile' => array(
                'title' => '联系电话',
                'rule' => 'valid_telphone'
        ),
        'intro' => '预约说明',
        'project_id' => '所在项目',
        'meet_time' => '预约时间',
    );
    $this->checkData($data);
    * @return json
    */
    protected function checFrontkData($data){
        //验证规则
        foreach($data as $name => $title){
            $rule = 'trim|required';
            $tip_str = $title;
            if(is_array($title)){
                    $tip_str = $title['title'];
                    $rule .= '|'.$title['rule'];
            }
            $this->form_validation->set_rules($name, $tip_str, $rule);
        }

        if($this->form_validation->run() == FALSE){
            $error = form_error($name);
            if($error){
                fail(strip_tags($error));
            }
        }
    }
}