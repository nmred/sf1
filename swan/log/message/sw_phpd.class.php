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
 
namespace swan\log\message;

/**
+------------------------------------------------------------------------------
* sw_phpd 
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
class sw_phpd extends sw_abstract
{
	// {{{ members

	/**
	 * __params 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__params = array(
		'proc_name' => '',
		'message'   => '',
	);

	// }}}	
	// {{{ functions

	/**
	 * 装配日志信息 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _assemble()
	{
		if ($this->__params['proc_name']) {
			return $this->__params['proc_name'] . ', ' . $this->__params['message'];	
		} else {
			return $this->__params['message'];	
		}
	}

	// }}}
}
