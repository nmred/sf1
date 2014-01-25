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

namespace swan\controller;
use swan\controller\router\sw_router;
use swan\controller\exception\sw_exception;
use swan\controller\plugin\sw_broker;

/**
* 前端控制器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_controller
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * 路由基地址 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__base_url = null;

	/**
	 *  sw_dispather 实例 
	 * 
	 * @var \swan\controller\dispatcher\sw_abstract
	 * @access protected
	 */
	protected $__dispather = null;

	/**
	 * 对象实例 
	 * 
	 * @var \swan\controller\sw_controller
	 * @access protected
	 */
	protected static $__instance = null;

	/**
	 * 调用参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__invoke_params = array();

	/**
	 * 请求对象 
	 * 
	 * @var \swan\controller\request\sw_abstract
	 * @access protected
	 */
	protected $__request = null;

	/**
	 * 响应对象 
	 * 
	 * @var \swan\controller\response\sw_abstract
	 * @access protected
	 */
	protected $__response = null;

	/**
	 * 路由管理对象 
	 * 
	 * @var \swan\controller\router\sw_abstract
	 * @access protected
	 */
	protected $__router = null;

	/**
	 * 分发器对象 
	 * 
	 * @var \swan\controller\dispatcher\sw_abstract
	 * @access protected
	 */
	protected $__dispatcher = null;

	/**
	 * 设置是否抛异常 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__throw_exceptions = false;

	/**
	 * 载入的插件列表 
	 * 
	 * @var \swan\controller\plugin\sw_broker
	 * @access protected
	 */
	protected $__plugins = null;

	/**
	 * 是否返回应答 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__return_response = false;

	// }}}
	// {{{ functions
	// {{{ protected function __construct()

	/**
	 * __construct 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function __construct()
	{
		$this->__plugins = new sw_broker();	
	}

	// }}}
	// {{{ private function __clone()

	/**
	 * 禁止克隆 
	 * 
	 * @access private
	 * @return void
	 */
	private function __clone()
	{
	}

	// }}}
	// {{{ public static function get_instance()

	/**
	 * 获取控制器的单件 
	 * 
	 * @static
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public static function get_instance()
	{
		if (null === self::$__instance) {
			self::$__instance = new self();	
		}

		return self::$__instance;
	}

	// }}}
	// {{{ public function throw_exceptions()

	/**
	 * 获取是否抛异常的标志 
	 * 
	 * @param boolean $flag 
	 * @access public
	 * @return boolean
	 */
	public function throw_exceptions($flag = null)
	{
		if ($flag !== null) {
			$this->__throw_exceptions = (bool) $flag;
			return $this;	
		}	

		return $this->__throw_exceptions;
	}

	// }}}
	// {{{ public function add_controller_namespace()

	/**
	 * 添加控制器的命名空间
	 * 
	 * @param string $namespace 
	 * @param string $module 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function add_controller_namespace($namespace, $module)
	{
		$this->get_dispatcher()->add_controller_namespace($namespace, $module);
		return $this;	
	}

	// }}}
	// {{{ public function set_controller_namespace()

	/**
	 * 设置控制器的命名空间 
	 * 
	 * @param string $namespace 
	 * @param string $module 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_controller_namespace($namespace, $module = null)
	{
		$this->get_dispatcher()->set_controller_namespace($namespace, $module);
		return $this;
	}

	// }}}
	// {{{ public function get_controller_namespace()

	/**
	 * 获取控制器的命名空间 
	 * 
	 * @param string $name 
	 * @access public
	 * @return string|array
	 */
	public function get_controller_namespace($name = null)
	{
		return $this->get_dispatcher()->get_controller_namespace($name);	
	}

	// }}}
	// {{{ public function remove_controller_namespace()

	/**
	 * 移除一个控制器的命名空间 
	 * 
	 * @param string $module 
	 * @access public
	 * @return boolean
	 */
	public function remove_controller_namespace($module)
	{
		return $this->get_dispatcher()->remove_controller_namespace($module);	
	}

	// }}}
	// {{{ public function set_default_controller()

	/**
	 * 设置默认的控制器 
	 * 
	 * @param string $controller 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_default_controller($controller)
	{
		$dispatcher = $this->get_dispatcher();
		$dispatcher->set_default_controller($controller);
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
		return $this->get_dispatcher()->get_default_controller();	
	}

	// }}}
	// {{{ public function set_default_action()

	/**
	 * 设置默认的 action 
	 * 
	 * @param string $action 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_default_action($action)
	{
		$this->get_dispatcher()->set_default_action($action);
		return $this;	
	}

	// }}}
	// {{{ public function get_default_action()

	/**
	 * 获取默认的 action  
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_action()
	{
		return $this->get_dispatcher()->get_default_action();
	}
	// }}}
	// {{{ public function set_default_module()

	/**
	 * 设置默认的模块 
	 * 
	 * @param string $module 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_default_module($module)
	{
		$this->get_dispatcher()->set_default_module($module);
		return $this;
	}

	// }}}
	// {{{ public function get_default_module()

	/**
	 * 设置默认的模块 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_default_module()
	{
		return $this->get_dispatcher()->get_default_module();
	}

	// }}}
	// {{{ public function set_request()

	/**
	 * 设置请求对象
	 * 
	 * @param string $request 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_request($request)
	{
		if (is_string($request)) {
			$namespace = '\\swan\\controller\\request\\' . $request;
			$request = new $namespace;			
		}

		if (!$request instanceof \swan\controller\request\sw_abstract) {
			throw new sw_exception('Invalid request class');     
		}

		$this->__request = $request;

		return $this;
	}

	// }}}
	// {{{ public function get_request()

	/**
	 * 获取请求对象 
	 * 
	 * @access public
	 * @return \swan\controller\request\sw_abstract
	 */
	public function get_request()
	{
		return $this->__request;	
	}

	// }}}
	// {{{ public function set_router()

	/**
	 * 设置路由管理对象
	 * 
	 * @param string $router 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_router($router)
	{
		if (is_string($router)) {
			$namespace = '\\swan\\controller\\router\\' . $router;
			$router = new $namespace;			
		}

		if (!$router instanceof \swan\controller\router\sw_abstract) {
			throw new sw_exception('Invalid router class');     
		}

		$this->__router = $router;

		return $this;
	}

	// }}}
	// {{{ public function get_router()

	/**
	 * 获取路由管理对象 
	 * 
	 * @access public
	 * @return \swan\controller\router\sw_abstract
	 */
	public function get_router()
	{
		if (null === $this->__router) {
			$this->set_router(new sw_router());		
		}

		return $this->__router;	
	}

	// }}}
	// {{{ public function set_base_url()

	/**
	 * 设置基础 url 地址到request 对象中 
	 * 
	 * @param string $base 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_base_url($base = null)
	{
		if (!is_string($base) && (null !== $base)) {
			throw new sw_exception('base must be a string');	
		}

		$this->__base_url = $base;

		if ((null !== ($request = $this->get_request())) && (method_exists($request, 'set_base_url'))) {
			$request->set_base_url($base);	
		}

		return $this;
	}

	// }}}
	// {{{ public function get_base_url()

	/**
	 * 获取基础 url 地址 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_base_url()
	{
		$request = $this->get_request();	
		if ((null !== $request) && method_exists($request, 'get_base_url')) {
			return $this->get_base_url();	
		}

		return $this->__base_url;
	}

	// }}}
	// {{{ public fucntion set_dispatcher()

	/**
	 * 设置分发器 
	 * 
	 * @param \swan\controller\dispatcher\sw_abstract $dispatcher 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_dispatcher(\swan\controller\dispatcher\sw_abstract $dispatcher)
	{
		$this->__dispatcher = $dispatcher;
		return $this;	
	}

	// }}}
	// {{{ public function get_dispatcher()

	/**
	 * 获取分发器 
	 * 
	 * @access public
	 * @return \swan\controller\dispatcher\sw_abstract
	 */
	public function get_dispatcher()
	{
		if (null === $this->__dispatcher) {
			$this->__dispatcher = new \swan\controller\dispatcher\sw_standard();		
		}

		return $this->__dispatcher;
	}

	// }}}
	// {{{ public function set_response()

	/**
	 * 设置响应对象
	 * 
	 * @param string $response 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function set_response($response)
	{
		if (is_string($response)) {
			$namespace = '\\swan\\controller\\response\\' . $response;
			$response = new $namespace;			
		}

		if (!$response instanceof \swan\controller\response\sw_abstract) {
			throw new sw_exception('Invalid response class');     
		}

		$this->__response = $response;

		return $this;
	}

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取响应对象 
	 * 
	 * @access public
	 * @return \swan\controller\response\sw_abstract
	 */
	public function get_response()
	{
		return $this->__response;	
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置一个参数 
	 * 
	 * @param string $name 
	 * @param mixed $value 
	 * @access public
	 * @return \swan\controller\sw_controller
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
	 * @return \swan\controller\sw_controller
	 */
	public function set_params(array $params)
	{
		$this->__invoke_params = array_merge($this->__invoke_params, $params);
		return $this;
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取参数 
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
	// {{{ public fucntion clear_params()

	/**
	 * 清除参数 
	 * 
	 * @param string|null $name 
	 * @access public
	 * @return \swan\controller\sw_controller
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
	// {{{ public function register_plugin()

	/**
	 * 注册插件 
	 * 
	 * @param \swan\controller\plugin\sw_abstract $plugin 
	 * @param int $stack_index 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function register_plugin(\swan\controller\plugin\sw_abstract $plugin, $stack_index = null)
	{
		$this->__plugins->register_plugin($plugin, $stack_index);
		return $this;	
	}

	// }}}
	// {{{ public function unregister_plugin()

	/**
	 * 注销一个插件 
	 * 
	 * @param string|\swan\controller\plugin\sw_abstract $plugin 
	 * @access public
	 * @return \swan\controller\sw_controller
	 */
	public function unregister_plugin($plugin)
	{
		$this->__plugins->unregister_plugin($plugin);
		return $this;	
	}

	// }}}
	// {{{ public function has_plugin()

	/**
	 * 判断是否存在该插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return boolean
	 */
	public function has_plugin($class)
	{
		return $this->__plugins->has_plugin($class);	
	}

	// }}}
	// {{{ public function get_plugin()

	/**
	 * 获取插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return false|\swan\controller\plugin\sw_abstract
	 */
	public function get_plugin($class)
	{
		return $this->__plugins->get_plugin($class);
	}

	// }}}
	// {{{ public function get_plugins()

	/**
	 * 获取所有的插件 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_plugins()
	{
		return $this->__plugins->get_plugins();	
	}

	// }}}
	// {{{ public function return_response()

	/**
	 * 是否返回应答 
	 * 
	 * @param boolean|null $flag 
	 * @access public
	 * @return boolean|\swan\controller\sw_controller
	 */
	public function return_response($flag = null)
	{
		if (true === $flag) {
			$this->__return_response = true;	
			return $this;
		} elseif (false === $flag) {
			$this->__return_response = false;
			return $this;
		}

		return $this->__return_response;
	}

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @param \swan\controller\response\sw_abstract $response 
	 * @access public
	 * @return void|\swan\controller\response\sw_abstract
	 */
	public function dispatch(\swan\controller\request\sw_abstract $request = null, \swan\controller\response\sw_abstract $response = null)
	{
		// 初始化请求对象
		if (null !== $request) {
			$this->set_request($request);	
		} elseif ((null === $request) && (null === ($this->get_request()))) {
			$request = new \swan\controller\request\sw_http();
			$this->set_request($request);	
		}

		// 设置基地址
		if (is_callable(array($this->__request, 'set_base_url'))) {
			if (null !== $this->__base_url) {
				$this->__request->set_base_url($this->__base_url);	
			}
		}

		// 初始化响应对象
		if (null !== $response) {
			$this->set_response($response);	
		} elseif ((null === $response) && (null === ($this->get_response()))) {
			$response = new \swan\controller\response\sw_http();
			$this->set_response($response);	
		}

		// 初始化插件
		$this->__plugins->set_request($request)
						->set_response($response);	

		// 初始化路由
		$router = $this->get_router();
		$router->set_params($this->get_params());

		// 初始化分发器
		$dispatcher = $this->get_dispatcher();
		$dispatcher->set_params($this->get_params())
				   ->set_response($this->__response);

		// 开始分发
		try {
			$this->__plugins->route_startup($this->__request);
			
			// 路由分发
			$router->route($this->__request);
			
			$this->__plugins->route_shutdown($this->__request);
			
			$this->__plugins->dispatch_loop_startup($this->__request);
			
			do {
				$this->__request->set_dispatched(true);
				$this->__plugins->pre_dispatch($this->__request);

				if (!$this->__request->is_dispatched()) {
					continue;	
				}

				try {
					$dispatcher->dispatch($this->__request, $this->__response);	
				} catch (\swan\exception\sw_exception $e) {
					if ($this->throw_exceptions()) {
						throw $e;	
					}
					$this->__response->set_exception($e);
				}

				$this->__plugins->post_dispatch($this->__request);
			} while(!$this->__request->is_dispatched());
		} catch (\swan\exception\sw_exception $e) {
			if ($this->throw_exceptions()) {
				throw $e;	
			}
			$this->__response->set_exception($e);
		}

		try {
			$this->__plugins->dispatch_loop_shutdown();	
		} catch (sw_exception $e) {
			if ($this->throw_exceptions()) {
				throw $e;	
			}
			$this->__response->set_exception($e);
		} 
		
		if ($this->return_response()) {
			return $this->__response;	
		}	

		$this->__response->send_response();
	}

	// }}}
	// }}}
}
