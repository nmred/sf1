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

namespace lib\controller\action;
use lib\controller\action\sw_broker_stack;
use lib\controller\action\exception\sw_exception;

/**
* 助手-经纪人类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_broker
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
	 * 助手堆栈 
	 * 
	 * @var \lib\controller\action\helper\sw_abstract
	 * @access protected
	 */
	protected static $__stack = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param \lib\controller\sw_action $action_controller 
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\controller\sw_action $action_controller)
	{
		$this->__action_controller = $action_controller;
		foreach (self::get_stack() as $helper) {
			$helper->set_action_controller($action_controller);
			$helper->init();	
		}	
	}

	// }}}
	// {{{ public static function add_helper()

	/**
	 * 添加助手 
	 * 
	 * @param \lib\controller\action\helper\sw_abstract $helper 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function add_helper(\lib\controller\action\helper\sw_abstract $helper)
	{
		self::get_stack()->push($helper);	
		return;
	}

	// }}}
	// {{{ public static function reset_helpers()

	/**
	 * 重置 helper 的堆栈 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function reset_helpers()
	{
		self::$__stack = null;	
	}

	// }}}
	// {{{ public static function get_static_helper()

	/**
	 * 获取 helper 
	 * 
	 * @static
	 * @param string $name 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public static function get_static_helper($name)
	{
		$name = self::_normalize_helper_name($name);
		$stack = self::get_stack();
		
		if (!isset($stack->{$name})) {
			self::_load_helper($name);	
		}

		return $stack->{$name};
	}

	// }}}
	// {{{ public static function get_existing_helper()

	/**
	 * 获取存在的动作助手 
	 * 
	 * @param string $name 
	 * @static
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public static function get_existing_helper($name)
	{
		$name  = self::_normalize_helper_name($name);
		$stack = self::get_stack();
		
		if (!isset($stack->{$name})) {
			throw new sw_exception('Action helper "' . $name . '" has not been registered with the helper broker');		
		}

		return $stack->{$name};
	}

	// }}}
	// {{{ public static function get_existing_helpers()

	/**
	 * 获取所有的已经注册的动作助手 
	 * 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_existing_helpers()
	{
		return self::get_stack()->get_helpers_by_name();	
	}

	// }}}
	// {{{ public static function has_helper()

	/**
	 * 判断堆栈中是否存在该 helper 
	 * 
	 * @static
	 * @param string $name 
	 * @access public
	 * @return boolean
	 */
	public static function has_helper($name)
	{
		$name = self::_normalize_helper_name($name);
		return isset(self::get_stack()->{$name});	
	}

	// }}}
	// {{{ public static function remove_helper()

	/**
	 * 移出一个动作助手 
	 * 
	 * @param string $name 
	 * @static
	 * @access public
	 * @return boolean
	 */
	public static function remove_helper($name)
	{
		$name = self::_normalize_helper_name($name);
		$stack = self::get_stack();
		if (isset($stack->{$name})) {
			unset($stack->{$name});
			return true;	
		}	

		return false;
	}

	// }}}
	// {{{ public static function get_stack()

	/**
	 * 获取动作助手堆栈 
	 * 
	 * @access public
	 * @return \lib\controller\action\sw_broker_stack
	 */
	public static function get_stack()
	{
		if (null == self::$__stack) {
			self::$__stack = new sw_broker_stack();	
		}

		return self::$__stack;
	}

	// }}}
	// {{{ public function notify_pre_dispatch()

	/**
	 * 在动作控制器分发前执行 
	 * 
	 * @access public
	 * @return void
	 */
	public function notify_pre_dispatch()
	{
		foreach (self::get_stack() as $helper) {
			$helper->pre_dispatch();	
		}	
	}

	// }}}
	// {{{ public function notify_post_dispatch()

	/**
	 * 在动作控制器分发后执行 
	 * 
	 * @access public
	 * @return void
	 */
	public function notify_post_dispatch()
	{
		foreach (self::get_stack() as $helper) {
			$helper->post_dispatch();	
		}	
	}

	// }}}
	// {{{ public function get_helper()

	/**
	 * 获取动作助手 
	 * 
	 * @param string $name 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public function get_helper($name)
	{
		$name = self::_normalize_helper_name($name);
		$stack = self::get_stack();

		if (!isset($stack->{$name})) {
			self::_load_helper($name);	
		}

		$helper = $stack->{$name};

		$initialize = false;
		if (null === ($action_controller = $helper->get_action_controller())) {
			$initialize = true;	
		} else if ($action_controller !== $this->__action_controller) {
			$initialize = true;	
		}

		if ($initialize) {
			$helper->set_action_controller($this->__action_controller)
				   ->init();	
		}

		return $helper;
	}

	// }}}
	// {{{ public function __call()

	/**
	 * __call 
	 * 可以在控制器中： $this->__helper->xxxx(); 调用助手
	 * 
	 * @param string $method 
	 * @param mixed $args 
	 * @access public
	 * @return mixed
	 */
	public function __call($method, $args)
	{
		$helper = $this->get_helper($method);
		if (!method_exists($helper, 'direct')) {
			throw new sw_exception('Helper `' . $method . '` does not support overloading via direct()');	
		}

		return call_user_func_array(array($helper, 'direct'), $args);
	}

	// }}}
	// {{{ public function __get()

	/**
	 * __get 
	 * 
	 * @param string $name 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public function __get($name)
	{
		return $this->get_helper($name);	
	}

	// }}}
	// {{{ protected static function _normalize_helper_name()

	/**
	 * 格式化动作助手的名称 
	 * 
	 * @param string $name 
	 * @static
	 * @access protected
	 * @return string
	 */
	protected static function _normalize_helper_name($name)
	{
		return $name;	
	}

	// }}}
	// {{{ protected static function _load_helper()

	/**
	 * 加载一个动作助手 
	 * 
	 * @param string $name 
	 * @static
	 * @access protected
	 * @return void
	 */
	protected static function _load_helper($name)
	{
		$helper = null;
		$full_class = __NAMESPACE__ . '\\helper\\' . self::HELPER_PREFIX . $name;		

		$helper = new $full_class();
		if (null === $helper) {
			throw new sw_exception('Action Helper by name ' . $name . ' not found');	
		}

		if (!($helper instanceof \lib\controller\action\helper\sw_abstract)) {
			throw new sw_exception('Helper name ' . $name . ' -> class ' . $full_class . ' is not of type \lib\controller\action\helper\sw_abstract');
		}

		self::get_stack()->push($helper);
	}

	// }}}
	// }}}
}
