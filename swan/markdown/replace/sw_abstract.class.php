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
	// }}}
}
