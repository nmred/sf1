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
		if (!isset(self::$__cfg)) {
			self::$__cfg = include(PATH_SF_CONF . 'config.php');
		}
		
		if (!isset($type)) {	
			return self::$__cfg;
		}

		if (isset(self::$__cfg[$type])) {
			return self::$__cfg[$type];	
		}

		return null;
	}

	// }}}
	// }}}		
}