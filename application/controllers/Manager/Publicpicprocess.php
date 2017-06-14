<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * [公共图片操作]
 * @date 2015-5-12
 **/
class PublicPicProcess extends Base_Controller {
	public function __construct() {
		parent::__construct();
			 $this->checkAdminLogin();
		}
	
	/**
	 * [上传]
	 * @date 2015-5-12
	 **/
	public function upload($upload = ''){
		$this->config->load('upload_config');
		$upload = $upload ? $upload : 'default';
		$config = $this->config->item($upload);
		//$create_thumb = request_get('create_thumb') ? request_get('create_thumb') : '';
		
		//缩略图配置
		//$thumb_config = request_get('thumb_config') ? request_get('thumb_config') : 'default';
		
		//缩略图前缀
		//$thumb_prefix = request_get('p') ? request_get('p') : 'thumb_';
		
		//当前（$upload）上传配置为空
		if(empty($config)){
			$config = $this->config->item('default');
		}
		
		//如果指定了上传路径($upload)，改变路径
		if($upload){
			$config['upload_path'] = './uploads/'.$upload.'/'.date('Y/m/d/');
		}
		
		//如果用GET方式指定文件上传对象，$upload就是GET方式传递的文件上传对象
		$filedata = !empty($_GET['filedata']) ? $_GET['filedata'] : $upload;
		if(empty($_FILES)){
			exit('文件对象为空');
		}
		//创建上传目录
		creat_dir_with_filepath($config['upload_path'].$_FILES[$filedata]['name']);
		$allow_size = str_replace('M','', ini_get('upload_max_filesize'))*1024*1024;
		if($_FILES[$filedata]['size'] > $allow_size){
			exit('图片大小不能超过'.ini_get('upload_max_filesize'));
		}
		
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload($filedata)){
			$data = array('error' => $this->upload->display_errors('',''));
		} else {
			$imgdata =  $this->upload->data();
			$data['url'] = trim($config['upload_path'],'.').$imgdata['file_name'];
		}
		//返回图片URL
		if(!empty($data['url'])){
			echo $data['url'];
		}else{
			echo ($data['error']);
		}
		exit;
		
	}
	
	/**
	 * [删除]
	 * @date 2015-5-12
	 **/
	public function delete(){
		$pics = request_post('pic') ? request_post('pic') : request_get('pic');
		$truepic = '';
		if($pics && is_array($pics)){
			foreach($pics as $k => $pic){
				$truepic = './'.$pic;
				if(file_exists($truepic)){
					@unlink($truepic);
					$newImg = getNewImg($pic);
					@unlink('.'.$newImg);
				}
			}
		}else{
			$truepic = '.'.$pics;
			if(file_exists($truepic)){
				@unlink($truepic);
				$newImg = getNewImg($pics);
				@unlink($newImg);
			}
		}
		echo 'ok';exit;
	}
}