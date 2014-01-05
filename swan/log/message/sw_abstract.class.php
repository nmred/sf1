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
 
namespace swan\log\message;
use \swan\log\message\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_abstract 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_abstract
{
	// {{{ members
	
	/**
	 * 参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__params = array();

	/**
	 * 默认参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__default_params = array();
	
	// }}}	
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param mixed $options 
	 * @access public
	 * @return void
	 */
	public function __construct($options = null)
	{
		if (!isset($options)) {
			return;	
		}
		
		foreach ($this->__default_params as $key => $val) {
			if (isset($options[$key])) {
				$this->__params[$key] = $options[$key];	
			}
		}	
	}

	// }}}
	// {{{ public function __set()

	/**
	 * __set 魔术函数 
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function __set($name, $value)
	{
		if (isset($this->__params[$name])) {
			$this->__params[$name] = $value;	
		} else {
			throw new sw_exception("set name `$name` not exists");	
		}
	}

	// }}}
	// {{{ public function __get()

	/**
	 * __get 
	 * 
	 * @param mixed $name 
	 * @access public
	 * @return void
	 */
	public function __get($name)
	{
		if (isset($this->__params[$name])) {
			return $this->__params[$name];	
		} else {
			throw new sw_exception("get name `$name` not exists.");	
		}
	}

	// }}}
	// {{{ public function __toString()
	
	/**
	 * __toString 
	 * 
	 * @access public
	 * @return void
	 */
	public function __toString()
	{
		return $this->_assemble();	
	}

	// }}}
	// {{{ protected function _merge_default_params()
	
	/**
	 * 合并参数 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _merge_default_params()
	{
		$this->__params = array_merge($this->__default_params, $this->__params);	
	}

	// }}}
	// {{{ abstract protected function _assemble()
	
	/**
	 * 日志内容拼接 
	 * 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function _assemble();

	// }}}$event
	// }}}
}
