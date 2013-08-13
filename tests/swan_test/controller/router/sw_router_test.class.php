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

namespace swan_test\controller\router;
use lib\test\sw_test;
use mock\controller\router\sw_router_mock;
use lib\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_router_test
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller
+------------------------------------------------------------------------------
*/
class sw_router_test extends sw_test
{
	// {{{ members

	/**
	 * router
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $__router = null;

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
		$this->__router = new sw_router_mock();

		$request = $this->getMock('\lib\controller\request\sw_http');
		$controller = \lib\controller\sw_controller::get_instance();
		$controller->set_request($request);
		$this->__router->set_controller($controller);
	}

	// }}}
	// {{{ public function test_add_route()

	/**
	 * test_add_route 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_route()
	{
		$route = $this->getMockForAbstractClass('\lib\controller\router\route\sw_abstract');
		
		$route->expects($this->any())
			  ->method('set_request')
			  ->will($this->returnValue($route));

		$rev = $this->__router->add_route('bar', $route);
		$this->assertInstanceOf('\mock\controller\router\sw_router_mock', $rev);
	}

	// }}}
	// {{{ public function test_add_routes()
	
	/**
	 * test_add_routes 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_routes()
	{
		$route = $this->getMockForAbstractClass('\lib\controller\router\route\sw_abstract');
		
		$route->expects($this->any())
			  ->method('set_request')
			  ->will($this->returnValue($route));

		$routes = array('bar' => $route, 'foo' => $route);
		$rev = $this->__router->add_routes($routes);
		$this->assertInstanceOf('\mock\controller\router\sw_router_mock', $rev);
	}
	 
	// }}}
	// {{{ public function test_remove_route()

	/**
	 * test_remove_route 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_remove_route()
	{
		try {
			$rev = $this->__router->remove_route('bar');
		} catch (sw_exception $e) {
			$this->assertContains('Route ', $e->getMessage());
		}
		$this->test_add_route();
			
		$rev = $this->__router->remove_route('bar');
		$this->assertInstanceOf('\mock\controller\router\sw_router_mock', $rev);
	}

	// }}}
	// {{{ public function test_has_route()

	/**
	 * test_has_route 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_has_route()
	{
		$route = $this->getMockForAbstractClass('\lib\controller\router\route\sw_abstract');
		
		$route->expects($this->any())
			  ->method('set_request')
			  ->will($this->returnValue($route));

		$this->__router->add_route('bar', $route);
		
		$this->assertFalse($this->__router->has_route('foo'));
		$this->assertTrue($this->__router->has_route('bar'));
	}

	// }}}
	// {{{ public function test_get_route()

	/**
	 * test_get_route 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_route()
	{
		$route = $this->getMockForAbstractClass('\lib\controller\router\route\sw_abstract');
		
		$route->expects($this->any())
			  ->method('set_request')
			  ->will($this->returnValue($route));

		$this->__router->add_route('bar', $route);
		$rev = $this->__router->get_route('bar');
		$this->assertInstanceOf('\lib\controller\router\route\sw_abstract', $rev);
		
		try {
			$this->__router->get_route('foo');	
		} catch (sw_exception $e) {
			$this->assertContains('Route ', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_get_current_route()

	/**
	 * test_get_current_route 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_current_route()
	{
		try {
			$this->__router->get_current_route();	
		} catch (sw_exception $e) {
			$this->assertContains('Current route is not defined', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_get_current_route_name()

	/**
	 * test_get_current_route_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_current_route_name()
	{
		try {
			$this->__router->get_current_route_name();	
		} catch (sw_exception $e) {
			$this->assertContains('Current route is not defined', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_get_routes()

	/**
	 * test_get_routes 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_routes()
	{
		$route = $this->getMockForAbstractClass('\lib\controller\router\route\sw_abstract');
		
		$route->expects($this->any())
			  ->method('set_request')
			  ->will($this->returnValue($route));

		$routes = array('bar' => $route, 'foo' => $route);
		$rev = $this->__router->add_routes($routes);
		$this->assertInstanceOf('\mock\controller\router\sw_router_mock', $rev);
		
		$this->assertEquals(2, count($this->__router->get_routes()));	
	}

	// }}}
	// }}}
}
