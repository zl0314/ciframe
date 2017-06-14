<?php
/**
 * 日志记录
 * @author mqq
 * usage : Log::error('this is a test error log');
 */
class MyLog{
	/**
	 * 调试记录
	 * @param $str
	 */
	public static function debug($str){
		self::checkPath('debug', $str);
	}
	/**
	 * 信息记录
	 * @param $str
	 */
	public static function info($str){
		self::checkPath('info', $str);
	}
	/**
	 * 警告记录
	 * @param $str
	 */
	public static function warn($str){
		self::checkPath('warn', $str);
	}
	/**
	 * 错误记录
	 * @param $str
	 */
	public static function error($str){
		self::checkPath('error', $str);
	}

	/**
	 * SQL记录
	 * @param $str
	 */
	public static function db($str){
		self::checkPath('db', $str);
	}

	/**
	 * 检查文件目录，并自动创建
	 * @param string $type
	 */
	private static function checkPath($type = 'error', $str){
	    defined('ROOT') || define('ROOT', dirname(BASEPATH));
		$file = ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.date('Y_m_d',time()).'.log';
		if($type == 'db'){
			$file = ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.date('Y_m_d_H',time()).'.log';
		}

		if(ERRORLOG){
			if(!file_exists(dirname($file))){
				creat_dir_with_filepath($file);
			}
			error_log(date('Y-m-d H:i:s',time()).': '.$str."\n", 3, $file);
		}
	} 
}
?>