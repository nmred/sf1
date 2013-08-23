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
use swan\markdown\span\sw_span;

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

	/**
	 * TAB 转化空格个数
	 */
	const TAB_WIDTH = 4;

	// }}}
	// {{{ members

	/**
	 * 是否支持 html 嵌入
	 *
	 * @var boolean
	 * @access protected
	 */
	protected $__markup = true;

	/**
	 *  行解析对象 
	 * 
	 * @var swan\markdown\span\sw_span
	 * @access protected
	 */
	protected $__span = null;

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
		$this->__span = new sw_span();	
	}

	// }}}
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

		$less_than_tab = self::TAB_WIDTH - 1;

		$block_tags_a_re = 'ins|del';
		$block_tags_b_re = 'p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|' .
						   'script|noscript|from|fieldset|iframe|math';

		// 标签内的属性
		$attr = '
			(?>
				\s
				(?>
					[^>"\/]+
				|
					\/+(?!>)
				|
					"[^"]*"
				|
					\'[^\']*\'
				)*
			)?
		';

		$nested_tags_level = 4;
		// 标签内的内容
		$content =
			str_repeat('
				(?>
					[^<]+
				|
					<\2
						' . $attr . '
						(?>
							\/>
						|
							>', $nested_tags_level) .
						'.*?' . 
			str_repeat('
							<\/\2\s*>
						)
				|
					<(?!\/\2\s*>
				)
			)*', $nested_tags_level);

		$content_b = str_replace('\2', '\3', $content);

		$pattern = '/(?>
			(?>
				(?<=\n\n)
				|
				\A\n?
			)
			(   # $1
				[ ]{0,' . $less_than_tab . '}
				<(' . $block_tags_b_re . ')
				' . $attr . '>
				' . $content . '
				<\/\2>
				[ ]*
				(?=\n+|\Z)
			|
				[ ]{0,' . $less_than_tab . '}
				<(' . $block_tags_a_re . ')
				' . $attr . '>[ ]*\n
				' . $content_b . '
				<\/\3>
				[ ]*
				(?=\n+|\Z)
			|
				[ ]{0,' . $less_than_tab . '}
				<(hr)
				' . $attr . '
				\/?>
				[ ]*
				(?=\n{2,}|\Z) # 一个空行结束
			|
				[ ]{0,' . $less_than_tab . '}
				(?s:
					<!-- .*? -->
				)
				[ ]*
				(?=\n{2,}|\Z) # 一个空行结束
			|
				[ ]{0,' . $less_than_tab . '}
				(?s:
					<([?%]) # $2
					.*?
					\2>
				)
				[ ]*
				(?=\n{2,}|\Z) # 一个空行结束
			)
		)/Sxmi';

		$text = preg_replace_callback($pattern, 
			array($this, '_hash_html_blocks_callback'), $text);

		return $text;
	}

	// }}}
	// {{{ protected function _hash_html_blocks_callback()

	/**
	 * hash 编码 html 代码的回调 
	 * 
	 * @param string $matches 
	 * @access protected
	 * @return string
	 */
	protected function _hash_html_blocks_callback($matches)
	{
		$text = $matches[1];
		$key  = sw_hash::hash_block($text);
		return "\n\n$key\n\n";	
	}

	// }}}
	// {{{ protected function _do_headers()

	/**
	 * 解析标题 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_headers($text)
	{
		//解析标题形如：
		//	Header 1
		//	========
		//	
		//	Header 2
		//	--------
		$pattern_setext = '/^(.+?)[ ]*\n(=+|-+)[ ]*\n+/mx';
		$text = preg_replace_callback($pattern_setext,
			array($this, '_do_headers_setext_callback'), $text);

		//解析标题形如：
		//	#Header 1
		//	##Header 2
		//	###Header 3
		//	#..Header 6
		$pattern_atx = '/^(\#{1,6})[ ]*(.+?)[ ]*\#*\n+/xm';
		$text = preg_replace_callback($pattern_atx,
			array($this, '_do_headers_axt_callback'), $text);

		return $text;
	}

	// }}}
	// {{{ protected function _do_headers_setext_callback()

	/**
	 * 解析标题回调 
	 * 
	 * @param array $matches 
	 * @access protected
	 * @return string
	 */
	protected function _do_headers_setext_callback($matches)
	{
		if ($matches[2] == '-' && preg_match('/^-(?: |$)/', $matches[1])) {
			return $matches[0];	
		}

		$level = ($matches[2][0] == '=' ? 1 : 2);
		$block = "<h$level>" . $this->__span->run($matches[1]) . "</h$level>";
		return "\n" . sw_hash::hash_block($block) . "\n\n";
	}

	// }}}
	// {{{ protected function _do_headers_axt_callback()

	/**
	 * 解析标题回调 
	 * 
	 * @param array $matches 
	 * @access protected
	 * @return string
	 */
	protected function _do_headers_axt_callback($matches)
	{
		$level = strlen($matches[1]);
		$block = "<h$level>" . $this->__span->run($matches[2]) . "</h$level>";
		return "\n" . sw_hash::hash_block($block) . "\n\n";
	}

	// }}}
	// {{{ protected function _do_horizontal_rules()

	/**
	 * 解析分割线 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_horizontal_rules($text)
	{
		$pattern = '/^[ ]{0,3}([-*_])(?>[ ]{0,2}\1){2,}[ ]*$/mx';

		$text = preg_replace($pattern, 
			"\n" . sw_hash::hash_block("<hr/>") . "\n", $text);
		return $text;
	}

	// }}}
	// {{{ protected function _do_code_blocks()

	/**
	 * 解析代码块 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_code_blocks($text)
	{
		$pattern = '/
			(?:\n\n|\A\n?)
			(
				(?>
					[ ]{' . self::TAB_WIDTH . '}
					.*\n+
				)+
			)
			((?=^[ ]{0,' . self::TAB_WIDTH . '}\S)|\Z)
		/mx';

		$text = preg_replace_callback($pattern,
			array($this, '_do_code_blocks_callback'), $text);

		return $text;
	}

	// }}}
	// {{{ protected function _do_code_blocks_callback()

	/**
	 * 解析代码块回调 
	 * 
	 * @param array $matches 
	 * @access protected
	 * @return string
	 */
	protected function _do_code_blocks_callback($matches)
	{
		$code_block = $matches[1];
		$code_block = $this->_outdent($code_block);
		$code_block = htmlspecialchars($code_block, ENT_NOQUOTES);

		$code_block = preg_replace('/\A\n+|\n+\z/', '', $code_block);
		
		$code_block = "<pre><code>$code_block\n</code></pre>";
		return "\n\n" . sw_hash::hash_block($code_block) . "\n\n";
	}

	// }}}
	// {{{ protected function _do_block_quotes()

	/**
	 * 解析块引用 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_block_quotes($text)
	{
			
	}

	// }}}
	// {{{ protected function _outdent()
	
	/**
	 * 去处行首 TAB 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _outdent($text)
	{
		$pattern = '/^(\t|[ ]{1,' . self::TAB_WIDTH . '})/m';
		return preg_replace($pattern, '', $text);	
	}

	// }}} 
	// }}}
}
