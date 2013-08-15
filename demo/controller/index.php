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
 
require_once dirname(dirname(__DIR__)) . '/sf_core.php';
require_once PATH_SF_LIB . 'loader/sw_standard_auto_loader.class.php';
$autoloader = new lib\loader\sw_standard_auto_loader(
	array(
		'namespaces' => array(
			'lib' => PATH_SF_BASE,
			'ui' => './',
		),
));
$autoloader->register();

use lib\controller\sw_controller;
use lib\controller\router\route\sw_default;

$controller = sw_controller::get_instance();
$controller->add_controller_namespace('\ui\user', 'user');

$road_map = array(
	'user' => array('default' => true),
);
sw_default::set_road_map($road_map);
$router = new sw_default();
$controller->get_router()->add_route('user', $router);

$controller->dispatch();
