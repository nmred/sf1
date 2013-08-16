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
use swan\test\sw_test;
use mock\controller\request\sw_http_mock;
use swan\controller\request\exception\sw_exception;

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
	// {{{ public function test_get_and_post_both_in_default_params_source()

	/**
	 * test_get_and_post_both_in_default_params_source 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_and_post_both_in_default_params_source()
	{
		$this->assertEquals(array('_GET', '_POST'), $this->__request->get_param_sources());
	}

	// }}}
	// {{{ public function test_can_set_param_sources()

	/**
	 * test_can_set_param_sources 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_can_set_param_sources()
	{
		$this->__request->set_param_sources(array());
		$this->assertSame(array(), $this->__request->get_param_sources());
		$this->__request->set_param_sources(array('_GET'));
		$this->assertSame(array('_GET'), $this->__request->get_param_sources());
	}

	// }}}
	// {{{ public function test_param_source()

	/**
	 * test_param_source 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_param_source()
	{
		$_GET  = array('foo' => 'bar');
		$_POST = array('foo' => 'baz');
		$this->__request->set_param_sources(array('_POST'));
		$this->assertEquals('baz', $this->__request->get_param('foo'));
	}

	// }}}
	// {{{ public function test_clear_params()

	/**
	 * test_clear_params 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_clear_params()
	{
		$this->__request->set_param('foo', 'bar');
		$this->__request->clear_params();
		$this->assertNull($this->__request->get_param('foo'));	
	}

	// }}}
	// {{{ public function test_set_get_param()

	/**
	 * test_set_get_param 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_get_param()
	{
		$this->__request->set_param('foo', 'bar');
		$this->assertEquals('bar', $this->__request->get_param('foo'));	
	}

	// }}}
	// {{{ public function test_set_get_params()

	/**
	 * test_set_get_params 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_get_params()
	{
		$params = array(
			'foo' => 'bar',
			'boo' => 'bah',
			'fee' => 'fi',
		);
		$this->__request->set_params($params);
		$received = $this->__request->get_params();
		$this->assertSame($params, array_intersect_assoc($params, $received));
	}

	// }}}
	// {{{ public function test_get_set_alias()

	/**
	 * test_get_set_alias 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_set_alias()
	{
		$this->__request->set_alias('controller', 'var1');
		$this->assertEquals('var1', $this->__request->get_alias('controller'));
	}

	// }}}
	// {{{ public function test_get_aliases()

	/**
	 * test_get_aliases 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_aliases()
	{
		$this->__request->set_alias('controller', 'var1');
		$this->__request->set_alias('action', 'var2');
		$this->assertEquals(array('controller' => 'var1', 'action' => 'var2'), $this->__request->get_aliases());
	}

	// }}}
	// {{{ public function test_get_method()

	/**
	 * test_get_method 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_method()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->assertEquals('POST', $this->__request->get_method());

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertEquals('GET', $this->__request->get_method());
	}

	// }}}
	// {{{ public function test_is_post()

	/**
	 * test_is_post 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_post()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->assertTrue($this->__request->is_post());	

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertFalse($this->__request->is_post());	
	}

	// }}}
	// {{{ public function test_is_get()

	/**
	 * test_is_get
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_get()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertTrue($this->__request->is_get());	
		$this->assertFalse($this->__request->is_post());	
	}

	// }}}
	// {{{ public function test_is_put()

	/**
	 * test_is_put
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_put()
	{
		$_SERVER['REQUEST_METHOD'] = 'PUT';
		$this->assertTrue($this->__request->is_put());	
		$this->assertFalse($this->__request->is_get());	
	}

	// }}}
	// {{{ public function test_is_delete()

	/**
	 * test_is_delete
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_delete()
	{
		$_SERVER['REQUEST_METHOD'] = 'DELETE';
		$this->assertTrue($this->__request->is_delete());	
		$this->assertFalse($this->__request->is_get());	
	}

	// }}}
	// {{{ public function test_is_head()

	/**
	 * test_is_head
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_head()
	{
		$_SERVER['REQUEST_METHOD'] = 'HEAD';
		$this->assertTrue($this->__request->is_head());	
		$this->assertFalse($this->__request->is_get());	
	}

	// }}}
	// {{{ public function test_is_options()

	/**
	 * test_is_options
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_options()
	{
		$_SERVER['REQUEST_METHOD'] = 'HEAD';
		$this->assertTrue($this->__request->is_head());	
		$this->assertFalse($this->__request->is_get());	
	}

	// }}}
	// {{{ public function test_is_xml_http_request()

	/**
	 * test_is_xml_http_request 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_xml_http_request()
	{
		$this->assertFalse($this->__request->is_xml_http_request());
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->assertTrue($this->__request->is_xml_http_request());	
	}

	// }}}
	// {{{ public function test_can_detect_flash_request()

	/**
	 * test_can_detect_flash_request 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_can_detect_flash_request()
	{
		$this->assertFalse($this->__request->is_flash_request());
		$_SERVER['HTTP_USER_AGENT'] = 'Shockwave Flash';
		$this->assertTrue($this->__request->is_flash_request());

		$_SERVER['HTTP_USER_AGENT'] = 'Adobe Flash Player 10';
		$this->assertTrue($this->__request->is_flash_request());
	}

	// }}}
	// {{{ public function test_get_header()

	/**
	 * test_get_header 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_header()
	{
		$_SERVER['HTTP_ACCEPT_ENCODING'] = 'UTF-8';
		$_SERVER['HTTP_CONTENT_TYPE']    = 'text/json';

		$this->assertEquals('UTF-8', $this->__request->get_header('Accept-Encoding'));
		$this->assertEquals('text/json', $this->__request->get_header('Content-Type'));

		$this->assertFalse($this->__request->get_header('X-No-Such-Thing'));
	}

	// }}}
	// {{{ public function test_get_client_ip()

	/**
	 * test_get_client_ip 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_client_ip()
	{
		$request = new sw_http_mock();
		
		$_SERVER['HTTP_CLIENT_IP'] = '192.168.1.10';
		$_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.1.11';
		$_SERVER['REMOTE_ADDR'] = '192.168.1.12';

		$this->assertEquals('192.168.1.10', $request->get_client_ip());

		$_SERVER['HTTP_CLIENT_IP'] = null;
		$_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.1.11';
		$_SERVER['REMOTE_ADDR'] = '192.168.1.12';

		$this->assertEquals('192.168.1.11', $request->get_client_ip());

		$_SERVER['HTTP_CLIENT_IP'] = null;
		$_SERVER['HTTP_X_FORWARDED_FOR'] = null;
		$_SERVER['REMOTE_ADDR'] = '192.168.1.12';

		$this->assertEquals('192.168.1.12', $request->get_client_ip());
	}

	// }}}
	// {{{ public function test_get_client_ip_no_proxy_check()

	/**
	 * test_get_client_ip_no_proxy_check 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_client_ip_no_proxy_check()
	{
		$request = new sw_http_mock();
		
		$_SERVER['HTTP_CLIENT_IP'] = '192.168.1.10';
		$_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.1.11';
		$_SERVER['REMOTE_ADDR'] = '192.168.1.12';

		$this->assertEquals('192.168.1.12', $request->get_client_ip(false));
	}

	// }}}
	// }}}
}
