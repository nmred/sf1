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
use swan\test\sw_test;
use mock\controller\action\sw_broker_stack_mock;
use swan\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_broker_stack_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_broker_stack_test extends sw_test
{
	// {{{ members

	/**
	 * stack 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__stack = null;

	/**
	 * 动作助手 
	 * 
	 * @var \swan\controller\action\helper\sw_abstract
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
		$this->__stack = new sw_broker_stack_mock();

		$this->__helper = $this->getMockForAbstractClass('\swan\controller\action\helper\sw_abstract', array(), 'sw_test');
	}

	// }}}
	// {{{ public function test_push()

	/**
	 * test_push 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_push()
	{
		$rev = $this->__stack->push($this->__helper);
		$this->assertInstanceOf('\mock\controller\action\sw_broker_stack_mock', $rev);	
	}

	// }}}
	// {{{ public function test_getIterator()

	/**
	 * test_getIterator 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_getIterator()
	{
		$this->assertInstanceOf('\ArrayObject', $this->__stack->getIterator());	
	}

	// }}}
	// {{{ public function test_offsetExists()

	/**
	 * test_offsetExists 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_offsetExists()
	{
		$this->__stack->push($this->__helper);

		$this->assertTrue($this->__stack->offsetExists(1));
	}

	// }}}
	// {{{ public function test_offsetGet()

	/**
	 * test_offsetGet 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_offsetGet()
	{
		try {
			$this->__stack->offsetGet(1);	
		} catch (sw_exception $e) {
			$this->assertContains('A helper with priority ', $e->getMessage());
		}

		$this->__stack->push($this->__helper);
		$rev = $this->__stack->offsetGet(1);
		$this->assertInstanceOf('\swan\controller\action\helper\sw_abstract', $rev);
	}

	// }}}
	// {{{ public function test_offsetSet()

	/**
	 * test_offsetSet 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_offsetSet()
	{
		$rev = $this->__stack->offsetSet(2, $this->__helper);	
		$rev = $this->__stack->offsetSet(3, $this->__helper);
		$this->assertInstanceOf('\mock\controller\action\sw_broker_stack_mock', $rev);

		$this->assertEquals(1, count($this->__stack->get_helpers_by_name()));
	}

	// }}}
	// {{{ public function test_offsetUnset()

	/**
	 * test_offsetUnset 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_offsetUnset()
	{
		try {
			$this->__stack->offsetUnset(1);	
		} catch (sw_exception $e) {
			$this->assertContains('A helper with priority ', $e->getMessage());	
		}

		$this->__stack->push($this->__helper);
		$this->assertEquals(1, count($this->__stack->get_helpers_by_name()));
		$rev = $this->__stack->offsetUnset(1);
		$this->assertEquals(0, count($this->__stack->get_helpers_by_name()));
		$this->assertInstanceOf('\mock\controller\action\sw_broker_stack_mock', $rev);
	}

	// }}}
	// {{{ public function test_count()

	/**
	 * test_count 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_count()
	{
		$this->assertEquals(0, $this->__stack->count());
		$this->__stack->push($this->__helper);	
		$this->assertEquals(1, $this->__stack->count());
	}

	// }}}
	// {{{ public function test_get_next_free_higher_priority()

	/**
	 * test_get_next_free_higher_priority 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_next_free_higher_priority()
	{
		$this->assertEquals(1, $this->__stack->get_next_free_higher_priority());	
		$this->__stack->push($this->__helper);
		$this->assertEquals(2, $this->__stack->get_next_free_higher_priority(1));	
		$this->__stack->push($this->__helper);
		$this->assertEquals(7, $this->__stack->get_next_free_higher_priority(7));	
	}

	// }}}
	// {{{ public function test_get_next_free_lower_priority()

	/**
	 * test_get_next_free_lower_priority 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_next_free_lower_priority()
	{
		$this->assertEquals(1, $this->__stack->get_next_free_lower_priority());	
	}

	// }}}
	// {{{ public function test_get_helpers_by_name()
	
	/**
	 * test_get_helpers_by_name 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_helpers_by_name()
	{
		$this->__stack->push($this->__helper);
		
		$expects = array('test' => $this->__helper);
		$this->assertSame($expects, $this->__stack->get_helpers_by_name());	
	}

	// }}}
	// {{{ public function test___get()

	/**
	 * test___get 
	 * 
	 * @access public
	 * @return void
	 */
	public function test___get()
	{
		$this->__stack->push($this->__helper);
		$this->assertInstanceOf('\swan\controller\action\helper\sw_abstract', $this->__stack->test);
	}

	// }}}
	// {{{ public function test___isset()

	/**
	 * test__isset 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__isset()
	{
		$this->assertFalse(isset($this->__stack->test1));
		$this->__stack->push($this->__helper);
		$this->assertTrue(isset($this->__stack->test));		
	}

	// }}}
	// {{{ public function test___unset()

	/**
	 * test__unset 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__unset()
	{
		$this->__stack->push($this->__helper);
		$this->assertEquals(1, count($this->__stack));	
		unset($this->__stack->test);
		$this->assertEquals(0, count($this->__stack));	
	}

	// }}}
	// }}}
}
