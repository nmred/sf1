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
 
namespace swan\operator;
use \swan\operator\exception\sw_exception;

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
	 * DB 操作类 
	 * 
	 * @var object
	 * @access protected
	 */
	protected $__db;

	// }}}	
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * 构造方法 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
	}

	// }}}
	// {{{ public function set_db()

	/**
	 * 设置 DB 的操作对象 
	 * 
	 * @param \swan\db\adapter\sw_abstract $db 
	 * @access public
	 * @return void
	 */
	public function set_db(\swan\db\adapter\sw_abstract $db)
	{
		$this->__db = $db;	
	}

	// }}}
	// {{{ public function _get()

	/**
	 * 获取列表 
	 * 
	 * @param sw_db_select $select 
	 * @param array $params 
	 * @access public
	 * @return array
	 */
	public function _get($select, $params)
	{
		if (isset($params['distinct']) && $params['distinct']) {
			$select->distinct();	
		}	

		//返回统计个数
		if (isset($params['is_count']) && $params['is_count']) {
			$select->columns('count(*)');
			return $this->__db->fetch_one($select);
		}

		if (isset($params['columns']) && !empty($params['columns'])) {
			$select->columns($params['columns']);	
		}

		if (isset($params['group'])) {
			$select->group($params['group']);	
		}

		if (isset($params['order'])) {
			$select->order($params['order']);	
		}

		if (isset($params['limit'])) {
			$select->limit($params['limit']['count'], $params['limit']['offset']);	
		} elseif (isset($params['limit_page'])) {
			$select->limit_page($params['limit_page']['page'], $params['limit_page']['rows_count']);
		}

		if (isset($params['is_fetch']) && $params['is_fetch']) {
			return $this->__db->query($select);	
		} else {
			return $this->__db->fetch_all($select);	
		}
	}

	// }}}
	// {{{ public function _check_require()

	/**
	 * 检测必要的字段 
	 * 
	 * @param array $attributes 
	 * @param array $require_fields 
	 * @access public
	 * @return void
	 */
	public function _check_require($attributes, $require_fields = array())
	{
		foreach ($require_fields as $field) {
			if (!isset($attributes[$field])) {
				throw new sw_exception("must given $field");	
			}
		}	
	}

	// }}}
	// }}}
}
