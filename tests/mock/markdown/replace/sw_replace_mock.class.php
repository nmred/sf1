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
 
namespace mock\markdown\replace;
use swan\markdown\replace\sw_default;

/**
+------------------------------------------------------------------------------
* sw_replace_mock 
+------------------------------------------------------------------------------
* 
* @uses sw_abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_replace_mock extends sw_default
{
	// {{{ functios
	// {{{ public function encode_attribute()

	/**
	 * encode_attribute 
	 * 
	 * @access public
	 * @return void
	 */
	public function encode_attribute($text)
	{
		return $this->_encode_attribute($text);
	}

	// }}}
	// }}}
}
