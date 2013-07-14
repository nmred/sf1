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

namespace lib\controller\request;
use lib\controller\request\sw_abstract;
use lib\controller\request\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_http
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
+------------------------------------------------------------------------------
*/
class sw_http extends sw_abstract
{
	// {{{ consts

	/**
	 * http 请求方式的描述  
	 */
	const SCHEMA_HTTP = 'http';

	/**
	 * https 请求方式的描述 
	 */
	const SCHEMA_HTTPS = 'https';

	// }}}
	// {{{ members

	/**
	 * 允许的参数来源 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__param_sources = array('_GET', '_POST');

	/**
	 * REQUEST_URI 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__request_uri;

	/**
	 * 请求URL的基地址 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__base_url = null;

	/**
	 * 请求路劲的基地址 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__base_path = null;

	/**
	 * PATH_INFO 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__path_info = '';

	/**
	 * 存放原始的POST数据 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__raw_body;

	/**
	 * 存放 request 参数 key 值的别名 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $_aliases = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->set_request_uri();	
	}

	// }}}
	// {{{ public function __get()

	/**
	 * get 魔术方法，按照 1.GET 2.POST 3.COOKIE 4.SERVER 5.ENV 的顺序返回 
	 * 
	 * @access public
	 * @return void
	 */
	public function __get($key)
	{
		switch (true) {
			case isset($this->__params[$key]):
				return $this->__params[$key];
			case isset($_GET[$key]):
				return $_GET[$key];
			case isset($_POST[$key]):
				return $_POST[$key];
			case isset($__COOKIE[$key]):
				return $__COOKIE[$key];
			case ('REQUEST_URI' === $key):
				return $this->get_request_uri();
			case isset($_SERVER[$key]):
				return $_SERVER[$key];
			case isset($_ENV[$key]):
				return $_ENV[$key];
			default:
				return null;
		}
	}

	// }}}
	// {{{ public function get()

	/**
	 * __get的别名 
	 * 
	 * @param string $key 
	 * @access public
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->__get($key);
	}

	// }}}
	// {{{ public function __set()

	/**
	 * 如果使用此方法设置参数将抛出异常，用set_param() 设置 
	 * 
	 * @param string $key 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function __set($key, $value)
	{
		throw new sw_exception('Setting values in superglobals not allowed; please use set_param()');	
	}

	// }}}
	// {{{ public function set()

	/**
	 * __set() 的别名 
	 * 
	 * @access public
	 * @return void
	 */
	public function set()
	{
		return $this->__set($key, $value);
	}

	// }}}
	// {{{ public function __isset()

	/**
	 * 检测一个参数是否存在 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function __isset($key)
	{
		switch (true) {
			case isset($this->__params[$key]):
				return true;
			case isset($_GET[$key]):
				return true;
			case isset($_POST[$key]):
				return true;
			case isset($_COOKIE[$key]):
				return true;
			case isset($__SERVER[$key]):
				return true;
			case isset($_ENV[$key]):
				return true;
			default:
				return false;	
		}	
	}

	// }}}
	// {{{ public function has()
	
	/**
	 * __isset() 的别名 
	 * 
	 * @param string $key 
	 * @access public
	 * @return boolean
	 */
	public function has($key)
	{
		return $this->__isset($key);	
	}

	// }}}
	// {{{ public function set_query()

	/**
	 * 设置 GET 的值 
	 * 
	 * @param string $spec 
	 * @param null|mixed $value 
	 * @access public
	 * @return sw_http
	 */
	public function set_query($spec, $value = null)
	{
		if ((null === $value) && !is_array($spec)) {
			throw new sw_exception('Invalid value passed to set_query(); must be either array of values or key/value pair');
		}

		if ((null === $value) && is_array($spec)) {
			foreach ($spec as $key => $value) {
				$this->set_query($key, $value);	
			}

			return $this;
		}

		$_GET[(string) $spec] = $value;
		return $this;
	}

	// }}}
	// {{{ public function get_query()

	/**
	 * 获取 GET的值 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_query($key = null, $default = null)
	{
		if (null === $key) {
			return $_GET;	
		}

		return (isset($_GET[$key])) ? $_GET[$key] : $default;
	}

	// }}}
	// {{{ public function set_post()

	/**
	 * 设置 POST 数据 
	 * 
	 * @param string|array $spec 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function set_post($spec, $value = null)
	{
		if ((null === $value) && !is_array($spec)) {
			throw new sw_exception('Invalid value passed to set_post(); must be either array of values or key/value pair');
		}

		if ((null === $value) && is_array($spec)) {
			foreach ($spec as $key => $value) {
				$this->set_post($key, $value);	
			}
			return $this;
		}

		$_POST[(string) $spec] = $value;

		return $this;
	}

	// }}}
	// {{{ public function get_post()

	/**
	 * 获取 POST 数据 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return void
	 */
	public function get_post($key = null, $default = null)
	{
		if (null === $key) {
			return $_POST;	
		}

		return (isset($_POST[$key])) ? $_POST[$key] : $default;
	}

	// }}}
	// {{{ public function get_cookie()

	/**
	 * 获取 COOKIE 的值 
	 * 
	 * @param null|string $key 
	 * @param null|mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_cookie($key = null, $default = null)
	{
		if (null === $key) {
			return $_COOKIE;	
		}	

		return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
	}

	// }}}
	// {{{ public function get_server()

	/**
	 * 获取SERVER数据 
	 * 
	 * @param string|null $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_server($key = null, $default = null)
	{
		if (null === $key) {
			return $_SERVER;	
		}	

		return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	}

	// }}}
	// {{{ public function get_env()

	/**
	 * 获取 ENV 数据 
	 * 
	 * @param string|null $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_env($key = null, $default = null)
	{
		if (null === $key) {
			return $_ENV;	
		}	

		return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
	}

	// }}}
	// }}}
}
