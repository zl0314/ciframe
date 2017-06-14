<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_Controller extends CI_Controller {

        function __construct()
        {
                parent::__construct();

                $site_class = $this->router->class;
                $site_method = $this->router->method;
                $this->siteclass = $site_class;
                $this->sitemethod = $site_method;
                $this->data['sitemethod'] = $this->sitemethod;
                $this->data['siteclass'] = $this->siteclass;

                //性别
                $this->data['user_sex'] = array(
                    '1' => '男',
                    '2' => '女',
                    '3' => '未知'
                );
                //是否推荐到首页
                $this->data['is_recommend'] = array(
                    '0' => '不推荐',
                    '1' => '推荐',
                );
                //是否显示
                $this->data['is_show'] = array(
                    '0' => '不显示',
                    '1' => '显示',
                );
                //投资干活分类 
                $this->data['invest_type'] = array(
                    '1' => '房产',
                    '2' => '税务',
                    '3' => '财务',
                    '4' => '移民',
                );
        }

        //前台，检查数据， 并ajax返回错误
        /**
         * DEMO:
         * $data = array(
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
         */
        protected function checkData($data){
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