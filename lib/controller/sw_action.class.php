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

namespace lib\controller;
use lib\controller\action\sw_interface;
use lib\controller\exception\sw_exception;

/**
* 动作控制器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
abstract class sw_action implements sw_interface
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * 控制器中的 action 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__class_methods;

	/**
	 * 调用参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_args = array();

	/**
	 * 前端控制器 
	 * 
	 * @var \lib\controller\sw_controller
	 * @access protected
	 */
	protected $__controller = null;

	/**
	 * 请求对象 
	 * 
	 * @var \lib\controller\request\sw_abstract
	 * @access protected
	 */
	protected $__request = null;

	/**
	 * 响应对象 
	 * 
	 * @var \lib\controller\response\sw_abstract
	 * @access protected
	 */
	protected $__response = null;

	/**
	 * 动作助手经纪人 
	 * 
	 * @var \lib\controller\action\sw_broker
	 * @access protected
	 */
	protected $__helper = null;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @param \lib\controller\response\sw_abstract $\lib\controller\response\sw_abstract 
	 * @param array $invoke_args 
	 * @access public
	 * @return void
	 */
	public function __construct(\lib\controller\request\sw_abstract $request, \lib\controller\response\sw_abstract $response, array $invoke_args = array())
	{
		$this->set_request($request)
			 ->set_response($response)
			 ->_set_invoke_args($invoke_args);
		
		$this->__helper = new \lib\controller\action\sw_broker($this);	
		$this->init();
	}

	// }}}
	// {{{ public function init()

	/**
	 * 初始化对象 
	 * 
	 * @access public
	 * @return void
	 */
	public function init()
	{
		
	}

	// }}}
	// {{{ public function init_view()

	/**
	 * 初始化视图 
	 * 
	 * @access public
	 * @return void
	 */
	public function init_view()
	{
		
	}

	// }}}
	// {{{ public function set_request()

	/**
	 * 设置请求对象 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return \lib\controller\sw_action
	 */
	public function set_request(\lib\controller\request\sw_abstract $request)
	{
		$this->__request = $request;
		return $this;	
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
		return $this->__request;	
	}

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象 
	 * 
	 * @param \lib\controller\response\sw_abstract $response 
	 * @access public
	 * @return \lib\controller\sw_action
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
	 * @return \lib\controller\response\sw_abstract
	 */
	public function get_response()
	{
		return $this->__response;	
	}

	// }}}
	// {{{ protected function _set_invoke_args()

	/**
	 * 设置参数 
	 * 
	 * @param array $args 
	 * @access protected
	 * @return \lib\controller\sw_action
	 */
	protected function _set_invoke_args(array $args = array())
	{
		$this->__invoke_args = $args;
		
		return $this;	
	}

	// }}}
	// {{{ public function get_invoke_args()

	/**
	 * 获取所有调用参数 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_invoke_args()
	{
		return $this->__invoke_args;	
	}

	// }}}
	// {{{ public function get_invoke_arg()

	/**
	 * 获取某个参数 
	 * 
	 * @param string $key 
	 * @access public
	 * @return mixed
	 */
	public function get_invoke_arg($key)
	{
		if (isset($this->__invoke_args[$key])) {
			return $this->__invoke_args[$key];	
		}	

		return null;
	}

	// }}}
	// {{{ public function get_helper()

	/**
	 * 获取动作助手 
	 * 
	 * @param string $helper_name 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public function get_helper($helper_name)
	{
		return $this->__helper->{$helper_name};	
	}

	// }}}
	// {{{ public function get_helper_copy()

	/**
	 * 获取一个克隆的动作助手 
	 * 
	 * @param string $helper_name 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract
	 */
	public function get_helper_copy($helper_name)
	{
		return clone $this->__helper->{$helper_name};	
	}

	// }}}
	// {{{ public funciton set_controller()

	/**
	 * 设置前端控制器 
	 * 
	 * @param \lib\controller\sw_controller $controller 
	 * @access public
	 * @return \lib\controller\sw_action
	 */
	public function set_controller(\lib\controller\sw_controller $controller)
	{
		$this->__controller = $controller;
		return $this;	
	}

	// }}}
	// {{{ public function get_controller()

	/**
	 * 获取前端控制器 
	 * 
	 * @access public
	 * @return \lib\controller\sw_controller
	 */
	public function get_controller()
	{
		if (null !== $this->__controller) {
			return $this->__controller;
		}

		if (class_exists('\lib\controller\sw_controller')) {
			$this->__controller = \lib\controller\sw_controller::get_instance();
			return $this->__controller;	
		}

		throw new sw_exception('Front controller class has not been loaded');
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发前执行 
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

	// }}}post_dispatch
	// {{{ public function __call()

	/**
	 * __call 
	 * 
	 * @param string $method_name 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	public function __call($method_name, $args)
	{
		if ('action' == substr($method_name, 0, 6)) {
			throw new sw_exception(sprintf('Action `%s` does not exist and was not trapped in __call()', $method_name), 404);	
		}

		throw new sw_exception(sprintf('Method `%s` does not exist and was not trapped in __call()', $method_name), 500);
	}

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发 action 
	 * 
	 * @param string $action 
	 * @access public
	 * @return void
	 */
	public function dispatch($action)
	{
		$this->__helper->notify_pre_dispatch();

		$this->pre_dispatch();
		if ($this->get_request()->is_dispatched()) {             
			if (null === $this->__class_methods) {
				$this->__class_methods = get_class_methods($this);  
			}    
			if (!($this->get_response()->is_redirect())) {
				if ($this->get_invoke_args('use_case_sensitive_actions') || in_array($action, $this->__class_methods)) {
					if ($this->get_invoke_args('use_case_sensitive_actions')) {
						trigger_error('Using case sensitive actions without word separators is deprecated; please do not rely on this "feature"');  
					}
					$this->$action();
				} else {
					$this->__call($action, array());    
				}
			}
			$this->post_dispatch();
		}

		$this->__helper->notify_post_dispatch();	
	}

	// }}}
	// {{{ public function json_stdout()

	/**
	 * 以 json 格式返回 
	 * 
	 * @param array $options 
	 * @access public
	 * @return void
	 */
	public function json_stdout(array $options)
	{
		ob_start();
		echo json_stdout($options);
		$this->get_response()->append_body(
			$this->get_request()->get_action_name(),
			ob_get_clean()
		);
	}

	// }}}
	// }}}
}
