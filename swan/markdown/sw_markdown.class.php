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
use swan\markdown\element\sw_element;
use swan\markdown\replace\sw_default;

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
	// }}}
	// {{{ members

	/**
	 * element 对象 
	 * 
	 * @var swan\markdown\element\sw_element
	 * @access protected
	 */
	protected static $__element = null;

	/**
	 * block 对象 
	 * 
	 * @var swan\markdown\block\sw_block
	 * @access protected
	 */
	protected static $__block = null;

	/**
	 * span 对象 
	 * 
	 * @var swan\markdown\span\sw_span
	 * @access protected
	 */
	protected static $__span = null;

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

	/**
	 * 语义替换引擎 
	 * 
	 * @var swan\markdown\replace\sw_abstract
	 * @access protected
	 */
	protected static $__replace = null;

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
		if (!isset(self::$__element)) {
			self::$__element = new sw_element();	
		}
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
		$this->set_url($this->get_default_url());	
		$this->set_url_title($this->get_default_url_title());	
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
		$this->set_url(array());
		$this->set_url_title(array());
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
		$text = $this->_detab($text);

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
		self::$__element->set_default_url($urls);
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
		return self::$__element->get_default_url($key_id);
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
		self::$__element->set_default_url_title($titles);
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
		return self::$__element->get_default_url_title($key_id);
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
		self::$__element->set_url($urls);
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
		return self::$__element->get_url($key_id);
	}

	// }}}
	// {{{ public function set_url_title()

	/**
	 * 设置的参考 URL 地址
	 *
	 * @access public
	 * @param array $titles
	 * @return swan\markdown\sw_markdown
	 */
	public function set_url_title($titles)
	{
		self::$__element->set_url_title($titles);
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
		return self::$__element->get_url_title($key_id);
	}

	// }}}
	// {{{ public function get_element()

	/**
	 * 获取 element 对象 
	 * 
	 * @access public
	 * @return swan\markdown\element\sw_element
	 */
	public function get_element()
	{
		return self::$__element;	
	}

	// }}}
	// {{{ public function get_block()

	/**
	 * 获取 block 对象 
	 * 
	 * @access public
	 * @return swan\markdown\block\sw_block
	 */
	public function get_block()
	{
		if (!isset(self::$__block)) {
			self::$__block = new sw_block($this);	
		}

		return self::$__block;	
	}

	// }}}
	// {{{ public function get_span()

	/**
	 * 获取 span 对象 
	 * 
	 * @access public
	 * @return swan\markdown\span\sw_span
	 */
	public function get_span()
	{
		if (!isset(self::$__span)) {
			self::$__span = new span\sw_span($this);	
		}

		return self::$__span;	
	}

	// }}}
	// {{{ public function get_replace()

	/**
	 * 获取语义替换引擎 
	 * 
	 * @access public
	 * @return swan\markdown\replace\sw_abstract
	 */
	public function get_replace()
	{
		if (!isset(self::$__replace)) {
			$this->set_replace();	
		}

		return self::$__replace;
	}

	// }}}
	// {{{ public function set_replace()

	/**
	 * 设置语义替换引擎 
	 * 
	 * @param null|swan\markdown\replace\sw_abstract $replace 
	 * @access public
	 * @return swan\markdown\sw_markdown
	 */
	public function set_replace($replace = null)
	{
		if (isset($replace) && (swan\markdown\replace\sw_abstract instanceof $replace)) {
			self::$__replace = $replace;
		} else {
			self::$__replace = new sw_default($this);	
		}
		
		return $this;
	}

	// }}}
	// {{{ public function outdent()

	/**
	 * 去处行首 TAB 
	 * 
	 * @param string $text 
	 * @access public
	 * @return string
	 */
	public function outdent($text)
	{
		$pattern = '/^(\t|[ ]{1,' . sw_element::TAB_WIDTH . '})/m';
		return preg_replace($pattern, '', $text);   
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
			$amount = sw_element::TAB_WIDTH - mb_strlen($line, 'UTF-8') % sw_element::TAB_WIDTH;
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
		$less_than_tab = sw_element::TAB_WIDTH - 1;

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
		$url[$link_id] = isset($matches[2]) ? $matches[2] : '';
		$url_title[$link_id] = isset($matches[3]) ? $matches[3] : '';
		$this->set_url($url);
		$this->set_url_title($url_title);
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
		return $this->get_block()->run($text);
	}

	// }}}
	// }}}
}
