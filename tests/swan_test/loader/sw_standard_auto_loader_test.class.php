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
 
namespace swan_test\loader;
use swan\test\sw_test;
use swan\loader\sw_auto;
use swan\loader\exception\sw_invalid_argument_exception;

/**
+------------------------------------------------------------------------------
* sw_standard_auto_loader_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
+------------------------------------------------------------------------------
* @group sw_loader
*/
class sw_standard_auto_loader_test extends sw_test 
{
	// {{{ members
	
	/**
	 * __loader 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__loader = array();

	/**
	 * __include_path 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__include_path = '';

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
		$this->__loader = spl_autoload_functions();
		if (!is_array($this->__loader)) {
			$this->__loader = array();	
		}

		$this->__include_path = get_include_path();
	}

	// }}}
	// {{{ public function tearDown()

	/**
	 * tearDown 
	 * 
	 * @access public
	 * @return void
	 */
	public function tearDown()
	{
		$loaders = spl_autoload_functions();
		if (is_array($loaders)) {
			foreach ($loaders as $loader) {
				spl_autoload_unregister($loader);	
			}
		}

		foreach ($this->__loader as $loader) {
			spl_autoload_register($loader);
		}

		set_include_path($this->__include_path);
	}

	// }}}
	// {{{ public function test_set_options_exception()

	/**
	 * test_set_options_exception 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_options_exception()
	{
		$loader = sw_auto::get_instance();
		
		$obj = new \stdClass;
		foreach (array(true, 'foo', $obj) as $arg) {
			try {
				$loader->set_options($arg);
				$this->fail('setting options with invalid type should fail');	
			} catch (\swan\loader\exception\sw_exception $e) {
				$this->assertContains('array or Traversable.', $e->getMessage());	
			}
		}
	}

	// }}}
	// {{{ public function test_set_options_array()

	/**
	 * test_set_options_array 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_set_options_array()
	{
		$options = array(
			'namespaces' => array(
				'swan_swan\\' => '.' . DIRECTORY_SEPARATOR,
			),
		);

		$loader = \mock\loader\sw_mock_auto::get_instance($options);
		$this->assertEquals($options['namespaces'], $loader->get_namespaces());
	}

	// }}}
	// {{{ public function test_auto_load_namespace()

	/**
	 * test_auto_load_namespace 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_auto_load_namespace()
	{
		$loader = \mock\loader\sw_mock_auto::get_instance();

		$loader->register_namespace('mock\loader', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
		$loader->autoload('mock\loader\namespace_test');
		$this->assertTrue(class_exists('mock\loader\namespace_test', false));
	}

	// }}}
	// {{{ public function test_register_register_callback_with_spl_autoload()

	/**
	 * test_register_register_callback_with_spl_autoload 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_register_register_callback_with_spl_autoload()
	{
		$loader = \mock\loader\sw_mock_auto::get_instance();
		$loader->register();
		$loaders = spl_autoload_functions();
		$this->assertGreaterThan($this->__loader, $loaders);
	}

	// }}}
	// }}}
}
