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

namespace swan_test\view;
use swan\test\sw_test;
use swan\exception\sw_exception;
use swan\view\sw_view;

/**
+------------------------------------------------------------------------------
* sw_view_test  
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_controller 
+------------------------------------------------------------------------------
*/
class sw_view_test extends sw_test
{
	// {{{ members

	/**
	 * view 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__view = null;

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
		$tpl_dir = dirname(__FILE__) . '/_files/tpl';
		$compile_dir = dirname(__FILE__) . '/_files/compile';
		$cache_dir = dirname(__FILE__) . '/_files/cache';
		$this->__view = new sw_view($tpl_dir, $cache_dir, $compile_dir); 
	}

	// }}}
	// {{{ public function test_render()

	/**
	 * test_render 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_render()
	{
		$this->__view->assign('test', 'hello smarty');	

		ob_start();
		$this->__view->render(dirname(__FILE__) . '/_files/tpl/test.html');
		$rev = ob_get_clean();

		$this->assertEquals('hello smarty' . PHP_EOL, $rev);
	}

	// }}}
	// }}}
}
