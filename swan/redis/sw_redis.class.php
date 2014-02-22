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

namespace swan\redis;
use swan\config\sw_config;
use swan\redis\exception\sw_exception;
use Redis;
 
/**
* 生成 redis 连接的类
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
*/
class sw_redis
{
	// {{{ const
	// }}}
	// {{{ members
	
	/**
	 * 存储数据库的单件 
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $__db = null; 

	// }}}
	// {{{ functions
	// {{{ public static function factory()

	/**
	 * 数据库工厂 
	 * 
	 * @param array $options 
	 * @static
	 * @access public
	 * @return swan\db\sw_abstract
	 */
	public static function factory($options = array())
	{
		if (empty($options) || !isset($options['host']) || !isset($options['port'])) {
			$options = sw_config::get_config('redis');
		}

		$redis = new Redis();
		try {
			if (isset($options['timeout'])) {
				$redis->connect($options['host'], $options['port'], $options['timeout']);	
			} else {
				$redis->connect($options['host'], $options['port']);	
			}
		} catch (\Exception $e) {
			throw new sw_exception($e);	
		}
		return $redis;
	}

	// }}}
	// {{{ public static function singleton()

	/**
	 * 生成单件 
	 * 
	 * @param array $options 
	 * @static
	 * @access public
	 * @return swan\db\sw_abstract 
	 */
	public static function singleton($options = array())
	{
		if (!isset(self::$__db)) {
			self::$__db = self::factory($options);
		}

		return self::$__db;
	}

	// }}}
	// {{{ public static function singleton_clear()

	/**
	 * 清除单件，多进程不能共享一个连接 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function singleton_clear()
	{
		self::$__db = null;	
	}

	// }}}
	// }}}
}
