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
	const CLASS_PREFIX = 'sw_'

	// }}}
	// {{{ members

	/**
	 * 当前目录
	 *
	 * @var string
	 * @access protected
	 */
	protected $__cur_directory;

	/**
	 * 当前模块
	 *
	 * @var string
	 * @access protected
	 */
	protected $__cur_module;

	/**
	 * 控制器的目录
	 *
	 * @var array
	 * @access protected
	 */
	protected $__controller_directory = array();

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
	// {{{ public function add_controller_directory()

	/**
	 * 添加控制器目录
	 *
	 * @param string $path
	 * @param string $module
	 * @access public
	 * @return lib\controller\dispatcher\sw_standard
	 */
	public function add_controller_directory($path, $module = null)
	{
		if (null === $module) {
			$module = $this->__default_module;
		}

		$module = (string) $module;
		$path = rtrim((string) $path, '/\\');

		$this->__controller_directory[$module] = $path;
		return $this;
	}

	// }}}
	// {{{ public function set_controller_directory()

	/**
	 * set_controller_directory
	 *
	 * @param string $directory
	 * @param string $module
	 * @access public
	 * @return lib\controller\dispatcher\sw_standard
	 */
	public function set_controller_directory($directory, $module = null)
	{
		$this->__controller_directory = array();

		if (is_string($directory)) {
			$this->add_controller_directory($directory, $module);
		} elseif (is_array($directory)) {
			foreach ((array) $directory as $module => $path) {
				$this->add_controller_directory($path, $module);
			}
		} else {
			throw new sw_exception('Controller directory spec must be either a string or an array');
		}

		return $this;
	}

	// }}}
	// {{{ public function get_controller_directory()

	/**
	 * 获取控制器目录 
	 * 
	 * @param string $module 
	 * @access public
	 * @return string|null
	 */
	public function get_controller_directory($module = null)
	{
		if (null === $module) {
			return $this->__controller_directory;	
		}

		$module = (string) $module;
		if (array_key_exists($module, $this->__controller_directory)) {
			return $this->__controller_directory[$module];	
		}

		return null;
	}

	// }}}
	// {{{ public function remove_controller_directory()

	/**
	 * 移除控制器目录 
	 * 
	 * @access public
	 * @return lib\controller\dispatcher\sw_standard
	 */
	public function remove_controller_directory($module)
	{
		$module = (string) $module;
		if (array_key_exists($module, $this->__controller_directory)) {
			unset($this->__controller_directory[$module]);
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

	public function format_class_name($module_name, $controller_name)
	{
		return self::CLASS_PREFIX;	
	}

	// }}}
	// }}}
}
