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

namespace swan_test\markdown;
use swan\test\sw_test;
use swan\exception\sw_exception;
use swan\markdown\sw_markdown;

/**
+------------------------------------------------------------------------------
* sw_view_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_markdown_test extends sw_test
{
	// {{{ members

	/**
	 * markdown 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__markdown = null;

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
		$this->__markdown = new sw_markdown(); 
	}

	// }}}
	// {{{ public function test_default_url()

	/**
	 * test_default_url
	 * 
	 * @access public
	 * @return void
	 */
	public function test_default_url()
	{
		$expects = array(
			'1' => 'http:://example.com',
			'2' => 'http:://example1.com',
		);
		$rev = $this->__markdown->set_default_url($expects);
		$this->assertInstanceOf('\swan\markdown\sw_markdown', $rev);
		$this->assertSame($expects, $this->__markdown->get_default_url());

		$this->assertEquals('http:://example1.com', $this->__markdown->get_default_url('2'));
	}

	// }}}
	// {{{ public function test_default_url_title()

	/**
	 * test_default_url_title
	 * 
	 * @access public
	 * @return void
	 */
	public function test_default_url_title()
	{
		$expects = array(
			'1' => 'example',
			'2' => 'example1',
		);
		$rev = $this->__markdown->set_default_url_title($expects);
		$this->assertInstanceOf('\swan\markdown\sw_markdown', $rev);
		$this->assertSame($expects, $this->__markdown->get_default_url_title());

		$this->assertEquals('example1', $this->__markdown->get_default_url_title('2'));
	}

	// }}}
	// {{{ public function test_url()

	/**
	 * test_url
	 * 
	 * @access public
	 * @return void
	 */
	public function test_url()
	{
		$expects = array(
			'1' => 'http:://example.com',
			'2' => 'http:://example1.com',
		);
		$rev = $this->__markdown->set_url($expects);
		$this->assertInstanceOf('\swan\markdown\sw_markdown', $rev);
		$this->assertSame($expects, $this->__markdown->get_url());

		$this->assertEquals('http:://example1.com', $this->__markdown->get_url('2'));
	}

	// }}}
	// {{{ public function test_url_title()

	/**
	 * test_url_title
	 * 
	 * @access public
	 * @return void
	 */
	public function test_url_title()
	{
		$expects = array(
			'1' => 'example',
			'2' => 'example1',
		);
		$rev = $this->__markdown->set_url_title($expects);
		$this->assertInstanceOf('\swan\markdown\sw_markdown', $rev);
		$this->assertSame($expects, $this->__markdown->get_url_title());

		$this->assertEquals('example1', $this->__markdown->get_url_title('2'));
	}

	// }}}
	// {{{ public function test_strip_link()

	/**
	 * test_strip_link 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_strip_link()
	{
		$test = <<<EOD
[id1]: http://www.example.com (test_title) 

EOD;
		$rev = $this->__markdown->to_html($test);	

		$this->assertEquals("\n", $rev);
	}

	// }}}
	// {{{ public function test_get_replace()

	/**
	 * test_get_replace 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_replace()
	{
		$replace = $this->__markdown->get_replace();		
		$this->assertInstanceOf('swan\markdown\replace\sw_default', $replace);
	}

	// }}}
	// }}}
}
