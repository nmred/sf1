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

namespace swan\controller\action;

/**
* ACTION-接口
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
interface sw_interface
{
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @param \swan\controller\response\sw_abstract $response 
	 * @access public
	 * @return void
	 */
	public function __construct(\swan\controller\request\sw_abstract $request, \swan\controller\response\sw_abstract $response);

	// }}}
	// {{{ public function dispatch()

	/**
	 * 分发器 
	 * 
	 * @param string $action 
	 * @access public
	 * @return void
	 */
	public function dispatch($action);

	// }}}
	// }}}
}
