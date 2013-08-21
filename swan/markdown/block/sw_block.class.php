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

namespace swan\markdown\block;
use swan\markdown\block\exception\sw_exception;
use swan\markdown\hash\sw_hash;

/**
* MarkDown 解析器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_block
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * 是否支持 html 嵌入
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__markup = true;

	// }}}
	// {{{ functions
	// {{{ public function markup()

	/**
	 * 设置解析器是否支持嵌入 html 代码 
	 * 
	 * @access public
	 * @return swan\markdown\block\sw_block|boolean
	 */
	public function markup($markup = null)
	{
		if (!isset($markup)) {
			return $this->__markup;	
		}

		$this->__markup = (boolean) $markup;
		return $this;
	}

	// }}}
	// {{{ public function set_url()

	/**
	 * 设置的参考 URL 地址
	 *
	 * @access public
	 * @param array $urls
	 * @return swan\markdown\block\sw_block
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
	// {{{ public function set_url_title()

	/**
	 * 设置的参考 URL 地址
	 *
	 * @access public
	 * @param array $titles
	 * @return swan\markdown\block\sw_block
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
	// {{{ protected function _hash_html_blocks()

	/**
	 * 将 markdown 文本中 html 保存 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _hash_html_blocks($text)
	{
		if (!$this->__markup) {
			return $text;	
		}

		//$less_than_tab = self::
	}

	// }}}
	// }}}
}
