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
			str_repeat('(?>[^\[\]+|\[', $this->__nested_brackets_depth) .
			str_repeat('\])*', $this->__nested_brackets_depth);

		$this->__nested_url_parenthesis_re =
			str_repeat('(?>[^()\s]+|\(', $this->__nested_url_parenthesis_depth) .
			str_repeat('(?>\)))*', $this->__nested_url_parenthesis_depth);
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
		// todo	
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
		// todo		
	}

	// }}}
	// }}}
}
