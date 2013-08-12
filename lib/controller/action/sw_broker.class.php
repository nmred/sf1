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

namespace lib\controller\plugin;
use lib\controller\plugin\sw_abstract;
use lib\controller\sw_controller;
use lib\controller\plugin\exception\sw_exception;

/**
* 插件-经纪人类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_broker extends sw_abstract
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * 存储插件对象 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__plugins = array();

	// }}}
	// {{{ functions
	// {{{ public function register_plugin()

	/**
	 * 注册插件 
	 * 
	 * @param lib\controller\plugin\sw_abstract $plugin 
	 * @param int|null $stack_index 
	 * @access public
	 * @return lib\controller\plugin\sw_broker
	 */
	public function register_plugin(\lib\controller\plugin\sw_abstract $plugin, $stack_index = null)
	{
		if (false !== array_search($plugin, $this->__plugins, true)) {
			throw new sw_exception('Plugin already registered');	
		}	

		$stack_index = (int) $stack_index;

		if ($stack_index) {
			if (isset($this->__plugins[$stack_index])) {
				throw new sw_exception('Plugin with stackIndex "' . $stack_index . '" already registered');	
			}
			$this->__plugins[$stack_index] = $plugin;	
		} else {
			$stack_index = count($this->__plugins);
			while (isset($this->__plugins[$stack_index])) {
				++$stack_index;	
			}
			$this->__plugins[$stack_index] = $plugin;
		}

		$request = $this->get_request();
		if ($request) {
			$this->__plugins[$stack_index]->set_request($request);	
		}
		$response = $this->get_response();
		if ($response) {
			$this->__plugins[$stack_index]->set_response($response);	
		}

		ksort($this->__plugins);

		return $this;
	}

	// }}}
	// {{{ public function unregister_plugin()

	/**
	 * 注销一个插件 
	 * 
	 * @param \lib\controller\plugin\sw_abstract|string $plugin 
	 * @access public
	 * @return \lib\controller\plugin\sw_broker
	 */
	public function unregister_plugin($plugin)
	{
		if ($plugin instanceof \lib\controller\plugin\sw_abstract) {
			$key = array_search($plugin, $this->__plugins, true);
			if (false === $key) {
				throw new sw_exception('Plugin never registered.');	
			}
			unset($this->__plugins[$key]);
		} elseif (is_string($plugin)) {
			foreach ($this->__plugins as $key => $value) {
				$type = get_class($value);
				if ($plugin == $type) {
					unset($this->__plugins[$key]);	
				}	
			}
		}

		return $this;
	}

	// }}}	
	// {{{ public function has_plugin()

	/**
	 * 判断是否存在插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return boolean
	 */
	public function has_plugin($class)
	{
		foreach ($this->__plugins as $plugin) {
			$type = get_class($plugin);
			if ($class == $type) {
				return true;	
			}
		}

		return false;
	}

	// }}}
	// {{{ public function get_plugin()

	/**
	 * 获取一个插件 
	 * 
	 * @param string $class 
	 * @access public
	 * @return \lib\controller\plugin\sw_abstract
	 */
	public function get_plugin($class)
	{
		$found = array();
		foreach ($this->__plugins as $plugin) {
			$type = get_class($plugin);
			if ($class == $type) {
				$found[] = $plugin;	
			}
		}

		switch (count($found)) {
			case 0:
				return false;
			case 1:
				return $found[0];
			default:
				return $found;	
		}
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
		return $this->__plugins;	
	}

	// }}}
	// {{{ public function set_request()

	/**
	 * 设置请求对象 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return \lib\controller\plugin\sw_abstract
	 */
	public function set_request(\lib\controller\request\sw_abstract $request)
	{
		$this->__request = $request;

		foreach ($this->__plugins as $plugin) {
			$plugin->set_request($request);	
		}

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
	 * @return \lib\controller\plugin\sw_abstract
	 */
	public function set_response(\lib\controller\response\sw_abstract $response)
	{
		$this->__response = $response;

		foreach ($this->__plugins as $plugin) {
			$plugin->set_response($response);	
		}
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
	// {{{ public function route_startup()

	/**
	 * 在路由分发前动作 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_startup(\lib\controller\request\sw_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->route_startup($request);	
			} catch (sw_exception $e) {
				if (sw_controller::get_instance()->throw_exceptions()) {
					throw $e;
				} else {
					$this->get_response()->set_exception($e);	
				}
			}
		}
	}

	// }}}
	// {{{ public function route_shutdown()

	/**
	 * 在路由分发后动作 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_shutdown(\lib\controller\request\sw_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->route_shutdown($request);	
			} catch (sw_exception $e) {
				if (sw_controller::get_instance()->throw_exceptions()) {
					throw $e;
				} else {
					$this->get_response()->set_exception($e);	
				}
			}
		}
	}

	// }}}
	// {{{ public function dispatch_loop_startup()

	/**
	 * 在分发器分发前动作 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_startup(\lib\controller\request\sw_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->dispatch_loop_startup($request);	
			} catch (sw_exception $e) {
				if (sw_controller::get_instance()->throw_exceptions()) {
					throw $e;
				} else {
					$this->get_response()->set_exception($e);	
				}
			}
		}
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发器调用 Action 分发前动作 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch(\lib\controller\request\sw_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->pre_dispatch($request);	
			} catch (sw_exception $e) {
				if (sw_controller::get_instance()->throw_exceptions()) {
					throw $e;
				} else {
					$this->get_response()->set_exception($e);	
				}
			}
		}
	}

	// }}}
	// {{{ public function post_dispatch()

	/**
	 * 在分发器调用 Action 分发后动作 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function post_dispatch(\lib\controller\request\sw_abstract $request)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->post_dispatch($request);	
			} catch (sw_exception $e) {
				if (sw_controller::get_instance()->throw_exceptions()) {
					throw $e;
				} else {
					$this->get_response()->set_exception($e);	
				}
			}
		}
	}

	// }}}
	// {{{ public function dispatch_loop_shutdown()

	/**
	 * 在分发器分发后动作 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_shutdown(\lib\controller\request\sw_abstract $request = null)
	{
		foreach ($this->__plugins as $plugin) {
			try {
				$plugin->dispatch_loop_shutdown($request);	
			} catch (sw_exception $e) {
				if (sw_controller::get_instance()->throw_exceptions()) {
					throw $e;
				} else {
					$this->get_response()->set_exception($e);	
				}
			}
		}
	}

	// }}}
	// }}}
}
