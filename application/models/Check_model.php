<?php
/**
 * Author       : Aaron Zhang
 * createTime   : 2017/4/19 13:40
 * Description  :  数据检查
 *
 */
class Check_model extends MY_Model {
    public $table;
    /**
     * @auther ZhangLong
     * @version 2016-06-28
     */
    function __construct(){
        parent::__construct() ;
    }

    /**
     * 检查数据是否存在
     */
    public function checkRow($tb, $primary, $id, $field = '*'){
        if(!$id){
           showMessage('参数错误');
        }
        $where = array(
            $primary => $id
        );
        $row = $this->Result_model->getRow($tb, $field, $where);
        if(empty($row)){
           showMessage('内容不存在');
        }
        return $row;
    }
}