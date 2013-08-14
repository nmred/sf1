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
use mock\controller\action\helper\sw_abstract_mock;
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
class sw_abstract_test extends sw_test
{
	// {{{ members

	/**
	 * 动作助手 
	 * 
	 * @var \mock\controller\action\helper\sw_abstract_mock
	 * @access protected
	 */
	protected $__helper = null;

	/**
	 * 动作控制器 
	 * 
	 * @var \lib\controller\sw_action
	 * @access protected
	 */
	protected $__action_controller = null;

	/**
	 * 前端控制器 
	 * 
	 * @var \lib\controller\sw_controller
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
		$request  = $this->getMockForAbstractClass('\lib\controller\request\sw_abstract');
		$response = $this->getMockForAbstractClass('\lib\controller\response\sw_abstract');
		$this->__action_controller = $this->getMockForAbstractClass('\lib\controller\sw_action', array($request, $response), 'sw_user_action');
		$this->__controller = \lib\controller\sw_controller::get_instance();

		$this->__helper = new sw_abstract_mock();
	}

	// }}}
	// {{{ public function test_set_action_controller()

	/**
	 * test_set_action_controller 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_action_controller()
	{
		$rev = $this->__helper->set_action_controller($this->__action_controller);
		
		$this->assertInstanceOf('\mock\controller\action\helper\sw_abstract_mock', $rev);		
	}

	// }}}
	// {{{ public function test_get_action_controller()

	/**
	 * test_get_action_controller 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_action_controller()
	{	
		$this->__helper->set_action_controller($this->__action_controller);
		$rev = $this->__helper->get_action_controller();
		
		$this->assertInstanceOf('\lib\controller\sw_action', $rev);		
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
		$rev = $this->__helper->set_controller($this->__controller);
		
		$this->assertInstanceOf('\mock\controller\action\helper\sw_abstract_mock', $rev);		
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
		$this->__helper->set_controller($this->__controller);
		$rev = $this->__helper->get_controller();
		
		$this->assertInstanceOf('\lib\controller\sw_controller', $rev);		
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
		$this->__helper->init();	
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
		$this->__helper->pre_dispatch();	
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
		$this->__helper->post_dispatch();	
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
		$this->__helper->set_action_controller($this->__action_controller);
		
		$rev = $this->__helper->get_request();
		
		$this->assertInstanceOf('\lib\controller\request\sw_abstract', $rev);	
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
		$this->__helper->set_action_controller($this->__action_controller);
		
		$rev = $this->__helper->get_response();
		
		$this->assertInstanceOf('\lib\controller\response\sw_abstract', $rev);	
	}

	// }}}
	// {{{ public function test_get_name()

	/**
	 * test_get_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_name()
	{
		$this->assertEquals('abstract_mock', $this->__helper->get_name());	
	}

	// }}}
	// }}}
}
