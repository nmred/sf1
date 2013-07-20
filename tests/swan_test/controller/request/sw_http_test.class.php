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

namespace swan_test\controller\request;
use lib\test\sw_test;
use mock\controller\request\sw_http_mock;
use lib\controller\request\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_http_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db 
+------------------------------------------------------------------------------
*/
class sw_http_test extends sw_test
{
	// {{{ members

	/**
	 * __request 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__request = null;

	/**
	 * 用来还原原来的 $_SERVER 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__server = array();

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
		$this->__server = $_SERVER;
		$_GET = array();
		$_POST = array();
		$_SERVER = array(
			'SCRIPT_FILENAME' => __FILE__,
			'PHP_SELF'        => __FILE__,
		);
		$this->__request = new sw_http_mock('http://swanlinux.net/news/3?var1=val1&var2=val2#anchor');
	}

	// }}}
	// {{{ public function tearDown()

	/**
	 * tearDown 
	 * 
	 * @access public
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->__request);
		$_SERVER = $this->__server;
	}

	// }}}
	// {{{ public function test_get()

	/**
	 * test_get 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get()
	{
		$rev = $this->__request->get('test');
		$this->assertNull($rev);

		$this->__request->set_param('test', 2);
		$rev = $this->__request->get('test');
		$this->assertEquals(2, $rev);
		
		// 清除params
		$this->__request->clear_params();
		$_GET['test'] = 2;
		$rev = $this->__request->get('test');
		$this->assertEquals(2, $rev);
		$_GET = null;

		$_POST['test'] = 2;
		$rev = $this->__request->get('test');
		$this->assertEquals(2, $rev);
		$_POST = null;
	}

	// }}}
	// {{{ public function test_set()

	/**
	 * test_set 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set()
	{
		try {
			$this->__request->set('test', 2);
		} catch (sw_exception $e) {
			$this->assertContains('Setting values in superglobals not allowed', $e->getMessage());	
		}	
	}

	// }}}
	// {{{ public function test_isset()

	/**
	 * test_isset 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_isset()
	{
		$rev = $this->__request->has('test');
		$this->assertFalse($rev);

		$this->__request->set_param('test', 2);
		$rev = $this->__request->has('test');
		$this->assertTrue($rev);
	}

	// }}}
	// {{{ public function test_set_query()

	/**
	 * test_set_query 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_query()
	{
		try {
			$this->__request->set_query('test');
		} catch (sw_exception $e) {
			$this->assertContains('Invalid value passed to set_query()', $e->getMessage());	
		}

		$arr = array(
			'test' => 'a',
			'test1' => 'b',
		);
		$_GET = null;
		$this->__request->set_query($arr);
		$rev = $_GET;
		$_GET = null;
		$this->assertEquals($arr, $rev);
	}

	// }}}
	// {{{ public function test_get_query()

	/**
	 * test_get_query 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_query()
	{
		$rev = $this->__request->get_query();
		$this->assertNull($rev);

		$arr = array(
			'test' => 'a',
			'test1' => 'b',
		);
		$_GET = null;
		$this->__request->set_query($arr);
		$rev = $this->__request->get_query('test');
		$_GET = null;
		$this->assertEquals('a', $rev);
	}

	// }}}
	// {{{ public function test_set_post()

	/**
	 * test_set_post 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_post()
	{
		try {
			$this->__request->set_post('test');	
		} catch (sw_exception $e) {
			$this->assertContains('Invalid value passed to set_post()', $e->getMessage());	
		}	

		$arr = array(
			'test' => 'a',
			'test1' => 'b',
		);
		$_POST = null;
		$this->__request->set_post($arr);
		$rev = $_POST;
		$_POST = null;
		$this->assertEquals($arr, $rev);
	}

	// }}}
	// {{{ public function test_get_post()

	/**
	 * test_get_post
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_post()
	{
		$rev = $this->__request->get_post();
		$this->assertSame(array(), $rev);

		$arr = array(
			'test' => 'a',
			'test1' => 'b',
		);
		$_POST = null;
		$this->__request->set_post($arr);
		$rev = $this->__request->get_post('test');
		$_POST = null;
		$this->assertEquals('a', $rev);
	}

	// }}}
	// {{{ public function test_get_cookie()

	/**
	 * test_get_cookie 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_cookie()
	{
		$_COOKIE['foo'] = 'bar';
		$this->assertEquals('bar', $this->__request->get_cookie('foo'));
		$this->assertEquals('baz', $this->__request->get_cookie('FOO', 'baz'));
		$this->assertSame(array('foo' => 'bar'), $this->__request->get_cookie());
	}

	// }}}
	// }}}
}
