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

namespace swan\markdown\replace;
use swan\markdown\hash\sw_hash;
use swan\markdown\element\sw_element;
use swan\markdown\exception\replace\sw_exception;

/**
* MarkDown 解析器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
abstract class sw_abstract
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * 解析器本身对象 
	 * 
	 * @var swan\markdown\sw_markdown
	 * @access protected
	 */
	protected $__markdown = null;

	/**
	 * 标签结束符
	 *
	 * @var string
	 * @access protected
	 */
	protected $__empty_element_suffix = ' />';

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
		$this->__markdown = $markdown;
	}

	// }}}
	// {{{ public function headers_setext_callback()

	/**
	 * 解析标题回调 
	 * 
	 * @param array $matches 
	 * @access public
	 * @return string
	 */
	public function headers_setext_callback($matches)
	{
		if ($matches[2] == '-' && preg_match('/^-(?: |$)/', $matches[1])) {
			return $matches[0];	
		}

		$level = ($matches[2][0] == '=' ? 1 : 2);
		$span = $this->__markdown->get_span();
		$block = "<h$level>" . $span->run($matches[1]) . "</h$level>";
		return "\n" . sw_hash::hash_block($block) . "\n\n";
	}

	// }}}
	// {{{ public function headers_axt_callback()

	/**
	 * 解析标题回调 
	 * 
	 * @param array $matches 
	 * @access public
	 * @return string
	 */
	public function headers_axt_callback($matches)
	{
		$span = $this->__markdown->get_span();
		$level = strlen($matches[1]);
		$block = "<h$level>" . $span->run($matches[2]) . "</h$level>";
		return "\n" . sw_hash::hash_block($block) . "\n\n";
	}

	// }}}
	// {{{ public function horizontal_rules_callback()

	/**
	 * 解析替换分割线 
	 * 
	 * @param array $matches 
	 * @access public
	 * @return string
	 */
	public function horizontal_rules_callback($matches)
	{
		return "\n" . sw_hash::hash_block("<hr/>") . "\n";
	}

	// }}}
	// {{{ public function code_blocks_callback()

	/**
	 * 解析代码块回调 
	 * 
	 * @param array $matches 
	 * @access public
	 * @return string
	 */
	public function code_blocks_callback($matches)
	{
		$code_block = $matches[1];
		$code_block = $this->__markdown->outdent($code_block);

		// 加入高亮代码，就不需要html实体化了
	//	$code_block = htmlspecialchars($code_block, ENT_NOQUOTES);

		$code_block = preg_replace('/\A\n+|\n+\z/', '', $code_block);
		
		$code_block = "<pre><code>$code_block\n</code></pre>";
		return "\n\n" . sw_hash::hash_block($code_block) . "\n\n";
	}

	// }}}
	// {{{ public function block_quotes_callback()

	/**
	 * 块引用回调 
	 * 
	 * @param string $matches 
	 * @access public
	 * @return string
	 */
	public function block_quotes_callback($matches)
	{
		$bq = $matches[1];
		$bq = preg_replace('/^[ ]*>[ ]?|^[ ]+$/m', '', $bq);
		$bq = $this->__markdown->get_block()->run_block($bq);
		
		$bq = preg_replace('/^/m', "  ", $bq);
		
		$bq = preg_replace_callback('/(\s*<pre>.+?<\/pre>)/sx',
			array($this, '_block_quotes_pre_callback'), $bq);

		return "\n" . sw_hash::hash_block("<blockquote>\n$bq\n</blockquote>") . "\n\n";
		
	}

	// }}}
	// {{{ public function images_reference_callback()

	/**
	 * 解析图片参考式回调
	 *
	 * @param array $matches
	 * @access public
	 * @return string
	 */
	public function images_reference_callback($matches)
	{
		$whole_match = $matches[1];
		$alt_text    = $matches[2];
		$link_id     = isset($matches[3]) ? $matches[3] : null;
		$link_id     = strtolower((string) $link_id);

		if ($link_id == "") {
			$link_id = strtolower($alt_text);
		}

		$alt_text = $this->_encode_attribute($alt_text);
		$url = $this->__markdown->get_element()->get_url($link_id);
		if (isset($url)) {
			$url = $this->_encode_attribute($url);
			$result = "<img src=\"$url\" alt=\"$alt_text\"";
			$title = $this->__markdown->get_element()->get_url_title($link_id);
			if (isset($title)) {
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
	// {{{ public function images_inline_callback()

	/**
	 * 解析图片的内行式回调
	 *
	 * @param array $matches
	 * @access public
	 * @return string
	 */
	public function images_inline_callback($matches)
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
	// {{{ public function anchors_reference_callback()

	/**
	 * 解析链接地址参考式回调
	 *
	 * @param array $matches
	 * @access public
	 * @return string
	 */
	public function anchors_reference_callback($matches)
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

		$url = $this->__markdown->get_element()->get_url($link_id);
		if (isset($url)) {
			$url = $this->_encode_attribute($url);
			$result = "<a href=\"$url\"";
			$title = $this->__markdown->get_element()->get_url_title($link_id);
			if (isset($title)) {
				$title = $this->_encode_attribute($title);
				$result .= " title=\"$title\"";
			}

			$link_text = $this->__markdown->get_span()->run($link_text);

			$result .= ">$link_text</a>";
			$result = sw_hash::hash_part($result);
		} else { // 没有 link id
			$result = $whole_match;
		}

		return $result;
	}

	// }}}
	// {{{ public function anchors_inline_callback()

	/**
	 * 解析链接地址的内行式回调
	 *
	 * @param array $matches
	 * @access public
	 * @return string
	 */
	public function anchors_inline_callback($matches)
	{
		$whole_match = $matches[1];
		$link_text   = $this->__markdown->get_span()->run($matches[2]); 
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
	// {{{ public function hard_breaks_callback()

	/**
	 * 解析换行回调 
	 * 
	 * @param array $matches 
	 * @access public
	 * @return string
	 */
	public function hard_breaks_callback($matches)
	{
		return sw_hash::hash_part("<br$this->__empty_element_suffix\n");
	}

	// }}}
	// {{{ public function autolinks_url_callback()

	/**
	 * 自动转化链接 url 回调 
	 * 
	 * @param array $matches 
	 * @access public
	 * @return string
	 */
	public function autolinks_url_callback($matches)
	{
		$url = $this->_encode_attribute($matches[1]);
		$link = "<a href=\"$url\">$url</a>";
		return sw_hash::hash_part($link);	
	}

	// }}}
	// {{{ public function autolinks_email_callback()

	/**
	 * 自动链接 email 的回调 
	 * 
	 * @param string $matches 
	 * @access public
	 * @return string
	 */
	public function autolinks_email_callback($matches)
	{
		$address = $matches[1];
		$link = $this->_encode_email_address($address);
		return sw_hash::hash_part($link);	
	}

	// }}}
	// {{{ protected function _block_quotes_pre_callback()

	/**
	 * 解析引用处理 <pre> 中空格 
	 * 
	 * @param array $matches 
	 * @access protected
	 * @return string
	 */
	protected function _block_quotes_pre_callback($matches)
	{
		$pre = $matches[1];
		$pre = preg_replace('/^  /m', '', $pre);
		return $pre;	
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
		$text = $this->__markdown->get_span()->encode_amps($text);
		$text = str_replace('"', '&quot;', $text);
		return $text;
	}

	// }}}
	// {{{ protected function _encode_email_address()

	/**
	 * 编码 email 地址 
	 * 
	 * @param string $addr 
	 * @access protected
	 * @return string
	 */
	protected function _encode_email_address($addr)
	{
		$addr = "mailto:" . $addr;
		$chars = preg_split('/(?<!^)(?!$)/', $addr);
		$seed = (int)abs(crc32($addr) / strlen($addr));

		foreach ($chars as $key => $char) {
			$ord = ord($char);
			// 忽略不是 ascii 字符
			if ($ord < 128) {
				$r = ($seed * (1 + $key)) % 100;
				if ($r > 90 && $char != '@') {
					// do nothing	
				} elseif ($r < 45) {
					$chars[$key] = '&#x' . dechex($ord) . ';';
				} else {
					$chars[$key] = '&#' . $ord . ';';	
				}
			}
		}

		$addr = implode('', $chars);
		$text = implode('', array_slice($chars, 7)); // 排除 mailto: 字符串
		$addr = "<a href=\"$addr\">$text</a>";

		return $addr;
	}

	// }}}
	// }}}
}
