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

namespace swan\markdown;
use swan\markdown\exception\sw_exception;
use swan\markdown\hash\sw_hash;
use swan\markdown\block\sw_block;

/**
* MarkDown 解析器
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_markdown
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
	 * 解析 markdown 的动作
	 * 数字表示执行的顺序
	 *
	 * @var array
	 * @access protected
	 */
	protected $__parser_action = array(
		'_strip_link' => 20,
		'_parser_block' => 30,
	);

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

	}

	// }}}
	// {{{ public function setup()

	/**
	 * 解析 markdown 前调用
	 *
	 * @access public
	 * @return void
	 */
	public function setup()
	{
		sw_hash::init();
		$this->__url = $this->get_default_url();
		$this->__url_title = $this->get_default_url_title();
	}

	// }}}
	// {{{ public function teardown()

	/**
	 * 在解析完成后执行
	 *
	 * @access public
	 * @return void
	 */
	public function teardown()
	{
		sw_hash::init();
		$this->__url = array();
		$this->__url_title = array();
	}

	// }}}
	// {{{ public function to_html()

	/**
	 * 转化成 html 代码
	 *
	 * @param string $text
	 * @access public
	 * @return string
	 */
	public function to_html($text)
	{
		$this->setup();

		// 删除 UTF-8 BOM 标记字符
		$text = preg_replace('/^\xEF\xBB\xBF|\x1A/', '', $text);

		// 替换成标准的 Unix 行结束符
		$text = preg_replace('/\r\n?/', "\n", $text);

		// 确保 $text 字符串结尾有2个换行
		$text .= "\n\n";

		// 将所有的 Tab 转化为空格
		$text .= $this->_detab($text);

		// 删除任何只有空格和 tab 组成的行，为了匹配空行的时候用 /\n+/
		$text = preg_replace('/^[ ]+$/m', '', $text);

		// 运行替换程序
		foreach ($this->__parser_action as $method => $priority) {
			$text = $this->$method($text);
		}

		$this->teardown();

		return $text . "\n";
	}

	// }}}
	// {{{ public function set_default_url()

	/**
	 * 设置默认的参考 URL 地址
	 *
	 * @access public
	 * @param array $urls
	 * @return swan\markdown\sw_markdown
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
	 * @return swan\markdown\sw_markdown
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
	 * @return swan\markdown\sw_markdown
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
	 * @return swan\markdown\sw_markdown
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
	// {{{ protected function _detab()

	/**
	 * 将所有的 tab 转化成空格
	 *
	 * @param string $text
	 * @access protected
	 * @return string
	 */
	protected function _detab($text)
	{
		$text = preg_replace_callback('/^.*\t.*$/m', array($this, '_detab_callback'), $text);

		return $text;
	}

	// }}}
	// {{{ protected function _detab_callback()

	/**
	 * 转化 tab 的回调
	 *
	 * @param array $matches
	 * @access protected
	 * @return string
	 */
	protected function _detab_callback($matches)
	{
		$line = $matches[0];
		$blocks = explode("\t", $line);
		$line = $blocks[0];
		unset($blocks[0]);
		foreach ($blocks as $block) {
			$amount = self::TAB_WIDTH - mb_strlen($line, 'UTF-8') % self::TAB_WIDTH;
			$line .= str_repeat(" ", $amount) . $block;
		}

		return $line;
	}

	// }}}
	// {{{ protected function _strip_link()

	/**
	 * 解析 markdown 支持的 URL 连接
	 *
	 * @param string $text
	 * @access protected
	 * @return string
	 */
	protected function _strip_link($text)
	{
		$less_than_tab = self::TAB_WIDTH - 1;

		// 匹配参考式的 url 定义
		$parrent = 
		'/
			^[ ]{0,' . $less_than_tab . '}\[(.+)\][ ]?: # id = $1
				[ ]*
				\n?               # maybe *one* newline
				[ ]*
				<?(\S+?)>?          # url = $2
				[ ]*
				\n?               # maybe one newline
				[ ]*
				(?:
				 (?<=\s)         # lookbehind for whitespace
				 ["(]
				(.*?)           # title = $3
				[")]
				 [ ]*
				)?  # title is optional
				(?:\n+|\Z)
		/xm';

		$text = preg_replace_callback($parrent, array($this, '_strip_link_callback'), $text);

		return $text;
	}

	// }}}
	// {{{ protected function _strip_link_callback()

	/**
	 * 解析参考式的 URL 地址回调
	 *
	 * @param array $matches
	 * @access protected
	 * @return string
	 */
	protected function _strip_link_callback($matches)
	{
		if (!isset($matches[1])) {
			return '';
		}

		$link_id = strtolower($matches[1]);
		$this->__url[$link_id] = isset($matches[2]) ? $matches[2] : '';
		$this->__url_title[$link_id] = isset($matches[3]) ? $matches[3] : '';
		return '';
	}

	// }}}
	// {{{ protected function _parser_block()

	/**
	 * 解析块元素
	 *
	 * @param string $text
	 * @access protected
	 * @return string
	 */
	protected function _parser_block($text)
	{
		$block = new sw_block();
		return $block->run($text);
	}

	// }}}
	// }}}
}
