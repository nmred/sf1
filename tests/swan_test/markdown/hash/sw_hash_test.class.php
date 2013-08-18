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

namespace swan_test\markdown\hash;
use swan\test\sw_test;
use swan\exception\sw_exception;
use swan\markdown\hash\sw_hash;

/**
+------------------------------------------------------------------------------
* sw_hash_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_hash_test extends sw_test
{
	// {{{ members
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
	}

	// }}}
	// {{{ public function test_unhash()

	/**
	 * test_unhash 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_unhash()
	{
		$str = sw_hash::hash_part("test");
		$this->assertEquals('test', sw_hash::unhash($str));
	}

	// }}}
	// {{{ public function test_hash_part()

	/**
	 * test_hash_part 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_hash_part()
	{
		$rev = sw_hash::hash_part('test_1');
		$text = 'test' . $rev;
		$rev = sw_hash::hash_part($text);

		$text = sw_hash::unhash($rev);
		$this->assertEquals('testtest_1', $text);
	}

	// }}}
	// }}}
}
