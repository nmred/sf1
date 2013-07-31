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
		$arr = array(
			'var1' => 'val1',
			'var2' => 'val2',
		);
		$rev = $this->__request->get_query();
		$this->assertEquals($arr, $rev);
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
	// {{{ public function test_get_server()

	/**
	 * test_get_server 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_server()
	{
		if (isset($_SERVER['REQUEST_MOTHED'])) {
			$this->assertEquals($_SERVER['REQUEST_MOTHED'], $this->__request->get_server('REQUEST_MOTHED'));	
		}

		$this->assertEquals('foo', $this->__request->get_server('BAR', 'foo'));
		$this->assertEquals($_SERVER, $this->__request->get_server());
	}

	// }}}
	// {{{ public function test_get_env()

	/**
	 * test_get_env 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_env()
	{
		if (isset($_ENV['PATH'])) {
			$this->assertEquals($_ENV['PATH'], $this->__request->get_env('PATH'));	
		}

		$this->assertEquals('foo', $this->__request->get_env('BAR', 'foo'));
		$this->assertEquals($_ENV, $this->__request->get_env());
	}

	// }}}
	// {{{ public function test_setget_request_uri()

	/**
	 * test_setget_request_uri 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_setget_request_uri()
	{
		$_SERVER['REQUEST_URI'] = '/mycontroller/myaction?foo=bar';
		$request = new sw_http_mock();
		$this->assertEquals('/mycontroller/myaction?foo=bar', $request->get_request_uri());	
	}

	// }}}
	// {{{ public function test_get_schema()

	/**
	 * test_get_schema 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_schema()
	{
		$this->assertEquals('http', $this->__request->get_schema());
		$_SERVER['HTTPS'] = 'on';	
		$this->assertEquals('https', $this->__request->get_schema());
	}

	// }}}
	// {{{ public function test_get_http_host()

	/**
	 * test_get_http_host 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_http_host()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$this->assertEquals('localhost', $this->__request->get_http_host());

		$_SERVER = array();
		$_SERVER['SERVER_NAME'] = 'localhost';
		$_SERVER['SERVER_PORT'] = 80;
		$this->assertEquals('localhost', $this->__request->get_http_host());
	}

	// }}}
	// {{{ public function test_set_base_url()

	/**
	 * test_set_base_url 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_base_url()
	{
		$this->__request->set_base_url('/news');
		$this->assertEquals('/news', $this->__request->get_base_url());	
	}

	// }}}
	// {{{ public function test_set_base_url_using_php_self()

	/**
	 * test_set_base_url_using_php_self 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_base_url_using_php_self()
	{
		$_SERVER['REQUEST_URI']     = '/index.php/news/3?var1=val1&var2=val2';
		$_SERVER['SCRIPT_NAME']	    = '/home.php';
		$_SERVER['PHP_SELF']        = '/index.php/news/3';
		$_SERVER['SCRIPT_FILENAME'] = '/var/web/html/index.php';
		$_GET = array(
			'var1' => 'var1',
			'var2' => 'var2',
		);
		
		$request = new sw_http_mock();
		$this->assertEquals('/index.php', $request->get_base_url());
	}

	// }}}
	// {{{ public function test_set_base_url_using_origscript_name()

	/**
	 * test_set_base_url_using_origscript_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_base_url_using_origscript_name()
	{
		$_SERVER['REQUEST_URI']      = '/index.php/news/3?var1=val1&var2=val2';		
		$_SERVER['SCRIPT_NAME']      = '/home.php';
		$_SERVER['PHP_SELF']         = '/home.php';
		$_SERVER['ORIG_SCRIPT_NAME'] = '/index.php';
		$_SERVER['SCRIPT_FILENAME']  = '/var/web/html/index.php';
		$_GET = array(
			'var1' => 'var1',
			'var2' => 'var2',
		);
		$request = new sw_http_mock();
		$this->assertEquals('/index.php', $request->get_base_url());
	}

	// }}}
	// {{{ public function test_set_base_url_using_request_uri()

	/**
	 * test_set_base_url_using_request_uri 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_base_url_using_request_uri()
	{
		$_SERVER['REQUEST_URI']      = '/index.php/news/3?var1=val1&var2=val2';		
		$_SERVER['PHP_SELF']         = '/index.php';
		$_SERVER['SCRIPT_FILENAME']  = '/var/web/html/index.php';
		$_GET = array(
			'var1' => 'var1',
			'var2' => 'var2',
		);
		$request = new sw_http_mock();
		$this->assertEquals('/index.php', $request->get_base_url());
	}

	// }}}
	// {{{ public function test_get_set_base_path()

	/**
	 * test_get_set_base_path 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_set_base_path()
	{
		$this->__request->set_base_path('/news');
		$this->assertEquals('/news', $this->__request->get_base_path());
	}

	// }}}
	// {{{ public function test_base_path_auto_discovery()

	/**
	 * test_base_path_auto_discovery 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_base_path_auto_discovery()
	{
		$_SERVER['REQUEST_URI']     = '/html/index.php/news/3?var1=var1&var2=var2';
		$_SERVER['PHP_SELF']        = '/html/index.php/news/3';
		$_SERVER['SCRIPT_FILENAME'] = '/var/web/html/index.php';
		$_GET = array(
			'var1' => 'var1',
			'var2' => 'var2',
		);

		$request = new sw_http_mock();
		$this->assertEquals('/html', $request->get_base_path());
	}

	// }}}
	// {{{ public function test_base_path_auto_discovery_with_php_file()

	/**
	 * test_base_path_auto_discovery_with_php_file 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_base_path_auto_discovery_with_php_file()
	{
		$_SERVER['REQUEST_URI']     = '/dir/action';
		$_SERVER['PHP_SELF']        = '/dir/index.php';
		$_SERVER['SCRIPT_FILENAME']	= '/var/web/dir/index.php';
		$request = new sw_http_mock();

		$this->assertEquals('/dir', $request->get_base_path());
	}

	// }}}
	// {{{ public function test_set_get_path_info()

	/**
	 * test_set_get_path_info 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_get_path_info()
	{
		$this->__request->set_pathinfo('/archives/past/4');
		$this->assertEquals('/archives/past/4', $this->__request->get_pathinfo());
	}

	// }}}get_pathinfo
	// {{{ public function test_pathinfo_needing_base_url()

	public function test_pathinfo_needing_base_url()
	{
		$request = new sw_http_mock('http://localhost/test/index.php/ctrl-name/act-name');
	}

	// }}}
	// }}}
}
