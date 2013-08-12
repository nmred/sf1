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
use lib\controller\dispatcher\sw_abstract;
use lib\controller\dispatcher\exception\sw_exception;

/**
* 分发器-标准分发器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_standard extends sw_abstract
{
	// {{{ consts

	/**
	 * action类名的前缀
	 *
	 * @var string
	 */
	const CLASS_PREFIX = 'sw_';

	// }}}
	// {{{ members

	/**
	 * 当前命名空间
	 *
	 * @var string
	 * @access protected
	 */
	protected $__cur_namespace;

	/**
	 * 当前模块
	 *
	 * @var string
	 * @access protected
	 */
	protected $__cur_module;

	/**
	 * 控制器的命名空间
	 *
	 * @var array
	 * @access protected
	 */
	protected $__controller_namespace = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct
	 *
	 * @param array $params
	 * @access public
	 * @return void
	 */
	public function __construct(array $params = array())
	{
		parent::__construct($params);
		$this->__cur_module = $this->get_default_module();
	}

	// }}}
	// {{{ public function add_controller_namespace()

	/**
	 * 添加控制器命名空间
	 *
	 * @param string $namespace
	 * @param string $module
	 * @access public
	 * @return lib\controller\dispatcher\sw_standard
	 */
	public function add_controller_namespace($namespace, $module = null)
	{
		if (null === $module) {
			$module = $this->__default_module;
		}

		$module = (string) $module;
		$namespace = rtrim((string) $namespace, '/\\');

		$this->__controller_namespace[$module] = $namespace;
		return $this;
	}

	// }}}
	// {{{ public function set_controller_namespace()

	/**
	 * set_controller_namespace
	 *
	 * @param string $namespace
	 * @param string $module
	 * @access public
	 * @return lib\controller\dispatcher\sw_standard
	 */
	public function set_controller_namespace($namespace, $module = null)
	{
		$this->__controller_namespace = array();

		if (is_string($namespace)) {
			$this->add_controller_namespace($namespace, $module);
		} elseif (is_array($namespace)) {
			foreach ((array) $namespace as $module => $path) {
				$this->add_controller_namespace($path, $module);
			}
		} else {
			throw new sw_exception('Controller namespace spec must be either a string or an array');
		}

		return $this;
	}

	// }}}
	// {{{ public function get_controller_namespace()

	/**
	 * 获取控制器命名空间 
	 * 
	 * @param string $module 
	 * @access public
	 * @return string|null
	 */
	public function get_controller_namespace($module = null)
	{
		if (null === $module) {
			return $this->__controller_namespace;	
		}

		$module = (string) $module;
		if (array_key_exists($module, $this->__controller_namespace)) {
			return $this->__controller_namespace[$module];	
		}

		return null;
	}

	// }}}
	// {{{ public function remove_controller_namespace()

	/**
	 * 移除控制器命名空间 
	 * 
	 * @access public
	 * @return lib\controller\dispatcher\sw_standard
	 */
	public function remove_controller_namespace($module)
	{
		$module = (string) $module;
		if (array_key_exists($module, $this->__controller_namespace)) {
			unset($this->__controller_namespace[$module]);
			return true;	
		}

		return false;
	}

	// }}}
	// {{{ public function format_module_name()

	/**
	 * 格式化模块名称 
	 * 
	 * @access public
	 * @param string $unformatted 
	 * @return string
	 */
	public function format_module_name($unformatted)
	{
		return strtolower($unformatted);	
	}

	// }}}
	// {{{ public function format_controller_name()

	/**
	 * 格式化控制器名称 
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_controller_name($unformatted)
	{
		return strtolower($unformatted);	
	}

	// }}}
	// {{{ public function format_action_name()

	/**
	 * 格式化方法的名称 
	 * 
	 * @param string $unformatted 
	 * @access public
	 * @return string
	 */
	public function format_action_name($unformatted)
	{	
		return strtolower($unformatted);	
	}

	// }}}
	// {{{ public function format_class_name()

	/**
	 * 获取类名 
	 * 
	 * @param string $module_name 
	 * @param string $controller_name 
	 * @access public
	 * @return string
	 */
	public function format_class_name($module_name, $controller_name)
	{
		$module_name = $this->format_module_name($module_name);
		$module_namespace = $this->get_controller_namespace($module_name);
		$namespace = isset($module_namespace) ? $module_namespace : $this->__cur_namespace;
		$controller_name = $this->format_controller_name($controller_name);

		$action = $namespace . '\\' . self::CLASS_PREFIX . $controller_name;

		return $action;
	}

	// }}}
	// {{{ public function get_controller_class()

	/**
	 * 获取控制器的类名 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return string
	 */
	public function get_controller_class(\lib\controller\request\sw_abstract $request)
	{
		$controller_name = $request->get_controller_name();
		if (empty($controller_name)) {
			if (!$this->get_param('use_default_controller_always')) {
				return false;	
			}	

			$controller_name = $this->get_default_controller();
			$request->set_controller_name($controller_name);
		}	

		$controller_spaces = $this->get_controller_namespace();
		$module = $request->get_module_name();
		if ($this->is_valid_module($module)) {
			$this->__cur_module = $module;
			$this->__cur_namespace = $controller_spaces[$module];	
		} elseif ($this->is_valid_module($this->__default_module)) {
			$request->set_module_name($this->__default_module);
			$this->__cur_module = $this->__default_module;
			$this->__cur_namespace = $controller_spaces[$this->__default_module];
		} else {
			throw new sw_exception('No default module defined for this application');	
		}

		$class_name = $this->format_class_name($request->get_module_name(), $controller_name);
		return $class_name;
	}

	// }}}
	// {{{ public function is_dispatchable()

	/**
	 * 判断是否可以分发 
	 * 
	 * @access public
	 * @return void
	 */
	public function is_dispatchable(\lib\controller\request\sw_abstract $request)
	{
		$class_name = $this->get_controller_class($request);
		if (!$class_name) {
			return false;	
		}

		if (class_exists($class_name)) {
			return true;	
		}

		return false;
	}

	// }}}
	// {{{ public function is_valid_module()

	/**
	 * 判断模块名是否是合法的 
	 * 
	 * @param string $module 
	 * @access public
	 * @return boolean
	 */
	public function is_valid_module($module)
	{
		if (!is_string($module)) {
			return false;	
		}

		$module = strtolower($module);
		$controller_spaces = $this->get_controller_namespace();

		foreach (array_keys($controller_spaces) as $module_name) {
			if ($module == strtolower($module_name)) {
				return true;	
			}	
		}

		return false;
	}

	// }}}
	// {{{ public function get_dispatch_namespace()
	
	/**
	 * 分发的命名空间 
	 * 
	 * @access public
	 * @return string
	 */
	public function get_dispatch_namespace()
	{
		return $this->__cur_namespace;	
	}
	 
	// }}}
	// {{{ public function get_action_method()

	/**
	 * 获取 action 的方法 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @access public
	 * @return string
	 */
	public function get_action_method(\lib\controller\request\sw_abstract $request)
	{
		$action = $request->get_action_name();
		if (empty($action)) {
			$action = $this->get_default_action();
			$request->set_action_name($action);	
		}

		return $this->format_action_name($action);
	}

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发器 
	 * 
	 * @param \lib\controller\request\sw_abstract $request 
	 * @param \lib\controller\response\sw_abstract $response 
	 * @access public
	 * @return void
	 */
	public function dispatch(\lib\controller\request\sw_abstract $request, \lib\controller\response\sw_abstract $response)
	{
		$this->set_response($response);	
		
		if (!$this->is_dispatchable($request)) {
			$controller_name = $request->get_controller_name();
			throw new sw_exception('Invalid controller specified (' . $controller_name . ')');	
		}

		$class_name = $this->get_controller_class($request);
		$controller = new $class_name($request, $this->get_response(), $this->get_params());

		if (!$controller instanceof \lib\controller\sw_action) {
			throw new sw_exception("Controller '$class_name' is not an instance of \lib\controller\sw_action");	
		}

		$action = $this->get_action_method($request);

		$request->set_dispatched(true);

		$disable_ob = $this->get_param('disable_output_buffering');
		$ob_level   = ob_get_level();
		if (empty($disable_ob)) {
			ob_start();	
		}

		try {
			$controller->dispatch($action);	
		} catch (sw_exception $e) {
			$cur_ob_level = ob_get_level();
			if ($cur_ob_level > $ob_level) {
				do {
					ob_get_clean();
					$cur_ob_level = ob_get_level();	
				} while ($cur_ob_level > $ob_level);
			}

			throw $e;
		}

		if (empty($disable_ob)) {
			$content = ob_get_clean();
			$response->append_body($content);	
		}

		$controller = null;
	}

	// }}}
	// }}}
}
