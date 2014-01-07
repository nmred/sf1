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
 
namespace swan\property;
use \swan\property\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_abstract 
+------------------------------------------------------------------------------
* 
* @abstract
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
abstract class sw_abstract
{
	// {{{ members

	/**
	 * 此对象允许设置的元素列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__allow_attributes = array();

	/**
	 * 主键属性元素， prepared_attributes 不获取相应的属性 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__key_attributes = array();

	/**
	 * 存储元素列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__attributes = array();

	/**
	 * 存储其他的属性列表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__propertys = array();

	/**
	 * 存储验证结果数组 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__validate = array();

	/**
	 * 存储验证结果信息（子类的验证规则） 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__validate_msg = array();

	/**
	 * 整形字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__int_fields = array();

	/**
	 * 布尔字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__bool_fields = array();

	/**
	 * 整形枚举字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__int_enum_fields = array();

	/**
	 * 字符串字段 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__str_filelds = array();

	// }}}	
}
