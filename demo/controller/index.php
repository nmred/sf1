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
			'ui' => './',
		),
));
$autoloader->register();

use swan\controller\sw_controller;
use ui\router\sw_router;

$controller = sw_controller::get_instance();

// 添加控制器命名空间
$controller->add_controller_namespace('\ui\action\user', 'user');

// 设置路由
$road_map = array(
	'user' => array('default' => true),
);
sw_router::set_road_map($road_map);
$router = new sw_router();
$controller->get_router()->add_route('user', $router);

// 分发
$controller->dispatch();
