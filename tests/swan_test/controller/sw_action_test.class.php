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
use swan\test\sw_test;
use mock\controller\sw_action_mock;
use swan\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_action_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_action_test extends sw_test
{
	// {{{ members

	/**
	 * action 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__action = null;

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
		$request  = $this->getMockForAbstractClass('\swan\controller\request\sw_abstract');
		$response = $this->getMockForAbstractClass('\swan\controller\response\sw_abstract');

		$this->__action = new sw_action_mock($request, $response, array('test' => true));
		//$this->__helper = $this->getMockForAbstractClass('\swan\controller\action\helper\sw_abstract', array(), 'sw_helper_test');
	}

	// }}}
	// {{{ public function test_init()

	/**
	 * test_init 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_init()
	{
		$this->__action->init();	
	}

	// }}}
	// {{{ public function test_init_view()

	/**
	 * test_init_view 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_init_view()
	{
		$this->__action->init_view();	
	}

	// }}}
	// {{{ public function test_get_request()

	/**
	 * test_get_request 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_request()
	{
		$rev = $this->__action->get_request();
		
		$this->assertInstanceOf('\swan\controller\request\sw_abstract', $rev);	
	}

	// }}}
	// {{{ public function test_get_response()

	/**
	 * test_get_response 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_response()
	{
		$rev = $this->__action->get_response();
		$this->assertInstanceOf('\swan\controller\response\sw_abstract', $rev);	
	}

	// }}}
	// {{{ public function test_get_invoke_args()

	/**
	 * test_get_invoke_args 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_invoke_args()
	{
		$this->assertSame(array('test' => true), $this->__action->get_invoke_args());
	}

	// }}}
	// {{{ public function test_get_invoke_arg()

	/**
	 * test_get_invoke_arg 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_invoke_arg()
	{
		$this->assertTrue($this->__action->get_invoke_arg('test'));
		$this->assertNull($this->__action->get_invoke_arg('test1'));
	}

	// }}}
	// {{{ public function test_set_controller()

	/**
	 * test_set_controller 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_controller()
	{
		$controller = \swan\controller\sw_controller::get_instance();
		
		$rev = $this->__action->set_controller($controller);
		$this->assertInstanceOf('\mock\controller\sw_action_mock', $rev);	

		$this->assertInstanceOf('\swan\controller\sw_controller', $this->__action->get_controller());
	}

	// }}}
	// {{{ public function test_get_controller()

	/**
	 * test_get_controller 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_controller()
	{
		try {
			$this->__action->get_controller();	
		} catch (sw_exception $e) {
			$this->assertContains('Front controller class has not been loaded', $e->getMessage());	
		}
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
		$this->__action->pre_dispatch();	
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
		$this->__action->post_dispatch();	
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
		try {
			$this->__action->action_default();	
		} catch (sw_exception $e) {
			$this->assertContains('Action `action_default` does not exist and was not trapped in __call()', $e->getMessage());	
			$this->assertEquals(404, $e->getCode());
		}

		try {
			$this->__action->test();	
		} catch (sw_exception $e) {
			$this->assertContains('Method `test` does not exist and was not trapped in __call()', $e->getMessage());	
			$this->assertEquals(500, $e->getCode());
		}

	}

	// }}}
	// }}}
}
