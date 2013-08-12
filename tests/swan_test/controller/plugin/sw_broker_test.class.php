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

namespace swan_test\controller\plugin;
use lib\test\sw_test;
use mock\controller\plugin\sw_broker_mock;
use lib\controller\plugin\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* 插件经纪人测试用例
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller
+------------------------------------------------------------------------------
*/
class sw_broker_test extends sw_test
{
	// {{{ members

	/**
	 * broker
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $__broker = null;

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
		$this->__broker = new sw_broker_mock();
	}

	// }}}
	// {{{ public function test_register_plugin()

	/**
	 * test_register_plugin
	 *
	 * @access public
	 * @return void
	 */
	public function test_register_plugin()
	{
		$plugin = $this->getMockForAbstractClass('\lib\controller\plugin\sw_abstract');

		$rev = $this->__broker->register_plugin($plugin);

		$this->assertInstanceOf('\mock\controller\plugin\sw_broker_mock', $rev);

		try {
			$rev = $this->__broker->register_plugin($plugin);
		} catch (sw_exception $e) {
			$this->assertContains('Plugin already registered', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_unregister_plugin()

	/**
	 * test_unregister_plugin
	 *
	 * @access public
	 * @return void
	 */
	public function test_unregister_plugin()
	{
		$plugin = $this->getMockForAbstractClass('\lib\controller\plugin\sw_abstract');

		$this->__broker->register_plugin($plugin);
		$rev = $this->__broker->unregister_plugin($plugin);
		$this->assertInstanceOf('\mock\controller\plugin\sw_broker_mock', $rev);

		try {
			$rev = $this->__broker->register_plugin($plugin);
		} catch (sw_exception $e) {
			$this->assertContains('Plugin never registered.', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_has_plugin()

	/**
	 * test_has_plugin 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_has_plugin()
	{	
		$this->markTestSkipped();
	}

	// }}}
	// {{{ public function test_get_plugin()

	/**
	 * test_get_plugin 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_plugin()
	{	
		$this->markTestSkipped();
	}

	// }}}
	// {{{ public function test_get_plugins()

	/**
	 * test_get_plugins 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_plugins()
	{
		$rev = $this->__broker->get_plugins();	
		$this->assertSame(array(), $rev);

		$plugin = $this->getMockForAbstractClass('\lib\controller\plugin\sw_abstract');
		$rev = $this->__broker->register_plugin($plugin);
		$this->assertSame(array($plugin), $this->__broker->get_plugins());
	}

	// }}}
	// {{{ public function test_set_request()

	/**
	 * test_set_request
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_request()
	{
		$request = $this->getMock('\lib\controller\request\sw_http');

		$rev = $this->__broker->set_request($request);
		$this->assertInstanceOf('\mock\controller\plugin\sw_broker_mock', $rev);

		$request = $this->__broker->get_request();
		$this->assertInstanceOf('\lib\controller\request\sw_http', $request);
	}

	// }}}
	// {{{ public function test_set_response()

	/**
	 * test_set_response
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_response()
	{
		$response = $this->getMock('\lib\controller\response\sw_http');

		$rev = $this->__broker->set_response($response);
		$this->assertInstanceOf('\mock\controller\plugin\sw_broker_mock', $rev);

		$response = $this->__broker->get_response();
		$this->assertInstanceOf('\lib\controller\response\sw_http', $response);
	}

	// }}}
	// {{{ public function test_route_startup()

	/**
	 * test_route_startup
	 *
	 * @access public
	 * @return void
	 */
	public function test_route_startup()
	{
		$request = $this->getMock('\lib\controller\request\sw_http');

		$this->__broker->route_startup($request);
	}

	// }}}
	// {{{ public function test_route_shutdown()

	/**
	 * test_route_shutdown
	 *
	 * @access public
	 * @return void
	 */
	public function test_route_shutdown()
	{
		$request = $this->getMock('\lib\controller\request\sw_http');

		$this->__broker->route_shutdown($request);
	}

	// }}}
	// {{{ public function test_dispatch_loop_startup()

	/**
	 * test_dispatch_loop_startup
	 *
	 * @access public
	 * @return void
	 */
	public function test_dispatch_loop_startup()
	{
		$request = $this->getMock('\lib\controller\request\sw_http');

		$this->__broker->dispatch_loop_startup($request);
	}

	// }}}
	// {{{ public function test_pre_dispatch()

	/**
	 * test_pre_dispatch
	 *
	 * @access public
	 * @return void
	 */
	public function test_pre_dispatch()
	{
		$request = $this->getMock('\lib\controller\request\sw_http');

		$this->__broker->pre_dispatch($request);
	}

	// }}}
	// {{{ public function test_post_dispatch()

	/**
	 * test_post_dispatch
	 *
	 * @access public
	 * @return void
	 */
	public function test_post_dispatch()
	{
		$request = $this->getMock('\lib\controller\request\sw_http');

		$this->__broker->post_dispatch($request);
	}

	// }}}
	// {{{ public function test_dispatch_loop_shutdown

	/**
	 * test_dispatch_loop_shutdown
	 *
	 * @access public
	 * @return void
	 */
	public function test_dispatch_loop_shutdown()
	{
		$this->__broker->dispatch_loop_shutdown();
	}

	// }}}
	// }}}
}
