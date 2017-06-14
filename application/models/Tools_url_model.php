<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @auther Aaron Zhang
 * @data 2017-04-21 18:43:51
 * @description :
 */
class Tools_url_model extends MY_Model{
	public $tb;
	function __construct(){
		parent::__construct();
		$this->tb = 'tools_url';
	}
}