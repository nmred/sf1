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

error_reporting( E_ALL | E_STRICT );
require_once dirname(__DIR__) . '/swanphp.php';

/**
+------------------------------------------------------------------------------
* 测试引导脚本 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/

$autoloader = \swan\loader\sw_auto::get_instance(array(
	'namespaces' => array(
		'swan_test' => './',
		'mock' => dirname(__FILE__),
	),
));

$autoloader->register();

// 初始化配置
\swan\config\sw_config::set_config('config.php');

define('SF_TBN_SF_UNIT', 'sf_unit');
