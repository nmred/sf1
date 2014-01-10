<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------
 
/**
* 核心处理程序 全局变量
*  
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

// {{{  绝对路劲
define('PATH_SF_BASE', realpath(dirname(__FILE__)));
    define('PATH_SF_LIB', PATH_SF_BASE . '/swan/');
    define('PATH_SF_EXT', PATH_SF_BASE . '/ventor/');
		define('PATH_SF_SMARTY', PATH_SF_EXT . 'smarty/');
// }}}
// {{{ 参数配置

// {{{ 软件详细信息

// 软件名称
if (!defined('SWAN_SOFTNAME')) {
	define('SWAN_SOFTNAME', 'swansoft');
}

// 软件版本号
if (!defined('SWAN_VERSION')) {
	define('SWAN_VERSION', '0.2.0');
}

// 软件发行号
if (!defined('SWANBR_RELEASE')) {
	define('SWANBR_RELEASE', 'beta');
}

//软件宣言 ------一切为了方便
if (!defined('SWANBR_SLOGAN')) {
	define('SWANBR_SLOGAN', 'Everything in order to facilitate');
}

//版权声明
if (!defined('SWANBR_COPYRIGHT')) {
	define('SWANBR_COPYRIGHT', '© 2011-2013 swanlinux');
}

//许可协议 
if (!defined('SWANBR_LICENSED_URL')) {
	define('SWANBR_LICENSED_URL', 'BSD');
}

// 官方网址
if (!defined('SWANBR_WEB_DOMAIN')) {
	define('SWANBR_WEB_DOMAIN', 'http://www.swanlinux.net');
}

// 作者
if (!defined('SWANBR_AUTHOR')) {
	define('SWANBR_AUTHOR', 'swanteam <nmred_2008@126.com>');
}

// }}}
// {{{ 参数设置

// 是否开始 WEB 调试
define('WEB_DEBUG', true);

// 默认时区设置
if (!defined('SWAN_TIMEZONE_DEFAULT')) {
	define('SWAN_TIMEZONE_DEFAULT', 'Asia/Chongqing');
}

// smarty 相关配置
if (!defined('SW_CACHE')) {
	define('SW_CACHE', false); // 是否开启 cache
}
if (!defined('SW_CACHE_TIME')) {
	define('SW_CACHE_TIME', '60'); // 缓存有效时间
}
if (!defined('SW_LEFT_DELIMITER')) {
	define('SW_LEFT_DELIMITER', '<!--{{'); // 左标记符
}
if (!defined('SW_RIGHT_DELIMITER')) {
	define('SW_RIGHT_DELIMITER', '}}-->'); // 右标记符
}
if (!defined('SW_SELF_NAME')) {
	define('SW_SELF_NAME', 'localhost');  // log 模块会用到主机名
}

// }}}
// {{{ 系统初始化

//初始化时区
date_default_timezone_set(SWAN_TIMEZONE_DEFAULT);

if (WEB_DEBUG) {
	require_once PATH_SF_EXT . 'firephp/fb.php';	
}

// }}}
// }}}
// {{{ autoload 管理

require_once PATH_SF_LIB . 'exception/sw_exception.class.php';
require_once PATH_SF_LIB . 'loader/sw_loader.class.php';
require_once PATH_SF_LIB . 'loader/sw_auto.class.php';
\swan\loader\sw_auto::get_instance(array(\swan\loader\sw_auto::AUTOREGISTER_SW => true));

// }}}

// {{{ 全局函数

/**
 * func_enjoy 
 * 
 * @access public
 * @return void
 */
function func_enjoy()
{
	// 空函数	
}
  
// }}}
