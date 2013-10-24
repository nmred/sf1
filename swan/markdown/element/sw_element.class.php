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

namespace swan\markdown\element;
use swan\markdown\element\exception\sw_exception;

/**
* MarkDown 解析器 参数容器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_element
{
	// {{{ consts

	/**
	 * TAB 转化空格个数
	 */
	const TAB_WIDTH = 4;

	// }}}
	// {{{ members

	/**
	 * URL
	 *
	 * @var array
	 * @access protected
	 */
	protected $__url = array();

	/**
	 * 默认 URL 参考地址
	 *
	 * @var array
	 * @access protected
	 */
	protected $__default_url = array();

	/**
	 * URL 标题
	 *
	 * @var array
	 * @access protected
	 */
	protected $__url_title = array();

	/**
	 * 默认 url title
	 *
	 * @var array
	 * @access protected
	 */
	protected $__default_url_title = array();

	/**
	 * 参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__params = array();

	// }}}
	// {{{ functions
	// {{{ public function set_default_url()

	/**
	 * 设置默认的参考 URL 地址
	 *
	 * @access public
	 * @param array $urls
	 * @return swan\markdown\element\sw_element
	 */
	public function set_default_url($urls)
	{
		if (!is_array($urls)) {
			$urls = (string) $urls;
		}

		$this->__default_url = $urls;
		return $this;
	}

	// }}}
	// {{{ public function get_default_url()

	/**
	 * 获取默认 URL
	 *
	 * @access public
	 * @param string|null $key_id
	 * @return array
	 */
	public function get_default_url($key_id = null)
	{
		if (isset($key_id)) {
			return isset($this->__default_url[$key_id]) ? $this->__default_url[$key_id] : null;
		}

		return $this->__default_url;
	}

	// }}}
	// {{{ public function set_default_url_title()

	/**
	 * 设置默认的参考 URL 地址
	 *
	 * @access public
	 * @param array $titles
	 * @return swan\markdown\element\sw_element
	 */
	public function set_default_url_title($titles)
	{
		if (!is_array($titles)) {
			$titles = (string) $titles;
		}

		$this->__default_url_title = $titles;
		return $this;
	}

	// }}}
	// {{{ public function get_default_url_title()

	/**
	 * 获取默认 url_title
	 *
	 * @access public
	 * @param string|null $key_id
	 * @return array
	 */
	public function get_default_url_title($key_id = null)
	{
		if (isset($key_id)) {
			return isset($this->__default_url_title[$key_id]) ? $this->__default_url_title[$key_id] : null;
		}

		return $this->__default_url_title;
	}

	// }}}
	// {{{ public function set_url()

	/**
	 * 设置的参考 URL 地址
	 *
	 * @access public
	 * @param array $urls
	 * @return swan\markdown\element\sw_element
	 */
	public function set_url($urls)
	{
		if (!is_array($urls)) {
			$urls = (string) $urls;
		}

		$this->__url = $urls;
		return $this;
	}

	// }}}
	// {{{ public function get_url()

	/**
	 * 获取 URL
	 *
	 * @access public
	 * @param string|null $key_id
	 * @return array
	 */
	public function get_url($key_id = null)
	{
		if (isset($key_id)) {
			return isset($this->__url[$key_id]) ? $this->__url[$key_id] : null;
		}

		return $this->__url;
	}

	// }}}
	// {{{ public function set_url()

	/**
	 * 设置的参考 URL 地址
	 *
	 * @access public
	 * @param array $titles
	 * @return swan\markdown\element\sw_element
	 */
	public function set_url_title($titles)
	{
		if (!is_array($titles)) {
			$titles = (string) $titles;
		}

		$this->__url_title = $titles;
		return $this;
	}

	// }}}
	// {{{ public function get_url_title()

	/**
	 * 获取 url_title
	 *
	 * @access public
	 * @param string|null $key_id
	 * @return array
	 */
	public function get_url_title($key_id = null)
	{
		if (isset($key_id)) {
			return isset($this->__url_title[$key_id]) ? $this->__url_title[$key_id] : null;
		}

		return $this->__url_title;
	}

	// }}}
	// {{{ public function get_param()

	/**
	 * 获取参数 
	 * 
	 * @param string $key 
	 * @param mixed $default 
	 * @access public
	 * @return mixed
	 */
	public function get_param($key, $default = null)
	{
		$key = (string) $key;
		if (isset($this->__params[$key])) {
			return $this->__params[$key];	
		}

		return $default;
	}

	// }}}
	// {{{ public function set_param()

	/**
	 * 设置参数 
	 * 
	 * @param string $key 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function set_param($key, $value)
	{
		$key = (string) $key;

		if ((null === $value) && isset($this->__params[$key])) {
			unset($this->__params[$key]);	
		} else if (null !== $value) {
			$this->__params[$key] = $value;	
		}

		return $this;
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
		return $this->__params;	
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 批量设置参数 
	 * 
	 * @param array $array 
	 * @access public
	 * @return swan\controller\request\sw_abstract
	 */
	public function set_params(array $array)
	{
		$this->__params = $this->__params + (array) $array;

		foreach ($array as $key => $value) {
			if (null === $value) {
				unset($this->__params[$key]);
			}	
		}

		return $this;
	}

	// }}}
	// {{{ public function clear_params()

	/**
	 * 清除所有的参数 
	 * 
	 * @access public
	 * @return swan\controller\request\sw_abstract
	 */
	public function clear_params()
	{
		$this->__params = array();
		
		return $this;	
	}

	// }}}
	// {{{ public function clear_member()

	/**
	 * 清除所有的成员变量 
	 * 
	 * @access public
	 * @return void
	 */
	public function clear_member()
	{
		$this->__url = array();	
		$this->__url_title = array();
		$this->__default_url = array();
		$this->__default_url_title = array();
		$this->__params = array();

		return $this;
	}

	// }}}
	// }}}
}
