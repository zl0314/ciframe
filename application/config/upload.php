<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['default'] = array(
		'upload_path' => './uploads/default/'.date('Y/m/d/'),
		'allowed_types' => 'gif|jpg|png|jpeg|PNG|JPG|JPEG',
		'encrypt_name' => TRUE,
		'create_thumb' => FALSE,
		'max_size' => ini_get('upload_max_filesize')*1024,
);


/* 
/* End of file upload_config.php */
/* Location: ./application/config/upload_config.php */