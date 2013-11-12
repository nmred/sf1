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

namespace swan_test\markdown\replace;
use swan\test\sw_test;
use swan\exception\sw_exception;
use mock\markdown\replace\sw_replace_mock;
use swan\markdown\hash\sw_hash;
use swan\markdown\sw_markdown;

/**
+------------------------------------------------------------------------------
* sw_replace_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_replace_test extends sw_test
{
	// {{{ members

	/**
	 * replace 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__replace = null;

	// }}}
	// {{{ functions
	// {{{ public function setUp()

	/**
	 * setUp 
	 * 
	 * @access public
	 * @return void
	 */
	public function setUp()
	{
		$markdown = new sw_markdown();
		$this->__replace = new sw_replace_mock($markdown); 
	}

	// }}}
	// {{{ public function test__encode_attribute()

	/**
	 * test__encode_attribute 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__encode_attribute()
	{
		$text = 'http://www.example.com?xxx=xxx&"sddsds<br>"';
		$rev = $this->__replace->encode_attribute($text);

		$this->assertEquals('http://www.example.com?xxx=xxx&amp;&quot;sddsds&lt;br>&quot;', $rev);
	}

	// }}}
	// }}}
}
