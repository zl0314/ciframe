<?php
/**
 * 自动加载模板
 * @author Aaron Zhang <nice5good@126.com>
 * @Date : 2016-12-15
 */

class Tpl{
    private $CI;
    private $siteclass;
    private $sitemethod;
    private $data            = array();
    private $template_file   = '';

    private $template_dir        = '';
    private $compile_dir         = '';
    private $cache_dir           = '';
    private $debugging           = TRUE;
    private $suffix              = '.php';
    private $default_class;
    private $manager_path;
	
    public function __construct(){
        $this->CI = get_instance();
        $this->data = null;
		$this->default_class = 'index';
		$this->manager_path = MANAGER_PATH;
		
        $this->template_dir   = APPPATH . 'views/';
        $this->compile_dir    = APPPATH . 'cache/templates_c/';
        $this->cache_dir      = APPPATH . 'cache/cache/';
        $this->template_file  = !empty( $this->CI->uri->segments[1] ) ?  ucwords($this->CI->uri->segments[1]) : ucwords($this->default_class);

        $this->template_file .= !empty( $this->CI->uri->segments[2] ) && (ucwords($this->CI->uri->segments[1]) == $this->manager_path )
                            ?  '/'. ucwords($this->CI->uri->segments[2])
                            : ( !empty($this->CI->uri->segments[2]) ? '/' . $this->CI->uri->segments[2] : '/'.$this->default_class );

        $this->template_file .= 
                            !empty( $this->CI->uri->segments[3]) && (ucwords($this->CI->uri->segments[1]) == $this->manager_path )
                            ? '/'.($this->CI->uri->segments[3])
                            : ( !empty($this->CI->uri->segments[1]) && ucwords($this->CI->uri->segments[1]) == $this->manager_path ? '/' . $this->default_class : '');
    }
    
    /**
     * 加载模板
     * @param string $template
     */
    public function display($template = NULL){
        $this->init_tpl_dir($template);
        $template = $template ? $template : $this->template_file;

		$data = !empty($this->CI->data) ? $this->CI->data : array();
        //加载Header文件
        if(!empty($this->CI->data['header'])){
            $this->CI->load->view($this->CI->data['header'], $data);
        }
        if(!empty($_GET['vue'])){
            if(!empty($_GET['callback'])){
                $json = json_encode($data);
                $callback = $_GET['callback'];
                echo $callback.'('.$json.')';exit;
            }
        }

		//加载内容文件
        $this->CI->load->view($template, $data);
		
		//加载Footer文件
        if(!empty($this->CI->data['footer'])){
            $this->CI->load->view($this->CI->data['footer'], $data);
        }
       
    }

    /**
     * [给模板赋值]
     **/
    public function assign( $tpl_var, $value = NULL ) {
        if( is_array($tpl_var) ) {
            foreach( $tpl_var as $k => $v ) {
                $this->assign($k , $v );
            }
            return true;
        }
        $this->CI->data[$tpl_var] = $value;
    }

    /**
     * 创建模板目录以及模板文件
     */
    public function init_tpl_dir($template = ''){
        $siteclass = $this->CI->router->class;
        $sitemethod = $this->CI->router->method;
        $this->siteclass = strtolower($siteclass);
        $this->sitemethod = strtolower($sitemethod);
        //创建目录 以及 当前方法的文件
        // $template_file = $this->template_dir.$this->siteclass.'/'.$this->sitemethod.$this->suffix;
        $template = $template ? $template : $this->template_file;
        $template_file = $this->template_dir.$template.$this->suffix;

        //创建目录
        if(!file_exists($template_file)){
            creat_dir_with_filepath($template_file);
        }
        //创建以当前方法为文件名的文件
         if(!file_exists($template_file)){
             $handle = @fopen($template_file, 'w');
             @fwrite($handle, ' ');
             @fclose($handle);
         }
    }
    
    
}