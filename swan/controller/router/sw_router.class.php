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

namespace swan\controller\router;
use swan\controller\router\sw_abstract;
use swan\controller\router\exception\sw_exception;
use swan\controller\sw_controller;

/**
* 路由管理器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_router extends sw_abstract
{
	// {{{ members

	/**
	 * 路由匹配 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__routes = array();

	/**
	 * 当前路由 
	 * 
	 * @var \swan\controller\router\route\sw_abstract
	 * @access protected
	 */
	protected $__current_route = null;

	// }}}
	// {{{ functions
	// {{{ public function add_route()

	/**
	 * 添加路由 
	 * 
	 * @param string $name 
	 * @param \swan\controller\router\route\sw_abstract $route 
	 * @access public
	 * @return \swan\controller\router\sw_router 
	 */
	public function add_route($name, \swan\controller\router\route\sw_abstract $route)
	{
		if (method_exists($route, 'set_request')) {
			$route->set_request($this->get_controller()->get_request());	
		}

		$this->__routes[$name] = $route;

		return $this;
	}

	// }}}
	// {{{ public function add_routes()

	/**
	 * 添加多个路由 
	 * 
	 * @param array $routes 
	 * @access public
	 * @return \swan\controller\router\sw_router 
	 */
	public function add_routes($routes)
	{
		foreach ($routes as $name => $route) {
			$this->add_route($name, $route);	
		}	

		return $this;
	}

	// }}}
	// {{{ public function remove_route()

	/**
	 * 从路由链中移除 
	 * 
	 * @param mixed $name 
	 * @access public
	 * @return void
	 */
	public function remove_route($name)
	{
		if (!isset($this->__routes[$name])) {
			throw new sw_exception("Route $name is not defined");	
		}

		unset($this->__routes[$name]);
		return $this;
	}

	// }}}
	// {{{ public function has_route()

	/**
	 * 检查路由是否存在 
	 * 
	 * @param string $name 
	 * @access public
	 * @return boolean 
	 */
	public function has_route($name)
	{
		return isset($this->__routes[$name]);	
	}

	// }}}
	// {{{ public function get_route()

	/**
	 * 获取一个路由对象 
	 * 
	 * @param string $name 
	 * @access public
	 * @return  \swan\controller\router\route\sw_abstract 
	 */
	public function get_route($name)
	{
		if (!isset($this->__routes[$name])) {
			throw new sw_exception("Route $name is not defined");	
		}	

		return $this->__routes[$name];
	}

	// }}}
	// {{{ public function get_current_route()

	/**
	 * 获取当前的路由对象 
	 * 
	 * @access public
	 * @return  \swan\controller\router\route\sw_abstract 
	 */
	public function get_current_route()
	{
		if (!isset($this->__current_route)) {
			throw new sw_exception("Current route is not defined");	
		}

		return $this->get_route($this->__current_route);
	}

	// }}}
	// {{{ public function get_current_route_name()

	/**
	 * 获取当前路由的名称 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_current_route_name()
	{
		if (!isset($this->__current_route)) {
			throw new sw_exception("Current route is not defined");	
		}

		return $this->__current_route;
	}

	// }}}
	// {{{ public function get_routes()

	/**
	 * 获取所有的路由链 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_routes()
	{
		return $this->__routes;	
	}

	// }}}
	// {{{ public function route()

	/**
	 * 查找匹配的路由器 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return \swan\controller\request\sw_abstract
	 */
	public function route(\swan\controller\request\sw_abstract $request)
	{
		foreach (array_reverse($this->__routes) as $name => $route) {
			if ($params = $route->match($request)) {
				$this->_set_request_params($request, $params);
				$this->__current_route = $name;
				break;	
			}
		}

		return $request;
	}

	// }}}
	// {{{ protected function _set_request_params()

	/**
	 * 设置 request 参数  
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @param array $params 
	 * @access protected
	 * @return void
	 */
	protected function _set_request_params($request, $params)
	{
		foreach ($params as $param => $value) {
			$request->set_param($param, $value);

			if ($param == $request->get_module_key()) {
				$request->set_module_name($value);	
			}

			if ($param == $request->get_controller_key()) {
				$request->set_controller_name($value);	
			}

			if ($param == $request->get_action_key()) {
				$request->set_action_name($value);	
			}
		}	
	}

	// }}}
	// }}}
}
