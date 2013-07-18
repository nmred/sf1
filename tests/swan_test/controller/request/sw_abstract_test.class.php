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

namespace swan_test\controller\request;
use lib\test\sw_test;
use mock\controller\request\sw_abstract_mock;

/**
+------------------------------------------------------------------------------
* sw_abstract_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db 
+------------------------------------------------------------------------------
*/
class sw_abstract_test extends sw_test
{
	// {{{ members

	/**
	 * __request 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__request = null;

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
		$this->__request = new sw_abstract_mock();
	}

	// }}}
	// {{{ public function test_get_module_name()

	/**
	 * test_get_module_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_module_name()
	{
		// 1
		$rev = $this->__request->get_module_name();
		$this->assertNull($rev);	

		// 2
		$this->__request->set_module_name('index');
		$rev = $this->__request->get_module_name();
		$this->assertEquals('index', $rev);
	}

	// }}}
	// {{{ public function test_set_module_name()

	/**
	 * test_set_module_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_module_name()
	{
		$rev = $this->__request->set_module_name('index');
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
	}

	// }}}
	// {{{ public function test_get_controller_name()

	/**
	 * test_get_controller_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_controller_name()
	{
		// 1
		$rev = $this->__request->get_controller_name();
		$this->assertNull($rev);	

		// 2
		$this->__request->set_controller_name('index');
		$rev = $this->__request->get_controller_name();
		$this->assertEquals('index', $rev);
	}

	// }}}
	// {{{ public function test_set_controller_name()

	/**
	 * test_set_controller_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_controller_name()
	{
		$rev = $this->__request->set_controller_name('index');
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
	}

	// }}}
	// {{{ public function test_get_action_name()

	/**
	 * test_get_action_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_action_name()
	{
		// 1
		$rev = $this->__request->get_action_name();
		$this->assertNull($rev);	

		// 2
		$this->__request->set_action_name('index');
		$rev = $this->__request->get_action_name();
		$this->assertEquals('index', $rev);
	}

	// }}}
	// {{{ public function test_set_action_name()

	/**
	 * test_set_action_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_action_name()
	{
		$rev = $this->__request->set_action_name('index');
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
	}

	// }}}
	// {{{ public function test_get_module_key()

	/**
	 * test_get_module_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_module_key()
	{
		// 1
		$rev = $this->__request->get_module_key();
		$this->assertEquals('module', $rev);	

		// 2
		$this->__request->set_module_key('ref_module');
		$rev = $this->__request->get_module_key();
		$this->assertEquals('ref_module', $rev);
	}

	// }}}
	// {{{ public function test_set_module_key()

	/**
	 * test_set_module_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_module_key()
	{
		$rev = $this->__request->set_module_key('ref_module');
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
		$rev = $this->__request->get_module_key();
		$this->assertEquals('ref_module', $rev);
	}

	// }}}
	// {{{ public function test_get_controller_key()

	/**
	 * test_get_controller_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_controller_key()
	{
		// 1
		$rev = $this->__request->get_controller_key();
		$this->assertEquals('controller', $rev);	

		// 2
		$this->__request->set_controller_key('ref_controller');
		$rev = $this->__request->get_controller_key();
		$this->assertEquals('ref_controller', $rev);
	}

	// }}}
	// {{{ public function test_set_controller_key()

	/**
	 * test_set_controller_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_controller_key()
	{
		$rev = $this->__request->set_controller_key('ref_controller');
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
		$rev = $this->__request->get_controller_key();
		$this->assertEquals('ref_controller', $rev);
	}

	// }}}
	// {{{ public function test_get_action_key()

	/**
	 * test_get_action_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_action_key()
	{
		// 1
		$rev = $this->__request->get_action_key();
		$this->assertEquals('action', $rev);	

		// 2
		$this->__request->set_action_key('ref_action');
		$rev = $this->__request->get_action_key();
		$this->assertEquals('ref_action', $rev);
	}

	// }}}
	// {{{ public function test_set_action_key()

	/**
	 * test_set_action_key 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_action_key()
	{
		$rev = $this->__request->set_action_key('ref_action');
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
		$rev = $this->__request->get_action_key();
		$this->assertEquals('ref_action', $rev);
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
		$rev = $this->__request->set_param('test_param', 2);	
		$rev = $this->__request->get_param('test_param');
		$this->assertEquals(2, $rev);
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
		$rev = $this->__request->set_param('test_param', 2);	
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
		$rev = $this->__request->get_param('test_param');
		$this->assertEquals(2, $rev);

		$this->__request->set_param('test_param');
		$rev = $this->__request->get_param('test_param');
		$this->assertNull($rev);
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
		$array = array(
			'test' => 1,
			'test1' => 2,
		);
		$rev = $this->__request->set_params($array);
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
		$rev = $this->__request->get_params();
		$this->assertEquals($array, $rev);
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
		$array = array(
			'test' => 1,
			'test1' => 2,
		);
		$this->__request->set_params($array);
		$rev = $this->__request->clear_params();
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
		$rev = $this->__request->get_params();
		$this->assertEquals(array(), $rev);
		
	}

	// }}}
	// {{{ public function test_set_dispatched()

	/**
	 * test_set_dispatched 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_dispatched()
	{
		$rev = $this->__request->set_dispatched(true);
		$this->assertInstanceOf('mock\controller\request\sw_abstract_mock', $rev);	
		$rev = $this->__request->is_dispatched();
		$this->assertTrue($rev);
	}

	// }}}
	// }}}
}
