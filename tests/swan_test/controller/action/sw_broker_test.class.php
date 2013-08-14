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

namespace swan_test\controller\action;
use lib\test\sw_test;
use mock\controller\action\sw_broker_mock;
use lib\controller\action\sw_broker;
use lib\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_broker_test  
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

	/**
	 * 动作助手 
	 * 
	 * @var \lib\controller\action\helper\sw_abstract
	 * @access protected
	 */
	protected $__helper = null;

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
		$request  = $this->getMockForAbstractClass('\lib\controller\request\sw_abstract');
		$response = $this->getMockForAbstractClass('\lib\controller\response\sw_abstract');
		$action_controller = $this->getMockForAbstractClass('\lib\controller\sw_action', array($request, $response), 'sw_user_action');

		$this->__broker = new sw_broker_mock($action_controller);
		$this->__helper = $this->getMockForAbstractClass('\lib\controller\action\helper\sw_abstract', array(), 'sw_helper_test');
	}

	// }}}
	// {{{ public function test_add_helper()

	/**
	 * test_add_helper 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_add_helper()
	{
		sw_broker::add_helper($this->__helper);			

		$stack = sw_broker::get_stack();
		$this->assertEquals(1, count($stack));
		$this->assertTrue(isset($stack->helper_test));
	}

	// }}}
	// {{{ public function test_reset_helpers()

	/**
	 * test_reset_helpers 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_reset_helpers()
	{
		sw_broker::add_helper($this->__helper);			

		$stack = sw_broker::get_stack();
		$this->assertEquals(1, count($stack));
		sw_broker::reset_helpers();
		$stack = sw_broker::get_stack();
		$this->assertEquals(0, count($stack));
	}

	// }}}
	// {{{ public function test_get_static_helper()

	/**
	 * test_get_static_helper 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_static_helper()
	{
		sw_broker::add_helper($this->__helper);
		$helper = sw_broker::get_static_helper('helper_test');
		$this->assertInstanceOf('\lib\controller\action\helper\sw_abstract', $helper);
	}

	// }}}
	// {{{ public function test_get_existing_helper()

	/**
	 * test_get_existing_helper 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_existing_helper()
	{
		try {
			sw_broker::get_existing_helper('helper_test');	
		} catch (sw_exception $e) {
			$this->assertContains('Action helper ', $e->getMessage());	
		}

		sw_broker::add_helper($this->__helper);
		$helper = sw_broker::get_existing_helper('helper_test');
		$this->assertInstanceOf('\lib\controller\action\helper\sw_abstract', $helper);
	}

	// }}}
	// {{{ public function test_get_existing_helpers()

	/**
	 * test_get_existing_helpers
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_existing_helpers()
	{
		sw_broker::reset_helpers();
		$helpers = sw_broker::get_existing_helpers();	
		$this->assertEquals(0, count($helpers));

		sw_broker::add_helper($this->__helper);
		$helpers = sw_broker::get_existing_helpers();
		$this->assertInstanceOf('\lib\controller\action\helper\sw_abstract', $helpers['helper_test']);
	}

	// }}}
	// {{{ public function test_has_helper()

	/**
	 * test_has_helper 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_has_helper()
	{
		sw_broker::reset_helpers();
		$this->assertFalse(sw_broker::has_helper('helper_test'));
		sw_broker::add_helper($this->__helper);	
		$this->assertTrue(sw_broker::has_helper('helper_test'));
	}

	// }}}
	// {{{ public function test_remove_helper()

	/**
	 * test_remove_helper 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_remove_helper()
	{
		sw_broker::reset_helpers();
		$this->assertFalse(sw_broker::has_helper('helper_test'));
		$this->assertFalse(sw_broker::remove_helper('helper_test'));
		sw_broker::add_helper($this->__helper);	
		$this->assertTrue(sw_broker::remove_helper('helper_test'));
		$this->assertFalse(sw_broker::has_helper('helper_test'));
			
	}

	// }}}
	// {{{ public function test_notify_pre_dispatch()

	/**
	 * test_notify_pre_dispatch 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_notify_pre_dispatch()
	{
		$this->__helper->expects($this->any())
					   ->method('pre_dispatch');
		
		sw_broker::add_helper($this->__helper);
		$this->__broker->notify_pre_dispatch();
	}

	// }}}
	// {{{ public function test_notify_post_dispatch()

	/**
	 * test_notify_post_dispatch 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_notify_post_dispatch()
	{
		$this->__helper->expects($this->any())
					   ->method('post_dispatch');
		
		sw_broker::add_helper($this->__helper);
		$this->__broker->notify_post_dispatch();
	}

	// }}}
	// {{{ public function test_get_helper()

	/**
	 * test_get_helper 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_helper()
	{
		$this->__helper->expects($this->any())
					   ->method('set_action_controller')
					   ->with($this->greaterThan(0));
		sw_broker::add_helper($this->__helper);	

		$helper = $this->__broker->get_helper('helper_test');
		$this->assertInstanceOf('\lib\controller\action\helper\sw_abstract', $helper);
	}

	// }}}
	// {{{ public function test__call()

	/**
	 * test__call 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__call()
	{
		sw_broker::add_helper($this->__helper);	
		try {
			$this->__broker->helper_test();	
		} catch (sw_exception $e) {
			$this->assertContains('Helper `helper_test` does not support overloading via direct()', $e->getMessage());
		}
	}

	// }}}
	// }}}
}
