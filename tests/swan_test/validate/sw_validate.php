<?php
define('PATH_SF_TEST_BASE', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
require PATH_SF_TEST_BASE . 'swanphp.php';
$autoloader = \swan\loader\sw_auto::get_instance(array(
     'namespaces' => array(
         'lib' => PATH_SF_TEST_BASE . 'swan',
     ),
 ));
$autoloader->register();

//try {
//	\swan\validate\sw_validate::validate_in_array('1', array(array(1, 2), false));
//} catch (\swan\exception\sw_exception $e) {
//	echo $e->getMessage() ;	
//}

//try {
//	\swan\validate\sw_validate::validate_ip('1');
//} catch (\swan\exception\sw_exception $e) {
//	echo $e->getMessage() ;	
//}

//try {
//	\swan\validate\sw_validate::validate_between('1', 3, 4);
//} catch (\swan\exception\sw_exception $e) {
//	echo $e->getMessage() ;	
//}

try {
	\swan\validate\sw_validate::validate_int('sds');
} catch (\swan\exception\sw_exception $e) {
	echo $e->getMessage() ;	
}
