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
	 * element 对象 
	 * 
	 * @var swan\markdown\element\sw_element
	 * @access protected
	 */
	protected $__element = null;

	/**
	 * 行解析器 
	 * 
	 * @var swan\markdown\span\sw_span
	 * @access protected
	 */
	protected $__span = null;

	/**
	 * 段落块解析器 
	 * 
	 * @var swan\markdown\block\sw_block
	 * @access protected
	 */
	protected $__block = null;

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
		$this->__element  = $markdown->get_element();
		$this->__block    = $markdown->get_block();
		$this->__span     = $markdown->get_span();
	}

	// }}}
	// }}}
}
