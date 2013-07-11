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
+------------------------------------------------------------------------------
* 核心处理程序 全局变量
+------------------------------------------------------------------------------
*  
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

// {{{  绝对路劲
define('PATH_SF_BASE', realpath(dirname(__FILE__)));
    define('PATH_SF_LIB', PATH_SF_BASE . '/lib/');
    define('PATH_SF_INC', PATH_SF_BASE . '/inc/');
        define('PATH_SF_LOCALE', PATH_SF_INC . '/locale/');
        define('PATH_SF_CONF', PATH_SF_INC . '/conf/'); // 系统配置文件， 由 etc 下的 ini自动生成
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

// 默认时区设置
define('SWAN_TIMEZONE_DEFAULT', 'Asia/Chongqing');

// 默认语言
define('SWAN_LANG_DEFAULT', 'zh_CN');

// 系统字符集
define('SWAN_CHARSET', 'UTF-8');

// 多语言支持的domain
define('SWAN_GETTEXT_DOMAIN', 'swan_translater');

//是否开启模板缓存
define('SW_CACHE_START', false);

//缓存过期时间
define('SW_CACHE_TIME', 0);

//模板定界符
define('SW_LEFT_DELIMITER', '<!--{{');
define('SW_RIGHT_DELIMITER', '}}-->');

//RRD相关
define('RRD_NL', "\\\n");

// }}}
// {{{ 系统初始化
//初始化时区
date_default_timezone_set(SWAN_TIMEZONE_DEFAULT);

// }}}
// }}}
// {{{ autoload 管理

require_once PATH_SF_LIB . 'loader/sw_standard_auto_loader.class.php';
$autoloader = new lib\loader\sw_standard_auto_loader(array(
	'namespaces' => array(
		'lib' => PATH_SF_BASE,
	),
));

$autoloader->register();

// }}}
