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
		$this->__helper = $this->getMockForAbstractClass('\lib\controller\action\helper\sw_abstract');
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
				
	}

	// }}}
	// }}}
}
