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

namespace swan\config;
use \swan\config\exception\sw_exception;

/**
* 系统配置接口 
* 
* @package swan
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
*/
class sw_config
{
	// {{{ members

	/**
	 * 存取配置文件配置项 
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $__cfg = null;

	/**
	 * 配置文件路劲 
	 * 
	 * @var string
	 * @access protected
	 */
	protected static $__config_file;

	/**
	 * 配置文件路劲 
	 * 
	 * @var string
	 * @access protected
	 */
	protected static $__configs = array();

	// }}}
	// {{{ functions
	// {{{ public static function get_config()

	/**
	 * 获取配置 
	 * 
	 * @param string $type 获取配置类型 
	 * @access public
	 * @return mixed|null
	 */
	public static function get_config($type = null)
	{
		if (!isset(self::$__cfg) && isset(self::$__config_file)) {
			self::$__cfg = include(self::$__config_file);
		}

		self::$__cfg = array_merge(self::$__cfg, self::$__configs);
		
		if (!isset($type)) {	
			return self::$__cfg;
		}

		if (false !== strpos($type, ':')) {
			list($type, $child_type) = explode(':', $type);	
			if (isset(self::$__cfg[$type][$child_type])) {
				return self::$__cfg[$type][$child_type];	
			} else {
				return null;	
			}
		}

		if (isset(self::$__cfg[$type])) {
			return self::$__cfg[$type];	
		}

		return null;
	}

	// }}}
	// {{{ public static function set_config()

	/**
	 * 设置配置 
	 * 
	 * @param mixed $value 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function set_config($value)
	{
		if (!is_array($value) && is_file($value)) {
			self::$__config_file = $value;
		} else if (is_array($value)){
			self::$__configs = $value;	
		} else {
			throw new sw_exception("param is invalid, param must is array or config path.");	
		}
	}

	// }}}
	// }}}		
}
