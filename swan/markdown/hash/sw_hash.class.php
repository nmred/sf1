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

namespace swan\markdown\hash;
use swan\markdown\hash\exception\sw_exception;

/**
* MarkDown 解析器 hash 堆栈
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_hash
{
	// {{{ consts

	/**
	 * ASCII 编码分隔符
	 */
	const SEPARATOR = "\x1A";

	/**
	 * ASCII 编码分隔符
	 */
	const REG_SEPARATOR = '\x1A';

	// }}}
	// {{{ members

	/**
	 * 存储 html 的 cache
	 *
	 * @var array
	 * @access protected
	 */
	protected static $__html_hashes = array();

	/**
	 * 生成唯一的 hash id
	 *
	 * @var int
	 * @access protected
	 */
	protected static $__unique_id = 0;

	// }}}
	// {{{ functions
	// {{{ public static function init()

	/**
	 * 初始化 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function init()
	{
		self::$__unique_id = 0;
		self::$__html_hashes = array();
	}

	// }}}
	// {{{ public static function unhash()

	/**
	 * 获取 html 从缓存中
	 * 将所有带标签的元素全部解开返回
	 *
	 * @param string $text
	 * @access public
	 * @return string
	 */
	public static function unhash($text)
	{
		return preg_replace_callback('/(.)' . self::REG_SEPARATOR . '[0-9]+\1/', array(__CLASS__, '_unhash_callback'), $text);
	}

	// }}}
	// {{{ protected static function _unhash_callback()

	/**
	 * 解析带标签的字符串回调函数
	 *
	 * @param array $matches
	 * @access protected
	 * @return string
	 */
	protected static function _unhash_callback($matches)
	{
		return self::$__html_hashes[$matches[0]];
	}

	// }}}
	// {{{ public static function hash_part()

	/**
	 * 转化成代表签的字符串
	 *
	 * @param string $text
	 * @param string $boundary
	 * @access protected
	 * @return string
	 */
	public static function hash_part($text, $boundary = 'X')
	{
		// 先将该字符串中存在标签的解析开，防止标签嵌套
		$text = self::unhash($text);

		self::$__unique_id++;
		$key = $boundary . self::SEPARATOR . self::$__unique_id . $boundary;
		self::$__html_hashes[$key] = $text;
		return $key;
	}

	// }}}
	// {{{ public static function hash_block()

	/**
	 * 转化块标记
	 *
	 * @param string $text
	 * @access public
	 * @return string
	 */
	public static function hash_block($text)
	{
		return self::hash_part($text, 'B');
	}

	// }}}
	// }}}
}
