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

namespace swan\view;
require_once PATH_SF_SMARTY . 'Smarty.class.php';

/**
* 视图模版
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_view extends \Smarty
{
	// {{{ consts
	// }}}
	// {{{ members
	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param string $template_dir 
	 * @param string $compile_dir 
	 * @param string $cache_dir 
	 * @access public
	 * @return void
	 */
	public function __construct($template_dir, $compile_dir, $cache_dir)
	{
		parent::__construct();	

		$this->template_dir    = $template_dir;
		$this->compile_dir     = $compile_dir;
		$this->caching         = SW_CACHE;
		$this->cache_lifetime  = SW_CACHE_TIME;
		$this->cache_dir       = $cache_dir;
		$this->left_delimiter  = SW_LEFT_DELIMITER;
		$this->right_delimiter = SW_RIGHT_DELIMITER;
	}

	// }}}
	// {{{ public function display()

	/**
	 * display 已禁用！！ 
	 * 
	 * @param mixed $resource_name 
	 * @param mixed $cache_id 
	 * @param mixed $compile_id 
	 * @access public
	 * @return void
	 */
	public function display($resource_name = null, $cache_id = null, $compile_id = null, $parent = null)
	{
		// 禁用 display
		return;	
	}

	// }}}
	// {{{ public function render()
	
	/**
	 * 渲染模版 
	 * 
	 * @param string $resource_name 
	 * @param string $cache_id 
	 * @param string $compile_id 
	 * @access public
	 * @return void
	 */
	public function render($resource_name = null, $cache_id = null, $compile_id = null)
	{
		parent::display($resource_name, $cache_id, $compile_id);	
	}
	 
	// }}}
	// }}}
}
