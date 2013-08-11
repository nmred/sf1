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

namespace lib\controller\router;
use lib\controller\router\exception\sw_exception;
use lib\controller\sw_controller;

/**
* 路由器-抽象类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
abstract class sw_abstract
{
	// {{{ members

	/**
	 * \lib\controller\sw_controller 实例 
	 * 
	 * @var \lib\controller\sw_controller
	 * @access protected
	 */
	protected $__controller;

	/**
	 * 从 action 中传入的参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_params = array();

	// }}}
	// {{{ functions
	// {{{ public function __constrcut()

	/**
	 * 构造函数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function __constrcut(array $params = array())
	{
		$this->set_params($params);	
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 增加或者修改一个参数 
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return lib\controller\router\sw_abstract
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
	 * 批量增加或修改参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return lib\controller\router\sw_abstract 
	 */
	public function set_params(array $params)
	{
		$this->__invoke_params = array_merge($this->__invoke_params, $params);
		return $this;	
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取一个参数值 
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
	 * 获取多个参数值 
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
	 * 清理参数 
	 * 
	 * @param string $name 
	 * @access public
	 * @return lib\controller\router\sw_abstract
	 */
	public function clear_params($name = null)
	{
		if (null === $name) {
			$this->__invoke_params = array();	
		} elseif (is_string($name) && isset($this->__invoke_params[$name])) {
			unset($this->__invoke_params[$name]);
		} elseif (is_array($name)) {
			foreach ($name as $key) {
				if (is_string($key) && isset($this->__invoke_params)) {
					unset($this->__invoke_params[$key]);	
				}	
			}	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_controller()

	/**
	 * 获取控制器 
	 * 
	 * @access public
	 * @return \lib\controller\sw_controller
	 */
	public function get_controller()
	{
		if (null !== $this->__controller) {
			return $this->__controller;	
		}

		$this->__controller = sw_controller::get_instance();
		return $this->__controller;
	}

	// }}}
	// {{{ public function set_controller()

	/**
	 * 设置 sw_controller 对象实例 
	 * 
	 * @param \lib\controller\sw_controller $controller 
	 * @access public
	 * @return lib\controller\router\sw_abstract 
	 */
	public function set_controller(\lib\controller\sw_controller $controller)
	{
		$this->__controller = $controller;
		return $this;	
	}

	// }}}
	// }}}
}
