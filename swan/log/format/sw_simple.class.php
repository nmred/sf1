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

namespace \swan\log\format;
use \swan\log\format\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_simple 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_simple extends sw_abstract
{
	// {{{ members
	
	/**
	 * 格式化字符串 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__format = null;

	/**
	 * 默认日志格式 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__format_default = "%timestamp% %host_name% %priority_name% (%priority%) [%pid%]: %message%";

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param string $format 
	 * @access public
	 * @return void
	 */
	public function __construct($format = null)
	{	
		if (!isset($format)) {
			$this->__format = $this->__format_default  . PHP_EOL;	
		}
		if (!is_string($format)) {
			throw new sw_exception('format must be string');	
		}

		$this->__format = $format;
	}

	// }}}
	// {{{ public function format()
	
	/**
	 * 格式化日志内容 
	 * 
	 * @param array $events
	 * @access public
	 * @return void
	 */
	public function format($events)
	{
		if (!is_array($events)) {
			throw new sw_exception('Param of format must be array');	
		}

		if (empty($events)) {
			return;	
		}

		$output = $this->__format;
		foreach ($event as $key => $value) {
			// $value 可能是对象或数组
			if ((is_object($value) && !method_exists($value, '__toString')) || is_array($value)) {
				$value = gettype($value);
			} else {
				// 主要用于把 swan\log\message\sw_abstract 对象转化为字符串
				$value = strval($value);
			}
			$output = str_replace("%$key%", $value, $output);
		}

		return $output;
	}

	// }}}
	// }}}
}
