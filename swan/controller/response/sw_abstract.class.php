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

namespace swan\controller\response;
use swan\controller\response\exception\sw_exception;

/**
* 控制器-响应抽象类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
abstract class sw_abstract
{
	// {{{ members

	/**
	 * body 体的内容 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__body = array();

	/**
	 * 异常堆栈 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__exceptions = array();

	/**
	 * 头信息：以 name => value 方式存储 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__headers = array();

	/**
	 * 头信息：以原始字符串方式存取 "name=value" 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__headers_raw = array();

	/**
	 * http 响应码 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__http_response_code = 200;

	/**
	 * 是否是一个跳转的响应 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__is_redirect = false;

	/**
	 * 是否渲染异常信息 
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $__render_exceptions = false;

	/**
	 * 头部是否发送异常信息 
	 * 
	 * @var boolean
	 * @access public
	 */
	public $header_sent_throws_exception = true;

	// }}}
	// {{{ functions
	// {{{ public function set_header()

	/**
	 * 设置头信息 
	 * 
	 * @param string $name 
	 * @param string $value 
	 * @param boolean $replace 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function set_header($name, $value, $replace = false)
	{
		$this->can_sent_headers(true);
		$name = $this->_normalize_header($name);
		$value = (string) $value;
		
		if ($replace) {
			foreach ($this->__headers as $key => $header) {
				if ($name == $header['name']) {
					unset($this->__headers[$key]);	
				}
			}
		}

		$this->__headers[] = array(
			'name'    => $name,
			'value'   => $value,
			'replace' => $replace,
		);

		return $this;
	}

	// }}}
	// {{{ public function set_redirect()

	/**
	 * 设置跳转的头 
	 * 
	 * @param string $url 
	 * @param int $code 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function set_redirect($url, $code = 302)
	{
		$this->can_sent_headers(true);
		$this->set_header('Location', $url, true)
			 ->set_http_response_code($code);
			 
		return $this;	
	}

	// }}}
	// {{{ public function is_redirect()

	/**
	 * 判断是否是跳转响应 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_redirect()
	{
		return $this->__is_redirect;	
	}

	// }}}
	// {{{ public function get_headers()

	/**
	 * 获取所有的头信息 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_headers()
	{
		return $this->__headers;	
	}

	// }}}
	// {{{ public function clear_headers()

	/**
	 * 清除 headers 
	 * 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function clear_headers()
	{
		$this->__headers = array();
		
		return $this;	
	}

	// }}}
	// {{{ public function clear_header()
	
	/**
	 * 清除单个 header 
	 * 
	 * @param string $name 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function clear_header($name)
	{
		if (!count($this->__headers)) {
			return $this;	
		}

		foreach ($this->__headers as $index => $header) {
			if ($name == $header['name']) {
				unset($this->__headers[$index]);	
			}
		}

		return $this;
	}
	 
	// }}}
	// {{{ public function set_raw_header()

	/**
	 * 设置原始形式的 header 
	 * 
	 * @param string $value 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function set_raw_header($value)
	{
		$this->can_sent_headers(true);
		if ('Location' == substr($value, 0, 8)) {
			$this->__is_redirect = true;	
		}

		$this->__headers_raw[] = (string) $value;
		return $this;
	}

	// }}}
	// {{{ public function get_raw_headers()

	/**
	 * 获取所有原始形式的 header 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_raw_headers()
	{
		return $this->__headers_raw;	
	}

	// }}}
	// {{{ public function clear_raw_headers()

	/**
	 * 清除所有的原始 header 信息 
	 * 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function clear_raw_headers()
	{
		$this->__headers_raw = array();
		return $this;	
	}

	// }}}
	// {{{ public function clear_raw_header()

	/**
	 * 清除指定的原始 header 
	 * 
	 * @param string $header_raw 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function clear_raw_header($header_raw)
	{
		if (!count($this->__headers_raw)) {
			return $this;	
		}

		$key = array_search($header_raw, $this->__headers_raw);
		if ($key !== false) {
			unset($this->__headers_raw[$key]);	
		}

		return $this;
	}

	// }}}
	// {{{ public function clear_all_headers()

	/**
	 * 清除所有的 header 信息 
	 * 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function clear_all_headers()
	{
		return $this->clear_headers()
			        ->clear_raw_headers();	
	}

	// }}}
	// {{{ public function set_http_response_code()

	/**
	 * 设置响应状态码 
	 * 
	 * @param int $code 
	 * @access public
	 * @throws swan\controller\response\exception\sw_exception 
	 * @return swan\controller\response\sw_abstract 
	 */
	public function set_http_response_code($code)
	{	
		if (!is_int($code) || (100 > $code) || (599 < $code)) {
			throw new sw_exception('Invalid HTTP response code');
		}

		if ((300 <= $code) && (307 >= $code)) {
			$this->__is_redirect = true;	
		} else {
			$this->__is_redirect = false;	
		}

		$this->__http_response_code = $code;
		return $this;
	}

	// }}}
	// {{{ public function get_http_response_code()

	/**
	 * 获取 http 响应状态码 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_http_response_code()
	{
		return $this->__http_response_code;	
	}

	// }}}
	// {{{void public function can_sent_headers()

	/**
	 * 根据 header 是否已经发送来判断是否允许发送 header 
	 * 
	 * @param boolean $throw 
	 * @access public
	 * @throws swan\controller\response\exception\sw_exception 
	 * @return boolean
	 */
	public function can_sent_headers($throw = false)
	{
		$ok = headers_sent($file, $line);
		if ($ok && $throw && $this->header_sent_throws_exception) {
			throw new sw_exception("Cannot send headers; headers already sent in $file , line $line");	
		}	

		return !$ok;
	}

	// }}}
	// {{{ public function send_headers()

	/**
	 * 发送所有的头信息 
	 * 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function send_headers()
	{
		if (count($this->__headers_raw) || count($this->__headers) || (200 != $this->__http_response_code)) {
			$this->can_sent_headers(true);	
		} else if (200 == $this->__http_response_code) {
			return $this;	
		}

		$http_code_sent = false;

		foreach ($this->__headers_raw as $header) {
			if (!$http_code_sent && $this->__http_response_code) {
				header($header, true, $this->__http_response_code);
				$http_code_sent = true;	
			} else {
				header($header);	
			}
		}

		foreach ($this->__headers as $header) {
			if (!$http_code_sent && $this->__http_response_code) {
				header($header['name'] . ':' . $header['value'], $header['replace'], $this->__http_response_code);
				$http_code_sent = true;	
			} else {
				header($header['name'] . ':' . $header['value'], $header['replace']);	
			}
		}

		if (!$http_code_sent) {
			header('HTTP/1.1 ' . $this->__http_response_code);
			$http_code_sent = true;
		}

		return $this;
	}

	// }}}
	// {{{ public function set_body()

	/**
	 * 设置响应内容 
	 * 
	 * @param string $content 
	 * @param string $name 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function set_body($content, $name = null)
	{
		if ((null === $name) || !is_string($name)) {
			$this->__body = array('default' => (string) $content);	
		} else {
			$this->__body[$name] = (string) $content;	
		}

		return $this;
	}

	// }}}
	// {{{ public function append_body()

	/**
	 * 追加内容 
	 * 
	 * @param string $content 
	 * @param string $name 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function append_body($content, $name = null)
	{
		if ((null === $name) || !is_string($name)) {
			if (isset($this->__body['default'])) {
				$this->__body['default'] .= (string) $content;	
			} else {
				return $this->append('default', $content);	
			}
		} else if (isset($this->__body[$name])) {
			$this->__body[$name] .= (string) $content;
		} else {
			return $this->append($name, $content);	
		}

		return $this;
	}

	// }}}
	// {{{ public function clear_body()

	/**
	 * 清除一个缓存内容 
	 * 
	 * @param string $name 
	 * @access public
	 * @return boolean 
	 */
	public function clear_body($name = null)
	{
		if (null !== $name) {
			$name = (string) $name;
			if (isset($this->__body[$name])) {
				unset($this->__body[$name]);
				return true;	
			}

			return false;
		}

		$this->__body = array();
		return true;
	}

	// }}}
	// {{{ public function get_body()
	
	/**
	 * 获取缓存中的内容 
	 * 
	 * @param boolean|string $spec 
	 * @access public
	 * @return null|string
	 */
	public function get_body($spec = false)
	{
		if (false === $spec) {
			ob_start();
			$this->output_body();
			return ob_get_clean();
		} else if (true === $spec) {
			return $this->__body;	
		} else if(is_string($spec) && isset($this->__body[$spec])) {
			return $this->__body[$spec];	
		}

		return null;
	}

	// }}}
	// {{{ public function append()

	/**
	 * 添加 body 内容 
	 * 
	 * @param string $content 
	 * @param string $name 
	 * @access public
	 * @throws swan\controller\response\exception\sw_exception 
	 * @return swan\controller\response\sw_abstract 
	 */
	public function append($name, $content)
	{
		if (!is_string($name)) {
			throw new sw_exception('Invalid body segment key ("' . gettype($name) . '")');	
		}

		if (isset($this->__body[$name])) {
			unset($this->__body[$name]);	
		}

		$this->__body[$name] = (string) $content;
		return $this;
	}

	// }}}
	// {{{ public function prepend()

	/**
	 * 在以前的内容前面添加 
	 * 
	 * @param string $name 
	 * @param string $content 
	 * @access public
	 * @throws swan\controller\response\exception\sw_exception 
	 * @return swan\controller\response\sw_abstract 
	 */
	public function prepend($name, $content)
	{
		if (!is_string($name)) {
			throw new sw_exception('Invalid body segment key ("' . gettype($name) . '")');	
		}	

		if (isset($this->__body[$name])) {
			unset($this->__body[$name]);
		}

		$new = array($name => (string) $content);
		$this->__body = $new + $this->__body;

		return $this;
	}

	// }}}
	// {{{ public function insert()

	/**
	 * 插入响应内容 
	 * 
	 * @param string $name 
	 * @param string $content 
	 * @param string $parent 
	 * @param boolean $before 判断是否在目标的前面插入 
	 * @access public
	 * @throws swan\controller\response\exception\sw_exception 
	 * @return swan\controller\response\sw_abstract 
	 */
	public function insert($name, $content, $parent = null, $before = null)
	{
		if (!is_string($name)) {
			throw new sw_exception('Invalid body segment key ("' . gettype($name) . '")');	
		}	

		if ((null !== $parent) && (!is_string($name))) {
			throw new sw_exception('Invalid body segment parent key ("' . gettype($name) . '")');	
		}

		if (isset($this->__body[$name])) {
			unset($this->__body[$name]);	
		}

		if ((null === $parent) || !isset($this->__body[$parent])) {
			return $this->append($name, $content);	
		}

		if (isset($this->__body[$name])) {
			unset($this->__body[$name]);	
		}

		if ((null === $parent) || !isset($this->__body[$parent])) {
			return $this->append($name, $content);	
		}

		$ins  = array($name => (string) $content);
		$keys = array_keys($this->__body);
		$loc  = array_search($parent, $keys);

		if (!$before) {
			++$loc;	
		}

		if (0 === $loc) {
			$this->__body = $ins + $this->__body;	
		} else if ($loc >= (count($this->__body))){
			$this->__body = $this->__body + $ins;
		} else {
			$pre  = array_slice($this->__body, 0, $loc);
			$post = array_slice($this->__body, $loc);
			$this->__body = $pre + $ins + $post;	
		}

		return $this;
	}

	// }}}
	// {{{ public function output_body()

	/**
	 * 输出内容 
	 * 
	 * @access public
	 * @return void
	 */
	public function output_body()
	{
		$body = implode('', $this->__body);
		echo $body;	
	}

	// }}}
	// {{{ public fucntion set_exception()

	/**
	 * 设置异常堆栈 
	 * 
	 * @param Exception $e 
	 * @access public
	 * @return swan\controller\response\sw_abstract 
	 */
	public function set_exception(\Exception $e)
	{
		$this->__exceptions[] = $e;
		
		return $this;	
	}

	// }}}
	// {{{ public function get_exception()

	/**
	 * 获取异常堆栈 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_exception()
	{
		return $this->__exceptions;	
	}

	// }}}
	// {{{ public function is_exception()

	/**
	 * 是否有元素在异常堆栈中 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function is_exception()
	{
		return !empty($this->__exceptions);	
	}

	// }}}
	// {{{ public function has_exception_of_type()

	/**
	 * 判断是否存在指定类型的异常在堆栈中 
	 * 
	 * @param string $type 
	 * @access public
	 * @return boolean
	 */
	public function has_exception_of_type($type)
	{
		foreach ($this->__exceptions as $e) {
			if ($e instanceof $type) {
				return true;	
			}	
		}

		return false;
	}

	// }}}
	// {{{ public function has_exception_of_message()

	/**
	 * 判断是否存在在指定提示信息的异常在堆栈中 
	 * 
	 * @param string $message 
	 * @access public
	 * @return boolean
	 */
	public function has_exception_of_message($message)
	{
		foreach ($this->__exceptions as $e) {
			if ($message == $e->getMessage()) {
				return true;	
			}
		}

		return false;
	}

	// }}}
	// {{{ public function has_exception_of_code()

	/**
	 * 判断是否存在指定错误码的异常在堆栈中 
	 * 
	 * @param int $code 
	 * @access public
	 * @return boolean
	 */
	public function has_exception_of_code($code)
	{
		foreach ($this->__exceptions as $e) {
			if ($code == $e->getCode()) {
				return true;	
			}
		}	

		return false;
	}

	// }}}
	// {{{ public function get_exception_by_type()

	/**
	 * 获取指定类型的异常 
	 * 
	 * @param string $type 
	 * @access public
	 * @return array|boolean
	 */
	public function get_exception_by_type($type)
	{
		$exceptions = array();
		foreach ($this->__exceptions as $e) {
			if ($e instanceof $type) {
				$exceptions[] = $e;
			}
		}

		if (empty($exceptions)) {
			return false;	
		}

		return $exceptions;
	}

	// }}}
	// {{{ public function get_exception_by_message()

	/**
	 * 获取指定提示信息的异常 
	 * 
	 * @param string $message 
	 * @access public
	 * @return array|boolean
	 */
	public function get_exception_by_message($message)
	{
		$exceptions = array();
		foreach ($this->__exceptions as $e) {
			if ($message == $e->getMessage()) {
				$exceptions[] = $e;	
			}
		}

		if (empty($exceptions)) {
			return false;	
		}

		return $exceptions;
	}

	// }}}
	// {{{ public function get_exception_by_code()

	/**
	 * 获取指定错误码的异常 
	 * 
	 * @param string $code 
	 * @access public
	 * @return array|code
	 */
	public function get_exception_by_code($code)
	{
		$exceptions = array();
		foreach ($this->__exceptions as $e) {
			if ($code == $e->getCode()) {
				$exceptions[] = $e;
			}
		}	

		if (empty($exceptions)) {
			return false;	
		}

		return $exceptions;
	}

	// }}}
	// {{{ public function render_exceptions()

	/**
	 * 设置是否显示异常信息 
	 * 
	 * @param boolean $flag 
	 * @access public
	 * @return boolean
	 */
	public function render_exceptions($flag = null)
	{
		if (null !== $flag) {
			$this->__render_exceptions = $flag ? true : false;	
		}	

		return $this->__render_exceptions;
	}

	// }}}
	// {{{ protected function _normalize_header()

	/**
	 * 格式化头信息的名称
	 * 返回类似于：X-Capitalized-Names 
	 * 
	 * @param string $name 
	 * @access protected
	 * @return string
	 */
	protected function _normalize_header($name)
	{
		$filtered = str_replace(array('-', '_'), ' ', (string) $name);
		$filtered = ucwords(strtolower($filtered));
		$filtered = str_replace(' ', '-', $filtered);
		
		return $filtered;	
	}

	// }}}
	// {{{ public function send_response()

	/**
	 * 发送一个响应 
	 * 
	 * @access public
	 * @return void
	 */
	public function send_response()
	{
		$this->send_headers();
		
		if ($this->is_exception() && $this->render_exceptions()) {
			$exceptions = '';
			foreach ($this->get_exception() as $e) {
				$exceptions .= $e->__toString() . PHP_EOL;	
			}

			echo $exceptions;
			return;
		}

		$this->output_body();
	}

	// }}}
	// {{{ public function __toString()

	/**
	 * __toString 
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		ob_start();
		$this->send_response();
		return ob_get_clean();	
	}

	// }}}
	// }}}
}
