<?php
class My_hook{
    public function __construct(){ }
    
    /**
     * 挂在在 pre_controller 的钩子 （在你的控制器调用之前执行，所有的基础类都已加载，路由和安全检查也已经完成。 ） 
     * @param array $arg
     */
    public function system_start($arg = array()){
        $GLOBALS['system_start_time'] = microtime();
//        echo '<!--start-->';
    }
    
    /**
     * 挂在在 post_system 的钩子 （在最终的页面发送到浏览器之后、在系统的最后期被调用。）
     * @param array $arg
     */
    public function system_end($arg = array()){
        $GLOBALS['system_end_time'] = microtime();
        $time_speed = $GLOBALS['system_end_time'] - $GLOBALS['system_start_time'];
//        echo '<p class="footer">Page rendered in <strong>'.$time_speed.'</strong> seconds.';
    }
    
}