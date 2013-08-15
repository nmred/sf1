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

namespace lib\controller\dispatcher;
use lib\controller\dispatcher\sw_interface;

/**
* 分发器-抽象类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
abstract class sw_abstract implements sw_interface
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * 参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_params = array();

	/**
	 * 设置响应对象 
	 * 
	 * @var lib\controller\response\sw_abstract
	 * @access protected
	 */
	protected $__response = null;

	/**
	 * 默认的模块名 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_module = 'default'; 

	/**
	 * 默认的方法名 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_action = 'action_default';

	/**
	 * 默认的控制器名称 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__default_controller = 'base';

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造器 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function __construct(array $params = array())
	{
		$this->set_params($params);	
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置参数 
	 * 
	 * @param string $name 
	 * @param string $value 
	 * @access public
	 * @return lib\controller\dispatcher\sw_abstract
	 */
	public function set_param($name, $value)
	{
		$name = (string) $name;
		$this->__invoke_params[$name] = $value;
		return $this;
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 设置参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return lib\controller\dispatcher\sw_abstract 
	 */
	public function set_params(array $params)
	{
		$this->__invoke_params = array_merge($this->__invoke_params, $params);
		return $this;
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取某个参数 
	 * 
	 * @param string $name 
	 * @access public
	 * @return mixed
	 */
	public function get_param($name)
	{
		if (isset($this->__invoke_params[$name])) {
			return $this->__invoke_params[$name];	
		}

		return null;
	}

	// }}}
	// {{{ public function get_params()

	/**
	 * 获取所有的参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_params()
	{
		return $this->__invoke_params;	
	}

	// }}}
	// {{{ public function clear_params()

	/**
	 * 清除全部或某个参数 
	 * 
	 * @param null|string|array $name 
	 * @access public
	 * @return lib\controller\dispatcher\sw_abstract 
	 */
	public function clear_params($name = null)
	{
		if (null === $name) {
			$this->__invoke_params = array();	
		} elseif (is_string($name) && isset($this->__invoke_params[$name])) {
			unset($this->__invoke_params[$name]);	
		} elseif (is_array($name)) {
			foreach ($name as $key) {
				if (is_string($key) && isset($this->__invoke_params[$key])) {
					unset($this->__invoke_params[$key]);	
				}	
			}	
		}

		return $this;
	}

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象 
	 * 
	 * @param lib\controller\response\sw_abstract $response 
	 * @access public
	 * @return lib\controller\dispatcher\sw_abstract
	 */
	public function set_response(\lib\controller\response\sw_abstract $response)
	{
		$this->__response = $response;
		return $this;
	}

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取响应对象 
	 * 
	 * @access public
	 * @return lib\controller\response\sw_abstract
	 */
	public function get_response()
	{
		return $this->__response;
	}

	// }}}
	// {{{ public function set_default_module()

	/**
	 * 设置默认的模块 
	 * 
	 * @param string $module 
	 * @access public
	 * @return \lib\controller\dispatcher\sw_abstract
	 */
	public function set_default_module($module)
	{
		if (is_string($module)) {
			$this->__default_module = $module;
		}

		return $this;
	}

	// }}}
	// {{{ public function get_default_module()

	/**
	 * 获取默认的模型 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_module()
	{
		return $this->__default_module;	
	}

	// }}}
	// {{{ public function set_default_controller()

	/**
	 * 设置默认的控制器 
	 * 
	 * @param string $controller 
	 * @access public
	 * @return \lib\controller\dispatcher\sw_abstract
	 */
	public function set_default_controller($controller)
	{
		if (is_string($controller)) {
			$this->__default_controller = $controller;
		}

		return $this;
	}

	// }}}
	// {{{ public function get_default_controller()

	/**
	 * 获取默认的控制器 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_controller()
	{
		return $this->__default_controller;	
	}

	// }}}
	// {{{ public function set_default_action()

	/**
	 * 设置默认的动作 
	 * 
	 * @param string $action 
	 * @access public
	 * @return \lib\controller\dispatcher\sw_abstract
	 */
	public function set_default_action($action)
	{
		if (is_string($action)) {
			$this->__default_action = $action;
		}

		return $this;
	}

	// }}}
	// {{{ public function get_default_action()

	/**
	 * 获取默认的方法 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_action()
	{
		return $this->__default_action;	
	}

	// }}}
	// }}}
}
