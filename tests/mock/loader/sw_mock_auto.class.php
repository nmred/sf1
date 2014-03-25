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
 
namespace mock\loader;
use swan\loader\sw_auto;

/**
+------------------------------------------------------------------------------
* sw_mock_auto 
+------------------------------------------------------------------------------
* 
* @uses sw_mock_standard_auto_loader
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_mock_auto extends sw_auto
{
	// {{{ members

	/**
	 * __instance 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected  static $__instance = null;

	// }}}
	// {{{ functions
	// {{{ public function get_namespaces()

	/**
	 * get_namespaces 
	 * 
	 * @access public
	 * @return void
	 */
	public function get_namespaces()
	{
		return $this->__namespaces;	
	}

	// }}}	
	// {{{ public static funciton get_instance()
	
	/**
	 * 获取自动加载对象 
	 * 
	 * @param mixed $options 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function get_instance($options = null)
	{
		if (!isset(self::$__instance)) {
			self::$__instance = new self();	
		}

		if (null !== $options) {
			self::$__instance->set_options($options);	
		}

		return self::$__instance; 
	}
	 
	// }}}
	// }}}
}
