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
			'format' => 'yyyy-mm-dd HH:mm:ss'
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
						$formHtml .= '<div class="form-row" id="wrapper_'.$k.'">
					          <label for="'.$k.'" class="form-field">'.$field.'</label>
					          <div class="form-cont">
					          <input type="text" id="'.$k.'" '.$required.' name="data['.$k.']" class="input-txt Wdate"   onfocus="WdatePicker({ dateFmt:\''.$r['format'].'\',readOnly:'.$r['readonly'].'})" value="'.$value.'" >
					          '.$form_error.'
					           </div>
					          <span style="display:block;">'.$tip.'</span>
					        </div>';
					    break;

					case 'image' : // 上传图片
						$src = $value ? 'src="'.$value.'"' : '';
                        $width = !empty($r['width']) ? $r['width'] : '';
                        $height = !empty($r['height']) ? $r['height'] : '';

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
				                            	<input id="type'.$radio_k.'" type="radio" name="data['.$name.']"  value="'.$k.'" '.$c.'/> '.$radio_r.'
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