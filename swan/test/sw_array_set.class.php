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

namespace swan\test;
use swan\test\exception\sw_exception;

/**
* 单元测试数组转化数据集 
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
*/
class sw_array_set extends \PHPUnit_Extensions_Database_DataSet_AbstractDataSet 
{
	// {{{ members
	
	/**
	 * 数据表 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__tables = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @param array $data 
	 * @access public
	 * @return void
	 */
	public function __construct(array $data)
	{
		foreach ($data as $table_name => $rows) {
			$columns = array();
			if (isset($rows[0])) {
				$columns = array_keys($rows[0]);	
			}

			$meta_data = new \PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData($table_name, $columns);
			$table = new \PHPUnit_Extensions_Database_DataSet_DefaultTable($meta_data);
			foreach ($rows as $row) {
				$table->addRow($row);
			}

			$this->__tables[$table_name] = $table;
		}
	}

	// }}}
	// {{{ public function createIterator()
	
	/**
	 * createIterator 
	 * 
	 * @param mixed $reverse 
	 * @access public
	 * @return void
	 */
	public function createIterator($reverse = false)
	{
		return new \PHPUnit_Extensions_Database_DataSet_DefaultTableIterator($this->__tables, $reverse);	
	}

	// }}}
	// {{{ public function getTable()
	
	/**
	 * 获取数据表 
	 * 
	 * @param string $table_name 
	 * @access public
	 * @return void
	 */
	public function getTable($table_name)
	{
		if (!isset($this->__tables[$table_name])) {
			throw new sw_exception("$table_name is  not a table in the current database.");
		}	

		return $this->__tables[$table_name];
	}

	// }}}
	// }}}
}
