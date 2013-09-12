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
use swan\markdown\element\sw_element;

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
	 * 解析调用次序 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__parse_action = array(
		'do_headers'             => 10,
		'do_horizontal_rules'    => 20,
		'do_code_blocks'         => 40,
		'do_lists'               => 30,
		'do_block_quotes'        => 50,	
	);

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

	/**
	 * __list_level
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__list_level = 0;

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(\swan\markdown\element\sw_element $element)
	{
		$this->__span = new sw_span($element);	
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

		$text = $this->_form_paragraphs($text);
		return $text;
	}

	// }}}
	// {{{ public function run_block()

	/**
	 * 解析 block 
	 * 
	 * @param string $text 
	 * @access public
	 * @return string
	 */
	public function run_block($text)
	{
		$text = $this->_hash_html_blocks($text);
		
		return $this->run($text);	
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

		$less_than_tab = sw_element::TAB_WIDTH - 1;

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
					[ ]{' . sw_element::TAB_WIDTH . '}
					.*\n+
				)+
			)
			((?=^[ ]{0,' . sw_element::TAB_WIDTH . '}\S)|\Z)
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
		$pattern = '/
			(
				(?>
					^[ ]*>[ ]?
					.+\n
					(.+\n)*
					\n*
				)+
			)
		/mx';
		$text = preg_replace_callback($pattern,
			array($this, '_do_block_quotes_callback'), $text);
		
		return $text; 
	}

	// }}}
	// {{{ protected function _do_block_quotes_callback()

	/**
	 * 块引用回调 
	 * 
	 * @param string $matches 
	 * @access protected
	 * @return string
	 */
	protected function _do_block_quotes_callback($matches)
	{
		$bq = $matches[1];
		$bq = preg_replace('/^[ ]*>[ ]?|^[ ]+$/m', '', $bq);
		$bq = $this->run_block($bq);
		
		$bq = preg_replace('/^/m', "  ", $bq);
		
		$bq = preg_replace_callback('/(\s*<pre>.+?<\/pre>)/sx',
			array($this, '_do_block_quotes_pre_callback'), $bq);

		return "\n" . sw_hash::hash_block("<blockquote>\n$bq\n</blockquote>") . "\n\n";
		
	}

	// }}}
	// {{{ protected function _do_block_quotes_pre_callback()

	/**
	 * 解析引用处理 <pre> 中空格 
	 * 
	 * @param array $matches 
	 * @access protected
	 * @return string
	 */
	protected function _do_block_quotes_pre_callback($matches)
	{
		$pre = $matches[1];
		$pre = preg_replace('/^  /m', '', $pre);
		return $pre;	
	}

	// }}}
	// {{{ protected function _do_lists()

	/**
	 * 解析列表 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _do_lists($text)
	{
		$less_than_tab = sw_element::TAB_WIDTH - 1;
		
		$marker_ul_re = '[*+-]';
		$marker_ol_re = '\d+[.]';	
		$marker_any_re = "(?:$marker_ul_re|$marker_ol_re)";

		$markers_relist = array($marker_ul_re, $marker_ol_re);

		foreach ($markers_relist as $marker_re) {
			$whole_list_re = '
				( # $1
					(
						[ ]{0,' . $less_than_tab . '}
						(' . $marker_re . ')
						[ ]+
					)
					(?s:.+?)
					(
						\z
						|
							\n{2,}
							(?=\s)
							(?!
								[ ]*
								' . $marker_re . '[ ]+
							)	
					)
				)
			';

			if ($this->__list_level) {
				$pattern = '/
					^
					' . $whole_list_re . '
				/mx';
				$text = preg_replace_callback($pattern, 
					array($this, '_do_lists_callback'), $text);	
			} else {
				$pattern = '/
					(?:(?<=\n)\n|\A\n?)
					' . $whole_list_re . '
				/mx';
				$text = preg_replace_callback($pattern,
					array($this, '_do_lists_callback'), $text);	
			}
		}

		return $text;
	}

	// }}}
	// {{{ protected function _do_lists_callback()

	/**
	 * 解析列表回调 
	 * 
	 * @param array $matches 
	 * @access protected
	 * @return string
	 */
	protected function _do_lists_callback($matches)
	{			
		$marker_ul_re = '[*+-]';
		$marker_ol_re = '\d+[.]';	
		$marker_any_re = "(?:$marker_ul_re|$marker_ol_re)";

		$list = $matches[1];
		$list_type = preg_match("/$marker_ul_re/", $matches[3]) ? 'ul' : 'ol';
		
		$marker_any_re = ( $list_type == 'ul' ? $marker_ul_re : $marker_ol_re);
		$list .= "\n";
		$result = $this->_process_list_items($list, $marker_any_re);

		$result = sw_hash::hash_block("<$list_type>\n" . $result . "</$list_type>");
		return "\n". $result ."\n\n";
	}

	// }}}
	// {{{ protected function _process_list_items()

	/**
	 * 解析列表具体条目 
	 * 
	 * @param string $list_str 
	 * @param string $marker_any_re 
	 * @access protected
	 * @return string
	 */
	protected function _process_list_items($list_str, $marker_any_re)
	{
		$this->__list_level++;
		
		$list_str = preg_replace("/\n{2,}\\z/", "\n", $list_str);
		
		$pattern = '/
			(\n)?
			(^[ ]*)
			(' . $marker_any_re .'
				(?:[ ]+|(?=\n))
			)
			((?s:.*?))
			(?:(\n+(?=\n))|\n)
			(?= \n* (\z | \2 ('.$marker_any_re.') (?:[ ]+|(?=\n))))
		/mx';

		$list_str = preg_replace_callback($pattern,
			array($this, '_process_list_items_callback'), $list_str);

		$this->__list_level--;
		return $list_str;
	}

	// }}}
	// {{{ protected function _process_list_items_callback()

	/**
	 * 解析列表具体条目回调 
	 * 
	 * @param string $matches 
	 * @access protected
	 * @return string
	 */
	protected function _process_list_items_callback($matches)
	{
		$item = $matches[4];
		$leading_line = isset($matches[1]) ? $matches[1] : null;
		$leading_space = isset($matches[2]) ? $matches[2] : null;	
		$marker_space = $matches[3];
		$tailing_blank_line = isset($matches[5]) ? $matches[5] : null;

		if ($leading_line || $tailing_blank_line || preg_match('/\n{2,}/', $item)) {
			$item = $leading_space . str_repeat(' ', strlen($marker_space)) . $item;
			$item = $this->run_block($this->_outdent($item) . "\n");	
		} else {
			$item = $this->_do_lists($this->_outdent($item));
			$item = preg_replace('/\n+$/', '', $item);
			$item = $this->__span->run($item);	
		}

		return "<li>" . $item . "</li>\n";
	}

	// }}}
	// {{{ protected function _form_paragraphs()

	/**
	 * 解析段落 
	 * 
	 * @param string $text 
	 * @access protected
	 * @return string
	 */
	protected function _form_paragraphs($text)
	{
		$text = preg_replace('/\A\n+|\n+\z/', '', $text);
		$grafs = preg_split('/\n{2,}/', $text, -1, PREG_SPLIT_NO_EMPTY);

		foreach ($grafs as $key => $value) {
			if (!preg_match('/^B\x1A[0-9]+B$/', $value)) {
				$value = $this->__span->run($value);	
				$value = preg_replace('/^([ ]*)/', '<p>', $value);
				$value .= '</p>';
				$grafs[$key] = sw_hash::unhash($value);
			} else {
				$block = sw_hash::unhash($value);
				$grafs[$key] = $block;	
			}
		}

		return implode("\n\n", $grafs);
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
		$pattern = '/^(\t|[ ]{1,' . sw_element::TAB_WIDTH . '})/m';
		return preg_replace($pattern, '', $text);	
	}

	// }}} 
	// }}}
}
