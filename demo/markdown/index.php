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
 
require_once dirname(dirname(__DIR__)) . '/swanphp.php';

// 设置命名空间
require_once PATH_SF_LIB . 'loader/sw_standard_auto_loader.class.php';
$autoloader = new swan\loader\sw_standard_auto_loader(
	array(
		'namespaces' => array(
			'swan' => PATH_SF_BASE,
			'markdown' => './',
		),
));
$autoloader->register();

use swan\markdown\sw_markdown;

$markdown = new sw_markdown();

$str = file_get_contents('rpm-all-compile.mk');
$output = $markdown->to_html($str);
echo $output;
