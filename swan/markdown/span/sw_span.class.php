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

namespace swan\markdown\span;
use swan\markdown\span\exception\sw_exception;
use swan\markdown\hash\sw_hash;

/**
* MarkDown 区段元素解析器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_span
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * markdown 特殊字符
	 *
	 * @var string
	 * @access protected
	 */
	protected $__escape_chars = '\`*_{}[]()>#+-.!';

	/**
	 * markdown 特殊字符的匹配正则
	 *
	 * @var string
	 * @access protected
	 */
	protected $__escape_chars_re = null;

	/**
	 * 是否支持 html 嵌入
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__markup = true;

	/**
	 * 是否开启强制实体化，开启后将不支持手动实体化 html
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__no_entities = false;

	/**
	 * 嵌套的最大深度
	 *
	 * @var float
	 * @access protected
	 */
	protected $__nested_brackets_depth = 6;

	/**
	 * 嵌套解析正则
	 *
	 * @var string
	 * @access protected
	 */
	protected $__nested_brackets_re = '';

	/**
	 * url 最深嵌套层数
	 *
	 * @var float
	 * @access protected
	 */
	protected $__nested_url_parenthesis_depth = 4;

	/**
	 * url 嵌套正则解析
	 *
	 * @var string
	 * @access protected
	 */
	protected $__nested_url_parenthesis_re;

	/**
	 * 参考连接地址
	 *
	 * @var array
	 * @access protected
	 */
	protected $__url = array();

	/**
	 * 参考连接地址的标题说明
	 *
	 * @var array
	 * @access protected
	 */
	protected $__url_title = array();

	/**
	 * 标签结束符
	 *
	 * @var string
	 * @access protected
	 */
	protected $__empty_element_suffix = ' />';

	/**
	 * 解析链接地址防止嵌套解析的标记
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__in_anchor = false;

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
		$this->__escape_chars_re = '[' . preg_quote($this->__escape_chars) . ']';

		$this->__nested_brackets_re =
			str_repeat('(?>[^\[\]]+|\[', $this->__nested_brackets_depth) .
			str_repeat('\])*', $this->__nested_brackets_depth);

		$this->__nested_url_parenthesis_re =
			str_repeat('(?>[^()\s]+|\(', $this->__nested_url_parenthesis_depth) .
			str_repeat('(?>\)))*', $this->__nested_url_parenthesis_depth);
	}

	// }}}
	// {{{ public function set_url()

	/**
	 * 设置的参考 URL 地址
	 *
	 * @access public
	 * @param array $urls
	 * @return swan\markdown\span\sw_span
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
	 * @return swan\markdown\span\sw_span
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
	// {{{ protected function _parse_span()

	/**
	 * 解析 markdown 中的特殊字符以及代码块
	 *
	 * @param string $str
	 * @access protected
	 * @return string
	 */
	protected function _parse_span($str)
	{
		$output = '';

		$markup = '
			|
				<!-- .*? --> #注释
			|
				<\?.*\?> | <%.*?%>
			|
				<[\/!$]?[-a-zA-Z0-9:]+  #常规标签
				(?>
					\s
					(?>[^"\'>]+|"[^"]*"|\'[^\']*\')*
				)?
				>
		';
		$span_re = '/
			(
				\\\\' . $this->__escape_chars_re . '
			|
				(?<![`\\\\])
				`+
			' . ($this->__markup ? $markup : '') . '
			)
		/xs';

		while(1) {
			$parts = preg_split($span_re, $str, 2,	PREG_SPLIT_DELIM_CAPTURE);

			if ($parts[0] != "") {
				$output .= $parts[0];
			}

			if (isset($parts[1])) {
				$output .= $this->_handle_span_token($parts[1], $parts[2]);
				$str = $parts[2];
			} else {
				break;
			}
		}

		return $output;
	}

	// }}}
	// {{{ protected function _handle_span_token()

	/**
	 * 处理匹配的内容
	 *
	 * @param string $token
	 * @param string $str
	 * @access protected
	 * @return string
	 */
	protected function _handle_span_token($token, &$str)
	{
		switch ($token{0}) {
			case "\\":
				return sw_hash::hash_part("&#" . ord($token{1}) . ";");
			case "`":
				if (preg_match('/^(.*?[^`])' . preg_quote($token) . '(?!`)(.*)$/sm', $str, $matches)) {
					$str = $matches[2];
					$codespan = $this->_make_code_span($matches[1]);
					return sw_hash::hash_part($codespan);
				}
				return $token;
			default:
				return sw_hash::hash_part($token);
		}
	}

	// }}}
	// {{{ protected function _make_code_span()

	/**
	 * 添加 code 标签
	 *
	 * @param string $code
	 * @access protected
	 * @return string
	 */
	protected function _make_code_span($code)
	{
		$code = htmlspecialchars(trim($code), ENT_NOQUOTES);
		return sw_hash::hash_part("<code>$code</code>");
	}

	// }}}
	// {{{ protected function _do_images()

	/**
	 * 解析 markdown 中的图片
	 *
	 * @param string $text
	 * @access protected
	 * @return string
	 */
	protected function _do_images($text)
	{
		// 匹配参考式图片 ![alt text] [id]
		$parrent_reference = '/
			(	# 匹配所有 $1
				!\[
					(' . $this->__nested_brackets_re . ') # alt text = $2
				\]

				[ ]? #空格
				(?:\n[ ]*)? # 新的一行

				\[
					(.*?) # id = $3
				\]

			)
		/xs';

		$text = preg_replace_callback($parrent_reference,
			array($this, '_do_images_reference_callback'), $text);

		// 匹配内行式图片 ![alt text] (url "optional text")
		$parrent_inline = '/
			(	# 匹配所有的 $1
				!\[
					(' . $this->__nested_brackets_re . ') # alt text = $2
				\]
				\s?
				\(
					[ ]*
					(?:
						<(\S*)> # src url = $3
					|
						(' . $this->__nested_url_parenthesis_re . ') # src url = $4
					)
					[ ]*
					( # $5
						([\'"]) # 引号 $6
						(.*?) # title $7
						\6
						[ ]*
					)?
				\)
			)
		/xs';

		$text = preg_replace_callback($parrent_inline,
			array($this, '_do_images_inline_callback'), $text);

		return $text;
	}

	// }}}
	// {{{ protected function _do_images_reference_callback()

	/**
	 * 解析图片参考式回调
	 *
	 * @param array $matches
	 * @access protected
	 * @return string
	 */
	protected function _do_images_reference_callback($matches)
	{
		$whole_match = $matches[1];
		$alt_text    = $matches[2];
		$link_id     = isset($matches[3]) ? $matches[3] : null;
		$link_id     = strtolower((string) $link_id);

		if ($link_id == "") {
			$link_id = strtolower($alt_text);
		}

		$alt_text = $this->_encode_attribute($alt_text);
		if (isset($this->__url[$link_id])) {
			$url = $this->_encode_attribute($this->__url[$link_id]);
			$result = "<img src=\"$url\" alt=\"$alt_text\"";
			if (isset($this->__url_title[$link_id])) {
				$title = $this->__url_title[$link_id];
				$title = $this->_encode_attribute($title);
				$result .= " title=\"$title\"";
			}
			$result .= $this->__empty_element_suffix;
			$result = sw_hash::hash_part($result);
		} else { // 没有 link id
			$result = $whole_match;
		}

		return $result;
	}

	// }}}
	// {{{ protected function _do_images_inline_callback()

	/**
	 * 解析图片的内行式回调
	 *
	 * @param array $matches
	 * @access protected
	 * @return string
	 */
	protected function _do_images_inline_callback($matches)
	{
		$whole_match = $matches[1];
		$alt_text    = $matches[2];
		$url         = $matches[3] == '' ? $matches[4] : $matches[3];
		$title       = isset($matches[7]) ? $matches[7] : null;

		$alt_text = $this->_encode_attribute($alt_text);
		$url = $this->_encode_attribute($url);
		$result = "<img src=\"$url\" alt=\"$alt_text\"";
		if (isset($title)) {
			$title = $this->_encode_attribute($title);
			$result .= " title=\"$title\"";
		}
		$result .= $this->__empty_element_suffix;

		return sw_hash::hash_part($result);
	}

	// }}}
	// {{{ protected function _do_anchors()

	/**
	 * 解析 <a> 链接
	 *
	 * @param string $text
	 * @access protected
	 * @return string
	 */
	protected function _do_anchors($text)
	{
		// 防止嵌套解析
		if ($this->__in_anchor) {
			return $text;
		}

		$this->__in_anchor = true;

		// 匹配参考式链接 [alt text] [id]
		$parrent_reference = '/
			(	# 匹配所有 $1
				\[
					(' . $this->__nested_brackets_re . ') # alt text = $2
				\]

				[ ]? #空格
				(?:\n[ ]*)? # 新的一行

				\[
					(.*?) # id = $3
				\]

			)
		/xs';

		$text = preg_replace_callback($parrent_reference,
			array($this, '_do_anchors_reference_callback'), $text);

		// 匹配内行式链接 [alt text] (url "optional text")
		$parrent_inline = '/
			(	# 匹配所有的 $1
				\[
					(' . $this->__nested_brackets_re . ') # alt text = $2
				\]
				\s?
				\(
					[ ]*
					(?:
						<(\S*)> # url = $3
					|
						(' . $this->__nested_brackets_re . ') # url = $4
					)
					[ ]*
					( # $5
						([\'"]) # 引号 $6
						(.*?) # title $7
						\6
						[ ]*
					)?
				\)
			)
		/xs';

		$text = preg_replace_callback($parrent_inline,
			array($this, '_do_anchors_inline_callback'), $text);

		$this->__in_anchor = false;
		return $text;
	}

	// }}}
	// {{{ protected function _do_anchors_reference_callback()

	/**
	 * 解析链接地址参考式回调
	 *
	 * @param array $matches
	 * @access protected
	 * @return string
	 */
	protected function _do_anchors_reference_callback($matches)
	{
		$whole_match = $matches[1];
		$link_text   = $matches[2];
		$link_id     = isset($matches[3]) ? $matches[3] : null;
		$link_id     = strtolower((string) $link_id);

		if ($link_id == "") {
			$link_id = strtolower($link_text);
		}

		$link_id = strtolower($link_id);
		$link_id = preg_replace('/[ ]?\n/', ' ', $link_id);

		if (isset($this->__url[$link_id])) {
			$url = $this->_encode_attribute($this->__url[$link_id]);
			$result = "<a href=\"$url\"";
			if (isset($this->__url_title[$link_id])) {
				$title = $this->__url_title[$link_id];
				$title = $this->_encode_attribute($title);
				$result .= " title=\"$title\"";
			}

	// todo
//			$link_text = $this->

			$result .= ">$link_text</a>";
			$result = sw_hash::hash_part($result);
		} else { // 没有 link id
			$result = $whole_match;
		}

		return $result;
	}

	// }}}
	// {{{ protected function _do_anchors_inline_callback()

	/**
	 * 解析链接地址的内行式回调
	 *
	 * @param array $matches
	 * @access protected
	 * @return string
	 */
	protected function _do_anchors_inline_callback($matches)
	{
		$whole_match = $matches[1];
		$link_text   = $matches[2]; // todo
		$url         = $matches[3] == '' ? $matches[4] : $matches[3];
		$title       = isset($matches[7]) ? $matches[7] : null;

		$url = $this->_encode_attribute($url);
		$result = "<a href=\"$url\"";
		if (isset($title)) {
			$title = $this->_encode_attribute($title);
			$result .= " title=\"$title\"";
		}

		$result .= ">$link_text</a>";
		return sw_hash::hash_part($result);
	}

	// }}}
	// {{{ protected function _do_hard_breaks()

	/**
	 * 解析 </br> html 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_hard_breaks($text)
	{
		return preg_replace_callback('/ {2,}\n/', 
			array($this, '_do_hard_breaks_callback'), $text);
	}

	// }}}
	// {{{ protected function _do_hard_breaks_callback()

	/**
	 * 解析换行回调 
	 * 
	 * @param array $matches 
	 * @access protected
	 * @return string
	 */
	protected function _do_hard_breaks_callback($matches)
	{
		return sw_hash::hash_part("<br$this->__empty_element_suffix\n");
	}

	// }}}
	// {{{ protected function _encode_attribute()

	/**
	 * 将 html 属性内容实体化
	 *
	 * @access protected
	 * @return string
	 */
	protected function _encode_attribute($text)
	{
		$text = $this->_encode_amps_and_angles($text);
		$text = str_replace('"', '&quot;', $text);
		return $text;
	}

	// }}}
	// {{{ protected function _encode_amps_and_angles()

	/**
	 * 将字符串实体化
	 *
	 * @param string $text
	 * @access protected
	 * @return string
	 */
	protected function _encode_amps_and_angles($text)
	{
		if ($this->__no_entities) { // 将所有的 & 全部实体化
			$text = str_replace('&', '&amp;', $text);
		} else { // 已经实体化的 & 将不再继续实体化
			$text = preg_replace('/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/', '&amp', $text);
		}

		$text = str_replace('<', '&lt;', $text);

		return $text;
	}

	// }}}
	// }}}
}
