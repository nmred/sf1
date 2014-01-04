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
 
namespace swan\log;
use \swan\log\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 日志模块 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_log
{
	// {{{ members
	
	/**
	 * 日志写入方式对象 
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $__writer = array();

	/**
	 * 日志级别 
	 * 
	 * @var array
	 * @access protected
	 */
	protected static $__priorities = array(
		LOG_EMERG   => 'EMERG',
		LOG_ALERT   => 'ALERT',
		LOG_CRIT    => 'CRIT',
		LOG_ERR     => 'ERR',
		LOG_WARNING => 'WARNING',
		LOG_NOTICE  => 'NOTICE',
		LOG_INFO    => 'INFO',
		LOG_DEBUG   => 'DEBUG',	
	);

	// }}}
	// {{{ functions
	// {{{ public static function log()
	
	/**
	 * log 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function log($message, $level)
	{
		if (empty(self::$__writer)) {
			throw new sw_exception('write objects are empty');	
		}

		if (!array_key_exists($level, self::$__priorities)) {
			throw new sw_exception("priority `$level` deny");	
		}

		// 默认的格式
		$space = ' ';
		$microtime_array = explode($space, microtime());
		$timestamp = date('c') . $space . $microtime_array[1] . substr($microtime_array[0], 1);
		$event = array(
			'timestamp'     => $timestamp,
			'host_name'     => SW_SELF_NAME,
			'message'       => $message,
			'priority'      => $level,
			'priority_name' => self::$__priorities[$level],
			'pid'           => posix_getpid()
		);

		// 写日志
		foreach (self::$__writer as $writer) {
			$writer->write($event);
		}
	}

	// }}}		
	// {{{ public static function writer_factory()
	
	/**
	 * 日志写入接口工厂 
	 * 
	 * @static
	 * @access public
	 * @param mixed $type 
	 * @param array $options 
	 * @return void
	 */
	public static function writer_factory($type, $options = array())
	{
		$class_name = "\\swan\\log\\writer\\sw_" . $type;	

		return new $class_name($options);
	}

	// }}}
	// {{{ public static function message_factory()
	
	/**
	 * 日志信息对象工厂 
	 * 
	 * @static
	 * @access public
	 * @param mixed $type 
	 * @param array $options 
	 * @return sw_message*
	 */
	public static function message_factory($type, $options = array())
	{
		$class_name = "\\swan\\log\\message\\sw_" . $type;	

		return new $class_name($options);
	}
	
	// }}}
	// {{{ public static function format_factory()
	
	/**
	 * format_factory 
	 * 
	 * @static
	 * @access public
	 * @param mixed $type 
	 * @param array $options 
	 * @return void
	 */
	public static function format_factory($type, $options = null)
	{
		$class_name = "\\swan\\log\\format\\sw_" . $type;	

		return new $class_name($options);
	}
	 
	// }}}
	// {{{ public static function add_writer()
	
	/**
	 * 添加日志输出方式 
	 * 
	 * @param \swan\log\writer\sw_abstract $writer 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function add_writer(\swan\log\writer\sw_abstract $writer)
	{
		self::$__writer[] = $writer;
	}

	// }}}
	// {{{ public static function del_all_writer()
	
	/**
	 * 清除所有的 writer 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function del_all_writer()
	{
		self::$__writer = array();		
	}

	// }}}
	// }}}
}
