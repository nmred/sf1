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

namespace swan_test\controller\response;
use swan\test\sw_test;
use mock\controller\response\sw_http_mock;
use swan\controller\response\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_http_test
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller
+------------------------------------------------------------------------------
*/
class sw_http_test extends sw_test
{
	// {{{ members

	/**
	 * __response
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $__response = null;

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
		$this->__response = new sw_http_mock();
		$this->__response->header_sent_throws_exception = false;
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
		unset($this->__response);
	}

	// }}}
	// {{{ public function test_set_header()

	/**
	 * test_set_header
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_header()
	{
		$expected = array(array('name' => 'Content-Type', 'value' => 'text/xml', 'replace' => false));
		$this->__response->set_header('Content-Type', 'text/xml');
		$this->assertSame($expected, $this->__response->get_headers());

		$expected[] = array('name' => 'Content-Type', 'value' => 'text/html', 'replace' => false);
		$this->__response->set_header('Content-Type', 'text/html');
		$this->assertSame($expected, $this->__response->get_headers());

		$expected = array(array('name' => 'Content-Type', 'value' => 'text/plain', 'replace' => true));
		$this->__response->set_header('Content-Type', 'text/plain', true);
		$count = 0;
		foreach ($this->__response->get_headers() as $header) {
			if ('Content-Type' == $header['name']) {
				if ('text/plain' == $header['value']) {
					++$count;
				} else {
					$this->fail('Found header, but incorrect value');
				}
			}
		}

		$this->assertEquals(1, $count);
	}

	// }}}
	// {{{ public function test_no_duplicate_location_header()

	/**
	 * test_no_duplicate_location_header
	 *
	 * @access public
	 * @return void
	 */
	public function test_no_duplicate_location_header()
	{
		$this->__response->set_redirect('http://www.example.com/foo/bar');
		$this->__response->set_redirect('http://www.example.com/bar/baz');
		$headers = $this->__response->get_headers();
		$location = 0;
		foreach ($headers as $header) {
			if ('Location' == $header['name']) {
				++$location;
			}
		}
		$this->assertEquals(1, $location);
	}

	// }}}
	// {{{ public function test_clear_headers()

	/**
	 * test_clear_headers
	 *
	 * @access public
	 * @return void
	 */
	public function test_clear_headers()
	{
		$this->__response->set_header('Content-Type', 'text/xml');
		$headers = $this->__response->get_headers();
		$this->assertEquals(1, count($headers));

		$this->__response->clear_headers();
		$headers = $this->__response->get_headers();
		$this->assertEquals(0, count($headers));
	}

	// }}}
	// {{{ public function test_clear_header()

	/**
	 * test_clear_header
	 *
	 * @access public
	 * @return void
	 */
	public function test_clear_header()
	{
		$this->__response->set_header('Connection', 'keep-alive');
		$original_headers = $this->__response->get_headers();

		$this->__response->clear_header('Connection');
		$updated_headers = $this->__response->get_headers();

		$this->assertFalse($original_headers == $updated_headers);
	}

	// }}}
	// {{{ public function test_set_raw_header()

	/**
	 * test_set_header
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_raw_header()
	{
		$this->__response->set_raw_header('HTTP/1.0 404 Not Found');
		$headers = $this->__response->get_raw_headers();
		$this->assertContains('HTTP/1.0 404 Not Found', $headers);
	}

	// }}}
	// {{{ public function test_clear_raw_headers()

	/**
	 * test_clear_raw_headers
	 *
	 * @access public
	 * @return void
	 */
	public function test_clear_raw_headers()
	{
		$this->__response->set_raw_header('HTTP/1.0 404 Not Found');
		$headers = $this->__response->get_raw_headers();
		$this->assertContains('HTTP/1.0 404 Not Found', $headers);

		$this->__response->clear_raw_headers();
		$headers = $this->__response->get_raw_headers();
		$this->assertTrue(empty($headers));
	}

	// }}}
	// {{{ public function test_clear_raw_header()

	/**
	 * test_clear_raw_header
	 *
	 * @access public
	 * @return void
	 */
	public function test_clear_raw_header()
	{
		$this->__response->set_raw_header('HTTP/1.0 404 Not Found');
		$this->__response->set_raw_header('HTTP/1.0 401 Unauthorized');
		$original_headers_raw = $this->__response->get_raw_headers();

		$this->__response->clear_raw_header('HTTP/1.0 404 Not Found');
		$updated_headers = $this->__response->get_raw_headers();

		$this->assertFalse($original_headers_raw == $updated_headers);
	}

	// }}}
	// {{{ public function test_clear_all_headers()

	/**
	 * test_clear_all_headers
	 *
	 * @access public
	 * @return void
	 */
	public function test_clear_all_headers()
	{
		$this->__response->set_raw_header('HTTP/1.0 404 Not Found');
		$this->__response->set_header('Content-Type', 'text/xml');

		$headers = $this->__response->get_headers();
		$this->assertFalse(empty($headers));

		$headers = $this->__response->get_raw_headers();
		$this->assertFalse(empty($headers));

		$this->__response->clear_all_headers();
		$headers = $this->__response->get_headers();
		$this->assertTrue(empty($headers));
		$headers = $this->__response->get_raw_headers();
		$this->assertTrue(empty($headers));
	}

	// }}}
	// {{{ public function test_set_http_response_code()

	/**
	 * test_set_http_response_code
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_http_response_code()
	{
		$this->assertEquals(200, $this->__response->get_http_response_code());
		$this->__response->set_http_response_code(302);
		$this->assertEquals(302, $this->__response->get_http_response_code());
	}

	// }}}
	// {{{ public function test_set_body()

	/**
	 * test_set_body
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_body()
	{
		$expected = 'content for the response body';
		$this->__response->set_body($expected);
		$this->assertEquals($expected, $this->__response->get_body());

		$expected = 'new content';
		$this->__response->set_body($expected);
		$this->assertEquals($expected, $this->__response->get_body());
	}

	// }}}
	// {{{ public function test_append_body()

	/**
	 * test_append_body
	 *
	 * @access public
	 * @return void
	 */
	public function test_append_body()
	{
		$expected = 'content for the response body';
		$this->__response->set_body($expected);

		$additional = '; and then there was more';
		$this->__response->append_body($additional);
		$this->assertEquals($expected . $additional, $this->__response->get_body());
	}

	// }}}
	// {{{ public function test_append()

	/**
	 * test_append
	 *
	 * @access public
	 * @return void
	 */
	public function test_append()
	{
		$this->__response->append('some', "some content\n");
		$this->__response->append('more', "more content\n");

		$content = $this->__response->get_body(true);
		$this->assertTrue(is_array($content));
		$expected = array(
			'some' => "some content\n",
			'more' => "more content\n",
		);
		$this->assertEquals($expected, $content);
	}

	// }}}
	// {{{ public function test_append_over_write()

	/**
	 * test_append_over_write 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_append_over_write()
	{
		$this->__response->append('some', "some content\n");
		$this->__response->append('some', "more content\n");

		$content = $this->__response->get_body(true);
		$this->assertTrue(is_array($content));
		$expected = array(
			'some' => "more content\n",
		);
		$this->assertEquals($expected, $content);
	}

	// }}}
	// {{{ public function test_prepend()

	/**
	 * test_prepend 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_prepend()
	{
		$this->__response->prepend('some', "some content\n");
		$this->__response->prepend('more', "more content\n");
		
		$content = $this->__response->get_body(true);
		$this->assertTrue(is_array($content));
		$expected = array(
			'more' => "more content\n",
			'some' => "some content\n",
		);
		$this->assertEquals($expected, $content);
	}

	// }}}
	// {{{ public function test_prepend_over_write()

	/**
	 * test_prepend_over_write 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_prepend_over_write()
	{
		$this->__response->prepend('some', "some content\n");
		$this->__response->prepend('some', "more content\n");

		$content = $this->__response->get_body(true);
		$this->assertTrue(is_array($content));
		$expected = array(
			'some' => "more content\n",
		);
		$this->assertEquals($expected, $content);
	}

	// }}}
	// {{{ public function test_insert()

	/**
	 * test_insert 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_insert()
	{
		$this->__response->append('some', "some content\n");
		$this->__response->append('more', "more content\n");
		$this->__response->insert('foobar', "foobar content\n", 'some');

		$content = $this->__response->get_body(true);
		$this->assertTrue(is_array($content));
		$expected = array(
			'some'   => "some content\n",
			'foobar' => "foobar content\n",
			'more'   => "more content\n",
		);
		$this->assertSame($expected, $content);
	}

	// }}}
	// {{{ public function test_insert_before()

	/**
	 * test_insert_before 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_insert_before()
	{
		$this->__response->append('some', "some content\n");
		$this->__response->append('more', "more content\n");
		$this->__response->insert('foobar', "foobar content\n", 'some', true);

		$content = $this->__response->get_body(true);
		$this->assertTrue(is_array($content));
		$expected = array(
			'foobar' => "foobar content\n",
			'some'   => "some content\n",
			'more'   => "more content\n",
		);
		$this->assertSame($expected, $content);
	}

	// }}}
	// {{{ public function test_render_exceptions()

	/**
	 * test_render_exceptions
	 *
	 * @access public
	 * @return void
	 */
	public function test_render_exceptions()
	{
		$this->assertFalse($this->__response->render_exceptions());
		$this->assertTrue($this->__response->render_exceptions(true));
		$this->assertTrue($this->__response->render_exceptions());
		$this->assertFalse($this->__response->render_exceptions(false));
		$this->assertFalse($this->__response->render_exceptions());
	}

	// }}}
	// {{{ public function test_get_exception()

	/**
	 * test_get_exception
	 *
	 * @access public
	 * @return void
	 */
	public function test_get_exception()
	{
		$e = new \Exception('Test');
		$this->__response->set_exception($e);

		$test = $this->__response->get_exception();
		$found = false;
		foreach ($test as $t) {
			if ($t == $e) {
				$found = true;
			}
		}

		$this->assertTrue($found);
	}

	// }}}
	// {{{ public function test_has_exception_of_type()

	/**
	 * test_has_exception_of_type 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_has_exception_of_type()
	{
		$this->assertFalse($this->__response->has_exception_of_type('swan\controller\response\exception\sw_exception'));
		$this->__response->set_exception(new sw_exception());
		$this->assertTrue($this->__response->has_exception_of_type('swan\controller\response\exception\sw_exception'));
	}

	// }}}
	// {{{ public function test_has_exception_of_message()

	/**
	 * test_has_exception_of_message 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_has_exception_of_message()
	{
		$this->assertFalse($this->__response->has_exception_of_message('foobar'));
		$this->__response->set_exception(new sw_exception('foobar'));
		$this->assertTrue($this->__response->has_exception_of_message('foobar'));
	}

	// }}}
	// {{{ public function test_has_exception_of_code()

	/**
	 * test_has_exception_of_code 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_has_exception_of_code()
	{
		$this->assertFalse($this->__response->has_exception_of_code(200));
		$this->__response->set_exception(new sw_exception('foobar', 200));
		$this->assertTrue($this->__response->has_exception_of_code(200));
			
	}

	// }}}
	// {{{ public function test_get_exception_by_type()

	/**
	 * test_get_exception_by_type 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_exception_by_type()
	{
		$this->assertFalse($this->__response->get_exception_by_type('swan\controller\response\exception\sw_exception'));
		$this->__response->set_exception(new sw_exception());
		$exceptions = $this->__response->get_exception_by_type('swan\controller\response\exception\sw_exception');
		$this->assertTrue(0 < count($exceptions));
		$this->assertTrue($exceptions[0] instanceof \swan\controller\response\exception\sw_exception);
	}

	// }}}
	// {{{ public function test_get_exception_by_message()

	/**
	 * test_get_exception_by_message 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_exception_by_message()
	{
		$this->assertFalse($this->__response->get_exception_by_message('foobar'));
		$this->__response->set_exception(new sw_exception('foobar'));
		$exceptions = $this->__response->get_exception_by_message('foobar');
		$this->assertTrue(0 < count($exceptions));
		$this->assertEquals('foobar', $exceptions[0]->getMessage());
	}

	// }}}
	// {{{ public function test_get_exception_by_code()

	/**
	 * test_get_exception_by_code 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_exception_by_code()
	{
		$this->assertFalse($this->__response->get_exception_by_code(200));
		$this->__response->set_exception(new sw_exception('foobar', 200));
		$exceptions = $this->__response->get_exception_by_code(200);
		$this->assertTrue(0 < count($exceptions));
		$this->assertEquals(200, $exceptions[0]->getCode());
	}

	// }}}
	// }}}
}
