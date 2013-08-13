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

namespace lib\controller\action\helper;
use lib\controller\action\helper\exception\sw_exception;

/**
* 助手-抽象类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
abstract class sw_abstract
{
	// {{{ consts

	/**
	 * 助手类名前缀  
	 */
	const HELPER_PREFIX = 'sw_';

	// }}}
	// {{{ members

	/**
	 * 控制器对象 
	 * 
	 * @var \lib\controller\sw_action
	 * @access protected
	 */
	protected $__action_controller;

	/**
	 * 前端控制器对象 
	 * 
	 * @var \lib\controller\sw_controller
	 * @access protected
	 */
	protected $__controller = null;

	// }}}
	// {{{ functions
	// {{{ public function set_action_controller()

	/**
	 * 设置动作控制器 
	 * 
	 * @param \lib\controller\sw_action $action_controller 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public function set_action_controller(\lib\controller\sw_action $action_controller)
	{
		$this->__action_controller = $action_controller;	
		return $this;
	}

	// }}}
	// {{{ public function get_action_controller()

	/**
	 * 获取动作控制器对象 
	 * 
	 * @access public
	 * @return \lib\controller\sw_action
	 */
	public function get_action_controller()
	{
		return $this->__action_controller;	
	}

	// }}}
	// {{{ public function set_controller()

	/**
	 * 设置前端控制器 
	 * 
	 * @param \lib\controller\sw_controller $controller 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public function set_controller(\lib\controller\sw_controller $controller)
	{
		$this->__controller = $controller;	
		return $this;
	}

	// }}}
	// {{{ public function get_controller()

	/**
	 * 获取前端控制器对象 
	 * 
	 * @access public
	 * @return \lib\controller\sw_controller
	 */
	public function get_controller()
	{
		return $this->__controller;	
	}

	// }}}
	// {{{ public function init()

	/**
	 * 初始化动作助手 
	 * 
	 * @access public
	 * @return void
	 */
	public function init()
	{
		
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发动作前执行 
	 * 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch()
	{
		
	}

	// }}}
	// {{{ public function post_dispatch()

	/**
	 * 在分发后执行 
	 * 
	 * @access public
	 * @return void
	 */
	public function post_dispatch()
	{
		
	}

	// }}}
	// {{{ public function get_request()

	/**
	 * 获取请求对象 
	 * 
	 * @access public
	 * @return \lib\controller\request\sw_abstract
	 */
	public function get_request()
	{
		$controller = $this->get_action_controller();
		if (null === $controller) {
			$controller = $this->get_controller();	
		}

		return $controller->get_request();
	}

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取响应对象 
	 * 
	 * @access public
	 * @return \lib\controller\response\sw_abstract
	 */
	public function get_response()
	{
		$controller = $this->get_action_controller();
		if (null === $controller) {
			$controller = $this->get_controller();
		}

		return $controller->get_response();
	}

	// }}}
	// {{{ public function get_name()

	/**
	 * 获取动作助手的名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_name()
	{
		$full_name = get_class($this);
		if (false !== strpos($full_name, '\\')) {
			$tmp = explode('\\', $full_name);
			$full_name = end($tmp);	
		}

		if (false !== strpos($full_name, self::HELPER_PREFIX)) {
			return str_replace(self::HELPER_PREFIX, '', $full_name);
		}

		return $full_name;
	}

	// }}}
	// }}}
}
