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
	// {{{ public function test__parse_span()

	/**
	 * test__parse_span 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__parse_span()
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
		$rev = $this->__span->encode_attribute($text);

		$this->assertEquals('http://www.example.com?xxx=xxx&amp&quot;sddsds&lt;br>&quot;', $rev);
	}

	// }}}
	// {{{ public function test__encode_amps_and_angles()

	/**
	 * test__encode_amps_and_angles 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__encode_amps_and_angles()
	{
		$text = 'http://www.example.com?xxx=xxx&&amp;"sddsds<br>"';

		$rev = $this->__span->encode_amps_and_angles($text);
		$this->assertEquals('http://www.example.com?xxx=xxx&amp&amp;"sddsds&lt;br>"', $rev);
	}

	// }}}
	// {{{ public function test__do_images()

	/**
	 * test__do_images 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__do_images()
	{
		$url = array('a' => 'http://www.example.com');
		$url_title = array('a' => 'example');

		$this->__span->set_url($url);
		$this->__span->set_url_title($url_title);

		$text = <<<EOD
![img_example][a] text
EOD;
		$rev = $this->__span->do_images($text);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals('<img src="http://www.example.com" alt="img_example" title="example" /> text', $rev);

		$text = <<<EOD
![img_example](<http://www.example.com> "example") text
EOD;
		$rev = $this->__span->do_images($text);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals('<img src="http://www.example.com" alt="img_example" title="example" /> text', $rev);

	}

	// }}}
	// {{{ public function test__do_anchors()

	/**
	 * test__do_anchors 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__do_anchors()
	{
		$url = array('a' => 'http://www.example.com');
		$url_title = array('a' => 'example');

		$this->__span->set_url($url);
		$this->__span->set_url_title($url_title);

		$text = <<<EOD
[a_example] [a] text
EOD;
		$rev = $this->__span->do_anchors($text);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals('<a href="http://www.example.com" title="example">a_example</a> text', $rev);

		$text = <<<EOD
[a_example](<http://www.example.com> "example") text
EOD;
		$rev = $this->__span->do_anchors($text);
		$rev = sw_hash::unhash($rev);
		$this->assertEquals('<a href="http://www.example.com" title="example">a_example</a> text', $rev);

	}

	// }}}
	// {{{ public function test__do_hard_breaks()

	/**
	 * test__do_hard_breaks 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__do_hard_breaks()
	{
		$test = <<<EOD
    
    
EOD;
		$this->assertEquals("<br />", trim(sw_hash::unhash($this->__span->do_hard_breaks($test))));
	}

	// }}}
	// }}}
}