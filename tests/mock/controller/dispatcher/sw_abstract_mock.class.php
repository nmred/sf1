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
 
namespace mock\controller\dispatcher;
use lib\controller\dispatcher\sw_abstract;

/**
+------------------------------------------------------------------------------
* sw_abstract_mock 
+------------------------------------------------------------------------------
* 
* @uses sw
* @uses _abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_abstract_mock extends sw_abstract
{
	// {{{ functios
	// {{{ public function format_module_name()

	/**
	 * format_module_name 
	 * 
	 * @param mixed $unformated 
	 * @access public
	 * @return void
	 */
	public function format_module_name($unformated)
	{
	}

	// }}}
	// {{{ public function format_controller_name()

	/**
	 * format_controller_name 
	 * 
	 * @param mixed $unformated 
	 * @access public
	 * @return void
	 */
	public function format_controller_name($unformated)
	{
	}

	// }}}
	// {{{ public function format_action_name()

	/**
	 * format_action_name 
	 * 
	 * @param mixed $unformated 
	 * @access public
	 * @return void
	 */
	public function format_action_name($unformated)
	{
	}

	// }}}
	// {{{ public function is_dispatchable()

	/**
	 * is_dispatchable 
	 * 
	 * @access public
	 * @return void
	 */
	public function is_dispatchable(\lib\controller\dispatcher\lib\controller\request\sw_abstract $request)
	{
	}

	// }}}
	// {{{ public function add_controller_namespace()

	/**
	 * add_controller_namespace 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_controller_namespace($namespace, $module = null)
	{
		
	}

	// }}}
	// {{{ public function set_controller_namespace()

	/**
	 * set_controller_namespace 
	 * 
	 * @param mixed $namespace 
	 * @param mixed $module 
	 * @access public
	 * @return void
	 */
	public function set_controller_namespace($namespace, $module = null)
	{
	}

	// }}}
	// {{{ public function get_controller_namespace()

	/**
	 * get_controller_namespace 
	 * 
	 * @param mixed $module 
	 * @access public
	 * @return void
	 */
	public function get_controller_namespace($module = null)
	{
		
	}

	// }}}
	// {{{ public function dispatch()

	/**
	 * dispatch 
	 * 
	 * @param mixed $request 
	 * @param mixed $response 
	 * @access public
	 * @return void
	 */
	public function dispatch(\lib\controller\dispatcher\lib\controller\request\sw_abstract $request, \lib\controller\dispatcher\lib\controller\response\sw_abstract $response)
	{
		
	}

	// }}}
	// {{{ public function is_valid_module()

	/**
	 * is_valid_module 
	 * 
	 * @param mixed $module 
	 * @access public
	 * @return void
	 */
	public function is_valid_module($module)
	{
		
	}

	// }}}
	// }}}
}
