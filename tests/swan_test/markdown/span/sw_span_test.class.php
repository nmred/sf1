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

namespace swan_test\markdown\span;
use swan\test\sw_test;
use swan\exception\sw_exception;
use mock\markdown\span\sw_span_mock;
use swan\markdown\hash\sw_hash;

/**
+------------------------------------------------------------------------------
* sw_span_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_span_test extends sw_test
{
	// {{{ members

	/**
	 * span 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__span = null;

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
		$this->__span = new sw_span_mock(); 
	}

	// }}}
	// {{{ public function test_parse_span()

	/**
	 * test_parse_span 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_parse_span()
	{
		$test = <<<EOD
\*test\!\!\!\*
`printf` 212121

<html>
	<!--dsds-->
	<?ddds?>
	<-p>hello</p>
</html>
EOD;
		$rev = $this->__span->parse_span($test);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals("&#42;test&#33;&#33;&#33;&#42;\n<code>printf</code> 212121\n\n<html>\n\t<!--dsds-->\n\t<?ddds?>\n\t<-p>hello</p>\n</html>", $rev);
	}

	// }}}
	// }}}
}
