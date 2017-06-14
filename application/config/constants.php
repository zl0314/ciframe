<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


//定义表前缀
define('DB_PREFIX', '');

//是否记录错误日志
define('ERRORLOG', TRUE);

//是否记录SQL语句
define('DBLOG', TRUE);

//缓存失效时间--- 以秒为单位
define('CACHE_EXP',86400*360);

/*
|--------------------------------------------------------------------------
| 网站根目录
|--------------------------------------------------------------------------
*/
define('SITE_ROOT', substr(str_replace('\\', '/', FCPATH), 0, -1));
define('ROOT', SITE_ROOT);

/*
|--------------------------------------------------------------------------
| 网站相对目录
|--------------------------------------------------------------------------
*/
define('SITE_PATH', str_ireplace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']),'',SITE_ROOT));

/*
|--------------------------------------------------------------------------
| 站点URL
|--------------------------------------------------------------------------
*/
$PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
$PHP_SCHEME = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
$PHP_PORT = $_SERVER['SERVER_PORT'] == '80' ? '' : ':'.$_SERVER['SERVER_PORT'];
$PHP_PATH = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
define('SITE_URL', $PHP_SCHEME.$_SERVER['SERVER_NAME'].$PHP_PORT);
define('SCRIPT_URL', $PHP_SCHEME.$_SERVER['SERVER_NAME'].$PHP_PORT.$PHP_PATH.($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''));
define('SITE_DOMAIN', $_SERVER['SERVER_NAME']);
define('REDIRECT_URL', isset($_GET['redirect_url']) ? urldecode($_GET['redirect_url']) : '');

//管理后台标题
define('ADMIN_TITLE', '管理后台');

/*http referer*/
define('HTTP_REFERER', !empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '');

/*
|--------------------------------------------------------------------------
| 时间处理
|--------------------------------------------------------------------------
*/
$mtime = explode(' ', microtime());
define('TIME_STAMP', $mtime[1]);
define('SUPE_STARTTIME', (TIME_STAMP + $mtime[0]));
define('SITE_TIME', TIME_STAMP);
define('SITE_DATETIME', date('Y-m-d H:i:s', TIME_STAMP));

//站点加密key
define('SITEKEY', 'opiIO#)213588&!jmOPI#&@)#QWjdi');

//用户表
define('USER_TABLE', 'user');

//每天允许发送短信的数量
define('ALLOW_SEND_SMS_COUNT', 5);

//后台控制器路径
define('MANAGER_PATH', 'Manager');


