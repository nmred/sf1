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
	 * 解析调用次序 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__parse_action = array(
		'parse_span'   => -30,
		'do_images'    => 10,
		'do_anchors'   => 20,
		'do_autolinks' => 30,
		'encode_amps_and_angles' => 40,	
		'do_italics_bold' => 50,
		'do_hard_breaks'  => 60,
	);

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

	/**
	 * ele 对象 
	 * 
	 * @var swan\markdown\element\sw_element
	 * @access protected
	 */
	protected $__element = null;

	/**
	 * replace 对象 
	 * 
	 * @var swan\markdown\replace\sw_replace
	 * @access protected
	 */
	protected $__replace = null;

	/**
	 * 解析链接地址防止嵌套解析的标记
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__in_anchor = false;

	/**
	 * 解析强调语法正则 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__em_strong_relist = array();

	/**
	 * 是否开启强制实体化，开启后将不支持手动实体化 html
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__no_entities = false;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(\swan\markdown\sw_markdown $markdown)
	{
		$this->__element = $markdown->get_element();
		$this->__replace = $markdown->get_replace();

		$this->__escape_chars_re = '[' . preg_quote($this->__escape_chars) . ']';

		$this->__nested_brackets_re =
			str_repeat('(?>[^\[\]]+|\[', $this->__nested_brackets_depth) .
			str_repeat('\])*', $this->__nested_brackets_depth);

		$this->__nested_url_parenthesis_re =
			str_repeat('(?>[^()\s]+|\(', $this->__nested_url_parenthesis_depth) .
			str_repeat('(?>\)))*', $this->__nested_url_parenthesis_depth);

		$this->_prepare_italics_bold();
	}

	// }}}
	// {{{ public function run()

	/**
	 * 运行解析 
	 * 
	 * @param string $text 
	 * @access public
	 * @return string
	 */
	public function run($text)
	{
		asort($this->__parse_action);
		
		foreach ($this->__parse_action as $method => $priority) {
			$method = '_' . $method;
			$text = $this->$method($text);
		}

		return $text;
	}

	// }}}
	// {{{ public function markup()

	/**
	 * 设置解析器是否支持嵌入 html 代码 
	 * 
	 * @access public
	 * @return void
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
	// {{{ public function encode_amps()

	/**
	 * encode_amps 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function encode_amps($text)
	{
		return $this->_encode_amps_and_angles($text);	
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
		$pattern_reference = '/
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

		$text = preg_replace_callback($pattern_reference,
			array($this->__replace, 'images_reference_callback'), $text);

		// 匹配内行式图片 ![alt text] (url "optional text")
		$pattern_inline = '/
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

		$text = preg_replace_callback($pattern_inline,
			array($this->__replace, 'images_inline_callback'), $text);

		return $text;
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
		$pattern_reference = '/
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

		$text = preg_replace_callback($pattern_reference,
			array($this->__replace, 'anchors_reference_callback'), $text);

		// 匹配内行式链接 [alt text] (url "optional text")
		$pattern_inline = '/
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

		$text = preg_replace_callback($pattern_inline,
			array($this->__replace, 'anchors_inline_callback'), $text);

		$this->__in_anchor = false;
		return $text;
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
			array($this->__replace, 'hard_breaks_callback'), $text);
	}

	// }}}
	// {{{ protected function _do_autolinks()

	/**
	 * 解析自动链接 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_autolinks($text)
	{
		// url <http://www.swanlinux.net>
		$pattern = '/<((https?|ftp|fict):[^\'">\s]+)>/i';
		$text = preg_replace_callback($pattern,
			array($this->__replace, 'autolinks_url_callback'), $text);
	
		// email <nmred@sina.cn>
		$pattern_email = '/
			<
			(?:mailto:)?
			(
				[-.\w\x80-\xFF]+
				\@
				[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF])*\.[a-z]+
			)
			>
		/xi';
		$text = preg_replace_callback($pattern_email,
			array($this->__replace, 'autolinks_email_callback'), $text);

		return $text;
	}

	// }}}
	// {{{ protected function _do_italics_bold()

	/**
	 * 解析强调语法
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_italics_bold($text)
	{
		$token_stack = array('');
		$text_stack  = array('');
		$em = '';
		$strong = '';
		$tree_char_em = false;		

		while (1) {
			$token_re = $this->__em_strong_relist["$em$strong"];

			$parts = preg_split($token_re, $text, 2, PREG_SPLIT_DELIM_CAPTURE);
			$text_stack[0] .= $parts[0];
			$token = isset($parts[1]) ? $parts[1] : null;
			$text  = isset($parts[2]) ? $parts[2] : null;

			if (!isset($token)) {
				while ($token_stack[0]) {
					$text_stack[1] .= array_shift($token_stack);
					$text_stack[0] .= array_shift($text_stack);
				}
				break;
			}

			$token_len = strlen($token);
			if ($tree_char_em) {
				if ($token_len == 3) {
					array_shift($token_stack);
					$span = array_shift($text_stack);
					$span = $this->run($span);
					$span = "<strong><em>$span</em></strong>";
					$text_stack[0] .= sw_hash::hash_part($span);
					$em = '';
					$strong = '';
				} else {
					$token_stack[0] = str_repeat($token[0], 3 - $token_len);
					$tag  = ($token_len == 2 ? "strong" : "em");	
					$span = $text_stack[0];
					$span = $this->run($span);
					$span = "<$tag>$span</$tag>";
					$text_stack[0] = sw_hash::hash_part($span);
					$$tag = '';
				}
				$tree_char_em = false;
			} elseif ($token_len === 3) {
				if ($em) {
					for ($i = 0; $i < 2; ++$i) {
						$shifted_token = array_shift($token_stack);
						$tag = strlen($shifted_token) == 2 ? "strong" : "em";
						$span = array_shift($text_stack);
						$span = $this->run($span);
						$span = "<$tag>$span</$tag>";
						$text_stack[0] .= sw_hash::hash_part($span);
						$$tag = '';
					}	
				} else {
					$em = $token{0};
					$strong = "$em$em";
					array_unshift($token_stack, $token);
					array_unshift($text_stack, '');
					$tree_char_em = true;	
				}	
			} elseif ($token_len == 2) {
				if ($strong) {
					if (strlen($token_stack[0]) == 1) {
						$text_stack[1] .= array_shift($token_stack);
						$text_stack[0] .= array_shift($text_stack);
					}
					array_shift($token_stack);
					$span = array_shift($text_stack);
					$span = $this->run($span);
					$span = "<strong>$span</strong>";
					$text_stack[0] .= sw_hash::hash_part($span);
					$strong = '';
				} else {
					array_unshift($token_stack, $token);
					array_unshift($text_stack, '');
					$strong = $token;
				}
			} else {
				if ($em) {
					if (strlen($token_stack[0]) == 1) {
						array_shift($token_stack);
						$span = array_shift($text_stack);
						$span = $this->run($span);
						$span = "<em>$span</em>";
						$text_stack[0] .= sw_hash::hash_part($span);
						$em = '';
					} else {
						$text_stack[0] .= $token;
					}
				} else {
					array_unshift($token_stack, $token);
					array_unshift($text_stack, '');
					$em = $token;
				}	
			}
		}

		return $text_stack[0];
	}

	// }}}
	// {{{ protected function _prepare_italics_bold()

	/**
	 * 解析强调语句正则预处理 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _prepare_italics_bold()
	{
		$em_relist = array(
			''  => '(?:(?<!\*)\*(?!\*)|(?<!_)_(?!_))(?=\S)(?![.,:;]\s)',
			'*' => '(?<=\S)(?<!\*)\*(?!\*)',
			'_' => '(?<=\S)(?<!_)_(?!_)',
		);

		$strong_relist = array(
			''   => '(?:(?<!\*)\*\*(?!\*)|(?<!_)__(?!_))(?=\S)(?![.,:;]\s)',
			'**' => '(?<=\S)(?<!\*)\*\*(?!\*)',
			'__' => '(?<=\S)(?<!_)__(?!_)',
		);

		$em_strong_relist = array(
			''    => '(?:(?<!\*)\*\*\*(?!\*)|(?<!_)___(?!_))(?=\S)(?![.,:;]\s)',
			'***' => '(?<=\S)(?<!\*)\*\*\*(?!\*)',
			'___' => '(?<=\S)(?<!_)___(?!_)',
		);

		foreach ($em_relist as $em => $em_re) {
			foreach ($strong_relist as $strong => $strong_re) {
				$token_relist = array();
				if (isset($em_strong_relist["$em$strong"])) {
					$token_relist[] = $em_strong_relist["$em$strong"];	
				}
				$token_relist[] = $em_re;
				$token_relist[] = $strong_re;

				$token_re = '/(' . implode('|', $token_relist) . ')/';
				$this->__em_strong_relist["$em$strong"] = $token_re;
			}	
		}
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
			$text = preg_replace('/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/', '&amp;', $text);
		}

		$text = str_replace('<', '&lt;', $text);

		return $text;
	}

	// }}}
	// }}}
}
