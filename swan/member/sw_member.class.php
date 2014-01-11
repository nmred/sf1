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
 
namespace swan\member;
use \swan\member\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 数据库映射对象工厂入口 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_member
{
	// {{{ members

	/**
	 * 工厂调用的空间 
	 * 
	 * @var string
	 * @access protected
	 */
	protected static $__namespace;

	// }}}
	// {{{ functions
	// {{{ public static function set_namespace()

	/**
	 * 设置命名空间 
	 * 
	 * @param string $namespace 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function set_namespace($namespace)
	{
		if (is_string($namespace)) { 
			self::$__namespace = $namespace;	
		}
	}

	// }}}
	// {{{ public static function property_factory()

	/**
	 * 属性工厂 
	 * 
	 * @param string $module 
	 * @param string $type 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public static function property_factory($module, $type, $params = array())
	{
		if (!isset(self::$__namespace)) {
			throw new sw_exception('not set namespace to member.');
		}
		
		$class_name = rtrim(self::$__namespace, '/') . "//$module//property//sw_$type";

		return new $class_name;
	}

	// }}}			
	// {{{ public static function condition_factory()

	/**
	 * 条件工厂 
	 * 
	 * @param string $module 
	 * @param string $type 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public static function condition_factory($module, $type, $params = array())
	{
		if (!isset(self::$__namespace)) {
			throw new sw_exception('not set namespace to member.');
		}
		
		$class_name = rtrim(self::$__namespace, '/') . "//$module//condition//sw_$type";

		return new $class_name;
	}

	// }}}			
	// {{{ public static function operater_factory()

	/**
	 * 操作工厂 
	 * 
	 * @param string $module 
	 * @param string $type 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public static function operater_factory($module, $type, $params = array())
	{
		if (!isset(self::$__namespace)) {
			throw new sw_exception('not set namespace to member.');
		}
		
		$class_name = rtrim(self::$__namespace, '/') . "//$module//operater//sw_$type";

		return new $class_name;
	}

	// }}}			
	// }}}
}
