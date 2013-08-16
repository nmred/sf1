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
use swan\test\sw_test;
use mock\controller\router\sw_abstract_mock;

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
		$this->__router = new sw_abstract_mock();
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
		$rev = $this->__router->set_param('bar', 'foo');
		$this->assertInstanceOf('\mock\controller\router\sw_abstract_mock', $rev);

		$this->assertEquals('foo', $this->__router->get_param('bar'));
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
		$rev = $this->__router->set_params($array);
		$this->assertInstanceOf('\mock\controller\router\sw_abstract_mock', $rev);

		$this->assertSame($array, $this->__router->get_params());
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
		$this->assertNull($this->__router->get_param('bar'));
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
		$this->assertSame(array(), $this->__router->get_params());
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
		$this->__router->set_params($array);

		$rev = $this->__router->clear_params();

		$this->assertInstanceOf('\mock\controller\router\sw_abstract_mock', $rev);
		$this->assertSame(array(), $this->__router->get_params());

		// 2
		$array = array('bar' => 'foo', 'baz' => 'var');
		$this->__router->set_params($array);
		$rev = $this->__router->clear_params('bar');
		$this->assertInstanceOf('\mock\controller\router\sw_abstract_mock', $rev);
		$this->assertSame(array('baz' => 'var'), $this->__router->get_params());
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
		$this->assertInstanceOf('\swan\controller\sw_controller', $this->__router->get_controller());
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
		$rev = $this->__router->set_controller($controller);
		$this->assertInstanceOf('\mock\controller\router\sw_abstract_mock', $rev);
	}

	// }}}
	// }}}
}
