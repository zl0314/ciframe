<?php
/**
 * 缓存帮助文件
 * Create By: ZhangLong
 * 使用方法：
 * 写入：
 * cache_write('test', $data);
 * 读取:
 * cache_read('test');
 *
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 读取缓存的内容
 * @param string $file
 * @return array $data
 */
function cache_read($file = 'default'){
    $data = array();
    if($file){
        $cache_file = get_cache_file($file);

        if( file_exists($cache_file) ){
            @$create_time = filectime($cache_file);
            if(( time() - $create_time ) > 7200 ){
               // cache_delete($file);
            }
        }
        if(file_exists($cache_file)){
            $contents = file_get_contents($cache_file);
            $data = unserialize($contents);
        }
    }
    return $data;
}

/**
 * 写入缓存
 * @param string $file
 * @param array $str
 */
function cache_write($file = 'default', $str = array()){
    if($file && $str){
        $cache_file = get_cache_file($file);
        if(is_array($str)){
            $contents = serialize($str);
        }else{
            $contents = $str;
        }
        @file_put_contents($cache_file, $contents);
    }
}

/**
 * 删除缓存文件
 * @param string $file
 */
function cache_delete($file = 'default'){
    $cache_file = get_cache_file($file);
    if(file_exists($cache_file)){
        @unlink($cache_file);
    }
}
/**
 * 得到缓存文件名
 * @param string $file
 * @return string $file
 */
function get_cache_file($file = 'default'){
    $CI = get_instance();
    $cache_path = $CI->config->item('cache_path');
    $cache_file = $cache_path . $file;
    return $cache_file;
}
?>