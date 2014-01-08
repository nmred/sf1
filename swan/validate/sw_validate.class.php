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
 
namespace swan\validate;
use \swan\validate\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_validate 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_validate
{
	// {{{ functions
	// {{{ static public function __call()
	
	/**
	 * __callStatic 
	 * 
	 * @param mixed $method 
	 * @param mixed $args 
	 * @static
	 * @access public
	 * @return void
	 */
	static public function __callStatic($method, $args)
	{
		if ('validate_' !== substr($method, 0, 9)) {
			throw new sw_exception("Not exists $method() function");
		}

		if (!isset($args[0])) {
			throw new sw_exception("Not exists will validate value. ");
		}

		$class_name = "\\swan\\validate\\sw_" . substr($method, 9);

		$options = func_get_args();
		$valid_value = array_shift($options[1]);
		$validate = new $class_name($options[1]);
		if ($validate->is_valid($valid_value)) {
			return true;	
		} else {
			throw new sw_exception(implode(' ', $validate->get_messages()));
		}
	}
	
	// }}}
	// }}}	
}
