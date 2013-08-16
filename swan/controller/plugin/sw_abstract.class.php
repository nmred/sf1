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

namespace swan\controller\plugin;

/**
* 插件-抽象类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
abstract class sw_abstract
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * 请求对象 
	 * 
	 * @var \swan\controller\request\sw_abstract
	 * @access protected
	 */
	protected $__request;

	/**
	 * 响应对象 
	 * 
	 * @var \swan\controller\response\sw_abstract
	 * @access protected
	 */
	protected $__response;

	// }}}
	// {{{ functions
	// {{{ public function set_request()

	/**
	 * 设置请求对象 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return \swan\controller\plugin\sw_abstract
	 */
	public function set_request(\swan\controller\request\sw_abstract $request)
	{
		$this->__request = $request;
		return $this;
	}

	// }}}
	// {{{ public function get_request()

	/**
	 * 获取请求对象 
	 * 
	 * @access public
	 * @return \swan\controller\request\sw_abstract 
	 */
	public function get_request()
	{
		return $this->__request;	
	}

	// }}}
	// {{{ public function set_response()
	
	/**
	 * 设置响应对象 
	 * 
	 * @param \swan\controller\response\sw_abstract $response 
	 * @access public
	 * @return \swan\controller\plugin\sw_abstract
	 */
	public function set_response(\swan\controller\response\sw_abstract $response)
	{
		$this->__response = $response;
		return $this;	
	}

	// }}}
	// {{{ public function get_response()

	/**
	 * 获取响应对象 
	 * 
	 * @access public
	 * @return \swan\controller\response\sw_abstract 
	 */
	public function get_response()
	{
		return $this->__response;	
	}

	// }}}
	// {{{ public function route_startup()

	/**
	 * 在路由分发前动作 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_startup(\swan\controller\request\sw_abstract $request)
	{
	}

	// }}}
	// {{{ public function route_shutdown()

	/**
	 * 在路由分发后动作 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function route_shutdown(\swan\controller\request\sw_abstract $request)
	{
	}

	// }}}
	// {{{ public function dispatch_loop_startup()

	/**
	 * 在分发器分发前动作 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_startup(\swan\controller\request\sw_abstract $request)
	{
	}

	// }}}
	// {{{ public function pre_dispatch()

	/**
	 * 在分发器调用 Action 分发前动作 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function pre_dispatch(\swan\controller\request\sw_abstract $request)
	{
	}

	// }}}
	// {{{ public function post_dispatch()

	/**
	 * 在分发器调用 Action 分发后动作 
	 * 
	 * @param \swan\controller\request\sw_abstract $request 
	 * @access public
	 * @return void
	 */
	public function post_dispatch(\swan\controller\request\sw_abstract $request)
	{
	}

	// }}}
	// {{{ public function dispatch_loop_shutdown()

	/**
	 * 在分发器分发后动作 
	 * 
	 * @access public
	 * @return void
	 */
	public function dispatch_loop_shutdown()
	{
	}

	// }}}
	// }}}
}
