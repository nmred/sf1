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

namespace swan_test\controller\dispatcher;
use swan\test\sw_test;
use mock\controller\dispatcher\sw_standard_mock;
use swan\controller\dispatcher\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_standard_test
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller
+------------------------------------------------------------------------------
*/
class sw_standard_test extends sw_test
{
	// {{{ members

	/**
	 * __dispatcher
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $__dispatcher = null;

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
		$this->__dispatcher = new sw_standard_mock();
	}

	// }}}
	// {{{ public function test_add_controller_namespace()

	/**
	 * test_add_controller_namespace
	 *
	 * @access public
	 * @return void
	 */
	public function test_add_controller_namespace()
	{
		$rev = $this->__dispatcher->add_controller_namespace('\swan\controller\\', 'bar');
		$this->assertInstanceOf('\mock\controller\dispatcher\sw_standard_mock', $rev);
		$this->assertEquals('\swan\controller', $this->__dispatcher->get_controller_namespace('bar'));
	}

	// }}}
	// {{{ public function test_set_controller_namespace()

	/**
	 * test_set_controller_namespace
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_controller_namespace()
	{
		// 1
		$this->__dispatcher->set_controller_namespace('\swan\controller\\', 'bar');
		$this->assertEquals('\swan\controller', $this->__dispatcher->get_controller_namespace('bar'));

		// 2
		$this->__dispatcher->set_controller_namespace(array('bar' => '\swan\controller\\', 'foo' => '\swan\controller\\'));
		$this->assertEquals('\swan\controller', $this->__dispatcher->get_controller_namespace('bar'));

		// 3
		try {
			$this->__dispatcher->set_controller_namespace(0, 'var');
		} catch (sw_exception $e) {
			$this->assertContains('Controller namespace spec must be either a string or an array', $e->getMessage());
		}
	}

	// }}}
	// {{{ public function test_get_controller_namespace()

	/**
	 * test_get_controller_namespace
	 *
	 * @access public
	 * @return void
	 */
	public function test_get_controller_namespace()
	{
		$this->assertNull($this->__dispatcher->get_controller_namespace('bar'));
	}

	// }}}
	// {{{ public function test_remove_controller_namespace()

	/**
	 * test_remove_controller_namespace
	 *
	 * @access public
	 * @return void
	 */
	public function test_remove_controller_namespace()
	{
		$rev = $this->__dispatcher->add_controller_namespace('\swan\controller\\', 'bar');
		$this->assertEquals('\swan\controller', $this->__dispatcher->get_controller_namespace('bar'));

		$this->assertTrue($this->__dispatcher->remove_controller_namespace('bar'));
		$this->assertFalse($this->__dispatcher->remove_controller_namespace('bar'));
	}

	// }}}
	// {{{ public function test_format_module_name()

	/**
	 * test_format_module_name
	 *
	 * @access public
	 * @return void
	 */
	public function test_format_module_name()
	{
		$str = 'Test_FORMAT';
		$this->assertEquals('test_format', $this->__dispatcher->format_module_name($str));
	}

	// }}}
	// {{{ public function test_format_controller_name()

	/**
	 * test_format_controller_name
	 *
	 * @access public
	 * @return void
	 */
	public function test_format_controller_name()
	{
		$str = 'Test_FORMAT';
		$this->assertEquals('test_format', $this->__dispatcher->format_controller_name($str));
	}

	// }}}
	// {{{ public function test_format_action_name()

	/**
	 * test_format_action_name
	 *
	 * @access public
	 * @return void
	 */
	public function test_format_action_name()
	{
		$str = 'Test_FORMAT';
		$this->assertEquals('test_format', $this->__dispatcher->format_action_name($str));
	}

	// }}}
	// {{{ public function test_format_class_name()

	/**
	 * test_format_class_name
	 *
	 * @access public
	 * @return void
	 */
	public function test_format_class_name()
	{
		$this->__dispatcher->set_controller_namespace('\swan\controller\ui\web\user', 'user');

		$rev = $this->__dispatcher->format_class_name('user', 'user_list');
		$this->assertEquals('\swan\controller\ui\web\user\sw_user_list', $rev);
	}

	// }}}
	// {{{ public function test_get_controller_class()

	/**
	 * test_get_controller_class
	 *
	 * @access public
	 * @return void
	 */
	public function test_get_controller_class()
	{
		$request = $this->getMock('swan\controller\request\sw_http');
						
		$request->expects($this->any())
				->method('get_controller_name')
				->will($this->returnValue('foo'));

		$request->expects($this->any())
				->method('get_module_name')
				->will($this->returnValue('user'));

		$this->__dispatcher->set_controller_namespace('\swan\controller\ui\web\user', 'user');

		$rev = $this->__dispatcher->get_controller_class($request);
		$this->assertEquals('\swan\controller\ui\web\user\sw_foo', $rev);
		$this->assertEquals('\swan\controller\ui\web\user', $this->__dispatcher->get_dispatch_namespace());

		// 2
		$this->__dispatcher->remove_controller_namespace('user');
		try {
			$rev = $this->__dispatcher->get_controller_class($request);
		} catch (sw_exception $e) {
			$this->assertContains('No default module defined for this application', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_is_valid_module()

	/**
	 * test_is_valid_module 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_valid_module()
	{
		$this->assertFalse($this->__dispatcher->is_valid_module(0));
		$this->assertFalse($this->__dispatcher->is_valid_module('bar'));

		$this->__dispatcher->set_controller_namespace('\swan\controller\ui\web\bar', 'bar');
		$this->assertTrue($this->__dispatcher->is_valid_module('bar'));
	}

	// }}}
	// {{{ public function test_get_action_method()

	/**
	 * test_get_action_method 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_action_method()
	{
		$request = $this->getMock('swan\controller\request\sw_http');
		$this->assertEquals('action_default', $this->__dispatcher->get_action_method($request));
			
		$request = $this->getMock('swan\controller\request\sw_http');
						
		$request->expects($this->any())
				->method('get_action_name')
				->will($this->returnValue('foo'));

		$this->assertEquals('foo', $this->__dispatcher->get_action_method($request));
	}

	// }}}
	// }}}
}
