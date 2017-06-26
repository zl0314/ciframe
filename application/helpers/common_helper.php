<?php

function get_page($tb, $where = array(), $field = '*', $order = '',  $page_query = '', $perpage = 10 ){
    $CI =& get_instance();
    $form_data = !empty($CI->data['form_data']) ? $CI->data['form_data'] : $CI->form_data;
    //拼接搜索条件
    if(!empty($form_data)){
        foreach($form_data as $k => $r){
            if(empty($r['show_in_search'])){
                continue;
            }
            if(isset($_GET[$k]) && $_GET[$k] !== ''){
                if(isset($r['search_type'])){
                    $where['like'] = array($k => _get($k));
                }else{
                    $where[$k] = _get($k);
                }
            }
        }
    }


    $model = $CI->Result_model;
    $total_rows_row = $model->getRow($tb, "Count(*) as cnt " , $where, $order);
    $page['total_rows'] = $total_rows_row['cnt'];
    // 加载分页类
    $CI->load->library('pagination');
    $pagination = new CI_Pagination();

    //路由
    $RTR =& load_class('Router');
    // 当前 控制器
    $siteclass = $RTR->fetch_class();
    //  当前方法
    $sitemethod = $RTR->fetch_method();

    // 分页属性配置
    $current_page = intval(max(1 , $CI->input->get('per_page')));

    //每页数量
    $page['per_page'] = isset( $page['per_page'] ) ? $page['per_page'] : ( $perpage ? $perpage : 10);
    $page['cur_page'] = ( $current_page < 1 ) ? 1 : $current_page;
    //页码
    $page['offset'] = ($page['cur_page']-1)*$page['per_page'];


    $page['first_link'] = ' 第一页 ';
    $page['last_link'] = ' 末页 ';
    $page['next_link'] = ' 下一页 ';
    $page['prev_link'] = ' 上一页 ';
    $page['use_page_numbers'] = TRUE;
    $page['page_query_string'] = TRUE;
    
    $GLOBALS['total_rows'] = $page['total_rows'];
    $GLOBALS['curpage'] = $page['cur_page'];
    $GLOBALS['perpage'] = $page['per_page'];

    //查询数据
    $data = array();
    $data['total_rows'] = $page['total_rows'];
    
    if($model){
        $data['list'] = $model->getList($tb, $field , $where ,$page['per_page'] , $page['offset'] , $order);
        $page['base_url'] = '';
        $page['base_url'] .= $page_query ? $page['base_url'].'/'.$page_query : $page['base_url'];
        $page['base_url'] .= sprintf('?hash=1');
        if(!empty($_SERVER['QUERY_STRING'])){
            $strA = explode('&', $_SERVER['QUERY_STRING']);
            $con = '&';
            $strA = array_unique($strA);
            foreach($strA as $k => $r){
                $rA = explode('=', $r);
                if($rA[0] != 'per_page'){
                    $page['base_url'] .= $con.$rA[0].'='.$rA[1];
                }
            }
        }
        $pagination->initialize( $page );
        $data['page_html'] = $pagination->create_links();
    }
    return $data;
}

//得到列表页面HTML

//得到列表页面HTML
function get_list_html($table_data, $data, $has_list_order = false, $more_operater = '', $has_operate = true){

    $CI =& get_instance();
    //路由
    $RTR =& load_class('Router');
    // 当前 控制器
    $siteclass = $RTR->fetch_class();
    //  当前方法
    $sitemethod = $RTR->fetch_method();
    //主键
    $primary = '';

    $html = get_search_item($table_data);

    //thead
    $html .= '<form action="" id="Form" method="post"><div class="set-area"><table class="table table-s1" width="100%" cellpadding="0" cellspacing="0" border="0"> <thead class="tb-tit-bg"><tr>';
    $html .= ' <th><div class="th-gap"><input type="checkbox" onclick="selallck(this)"> </div></th>';

    //列表标题
    foreach($table_data as $k => $r){
        if(!empty($r['is_primary'])){
            $primary = $k;
        }
        if(empty($r['show_in_table'])){
            continue;
        }
        $html .= '<th ><div class="th-gap" >'.$r['field'].'</div></th>' ;
    }

    $html .= $has_operate ? ' <th ><div class="th-gap">操作</div></th>' : '';
    $html .= '</tr></thead>';

    //tfoot
    $page_html = !empty($data['page_html']) ? $data['page_html'] : '';
    $lit_order_html = $has_list_order ? '<input type="button" value="排 序"onclick="listorder()">' : '';
    $html .= '<tfoot class="td-foot-bg"><tr><td>'.$lit_order_html.'<input type="button" class="batch delect_batch" value="删 除" onclick="delitem(\'a\', this)">
    一共 <b>'.$GLOBALS['total_rows'].'</b> 条数据 <div class="pre-next"> '.$page_html.'</div></td></tr></tfoot>';

    //tbody
    $html .= '<tbody>';
    if(!empty($data['list'])){
        foreach($data['list'] as $k => $r){
            $html .= '<tr id="item_'.$r[$primary].'">';
            foreach($table_data as $tk => $tr){
                $value = !empty($r[$tk]) ? $r[$tk] : '';
                if(empty($tr['show_in_table']) && empty($tr['is_primary'])){
                    continue;
                }
                //多选按钮
                if(!empty($tr['is_primary'])){
                    $primary = $tk;
                    $html .= '<td><input type="checkbox" name="ids[]" value="'.$r[$tk].'" ></td>';
                    continue;
                }

                if(!empty($tr['data'])){
                    if(strpos($r[$tk], ',') !== false){
                        $list_arr = explode(',', $r[$tk]);
                        $list_arr_html = '';
                        $list_arr_html_contact = '';
                        foreach($list_arr as $lk => $lr){
                            $list_arr_html .= $list_arr_html_contact . $tr['data'][$lr];
                            $list_arr_html_contact = ',';
                        }
                        $html .=  '<td>'.$list_arr_html.'</td>';continue;
                    }else{
                        $html .=  '<td>'.$tr['data'][$r[$tk]].'</td>';continue;
                    }
                }
                //时间转换
                if(!empty($tr['type']) && $tr['type'] == 'time'){
                    if(!empty($r[$tk]) && is_numeric($r[$tk])){
                        $html .=  '<td>'.strtotime($tr['format'], $value).'</td>';
                    }else{
                        $html .=  '<td>'.$value.'</td>';
                    }
                    continue;
                }

                $html .=  '<td>'.$value.'</td>';
            }
            //其它的操作项
            $more_operater = str_replace(
                ['{id}'],
                [$r[$primary]],
                $more_operater
            );

            $html .= $has_operate ? '<td>
                        <a  title="编辑" href="'.get_url(MANAGER_PATH . '/'.$siteclass . '/edit/' . $r[$primary]).'">编辑</a> |
                        <a  onclick="delitem(\''.$r[$primary].'\',this)"  title="删除" href="javascript:;">删除</a><br>
                        '.$more_operater.'
                        </td>' : '';
            $html .= '</tr>';
        }
    }else{
        $html .= '<tr><td><div class="no-data">没有数据</div></td></tr>';
    }

    $html .= '</tbody></table></div></form>';

    return $html;
}

//获取搜索表单
function get_search_item($table_data){
    $show_search = false;
    $html = '<form action="" method="get" id="searchForm">';
    foreach($table_data as $k => $r){
        //过滤搜索选项
        if(empty($r['show_in_search'])){
            continue;
        }
        $show_search = true;

        if(empty($r['data'])){
            $html .= $r['field'] . ' &nbsp;&nbsp;<input class="input-txt" type="text" value="'._get($k).'" name="'.$k.'" />';
        }else{
            $html .= $r['field'] . '&nbsp;&nbsp;<select name="'.$k.'" id="'.$k.'" onchange="$(\'#searchForm\').submit()"><option value="">请选择</option>';
            foreach($r['data'] as $dk => $dr){
                $selected = '';
                if(isset($_GET[$k]) && $_GET[$k] !== '' && $dk == $_GET[$k]){
                    $selected = 'selected';
                }
                $html .= '<option value="'.$dk.'" '.$selected.'>'.$dr.'</option>';
            }
            $html .= '</select>&nbsp;&nbsp;';
        }
    }
    $html .= $show_search ? '&nbsp;&nbsp;<input type="submit" value="搜 索" class="batch delect_batch input-button" />' : '';
    $html .= '</form>';
    return $html;
}

/**
 * 创建多级文件夹 参数为带有文件名的路径
 * @param string $path 路径名称
 */
function creat_dir_with_filepath($path,$mode=0777){
    return creat_dir(dirname($path),$mode);
}

/**
 * 创建多级文件夹
 * @param string $path 路径名称
 */
function creat_dir($path,$mode=0777){
    if(!is_dir($path)){
        if(creat_dir(dirname($path))){
            return @mkdir($path,$mode);
        }
    }else{
        return true;
    }
}


/**
 * 执行成功输出, 用ajax请求输出JSON数据
 * @param array $data
 * @param string $message
 * @param boolean $is_app
 * @param int $success
 */
function success($data = array() , $message = '', $success = '1'){
    $result = array(
        'success' => $success,
        'data' => $data,
        'message' => $message,
    );
    if(!is_array($data)){
        $result['message'] = $data;
    }
    if(is_array($message)){
        $result['data'] = $message;
        $result['message'] = !empty($message['message']) ? $message['message'] : $message;
    }
    echo json_encode( $result );exit;
}

/**
 * 执行失败输出, 用ajax请求输出JSON数据
 * @param array $data
 * @param string $message
 * @param boolean $is_app
 * @param int $success
 */
function fail($message = '', $data = array()){
    success($data, $message,'0');
}

/**
 * 错误信息提示
 * @param $err  错误提示
 * @param string $url  要跳转到的URL
 * @param int $sec   N秒后跳转到$url
 */
function showMessage($err, $url = '', $sec = 2){
    $CI = &get_instance();
    $prefix = $CI->uri->segment(1);

    $data = array(
        'sec' => $sec*1000,
        'url' => reMoveXss($url),
        'err' => reMoveXss($err)
    );

    if($prefix == MANAGER_PATH){
        $CI->load->view(MANAGER_PATH.'/message', $data);
    }else{
        if($CI->agent->is_mobile){
            mobileMessage($err, 1);exit;
        }else{
            $CI->load->view('message', $data);
        }
    }
}
/*
 * 获取完整表名
 * @param string $tb 
 */
function tname($tb){
   return DB_PREFIX.$tb;
}
/**
 * 打印数组，
 * @param array $arr
 */
function P($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}
/**
 * 打印数组，并退出
 * @param array $arr
 */
function dd($var){
    var_dump($var);
    exit;
}

//过滤字符
function newhtmlspecialchars($string) {
    if(is_array($string)){
        return array_map('newhtmlspecialchars', $string);
    } else {
        $string = htmlspecialchars($string);
        $string = sstripslashes($string);
        $string = saddslashes($string);
        return trim($string);
    }
}
//去掉slassh
function sstripslashes($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = sstripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}
function saddslashes($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = saddslashes($val);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}


/**
 * 得到$_POST下某值
 * @param string $key
 * @param string $default 默认值
 * @param bool $strict 是否严谨模式，在严谨模式下，会判断值是否为空
 * @return array string  NULL
 * @author ZhangLong
 * @date 2015-05-12
 */
function _post($key = '', $default = '', $strict = false, $act = '_POST'){
    $method = $_POST;
    if($act == '_GET'){
        $method = $_GET;
    }
    if($key){
        if(!$strict){
            if(isset($method[$key])){
                return newhtmlspecialchars($method[$key]);
            }else{
                return $default;
            }
        }else{
            if(!empty($method[$key])){
                return newhtmlspecialchars($method[$key]);
            }else{
                return $default;
            }
        }
    }else{
        return newhtmlspecialchars($method);
    }
}
/**
 * 得到$_GET下某值
 * @param string $key
 * @param string $default 默认值
 * @param bool $strict 是否严谨模式，在严谨模式下，会判断值是否不为空
 * @return array string  NULL
 * @author ZhangLong
 * @date 2015-05-12
 */
function _get($key = '', $default = '', $strict = false){
    return _post($key, $default, $strict,'_GET');
}

//判断提交是否正确
function submitcheck($var) {
    if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == formhash()) {
            return TRUE;
        } else {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
                fail('您的请求来路不正确或表单验证串不符，无法提交');
            }else{
                showMessage('您的请求来路不正确或表单验证串不符，无法提交');
            }
        }
    } else {
        return FALSE;
    }
}

/**
 * 表单验证随即码
 */
function formhash() {
    $CI =& get_instance();
    $formhash = substr(md5(substr(SITE_TIME, 0, -4).'|'.md5(SITEKEY)), 8, 8);
    return $formhash;
}

//根据原图片，给图片加前缀
function getNewImg($url, $prefix = 'thumb_', $addpath = true){
    $sourceImg = explode('/', $url);
    $imgName = $sourceImg[count($sourceImg) - 1];
    unset( $sourceImg[count($sourceImg) - 1]);
    if($addpath){
        $newImg = '.'.implode('/', $sourceImg).'/'.$prefix.$imgName;
    }else{
        $newImg = implode('/', $sourceImg).'/'.$prefix.$imgName;
    }
    return $newImg;
}

/**
 +----------------------------------------------------------
 * 字符串命名风格转换
 * type
 * =0 将Java风格转换为C的风格
 * =1 将C风格转换为Java的风格
 +----------------------------------------------------------
 * @access protected
 +----------------------------------------------------------
 * @param string $name 字符串
 * @param integer $type 转换类型
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function parse_name($name,$type=0) {
    if($type) {
        return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
    }else{
        $name = preg_replace("/[A-Z]/", "_\\0", $name);
        return strtolower(trim($name, "_"));
    }
}

//判断是否为邮箱格式
function isemail($email) {
    return strlen ( $email ) > 8 && preg_match ( "/^[-_+.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+([a-z]{2,4})|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email );
}

function isusername($string){
    //只允许汉字，大小写字母，数字作为用户名
    return preg_match("/^[\x{4e00}-\x{9fa5}|a-z|A-Z|0-9]+$/u", $string);
}

//是否是正确的URL
function is_url($url){
    return preg_match('/http:\/\/([a-zA-Z0-9-]*\.)+[a-zA-Z]{2,3}/', $url);
}

//姓名格式判断
function isrealname($string){
    //只允许汉字，大小写字母，数字作为用户名
    return preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $string);
}

/**
 * @desc 检查是否是合法的手机号格式，现阶段合法的格式：以13,15,18,17开头的11位数字
 * @param $cellphone
 */
function istelphone($cellphone) {
    $pattern = "/^(13|15|18|17|14){1}\d{9}$/";
    return str_match($pattern, $cellphone);
}
//检查是否是合法的固定电话
function iscellphone($telphone) {
    $pattern = "/^(0){1}[0-9]{2,3}\-\d{7,8}(\-\d{1,6})?$/";
    return str_match($pattern, $telphone);
}

//是否身份证号
function isidcard($idcard){
    $cardnumPattern = '/^\d{6}((1[89])|(2\d))\d{2}((0\d)|(1[0-2]))((3[01])|([0-2]\d))\d{3}(\d|X)$/i';
    $match = preg_match($cardnumPattern, $idcard);
    return $match;
}
//字符串匹配
function str_match($pattern, $str){
    if(!empty($str)){
        if(preg_match($pattern, $str)) {
            return TRUE;
        }
    }
    return FALSE;
}

//用户密码获取随机码，
function password_salt($len = 6){
    $salt = substr(md5(time().uniqid().SITEKEY), 0, $len);
    return $salt;
}

//用户密码加密  密码+salt
function password($password, $salt = '', $returnarr = false){
    $salt = $salt ? $salt : password_salt();
    $password = md5(md5($password).$salt);
    if($returnarr){
        return array('password' => $password, 'salt' => $salt);
    }
    return $password;
}

//检查用户密码是否正确
/**
 * $post_password 用户输入密码
 * $salt 此用户名下salt字段
 * $dbpassword 此用户 表中的密码
 */
function check_password($post_password, $salt, $dbpassword){
    if($post_password && $salt && $dbpassword){
        $password = md5(md5($post_password).$salt);
        if($password == $dbpassword){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
//如果URL没有HTTP， 添加HTTP， 如果URL为空，则链接为javascript:;
function get_add_http_url($url){
    if($url){
        if(strpos($url, 'http') !== false){
            return $url;
        }else{
            return 'http://'.$url;
        }
    }else{
        return 'javascript:;';
    }
}

function mdate($time = NULL) {
    $text = '';
    $time = $time === NULL || $time > time() ? time() : $time;
    $t = time() - $time; //时间差 （秒）
    $y = date('Y', $time)-date('Y', time());//是否跨年

    switch($t){
     case $t == 0:
       $text = '刚刚';
       break;
     case $t < 60:
      $text = $t . '秒前'; // 一分钟内
      break;
     case $t < 60 * 60:
      $text = floor($t / 60) . '分钟前'; //一小时内
      break;
     case $t < 60 * 60 * 24:
      $text = floor($t / (60 * 60)) . '小时前'; // 一天内
      break;
     case $t < 60 * 60 * 24 * 3:
      $text = floor($time/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ; //昨天和前天
      break;
     case $t < 60 * 60 * 24 * 30:
      $text = date('m月d日', $time); //一个月内
      break;
     case $t < 60 * 60 * 24 * 365&&$y==0:
      $text = date('m月d日', $time); //一年内
      break;
     default:
      $text = date('Y年m月d日', $time); //一年以前
      break; 
    }
    return $text;
}

/**
 * 后台Form表单Input表单获取
 * @param string 表单名
 * @param string 标题
 * @param array 数据项
 * @param string 提示信息
 * @param int  输入框类型 1input  2textarea 3上传文件-图片 4时间 5 文件上传--文件
 * @paream bool 是否使用编辑器 $editor
 * @param bool 是否将内容放进input的value属性内
 */
function getInput($name = '', $title = '', $row = array(), $tip='',$readonly = false){
    $value = !empty($row[$name]) ? html_entity_decode($row[$name]) : set_value('data['.$name.']', '', false);
    if($name == 'listorder' && $value === ''){
        $value = '0';
    }
    //错误提示文字
    $form_error = form_error('data['.$name.']');
    $readonly = $readonly ? 'readonly' : '';
    //输入框
    $input = '<input class="input-txt" id="'.$name.'" '.$readonly.' type="text" name="data['.$name.']" value="'.$value.'">';

    echo '<div class="form-row" id="div_'.$name.'" >
          <label for="'.$name.'" class="form-field">'.$title.'</label>
          <div class="form-cont">
          '.$input.$form_error.'<br><span style="display:block;margin-top:5px;">'.$tip.'</span>
          </div>
        </div>';
}

/**
 * 后台Form表单时间插件
 * @param string 表单名
 * @param string 表单标题
 * @param array 数据项
 * @param string 提示信息
 * @param int  输入框类型 1input  2textarea
 * @paream bool 是否使用编辑器 $editor
 */
function getTime($name, $title,  $row = array(), $format = 'yyyy-MM-dd HH:mm:ss', $tip = ''){
    $value = !empty($row[$name]) ? $row[$name] : date('Y-m-d H:i:s');
    echo '<div class="form-row">
          <label for="'.$name.'" class="form-field">'.$title.'</label>
          <div class="form-cont">
              <input type="text" name="data['.$name.']" class="input-txt Wdate"   onClick="WdatePicker({ dateFmt:\''.$format.'\',readOnly:true})" value="'.$value.'">
          </div>
      </div>';
}

/**
 * @param string 表单名
 * @param string 表单标题
 * @param string 表单值
 * @param array  数据项
 */
function getNoneInput($name, $title,  $value = '', $row = array()){
    $value = $value ? $value : ( !empty($row[$name]) ? $row[$name] : '');
    $input = '<input class="input-txt" style="background:#ccc"   id="'.$name.'" readonly type="text" name="data['.$name.']" value="'.$value.'">';
    echo '<div class="form-row">
          <label for="'.$name.'" class="form-field">'.$title.'</label>
          <div class="form-cont">
               '.$input.'
          </div>
      </div>';
}

/**
 *
 * 得到Select表单
 * @param $name 表单名
 * @param $title  表单标题
 * @param bool|true $is_required 是否必填
 * @param array $opts  数据列表
 * @param int $selected 选中数据
 * @param string $attr   其它属性
 */
function getSelect($name, $title, $is_required = true, $opts = array(), $selected = 0, $attr = '', $readonly = false){
    $required = $is_required ? 'required ' : '';
    $selected = !empty($selected) && is_array($selected) ? $selected[$name] : $selected;
//    $selected = !empty($selected) ? $selected : $row[$name];
    //错误提示文字
    $tip = form_error('data['.$name.']');
    $opt = '<option value="">请选择</option>';
    if(!empty($opts)){
        foreach($opts as $k => $r){
            $sel = '';
            if($selected == $k){
                $sel = 'selected';
            }
            $opt .= '<option value="'.$k.'" '.$sel.'>'.$r.'</option>';
        }
    }
    $readonly_str = $readonly ? 'disabled="disabled"' : '';
    echo '<div class="form-row">
              <label for="'.$name.'" class="form-field">'.$title.'</label>
              <div class="form-cont">
                      <select '.$readonly_str.' id="'.$name.'" name="data['.$name.']" '.$required.$attr.'  >
                          '.$opt.'
                      </select> '.$tip.'
              </div>
          </div>';
}

//隐藏表单
function getHidden($name, $row){
    $value = !empty($row[$name]) ? $row[$name] : '';
    echo "<input type='hidden' name='data[$name]' value='$value' id='$name'>";
}

/**
 * @return Array 解析加密后的请求数据
 */
function parsePostData(){
    $result = '';
    $data = _post('data');
    if(empty($data)){
        return false;
    }
    $data = base64_decode($data);
    $data = parse_str($data, $result);
    return $result;
}


/**
 * 发送短信给用户
 * @$mobile  手机号
 * @$content 短信内容
 */
function sendUserMessage($mobile = '', $content = ''){
    if($mobile == '' && $content == ''){
        return false;
    }
    $sn ='SDK-WKS-010-01094'; //提供的账号
    $pwd= strtoupper(md5($sn.'605697'));
    $data = array(
        'sn' => $sn, //提供的账号
        'pwd' =>$pwd, //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
        'mobile' => $mobile, //手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
        'content' =>$content .'【家门活动】',
        'ext' => '',
        'stime' => '', //定时时间 格式为2011-6-29 11:09:21
        'rrid' => '',//默认空 如果空返回系统生成的标识串 如果传值保证值唯一 成功则返回传入的值
        'msgfmt'=>''
    );
    $url = "http://sdk2.entinfo.cn:8061/mdsmssend.ashx";
    $retult=api_notice_increment($url,$data);
    $retult=str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>","",$retult);
    $retult=str_replace("<string xmlns=\"http://tempuri.org/\">","",$retult);
    $retult=str_replace("</string>","",$retult);
    if($retult>0){
        Mylog::error($mobile.'发送成功返回值为:'.$retult.' content '.$content);
        return true;
    }else{
        Mylog::error($mobile.'发送失败 返回值为 : '.$retult.' content '.$content);
        return false;
    }
}

function api_notice_increment($url, $data){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    $data = http_build_query($data);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

    $lst = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl);
    return $lst;
}


//获取在线IP
function getonlineip($format=0) {

    if(isset($_SERVER['HTTP_CDN_SRC_IP']) && $_SERVER['HTTP_CDN_SRC_IP'] && strcasecmp($_SERVER['HTTP_CDN_SRC_IP'], 'unknown')){
        $onlineip = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
    $onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';

    if($format) {
        $ips = explode('.', $onlineip);
        for($i=0;$i<3;$i++) {
            $ips[$i] = intval($ips[$i]);
        }
        return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
    } else {
        return $onlineip;
    }
}

//得到单页面的内容
function getPagesContent($hook){
    $sql = "SELECT content FROM " . tname('pages'). " WHERE hook='$hook'";
    $content = getOne($sql);
    return html_entity_decode(html_entity_decode($content));
}
/**
 * 得到订单号
 * @return string
 */
function getOrderNumber(){
    return  date('YmdHis').rand(100000, 999999);
}

/**
 * 根据所给数据， 生成条件sql字符串
 * @param string $setsqlarr  条件数组
 * $param string $alias 主表别名
 * @return string  返回的条件字符串  返回的where条件的字段将以 $alias.username的形式返回用
 */
function getWhereTostring($setsqlarr = '', $alias= ''){
    $wheresql = ' 1 ';
    $alias = $alias ? $alias . '.' : '';
    if(!empty($setsqlarr)){
        $comma = ' AND ';
        foreach ($setsqlarr as $set_key => $set_value) {
            if($set_key == 'like'){
                foreach($set_value as $sk => $sv){
                    $wheresql .= $comma.$alias."$sk like '%$sv%' ";
                }
            }else{
                $wheresql .= $comma.$alias.'`'.$set_key.'`'.'=\''.$set_value.'\'';
            }
        }
    }
    return ' WHERE '.$wheresql;
}

/**
 *检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全
 *参　　数：$sql_str: 提交的变量
 *返 回 值：返回检测结果，ture or false
 */

if( !function_exists("inject_check") ){
    function inject_check($sql_str) {
        return @eregi('select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str); // 进行过滤
    }
}

//过滤XSS攻击
if(!function_exists("reMoveXss")){
    function reMoveXss($val) {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08|\x0b-\x0c|\x0e-\x19])/', '', $val);

        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=@avascript:alert('XSS')>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
            // @ @ search for the hex values
            $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
            // @ @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
        }

        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', '<script', 'object', 'iframe', 'frame', 'frameset', 'ilayer'/* , 'layer' */, 'bgsound', 'base');
        $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);

        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(&#0{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop
                    $found = false;
                }
            }
        }
        return $val;
    }

}

//隐藏手机号中间部分
function format_tel($tel){
    if($tel){
        $telpre = substr($tel, 0, 3);
        $telsuffix = substr($tel, strlen($tel)-4);
        $tel = $telpre.str_repeat('*', strlen($tel)-7).$telsuffix;
        return $tel;
    }
}


//隐藏身份证中间部分
function format_idcard($idcard){
    if($idcard){
        $pre = substr($idcard, 0, 3);
        $suffix = substr($idcard, strlen($idcard)-4);
        $idcard = $pre.str_repeat('*', strlen($idcard)-7).$suffix;
        return $idcard;
    }
    return '';
}

/**
 * 从内容中获取第一张图作为缩略图
 * @param $content  内容
 * @return string   匹配为到的图片
 */
function getImgFromContent($content){
    $thumb = '';
    $content = html_entity_decode($content);
    $pattern = "/<img(.*?)src=[\'\"](.*?)[\'\"](.*?)>/ius";
    $res = preg_match($pattern,$content, $match);
    if($res && $match[2]){
        $thumb = $match[2];
    }
    return $thumb;
}

//输出手机端信息
function mobileMessage($msg, $exit = false){
    $str = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>'.$msg.'</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<body style="text-align:center;">
	<div style="position:absolute; top:50%;width:100%;text-align:center;">'.$msg.'</div></body></html>
	';
    echo $str;
    if($exit){
        exit;
    }
}


//表格导出函数
/**
 * title 表的标题列
 * data  表的数据
 * file 表名
 */
function do_export($title = array(), $data = array(), $filename = '', $encode = true){
    if($data && $title){
        $filename = $filename ? $filename : '数据导出';

        $filename_type='xls';
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        Header("Accept-Ranges:bytes");
        Header("Content-Disposition:attachment;filename=".$filename.".".$filename_type);  //$filename导出的文件名
        header("Pragma: no-cache");
        header("Expires: 0");
        if($filename_type=='xls'){
            $table = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
			   xmlns:x="urn:schemas-microsoft-com:office:excel"
			   xmlns="http://www.w3.org/TR/REC-html40">
			  <head>
			   <meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
			   <meta http-equiv=Content-Type content="text/html; charset=utf-8">
			   <!--[if gte mso 9]><xml>
			   <x:ExcelWorkbook>
			   <x:ExcelWorksheets>
			     <x:ExcelWorksheet>
			     <x:Name></x:Name>
			     <x:WorksheetOptions>
			       <x:DisplayGridlines/>
			     </x:WorksheetOptions>
			     </x:ExcelWorksheet>
			   </x:ExcelWorksheets>
			   </x:ExcelWorkbook>
			   </xml><![endif]-->

			  </head>';
        }

        $table .= '<table class="table table-bordered" border=1><thead><tr>'."\r\n";
        foreach($title as $k => $v){
            $table .= '<th>'.$v.'</th>'."\t";
        }
        $table .= '</tr></thead>'."\r\n".'<tbody>'."\r\n";
        foreach($data as $v){
            $table .= '<tr>';
            foreach($title as $k => $tv){
//                $k += 1;
                if(strpos($v['item'.$k], 'idcard') !== false){
                    $idcardv = str_replace('idcard','',$v['item'.$k]);
                    $table .= '<td style="vnd.ms-excel.numberformat:@">'.$idcardv.'</td>'."\t";
                }else{
                    $table .= '<td style="vnd.ms-excel.numberformat:@">'.$v['item'.$k].'</td>'."\t";
                }
            }
            $table .= '</tr>'."\r\n\r\n";
        }
        $html = $table;
        if($encode){
            $html = iconv('utf-8','gbk',$table);
        }
        echo $html;
    }
}


/**
 * 跳转到的URL
 * @param  [type] $url   URL地址， 可以类似 User/login 或是http:www.baidu.com
 * @param  array  $param URL所携带的参数
 * @return [type]        完整的URL
 */
function get_url($url, $param = array()){
    $paream_str = '';
    $contact = '';
    if(!empty($param)){
        foreach($param as  $k => $v){
            $paream_str = $contact . $k .'='.$v;
            $contact = '&';
        }
    }

    if ( ! preg_match('#^(\w+:)?//#i', $url)) {
        $uri = site_url($url . '?' . $paream_str);
    }else{
        if(strpos($url, '?') === false){
            $uri = $url . '?' . $paream_str;
        }else{
            $uri = $url . '&' . $paream_str;
        }
    }
    return $uri;
}

//要跳转到的URL,如果未指定URL， 则跳转到 siteclass/sitemethod
function url_to($siteclass = '', $sitemethod = 'index', $param = array()){
    //路由
    $RTR =& load_class('Router');
    // 当前 控制器
    $siteclass = $siteclass ? $siteclass : $RTR->fetch_class();
    //  当前方法
    $sitemethod = $sitemethod ? $sitemethod : $RTR->fetch_method();
    $url = $siteclass . '/'. $sitemethod;
    if(strpos(strtolower($_SERVER['REQUEST_URI']), strtolower(MANAGER_PATH)) !== false){
        $url = MANAGER_PATH . '/' . $url;
    }

    $uri = get_url($url, $param);
    redirect($uri);
}


//获取一行记录
function getRow($sql = '', $tbname = '', $field = '') {
    $CI = & get_instance();
    $CI->load->database();
    $db = $CI->db;
    if(!is_array($sql)){
        $query = $db->query($sql .' LIMIT 1');
        if($query){
            $result = $query->row_array();
            return $result;
        }
    }else if(is_array($sql)){
        $wheresql = '';
        $con = '';
        foreach($sql as $k => $v){
            $wheresql .= $con."`$k` = '$v'";
            $con = ' AND ';
        }
        $sql = "SELECT $field FROM ".tname($tbname) . " WHERE $wheresql";
        $query = $db->query($sql .' LIMIT 1');
        if($query){
            $result = $query->row_array();
            return $result;
        }
    }
    return null;
}
//获取值
function getOne($sql, $tbname = '', $field = ''){
    if($row = GetRow($sql, $tbname, $field)){
        $row = array_values($row);
        return $row[0];
    }
    return null;
}

//更新数据
function updatetable($tablename, $setsqlarr, $wheresqlarr) {
    $CI =& get_instance();
    $CI->load->database();
    $db = $CI->db;
    $setsql = $comma = '';
    foreach ($setsqlarr as $set_key => $set_value) {
        $setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
        $comma = ', ';
    }
    $where = $comma = '';
    if(empty($wheresqlarr)) {
        $where = '1';
    } elseif(is_array($wheresqlarr)) {
        foreach ($wheresqlarr as $key => $value) {
            $where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
            $comma = ' AND ';
        }
    } else {
        $where = $wheresqlarr;
    }
    return $db->query('UPDATE '.tname($tablename).' SET '.$setsql.' WHERE '.$where);
}
//格式化时间格式
function format_time($time, $format = 'Y.m.d'){
    if(is_string($time)){
        return $format ? date($format, strtotime($time)) : $time;
    }else{
        return $format ? date($format, $time) : date('Y-m-d H:i:s', $time);
    }
}