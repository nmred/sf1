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
	// }}}
}
