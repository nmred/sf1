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
 
namespace swan\loader;

require_once __DIR__ . '/sw_loader.class.php';

/**
* 标准自动加载的实现类 
* 
* @uses sw_loader
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
*/
class sw_auto implements sw_loader
{
	// {{{ consts

	const NS_SEPARATOR     = '\\';  // 命名空间的语法分隔符
	const LOAD_NS          = 'namespaces';
	const AUTOREGISTER_SW  = 'autoregister_sw';

	// }}}	
	// {{{ members

	/**
	 * __namespaces 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__namespaces = array();

	/**
	 * __instance 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected  static $__instance = null;

	// }}}
	// {{{ functions
	// {{{ public static funciton get_instance()
	
	/**
	 * 获取自动加载对象 
	 * 
	 * @param mixed $options 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function get_instance($options = null)
	{
		if (!isset(self::$__instance)) {
			self::$__instance = new self();	
		}

		if (null !== $options) {
			self::$__instance->set_options($options);	
		}

		return self::$__instance; 
	}
	 
	// }}}
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	protected function __construct()
	{
	}

	// }}}
	// {{{ public function set_options()
	
	/**
	 * set_options 
	 * 
	 * @param array|Traversable $options 
	 * @access public
	 * @return sw_auto
	 */
	public function set_options($options)
	{
		if (!is_array($options) && !($options instanceof \Traversable)) {
			throw new sw_exception('options must be either an array or Traversable. ');	
		}

		foreach ($options as $type => $pairs) {
			switch ($type) {
				case self::AUTOREGISTER_SW:
					if ($pairs) {
						$this->register_namespace('swan', dirname(dirname(__DIR__)));	
					}
					break;
				case self::LOAD_NS:
					if (is_array($pairs) || $pairs instanceof \Traversable) {
						$this->register_namespaces($pairs);	
					}
					break;
				default:
					// 忽略
			}
		}

		return $this;
	}

	// }}} 
	// {{{ public function register_namespace()

	/**
	 * register_namespace 
	 * 
	 * @param string $namespace 
	 * @param string $directory 
	 * @access public
	 * @return sw_auto
	 */
	public function register_namespace($namespace, $directory)
	{
		$namespace = rtrim($namespace, self::NS_SEPARATOR) . self::NS_SEPARATOR;
		$this->__namespaces[$namespace] = $this->_normalize_directory($directory);	
		return $this;
	}

	// }}}
	// {{{ public function register_namespaces()

	/**
	 * 批量注册命名空间 
	 * 
	 * @param array|Traversable $namespaces 
	 * @access public
	 * @return sw_auto
	 */
	public function register_namespaces($namespaces)
	{
		if (!is_array($namespaces) && !$namespaces instanceof \Traversable) {
			throw new sw_exception('prefix pairs must be either an array or Traversable. ');	
		}

		foreach ($namespaces as $namespace => $directory) {
			$this->register_namespace($namespace, $directory);	
		}

		return $this;
	}

	// }}}
	// {{{ public function register()

	/**
	 * register 
	 * 
	 * @access public
	 * @return void
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'autoload'));	
	}

	// }}}
	// {{{ public function autoload()

	/**
	 * autoload 
	 * 
	 * @param string $class 
	 * @access public
	 * @return boolean|string
	 */
	public function autoload($class)
	{
		if (false !== strpos($class, self::NS_SEPARATOR)) {
			if ($this->_load_class($class, self::LOAD_NS)) {
				return $class;		
			}
		}
		return false;
	}

	// }}}
	// {{{ protected function _load_class()
	
	/**
	 * 加载类 
	 * 
	 * @param string $class 
	 * @param string $type 
	 * @access protected
	 * @return boolean
	 */
	protected function _load_class($class, $type)
	{
		if (!in_array($type, array(self::LOAD_NS))) {
			throw new sw_exception();	
		}
		
		$attribute = '__' . $type;
		foreach ($this->{$attribute} as $leader => $path) {
			if (0 === strpos($class, $leader)) {
				$filename = $this->_transform_classname_to_filename($class, $path);
				if (file_exists($filename)) {
					return require_once $filename;	
				}

				return false;
			}
		}

		return false;
	}

	// }}}
	// {{{ protected function _transform_classname_to_filename()

	/**
	 * 将类名转化为文件名 
	 * 
	 * @param string $class 
	 * @param string $directory 
	 * @access protected
	 * @return string
	 */
	protected function _transform_classname_to_filename($class, $directory)
	{
		$matches = array();
		preg_match('/(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $class, $matches);
		
		$class     = (isset($matches['class'])) ? $matches['class'] : '';
		$namespace = (isset($matches['namespace'])) ? $matches['namespace'] : '';
		
		return $directory
		     . str_replace(self::NS_SEPARATOR, '/', $namespace)
			 . $class
			 . '.class.php';	
	}

	// }}}
	// {{{ protected function _normalize_directory()

	/**
	 * 统一目录的编写规范 /usr/swan . \usr\swan 最后加上/ \
	 * 
	 * @param  string $directory
	 * @access protected
	 * @return string
	 */
	protected function _normalize_directory($directory)
	{
		$last = $directory[strlen($directory) - 1];
		if (in_array($last, array('/', '\\'))) {
			$directory[strlen($directory) - 1] = DIRECTORY_SEPARATOR;	
			return $directory;
		}

		$directory .= DIRECTORY_SEPARATOR;
		return $directory;
	}

	// }}}
	// }}}
}
