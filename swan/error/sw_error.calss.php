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
 
/**
+------------------------------------------------------------------------------
* PHP 错误处理类 
+------------------------------------------------------------------------------
* 
* @final
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
final class sw_error
{
	// {{{ functions
	// {{{ public static function error_to_string()

	/**
	 * 将错误的数组信息转化为字符串 
	 * 
	 * @param array $error 
	 * @static
	 * @access public
	 * @return string
	 */
	public static function error_to_string(array $error)
	{
		return self::_error_no_to_string($error[0]) . ": {$error[1]} in {$error[2]} on line {$error[3]}";	
	}

	// }}}
	// {{{ public static function _error_no_to_string()

	/**
	 * 获取对应的错误编号的信息 
	 * 
	 * @param mixed $errno 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function _error_no_to_string($err_no)
	{
        $array_no = array (
        	E_ERROR              => 'Error',
        	E_WARNING            => 'Warning',
        	E_PARSE              => 'Parsing Error',
        	E_NOTICE             => 'Notice',
        	E_CORE_ERROR         => 'Core Error',
        	E_CORE_WARNING       => 'Core Warning',
        	E_COMPILE_ERROR      => 'Compile Error',
        	E_COMPILE_WARNING    => 'Compile Warning',
        	E_USER_ERROR         => 'User Error',
        	E_USER_WARNING       => 'User Warning',
        	E_USER_NOTICE        => 'User Notice',
        	E_STRICT             => 'Runtime Notice',
        	E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
     	);

    	return isset($array_no[$err_no]) ? $array_no[$err_no] : 'Unknow';		
	}

	// }}}
	// }}}
}
