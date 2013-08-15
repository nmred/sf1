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

namespace swan_test\controller;
use lib\test\sw_test;
use lib\controller\sw_controller;
use lib\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_controller_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_controller_test extends sw_test
{
	// {{{ members

	/**
	 * controller 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__controller = null;

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
		$this->__controller = sw_controller::get_instance(); 
	}

	// }}}
	// {{{ public function test_throw_exceptions()

	/**
	 * test_throw_exceptions 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_throw_exceptions()
	{
		$this->assertFalse($this->__controller->throw_exceptions());	
		$rev = $this->__controller->throw_exceptions(true);
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);
		$this->assertTrue($this->__controller->throw_exceptions());
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
		$rev = $this->__controller->add_controller_namespace('\lib\ui\web\user', 'user');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
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
		$namespace = $this->__controller->get_controller_namespace('user');
		$this->assertEquals('\lib\ui\web\user', $namespace);	
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
		$rev = $this->__controller->set_controller_namespace('\lib\ui\web\admin', 'user');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
		$namespace = $this->__controller->get_controller_namespace('user');
		$this->assertEquals('\lib\ui\web\admin', $namespace);	
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
		$rev = $this->__controller->remove_controller_namespace('user');		
		$this->assertTrue($rev);	

		$namespace = $this->__controller->get_controller_namespace('user');
		$this->assertNull($namespace);
	}

	// }}}
	// {{{ public function test_set_default_controller()

	/**
	 * test_set_default_controller 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_default_controller()
	{
		$rev = $this->__controller->set_default_controller('default');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
	}

	// }}}
	// {{{ public function test_get_default_controller()

	/**
	 * test_get_default_controller 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_default_controller()
	{
		$this->assertEquals('default', $this->__controller->get_default_controller());
	}

	// }}}
	// {{{ public function test_set_default_module()

	/**
	 * test_set_default_module 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_default_module()
	{
		$rev = $this->__controller->set_default_module('default');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
	}

	// }}}
	// {{{ public function test_get_default_module()

	/**
	 * test_get_default_module 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_default_module()
	{
		$this->assertEquals('default', $this->__controller->get_default_module());
	}

	// }}}
	// {{{ public function test_set_default_action()

	/**
	 * test_set_default_action 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_default_action()
	{
		$rev = $this->__controller->set_default_action('default');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
	}

	// }}}
	// {{{ public function test_get_default_action()

	/**
	 * test_get_default_action 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_default_action()
	{
		$this->assertEquals('default', $this->__controller->get_default_action());
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
		try {
			$this->__controller->set_request(array());	
		} catch (sw_exception $e) {
			$this->assertContains('Invalid request class', $e->getMessage());	
		}

		$rev = $this->__controller->set_request('sw_http');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
		$request = $this->__controller->get_request();
		$this->assertInstanceOf('\lib\controller\request\sw_abstract', $request);

		$request  = $this->getMockForAbstractClass('\lib\controller\request\sw_abstract');
		$this->__controller->set_request($request);
		$request = $this->__controller->get_request();
		$this->assertInstanceOf('\lib\controller\request\sw_abstract', $request);
	}

	// }}}
	// {{{ public function test_set_router()

	/**
	 * test_set_router 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_router()
	{
		try {
			$this->__controller->set_router(array());	
		} catch (sw_exception $e) {
			$this->assertContains('Invalid router class', $e->getMessage());	
		}

		$rev = $this->__controller->set_router('sw_router');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
		$router = $this->__controller->get_router();
		$this->assertInstanceOf('\lib\controller\router\sw_abstract', $router);

		$router  = $this->getMockForAbstractClass('\lib\controller\router\sw_abstract');
		$this->__controller->set_router($router);
		$router = $this->__controller->get_router();
		$this->assertInstanceOf('\lib\controller\router\sw_abstract', $router);
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
		try {
			$this->__controller->set_base_url();	
		} catch (sw_exception $e) {
			$this->assertContains('base must be a string', $e->getMessage());	
		}

		$rev = $this->__controller->set_base_url('base_url');	
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
		$this->assertEquals('base_url', $this->__controller->get_base_url());
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
		try {
			$this->__controller->set_response(array());	
		} catch (sw_exception $e) {
			$this->assertContains('Invalid response class', $e->getMessage());	
		}

		$rev = $this->__controller->set_response('sw_http');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);	
		$response = $this->__controller->get_response();
		$this->assertInstanceOf('\lib\controller\response\sw_abstract', $response);

		$response  = $this->getMockForAbstractClass('\lib\controller\response\sw_abstract');
		$this->__controller->set_response($response);
		$response = $this->__controller->get_response();
		$this->assertInstanceOf('\lib\controller\response\sw_abstract', $response);
	}

	// }}}
	// {{{ public function test_set_param()

	/**
	 * test_set_params
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_param()
	{
		$rev = $this->__controller->set_param('bar', 'foo');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);

		$this->assertEquals('foo', $this->__controller->get_param('bar'));
	}

	// }}}
	// {{{ public function test_set_params()

	/**
	 * test_set_params
	 *
	 * @access public
	 * @return void
	 */
	public function test_set_params()
	{
		$array = array('bar' => 'foo', 'baz' => 'var');
		$rev = $this->__controller->set_params($array);
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);

		$this->assertSame($array, $this->__controller->get_params());
	}

	// }}}
	// {{{ public function test_get_param()

	/**
	 * test_get_param
	 *
	 * @access public
	 * @return void
	 */
	public function test_get_param()
	{
		$this->__controller->clear_params();
		$this->assertNull($this->__controller->get_param('bar'));
	}

	// }}}
	// {{{ public funciton test_get_params()

	/**
	 * test_get_params
	 *
	 * @access public
	 * @return void
	 */
	public function test_get_params()
	{
		$this->__controller->clear_params();
		$this->assertSame(array(), $this->__controller->get_params());
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
		// 1
		$array = array('bar' => 'foo', 'baz' => 'var');
		$this->__controller->set_params($array);

		$rev = $this->__controller->clear_params();

		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);
		$this->assertSame(array(), $this->__controller->get_params());

		// 2
		$array = array('bar' => 'foo', 'baz' => 'var');
		$this->__controller->set_params($array);
		$rev = $this->__controller->clear_params('bar');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);
		$this->assertSame(array('baz' => 'var'), $this->__controller->get_params());
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
		$plugin = $this->getMockForAbstractClass('\lib\controller\plugin\sw_abstract', array(), 'test_plugin');	

		$rev = $this->__controller->register_plugin($plugin);
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);

		$this->assertTrue($this->__controller->has_plugin('test_plugin'));
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
		$rev = $this->__controller->unregister_plugin('test_plugin');
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);
		$this->assertFalse($this->__controller->has_plugin('test_plugin'));
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
		$plugin = $this->getMockForAbstractClass('\lib\controller\plugin\sw_abstract', array(), 'test_plugin');	

		$this->__controller->register_plugin($plugin);
		$rev = $this->__controller->get_plugin('test_plugin');
		$this->assertInstanceOf('\lib\controller\plugin\sw_abstract', $rev);
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
		$plugin1 = $this->getMockForAbstractClass('\lib\controller\plugin\sw_abstract', array(), 'test_plugin1');	

		$this->__controller->register_plugin($plugin1);
		$rev = $this->__controller->get_plugins();
		$this->assertEquals(2, count($rev));
	}

	// }}}
	// {{{ public function test_return_response()

	/**
	 * test_return_response 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_return_response()
	{
		$this->assertFalse($this->__controller->return_response());	
		$rev = $this->__controller->return_response(true);
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);
		$this->assertTrue($this->__controller->return_response());
	}

	// }}}
	// }}}
}
