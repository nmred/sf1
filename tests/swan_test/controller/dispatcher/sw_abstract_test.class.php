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
use mock\controller\dispatcher\sw_abstract_mock;

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
		$this->__dispatcher = new sw_abstract_mock();
	}

	// }}}
	// {{{ public function test_set_param()

	/**
	 * test_set_param 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_param()
	{
		$rev = $this->__dispatcher->set_param('bar', 'foo');
		$this->assertInstanceOf('\mock\controller\dispatcher\sw_abstract_mock', $rev);
		$this->assertEquals('foo', $this->__dispatcher->get_param('bar'));
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
		$this->assertNull($this->__dispatcher->get_param('foo'));	

		$this->__dispatcher->set_param('bar', 'foo');
		$this->assertEquals('foo', $this->__dispatcher->get_param('bar'));
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
		$rev = $this->__dispatcher->set_params(array('bar', 'foo'));
		$this->assertInstanceOf('\mock\controller\dispatcher\sw_abstract_mock', $rev);

		$this->assertSame(array('bar', 'foo'), $this->__dispatcher->get_params());
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
		$this->__dispatcher->set_params(array('bar' => 'foo'));
		$this->assertSame(array('bar' => 'foo'), $this->__dispatcher->get_params());
		$this->__dispatcher->clear_params('bar');
		$this->assertSame(array(), $this->__dispatcher->get_params());
	}

	// }}}
	// {{{ public function test_set_response()

	/**
	 * test_set_response 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_get_response()
	{
		$response = $this->getMockBuilder('swan\controller\response\sw_http')
		                 ->getMock();

		$rev = $this->__dispatcher->set_response($response);
		$this->assertInstanceOf('\mock\controller\dispatcher\sw_abstract_mock', $rev);
		$this->assertInstanceOf('\swan\controller\response\sw_abstract', $this->__dispatcher->get_response());
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
		$this->assertEquals('default', $this->__dispatcher->get_default_module());	
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
		$this->assertEquals('base', $this->__dispatcher->get_default_controller());	
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
		$this->assertEquals('action_default', $this->__dispatcher->get_default_action());
	}

	// }}}
	// }}}
}
