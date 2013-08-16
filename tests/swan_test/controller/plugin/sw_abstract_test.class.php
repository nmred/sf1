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
use swan\test\sw_test;
use mock\controller\plugin\sw_abstract_mock;

/**
+------------------------------------------------------------------------------
* sw_abstract_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_abstract_test extends sw_test
{
	// {{{ members

	/**
	 * plugin 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__plugin = null;

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
		$this->__plugin = new sw_abstract_mock();
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
		$request = $this->getMock('\swan\controller\request\sw_http');
		
		$rev = $this->__plugin->set_request($request);	
		$this->assertInstanceOf('\mock\controller\plugin\sw_abstract_mock', $rev);

		$request = $this->__plugin->get_request();
		$this->assertInstanceOf('\swan\controller\request\sw_http', $request);
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
		$response = $this->getMock('\swan\controller\response\sw_http');
		
		$rev = $this->__plugin->set_response($response);	
		$this->assertInstanceOf('\mock\controller\plugin\sw_abstract_mock', $rev);

		$response = $this->__plugin->get_response();
		$this->assertInstanceOf('\swan\controller\response\sw_http', $response);
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
		$request = $this->getMock('\swan\controller\request\sw_http');

		$this->__plugin->route_startup($request);
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
		$request = $this->getMock('\swan\controller\request\sw_http');

		$this->__plugin->route_shutdown($request);
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
		$request = $this->getMock('\swan\controller\request\sw_http');

		$this->__plugin->dispatch_loop_startup($request);
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
		$request = $this->getMock('\swan\controller\request\sw_http');

		$this->__plugin->pre_dispatch($request);
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
		$request = $this->getMock('\swan\controller\request\sw_http');

		$this->__plugin->post_dispatch($request);
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
		$this->__plugin->dispatch_loop_shutdown();
	}

	// }}}
	// }}}
}
