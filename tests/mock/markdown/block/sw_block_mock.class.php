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
 
namespace mock\markdown\block;
use swan\markdown\block\sw_block;

/**
+------------------------------------------------------------------------------
* sw_block_mock 
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
class sw_block_mock extends sw_block
{
	// {{{ functios
	// {{{ public function hash_html_blocks()

	/**
	 * hash_html_blocks 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function hash_html_blocks($text)
	{
		return $this->_hash_html_blocks($text);
	}

	// }}}
	// {{{ public function do_headers()

	/**
	 * do_headers 
	 * 
	 * @access public
	 * @return void
	 */
	public function do_headers($text)
	{
		return $this->_do_headers($text);	
	}

	// }}}
	// {{{ public function do_horizontal_rules()

	/**
	 * do_horizontal_rules 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function do_horizontal_rules($text)
	{
		return $this->_do_horizontal_rules($text);	
	}

	// }}}
	// {{{ public funciton do_code_blocks()

	/**
	 * do_code_blocks 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function do_code_blocks($text)
	{
		return $this->_do_code_blocks($text);
	}

	// }}}
	// }}}
}
