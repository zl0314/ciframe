<?php
/**
 * 表单创建帮助函数
 * @date 2017-4-21
 * @usage:
 *   $data = array(
'user_name' => array(
'field' => '用户名',
'type' => 'text'
),
'tools_id' => array(
'type' => 'hidden',
),
'start_time' => array(
'type' => 'time',
'field' => '开始时间',
'readonly' => true,
'format' => 'yyyy-MM-dd HH:mm:ss'
),
'thumb' => array(
'type' => 'image',
'field' => '缩略图',
'readonly' => true,
'tip' => '宽100, 高100'
),
'file' => array(
'type' => 'file',
'field' => '上传文档',
'readonly' => true,
'rule' => 'trim'
),
'intro' => array(
'field' => '简介',
'type' => 'textarea',
'editor' => false,
),
'content' => array(
'field' => '内容',
'type' => 'textarea',
'editor' => true,
),
'role_id' => array(
'field' => '所属角色',
'type' => 'select',
'multiple' => true,
'data' => array(1 => '管理员', '2' => '编辑', '3' => '站长', '4' => '部门经理')
),
'group' => array(
'field' => '所在部门',
'type' => 'select',
'data' => array(1 => '技术', '2' => '设计', '3' => '营销', '4' => '财务')
),
'interesting' => array(
'field' => '兴趣',
'type' => 'checkbox',
'data' => array('1' => '打球', '2' => '读书', '3' => '音乐', '4' => '跑步')
),
'is_index' => array(
'field' => '推荐首页',
'type' => 'radio',
'default' => 0,
'data' => array('0' => '否', '1' => '是')
),
);

 * @author   <[Aaron Zhang]>
 **/

if(!function_exists('createFormHtml')){
    function createFormHtml($data, $row = array()){
        $formHtml = '<form action="" method="post" id="contentForm">';
        $CI = &get_instance();
        $siteclass = $CI->router->class;
        $form_more_pic_html = '';
        if(!empty($data)){
            foreach($data as $k => $r){

                //文档框的值
                $value = !empty($row[$k]) ? $row[$k] : '';

                //错误提示文字
                $form_error = form_error('data['.$k.']');

                //文本框名
                $name = $k;

                //类型
                $type = !empty($r['type']) ? $r['type'] : 'text';

                //文本框名
                $field = !empty($r['field']) ? $r['field'] : '';

                //提示文字
                $tip = !empty($r['tip']) ? $r['tip'] : '';

                //是否只读
                $readonly = !empty($r['readonly']) ? 'true'  : 'false';

                //是否必须
                $required = !empty($r['is_require']) ? 'required' : '';
                $width = !empty($r['width']) ? $r['width'] : '';
                $height = !empty($r['height']) ? $r['height'] : '';

                switch( $type ){
                    case 'hidden' : //隐藏域
                        $formHtml .= '<input id="'.$k.'" type="'.$type.'" name="data['.$k.']" '.$required.' class="input-txt" value="'.$value.'" /> ';
                        break;

                    case  'text' :	//文本框
                        $formHtml .= '<div class="form-row" id="wrapper_'.$k.'">
					          <label for="'.$k.'" class="form-field">'.$field.'</label>
					          <div class="form-cont">
					            <input id="'.$k.'" type="'.$type.'" name="data['.$k.']" '.$required.' class="input-txt" value="'.$value.'" /> '.$form_error.'
					          </div>
					          <span style="display:block;">'.$tip.'</span>
					        </div>';
                        break;

                    case 'time' :	//时间选择
                        $format = !empty($r['format']) ? $r['format'] : 'yyyy-MM-dd HH:mm:ss';
                        $formHtml .= '<div class="form-row" id="wrapper_'.$k.'">
					          <label for="'.$k.'" class="form-field">'.$field.'</label>
					          <div class="form-cont">
					          <input type="text" id="'.$k.'" '.$required.' name="data['.$k.']" class="input-txt Wdate"   onfocus="WdatePicker({ dateFmt:\''.$format.'\',readOnly:'.$readonly.'})" value="'.$value.'" >
					          '.$form_error.'
					           </div>
					          <span style="display:block;">'.$tip.'</span>
					        </div>';
                        break;

                    case 'image' : // 上传图片
                        $src = $value ? 'src="'.$value.'"' : '';

                        $formHtml .= '<div class="form-row" id="wrapper_'.$k.'">
								<label for="'.$k.'" class="form-field">'.$field.'</label>
								<div class="form-cont">
									<input id="'.$k.'" type="text" name="data['.$k.']" readonly class="input-txt" value="'.$value.'" />
									 <input type="button" class="ajaxUploadBtn" id="'.$name.'_button" onclick="ajaxUpload(\''.$name.'\', \''.$siteclass.'\', '.$width.', '.$height.')" value="上传图片" style="width:70px; height:25px;">
									'.$form_error.'
									 <span style="display:block;">'.$tip.'</span>
									 <img id="preview_'.$name.'" class="img_prview" style="width:200px;height:130px;" '.$src.'>
								</div>
					        </div>';
                        break;
                    case 'morepic':
                        $CI->config->load('upload');
                        $config = $CI->config->item('default');
                        $max_size = $config['max_size'] ? $config['max_size'] : 2000;

                        $formHtml .= '<script type="text/template" id="'.$k.'_demo">
                            <li class="imgbox post file" style="list-type:none;width:134px;height:185px;margin-left:5px;">
                                <a class="item_close" href="javascript:void(0)" onclick="delscroll(this)" title="删除">
                                </a>
                                <span  class="item_box"style="width:121px;height:176px;">
                        <img src="" style="width:121px;height:174px;">
                        </span>
                                <input type="hidden" name="data['.$k.'][]" class="">
                            </li>
                        </script>
                        
                        <!-- 轮播图 -->
                        
                        <div class="form-row">
                            <label class="form-field">'.$field.'：</label>
                            <div class="form-cont">
                                <span class="help-inline"><button class="btn select_img" id="'.$k.'"  type="button">&nbsp;</button></span><span class="help-inline" style="margin-left:35px;">推荐大小为宽'.$width.'，高'.$height.'</span>
                            </div>
                            '.$form_error.'
                            <ul class="ipost-list ui-sortable" id="'.$k.'_fileList"  style="width:1000px;height:100%;margin-left:90px;margin-top:15px;clear:both;">
                                ';
                        if (!empty($value)){
                            $scrollpic = explode(',', $value);
                            if(!empty($scrollpic))foreach($scrollpic as $r){
                                $formHtml .='
                                        <li class="imgbox post file" style="list-type:none;height:185px;width:134px;margin-left:5px;">
                                            <a class="item_close" href="javascript:void(0)" onclick="delscroll(this)" title="删除">
                                            </a>
                                            <span class="item_box" style="width:121px;height:176px;">
                                                <img src="'.$r.'"   style="width:121px;height:174px;">
                                            </span>
                                            <input type="hidden"  name="data['.$k.'][]" value="'.$r.'" class="config_scroll">
                                        </li>';
                            }
                        }
                        $formHtml .= '    </ul>
                                                    </div>
                                                    <!-- //轮播图 -->';
                        $formHtml .= '<script>
                                            var SITE_URL = \'http://\'+document.domain;
                                            var uploadify_id = \''.$k.'\';
                                        </script>
                                        <script type="text/javascript" src="/static/js/jquery_ui_custom.js"></script>
                                        <link rel="stylesheet" type="text/css" href="/static/uploadify/uploadify.css">
                                        <script type="text/javascript" src="/static/uploadify/jquery.uploadify.js"></script>
                                        <link rel="stylesheet" type="text/css" href="/static/uploadify/uploadify_t.css" media="all" />
                                        ';
                        $formHtml .= '<script>
                                            function addoneing'.$k.'(m,n){
                                                m = $.trim(m);
                                                $(\'#'.$k.'_fileList\').append($(\'#'.$k.'_demo\').html());
                                                var li = $(\'#'.$k.'_fileList\').children(\'li:last\');
                                                li.find(\'img\').attr(\'src\',m);
                                                li.find("input").val(m);
                                            }
                                        
                                            function imgSelectorInit'.$k.'() {
                                                $(\'#imgSelectorChoice\').delegate(\'img\', \'click\', function () {
                                                    var el = $(this);
                                                    if (!el.hasClass(\'selected\'))
                                                        buildSelectedImg(el);
                                                }).delegate(\'img\', \'mouseenter\', function () {
                                                    $(this).addClass(\'hovered\');
                                                }).delegate(\'img\', \'mouseleave\', function () {
                                                    $(this).removeClass(\'hovered\');
                                                });
                                            }
                                            function ipostSortInit'.$k.'() {
                                                $(\'.ipost-list\').sortable({
                                                    items: \'> .post\',
                                                    containment: \'parent\',
                                                    appendTo: \'parent\',
                                                    tolerance: \'pointer\',
                                                    placeholder: \'holder\',
                                                    forceHelperSize: true,
                                                    forcePlaceholderSize: true,
                                                    axis: \'x\',
                                                    opacity: 0.8,
                                                    cursor: \'ns-resize\'
                                                });
                                            }
                                            
                                            imgSelectorInit'.$k.'();
                                            ipostSortInit'.$k.'();
                                            $(\'#'.$k.'\').click( function(){
                                                $(\'#'.$k.'\').uploadify({
                                                    height        : 31,
                                                    fileSizeLimit	  : \''.$max_size.'KB\',
                                                    fileTypeExts : \'*.gif; *.jpg; *.png; *.jpeg\',
                                                    swf           : SITE_URL+\'/static/uploadify/uploadify.swf\',
                                                    uploader      : \''.site_url(MANAGER_PATH . '/Publicpicprocess/upload/scrollpic?filedata=Filedata').'\',
                                                    width         : 98,
                                                    multi         : true,
                                                    buttonText	  : \'<span class="uploadify-button-text"><i class="icon-plus-sign"></i></span>\',
                                                    buttonClass   : \'btn pl_add btn-primary\',
                                                    onUploadSuccess    : function (a, b, c, d, e){
                                                        addoneing'.$k.'(b);
                                                    },
                                                });
                                            });
                                        
                                            setTimeout(function(){
                                                $("#'.$k.'").trigger(\'click\');
                                            },2000)
                                        
                                            function delscroll(obj){
                                                if(confirm(\'确认要删除吗？\')){
                                                    var pic = $(obj).parent().find(\'img\').attr(\'src\');
                                                    delpic(pic);
                                                    $(obj).parent().remove();
                                                }
                                            }
                                        </script>
                                        
                                        <style>
                                            #'.$k.' { width:98px;height:31px; background:#368EE0; background-image:url(/static/uploadify/upload_btn.png)}
                                            .help-inline{display:inline;float:left;}
                                            a.item_close{
                                                background-image: url("/static/uploadify/image_upload.png");
                                                background-position: -100px -182px;
                                                display: block;
                                                height: 14px;
                                                overflow: hidden;
                                                position: absolute;
                                                right: 6px;
                                                text-indent: -9999px;
                                                top: 6px;
                                                width: 14px;
                                            }
                                            a.item_close:hover{
                                                text-decoration:none;
                                                background-position: -100px -200px;
                                            }
                                        </style>';

                        break;
                    case 'file' :	// 上传文件
                        $src = $value ? 'src="'.$value.'"' : '';
                        $formHtml .= '<div class="form-row" id="wrapper_'.$k.'">
                            <label for="'.$k.'" class="form-field">'.$field.'</label>
                            <div class="form-cont">
                                <input id="'.$k.'" type="text" name="data['.$k.']" readonly class="input-txt" value="'.$value.'" />
                                 <input type="button" class="ajaxUploadBtn" id="'.$name.'_button" onclick="ajaxUpload(\''.$name.'\', \''.$siteclass.'\')" value="上传文件" style="width:70px; height:25px;">
                                '.$form_error.'
                                 <span style="display:block;">'.$tip.'</span>
                            </div>
                        </div>';
                        break;

                    case 'textarea' :	//输入框
                        $is_editor = !empty($r['editor']) ? $r['editor'] :  false;
                        $textareahtml = '<textarea class="input-area" id="'.$name.'"  name="data['.$name.']" >'.$value.'</textarea>';
                        $value = !empty($row[$k]) ? html_entity_decode($row[$k]) : '';
                        if($is_editor){
                            $textareahtml = '<script id="'.$name.'" name="data['.$name.']" type="text/plain" style="width:650px;height:250px;">'.$value.'</script>';
                            $textareahtml .= '<script type="text/javascript" charset="utf-8">
                                                var ue = UE.getEditor(\''.$name.'\',{readonly : '.$readonly.'});
                                                </script>';
                        }
                        $formHtml .= '<div class="form-row" id="wrapper_'.$k.'">
                               <label for="'.$k.'" class="form-field">'.$field.'</label>
                              <div class="form-cont">
                                '.$textareahtml.$form_error.'
                               </div>
                               <span style="display:block;">'.$tip.'</span>
                            </div>';
                        break;

                    case  'select' :	// 下拉选项
                        $multiple = !empty($r['multiple']) ? 'multiple size=10' : '' ;
                        $as_arr = '';
                        if($multiple){
                            $as_arr = '[]';
                        }
                        $selected_items = array();
                        if(!empty($row[$name]) && !is_array($row[$name])){
                            $selected_items = explode(',', $row[$name] );
                        }else{
                            $selected_items = !empty($row[$name]) ? $row[$name] : array();
                        }
                        $formHtml .= '<div class="form-row"  id="wrapper_'.$k.'" >
                                <label for="'.$k.'" class="form-field">'.$field.'</label>
                                <div class="form-cont">
                                <select id="'.$name.'"  '.$required.' name="data['.$name.']'.$as_arr.'"  '.$multiple.' >
                                    <option value="">请选择</option>';

                        if(!empty($r['data'])){
                            foreach( $r['data'] as $select_k => $select_v ) {
                                $selected = '';
                                if(in_array($select_k, $selected_items)){
                                    $selected = 'selected';
                                }
                                $formHtml .= '<option  value="'.$select_k.'" '.$selected.'>'.$select_v.'</option>';
                            }
                        }

                        $formHtml .= '</select>'.$form_error.'
                            </div></div>';
                        break;

                    case 'checkbox' :	// 多选按钮
                        $formHtml .= '<div class="form-row" style="margin-top:15px;"   id="wrapper_'.$k.'" >
                                <label for="'.$k.'" class="form-field">'.$field.'</label>
                                <div class="form-cont" style="height:30px;line-height: 20px; ">';

                        $selected_items = array();
                        if(!empty($row[$name])  && !is_array($row[$name])){
                            $selected_items = explode(',', $row[$name] );
                        }else{
                            $selected_items = !empty($row[$name]) ? $row[$name] : array();
                        }
                        if(!empty($r['data'])){
                            foreach($r['data'] as $checkhox_k => $checkhox_r){
                                $c = '';
                                if(in_array($checkhox_k, $selected_items)){
                                    $c = 'checked';
                                }
                                $formHtml .= '<label  style="cursor:pointer;">
                                        <input '.$c.' type="checkbox" value="'.$checkhox_k.'" name="data['.$k.'][]">&nbsp;'.$checkhox_r.'&nbsp;&nbsp;</label>';
                            }
                        }
                        $formHtml .= $form_error.'           
                                </div>
                            </div>';
                        break;
                    case 'radio' : 	//单选按钮
                        $formHtml .= '<div class="form-row"   id="wrapper_'.$k.'" >
                                   <label for="'.$k.'" class="form-field">'.$field.'</label>
                                    <div class="form-cont">';
                        $selected_items = isset($row[$name]) ? $row[$name] : ( !empty($r['default']) ? $r['default'] : '');
                        if(!empty($r['data'])){
                            foreach($r['data'] as $radio_k => $radio_r){
                                $c = '';
                                if($selected_items == $radio_k){
                                    $c = 'checked';
                                }
                                $formHtml .= '<label for="type'.$radio_k.'">
                                            <input id="type'.$radio_k.'" type="radio" name="data['.$name.']"  value="'.$radio_k.'" '.$c.'/> '.$radio_r.'
                                        </label>';
                            }
                        }

                        $formHtml .= $form_error.'</div>
                                </div>';
                        break;
                }
            }
        }
        $formHtml .= '<input type="submit" value="保 存" class="input-btn" style="width:70px; height:25px;"> ';
        $formHtml .= '</form>';
        return $formHtml;
    }
}