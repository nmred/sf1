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
* HTTP 请求类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
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
	protected $__aliases = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param string $request_uri 
	 * @access public
	 * @return void
	 */
	public function __construct($request_uri = null)
	{
		$this->set_request_uri($request_uri);
	}

	// }}}
	// {{{ public function __get()

	/**
	 * get 魔术方法，按照 1.GET 2.POST 3.COOKIE 4.SERVER 5.ENV 的顺序返回
	 *
	 * @param string $key
	 * @access public
	 * @return mixed
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
	 * @throws lib\controller\request\exception\sw_exception
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
	public function set($key, $value)
	{
		return $this->__set($key, $value);
	}

	// }}}
	// {{{ public function __isset()

	/**
	 * 检测一个参数是否存在
	 *
	 * @param string $key
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
	 * @return lib\controller\request\sw_http
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
	 * @throws lib\controller\request\exception\sw_exception
	 * @return lib\controller\request\sw_http
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
	 * @return mixed
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
	// {{{ public function set_request_uri()

	/**
	 * 设置 request uri
	 *
	 * @param null|string $request_uri
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_request_uri($request_uri = null)
	{
		if (null === $request_uri) {
			if (isset($_SERVER['REQUEST_URI'])) {
				$request_uri = $_SERVER['REQUEST_URI'];
				$schema_and_http_host = $this->get_schema() . '://' . $this->get_http_host();
				if (0 === strpos($request_uri, $schema_and_http_host)) {
					$request_uri = substr($request_uri, strlen($schema_and_http_host));
				}
			} else if (isset($_SERVER['ORIG_PATH_INFO'])) {
				$request_uri = $_SERVER['ORIG_PATH_INFO'];
				if (!empty($_SERVER['ORIG_PATH_INFO'])) {
					$request_uri .= '?' . $_SERVER['QUERY_STRING'];
				}
			} else {
				return $this;
			}
		} else if (!is_string($request_uri)) {
			return $this;
		} else {
			if (false !== strpos($request_uri, '?')) {
				$query = parse_url($request_uri);
				parse_str($query['query'], $vars);
				$this->set_query($vars);
			}
		}

		$this->__request_uri = $request_uri;
		return $this;
	}

	// }}}
	// {{{ public function get_request_uri()

	/**
	 * 获取 request uri
	 *
	 * @access public
	 * @return string
	 */
	public function get_request_uri()
	{
		if (empty($this->__request_uri)) {
			$this->set_request_uri();
		}

		return $this->__request_uri;
	}

	// }}}
	// {{{ public function get_schema()

	/**
	 * 获取 HTTP 的协议
	 *
	 * @access public
	 * @return string
	 */
	public function get_schema()
	{
		return ($this->get_server('HTTPS') == 'on') ? self::SCHEMA_HTTPS : self::SCHEMA_HTTP;
	}

	// }}}
	// {{{ public function get_http_host()

	/**
	 * 获取 http 主机名称
	 *
	 * @access public
	 * @return string
	 */
	public function get_http_host()
	{
		$host = $this->get_server('HTTP_HOST');
		if (!empty($host)) {
			return $host;
		}

		$schema = $this->get_schema();
		$name   = $this->get_server('SERVER_NAME');
		$port   = $this->get_server('SERVER_PORT');

		if (null === $name) {
			return '';
		} else if (($schema == self::SCHEMA_HTTP && $port == 80)) {
			return $name;
		} else {
			return $name . ':' . $port;
		}
	}

	// }}}
	// {{{ public function set_base_url()

	/**
	 * 设置基本地址
	 *
	 * @param null|string $base_url
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_base_url($base_url = null)
	{
		if ((null !== $base_url) && !is_string($base_url)) {
			return $this;
		}

		if (null === $base_url) {
			$filename = (isset($_SERVER['SCRIPT_FILENAME'])) ? basename($_SERVER['SCRIPT_FILENAME']) : '';
			if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $filename) {
				$base_url = $_SERVER['SCRIPT_NAME'];
			} else if (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $filename) {
				$base_url = $_SERVER['PHP_SELF'];
			} else if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $filename) {
				$base_url = $_SERVER['ORIG_SCRIPT_NAME'];
			} else {
				$path     = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
				$file     = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
				$segs     = explode('/', trim($file, '/'));
				$segs     = array_reverse($segs);
				$index    = 0;
				$last     = count($segs);
				$base_url = '';
				do {
					$seg = $segs[$index];
					$base_url = '/' . $seg . $base_url;
					++$index;
				} while (($last > $index) && (false !== ($pos = strpos($path, $base_url))) && (0 != $pos));
			}

			$request_uri = $this->get_request_uri();
			if (0 === strpos($request_uri, $base_url)) {
				$this->__base_url = $base_url;
				return $this;
			}

			if (0 === strpos($request_uri, dirname($base_url))) {
				$this->__base_url = rtrim(dirname($base_url), '/');
				return $this;
			}

			$truncated_request_uri = $request_uri;
			if (($pos = strpos($request_uri, '?')) !== false) {
				$truncated_request_uri = substr($request_uri, 0, $pos);
			}

			$basename = basename($base_url);
			if (empty($basename) || !strpos($truncated_request_uri, $basename)) {
				$this->__base_url = '';
				return $this;
			}

			if ((strlen($request_uri) >= strlen($base_url))
					&& ((false !== ($pos = strpos($request_uri, $base_url))) && (0 !== $pos))) {
				$base_url = substr($request_uri, 0, $pos + strlen($base_url));
			}
		}

		$this->__base_url = rtrim($base_url, '/');
		return $this;
	}

	// }}}
	// {{{ public function get_base_url()

	/**
	 * 获取基地址
	 *
	 * @param boolean $raw
	 * @access public
	 * @return string
	 */
	public function get_base_url($raw = true)
	{
		if (null === $this->__base_url) {
			$this->set_base_url();
		}

		return (($raw == false) ? urldecode($this->__base_url) : $this->__base_url);
	}

	// }}}
	// {{{ public function set_base_path()

	/**
	 * 获取基本地址的路劲
	 *
	 * @param null|string $base_path
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_base_path($base_path = null)
	{
		if ($base_path === null) {
			$filename = (isset($_SERVER['SCRIPT_FILENAME'])) ? basename($_SERVER['SCRIPT_FILENAME']) : '';
			$base_url = $this->get_base_url();
			if (empty($base_url)) {
				$this->__base_path = '';
				return $this;
			}

			if (basename($base_url) === $filename) {
				$base_path = dirname($base_url);
			} else {
				$base_path = $base_url;
			}
		}

		if (substr(PHP_OS, 0, 3) === 'WIN') {
			$base_path = str_replace('\\', '/', $base_path);
		}

		$this->__base_path = rtrim($base_path, '/');
		return $this;
	}

	// }}}
	// {{{ public function get_base_path()

	/**
	 * 获取基本地址的路劲
	 *
	 * @access public
	 * @return string
	 */
	public function get_base_path()
	{
		if (null === $this->__base_path) {
			$this->set_base_path();
		}

		return $this->__base_path;
	}

	// }}}
	// {{{ public function set_pathinfo()

	/**
	 * 设置 PATHINFO
	 *
	 * @param string $pathinfo
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_pathinfo($pathinfo = null)
	{
		if (null === $pathinfo) {
			$base_url = $this->get_base_url();
			$base_url_raw = $this->get_base_url(false);
			$base_url_encode = urlencode($base_url_raw);


			if (null == ($request_uri = $this->get_request_uri())) {
				return $this;
			}

			if ($pos = strpos($request_uri, '?')) {
				$request_uri = substr($request_uri, 0, $pos);
			}

			if (!empty($base_url) || !empty($base_url_raw)) {
				if (0 === strpos($request_uri, $base_url)) {
					$pathinfo = substr($request_uri, strlen($base_url));
				} elseif (0 === strpos($request_uri, $base_url_raw)) {
					$pathinfo = substr($request_uri, strlen($base_url_raw));
				} elseif (0 === strpos($request_uri, $base_url_encode)) {
					$pathinfo = substr($request_uri, strlen($base_url_encode));
				} else {
					$pathinfo = $request_uri;
				}
			} else {
				$pathinfo = $request_uri;
			}
		}

		$this->__path_info = (string) $pathinfo;
		return $this;
	}

	// }}}
	// {{{ public function get_pathinfo()

	/**
	 * 获取 PATHINFO
	 *
	 * @access public
	 * @return string
	 */
	public function get_pathinfo()
	{
		if (empty($this->__path_info)) {
			$this->set_pathinfo();
		}

		return $this->__path_info;
	}

	// }}}
	// {{{ public function set_param_sources()

	/**
	 * 设置允许设置的参数类型
	 *
	 * @param array $param_sources
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_param_sources(array $param_sources = array())
	{
		$this->__param_sources = $param_sources;
		return $this;
	}

	// }}}
	// {{{ public function get_param_sources()

	/**
	 * 获取允许设置参数的类型
	 *
	 * @access public
	 * @return array
	 */
	public function get_param_sources()
	{
		return $this->__param_sources;
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置参数值
	 *
	 * @param string $key
	 * @param mixed $value
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_param($key, $value = null)
	{
		$key = (null !== ($alias = $this->get_alias($key))) ? $alias : $key;
		parent::set_param($key, $value);
		return $this;
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取参数值
	 *
	 * @param string $key
	 * @param mixed $default
	 * @access public
	 * @return mixed
	 */
	public function get_param($key, $default = null)
	{
		$key_name = (null !== ($alias = $this->get_alias($key))) ? $alias : $key;

		$param_sources = $this->get_param_sources();
		if (isset($this->__params[$key_name])) {
			return $this->__params[$key_name];
		} elseif (in_array('_GET', $param_sources) && (isset($_GET[$key_name]))) {
			return $_GET[$key_name];
		} elseif (in_array('_POST', $param_sources) && (isset($_POST[$key_name]))) {
			return $_POST[$key_name];
		}

		return $default;
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 批量设置参数
	 *
	 * @param array $params
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_params(array $params)
	{
		foreach ($params as $key => $value) {
			$this->set_param($key, $value);
		}

		return $this;
	}

	// }}}
	// {{{ public function get_params()

	/**
	 * 获取所有参数
	 *
	 * @access public
	 * @return mixed
	 */
	public function get_params()
	{
		$return        = $this->__params;
		$param_sources = $this->get_param_sources();
		if (in_array('_GET', $param_sources) && isset($_GET) && is_array($_GET)) {
			$return = array_merge($return, $_GET);
		}

		if (in_array('_POST', $param_sources) && isset($_POST) && is_array($_POST)) {
			$return = array_merge($return, $_POST);
		}

		return $return;
	}

	// }}}
	// {{{ public function set_alias()

	/**
	 * 设置参数别名
	 *
	 * @param string $name
	 * @param string $target
	 * @access public
	 * @return lib\controller\request\sw_http
	 */
	public function set_alias($name, $target)
	{
		$this->__aliases[$name] = $target;
		return $this;
	}

	// }}}
	// {{{ public function get_alias()

	/**
	 * 获取别名
	 *
	 * @param string $name
	 * @access public
	 * @return null|string
	 */
	public function get_alias($name)
	{
		if (isset($this->__aliases[$name])) {
			return $this->__aliases[$name];
		}

		return null;
	}

	// }}}
	// {{{ public function get_aliases()

	/**
	 * 获取所有的别名
	 *
	 * @access public
	 * @return array
	 */
	public function get_aliases()
	{
		return $this->__aliases;
	}

	// }}}
	// {{{ public function get_method()

	/**
	 * 获取请求方式
	 *
	 * @access public
	 * @return string
	 */
	public function get_method()
	{
		return $this->get_server('REQUEST_METHOD');
	}

	// }}}
	// {{{ public function is_post()

	/**
	 * 是否是POST 方式请求
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_post()
	{
		if ('POST' == $this->get_method()) {
			return true;
		}

		return false;
	}

	// }}}
	// {{{ public function is_get()

	/**
	 * 是否是 GET 方式请求
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_get()
	{
		if ('GET' == $this->get_method()) {
			return true;
		}

		return false;
	}

	// }}}
	// {{{ public function is_put()

	/**
	 * 是否是PUT方式请求
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_put()
	{
		if ('PUT' == $this->get_method()) {
			return true;
		}

		return false;
	}

	// }}}
	// {{{ public function is_delete()

	/**
	 * 是否是DELETE 方式请求
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_delete()
	{
		if ('DELETE' == $this->get_method()) {
			return true;
		}

		return false;
	}

	// }}}
	// {{{ public function is_head()

	/**
	 * 是否是 HEAD 方式请求
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_head()
	{
		if ('HEAD' == $this->get_method()) {
			return true;
		}

		return false;
	}

	// }}}
	// {{{ public function is_options()

	/**
	 * 是否是 Options 方式请求
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_options()
	{
		if ('OPTIONS' == $this->get_method()) {
			return true;
		}

		return false;
	}

	// }}}
	// {{{ public function is_xml_http_request()

	/**
	 * 是否是 ajax 方式
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_xml_http_request()
	{
		return ($this->get_server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest');
	}

	// }}}
	// {{{ public function is_flash_request()

	/**
	 * 是否是 flash 请求
	 *
	 * @access public
	 * @return boolean
	 */
	public function is_flash_request()
	{
		$header = strtolower($this->get_header('USER_AGENT'));
		return (strstr($header, ' flash')) ? true : false;
	}

	// }}}
	// {{{ public function is_secure()

	/**
	 * 是否是通过 HTTPS 请求 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_secure()
	{
		return ($this->get_schema() === self::SCHEMA_HTTPS);	
	}

	// }}}
	// {{{ public function get_raw_body()

	/**
	 * 获取标准输入的信息 
	 * 
	 * @access public
	 * @return string|boolean
	 */
	public function get_raw_body()
	{
		if (null === $this->__raw_body) {
			$body = file_get_contents('php://input');

			if (strlen(trim($body)) > 0) {
				$this->__raw_body = $body;  
			} else {
				$this->__raw_body = false;  
			}
		}

		return $this->__raw_body;	
	}

	// }}}
	// {{{ public function get_header()
	
	/**
	 * 获取头信息 
	 * 
	 * @access public
	 * @param mixed $header 
	 * @return string|boolean
	 */
	public function get_header($header)
	{
		if (empty($header)) {
			throw new sw_exception("An HTTP header name is required");
		}   

		$temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
		if (isset($_SERVER[$temp])) {
			return $_SERVER[$temp]; 
		}

		if (function_exists('apache_request_headers')) {
			$headers = apache_request_headers();
			if (isset($headers[$header])) {
				return $headers[$header];   
			}
			$header = strtolower($header);  
			foreach ($headers as $key => $value) {
				if (strtolower($key) == $header) {
					return $value;  
				}
			}       
		}       

		return false;	
	}

	// }}}
	// {{{ public function get_client_ip()

	/**
	 * 获取客户端的 IP 
	 * 
	 * @param boolean $check_proxy 
	 * @access public
	 * @return string
	 */
	public function get_client_ip($check_proxy = true)
	{
		if ($check_proxy && $this->get_server('HTTP_CLIENT_IP') !== null) {
			$ip = $this->get_server('HTTP_CLIENT_IP');  
		} elseif ($check_proxy && $this->get_server('HTTP_X_FORWARDED_FOR') !== null) {
			$ip = $this->get_server('HTTP_X_FORWARDED_FOR');    
		} else {
			$ip = $this->get_server('REMOTE_ADDR'); 
		}

		return $ip;	
	}

	// }}}
	// }}}
}
