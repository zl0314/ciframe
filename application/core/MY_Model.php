<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 公共Model 
 * @auther Zhanglong
 * @version 2013-12-28
 */
class MY_Model extends CI_Model
{
	protected $master ;
	protected $slave ;
	public $table ;
	

	function __construct()
	{
		parent::__construct() ;
	}


}
/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */