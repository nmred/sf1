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
 
namespace mock\markdown\span;
use swan\markdown\span\sw_span;

/**
+------------------------------------------------------------------------------
* sw_span_mock 
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
class sw_span_mock extends sw_span
{
	// {{{ functios
	// {{{ public function parse_span()

	/**
	 * parse_span 
	 * 
	 * @access public
	 * @return void
	 */
	public function parse_span($str)
	{	
		return $this->_parse_span($str);
	}

	// }}}
	// {{{ public function encode_amps_and_angles()

	/**
	 * encode_amps_and_angles 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function encode_amps_and_angles($text)
	{
		return $this->_encode_amps_and_angles($text);	
	}

	// }}}
	// {{{ public function do_images()

	/**
	 * do_images 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function do_images($text)
	{
		return $this->_do_images($text);	
	}

	// }}}
	// {{{ public function do_anchors()

	/**
	 * do_anchors 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function do_anchors($text)
	{
		return $this->_do_anchors($text);	
	}

	// }}}
	// {{{ public function do_hard_breaks()

	/**
	 * do_hard_breaks 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function do_hard_breaks($text)
	{
		return $this->_do_hard_breaks($text);
	}

	// }}}
	// {{{ public function do_autolinks()

	/**
	 * do_autolinks 
	 * 
	 * @access public
	 * @return void
	 */
	public function do_autolinks($text)
	{
		return $this->_do_autolinks($text);	
	}

	// }}}
	// {{{ public function do_italics_bold()

	/**
	 * do_italics_bold 
	 * 
	 * @param mixed $text 
	 * @access public
	 * @return void
	 */
	public function do_italics_bold($text)
	{
		return $this->_do_italics_bold($text);	
	}

	// }}}
	// }}}
}
