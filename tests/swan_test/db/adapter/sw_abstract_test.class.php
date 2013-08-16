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
 
namespace swan_test\db\adapter;
use swan\test\sw_test_db;
use swan\db\adapter\sw_mysql;
use swan\db\adapter\exception\sw_exception;
use swan\db\sw_db;
use mock\db\adapter\sw_mysql as mock_mysql;

/**
+------------------------------------------------------------------------------
* sw_abatract_test 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @group sw_db
+------------------------------------------------------------------------------
*/
class sw_abstract_test extends sw_test_db
{
	// {{{ members

	/**
	 * __db 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__db = null;

	// }}}
	// {{{ functions
	// {{{ public function get_data_set()

	/**
	 * 获取数据集 
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get_data_set()
	{
		return array(
			dirname(__FILE__) . '/_files/adapter_pre.xml',
		);
	}

	// }}}
	// {{{ public function setUp()

	/**
	 * setUp 
	 * 
	 * @access public
	 * @return void
	 */
	public function setUp()
	{
		$this->__db = new sw_mysql();	
		parent::setUp();
	}

	// }}}
	// {{{ public function test_construct()

	/**
	 * test_construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_construct()
	{
		try {
			$db = new sw_mysql('aa');
		} catch (sw_exception $e) {
			$this->assertContains('config param must is array', $e->getMessage());	
		}

		$array = array('testkey' => 0);
		$db = new sw_mysql($array);
		$this->assertArrayHasKey('testkey', $db->get_config());
		$profiler = $this->__db->get_profiler();
		$this->assertInstanceof('swan\db\profiler\sw_profiler', $profiler);
	}

	// }}}
	// {{{ public function test_connect()

	/**
	 * test_connect 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_connect()
	{
		$conns = $this->__db->get_connection();
		$this->assertInstanceof('\PDO', $conns);
	}

	// }}}
	// {{{ public function test_get_statement_class()

	/**
	 * test_get_statement_class 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_statement_class()
	{
		$this->__db->set_statement_class('standard_class');
		$rev = $this->__db->get_statement_class();
		$this->assertEquals('standard_class', $rev);
	}

	// }}}
	// {{{ public function test_get_fetch_mode()

	/**
	 * test_get_fetch_mode 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_fetch_mode()
	{
		$this->__db->set_fetch_mode(\PDO::FETCH_ASSOC);
		$rev = $this->__db->get_fetch_mode();
		
		$this->assertEquals(\PDO::FETCH_ASSOC, $rev);	

		try {
			$this->__db->set_fetch_mode('ww');	
		} catch (sw_exception $e) {
			$this->assertContains('Invalid fetch mode', $e->getMessage());	
		}
	}

	// }}}
	// {{{ public function test_is_connected()

	/**
	 * test_is_connected 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_is_connected()
	{
		$this->__db->close_connection();
		$conns = $this->__db->is_connected();
		$this->assertFalse($conns);

		$conns = $this->__db->get_connection();
		$conns = $this->__db->is_connected();
		$this->assertTrue($conns);
	}

	// }}}
	// {{{ public function test_close_connection()

	/**
	 * test_close_connection
	 * 
	 * @access public
	 * @return void
	 */
	public function test_close_connection()
	{
		$conns = $this->__db->get_connection();
		$conns = $this->__db->is_connected();
		$this->assertTrue($conns);

		$this->__db->close_connection();

		$conns = $this->__db->is_connected();
		$this->assertFalse($conns);
	}

	// }}}
	// {{{ public function test_exec()

	/**
	 * test_exec 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_exec()
	{
		$sql = "insert into unit_host(host_id, host_name, group_id) values(4, 'test_insert', 2)";
		$affected = $this->__db->exec($sql);
		$this->assertEquals(1, $affected);
	}

	// }}}
	// {{{ public function test_prepare()

	/**
	 * test_prepare 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_prepare()
	{
		$sql = 'select 1 + 2;';
		$rev = $this->__db->prepare($sql);
		$this->assertInstanceof('\swan\db\statement\sw_abstract', $rev);
	}

	// }}}
	// {{{ public function test_query()

	/**
	 * test_query 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_query()
	{
		$stmt = $this->__db->query('select * from unit_host where host_id > ?', array(1));
		$this->assertInstanceof('\swan\db\statement\sw_abstract', $stmt);
		$rev = $stmt->fetch_all();
		$expect = array(
			array(
				'host_id'  => '2',
				'group_id' => '1',
				'host_name' => 'lan-114',
			),
			array(
				'host_id'  => '3',
				'group_id' => '1',
				'host_name' => 'lan-115',
			),
		);
		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_insert()

	/**
	 * test_insert 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_insert()
	{
		$bind = array(
			'host_id' => 4, 
			'group_id' => '2', 
			'host_name' => 'test_insert'
		);
		$this->__db->insert('unit_host', $bind);
		$query_table = $this->getConnection()
							->CreateQueryTable('unit_host', 'select * from unit_host;');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/insert_result.xml')
					   ->getTable('unit_host');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_last_insert_id()

	/**
	 * test_last_insert_id 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_last_insert_id()
	{
		$sql = "insert into unit_host(host_id, host_name, group_id) values(4, 'test_insert', 2)";
		$affected = $this->__db->exec($sql);
		$last_id = $this->__db->last_insert_id('unit_host');
		$this->assertEquals('0', $last_id);
	}

	// }}}
	// {{{ public function test_update()

	/**
	 * test_update 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_update()
	{
		$bind = array(
			'group_id' => '2', 
			'host_name' => 'test_update'
		);
		$where = array(
			'host_id > ? ' => 2,
		);
		$this->__db->update('unit_host', $bind, $where);
		$query_table = $this->getConnection()
							->CreateQueryTable('unit_host', 'select * from unit_host;');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/update_result.xml')
					   ->getTable('unit_host');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_delete()

	/**
	 * test_delete
	 * 
	 * @access public
	 * @return void
	 */
	public function test_delete()
	{
		$where = array(
			'host_id > ? ' => 2,
		);
		$this->__db->delete('unit_host', $where);
		$query_table = $this->getConnection()
							->CreateQueryTable('unit_host', 'select * from unit_host;');
		$expect = $this->createXMLDataSet(dirname(__FILE__) . '/_files/delete_result.xml')
					   ->getTable('unit_host');
		$this->assertTablesEqual($expect, $query_table);
	}

	// }}}
	// {{{ public function test_select()

	/**
	 * test_select 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_select()
	{
		$rev = $this->__db->select();
		$this->assertInstanceof('\swan\db\select\sw_select', $rev);			
	}

	// }}}
	// {{{ public function test_fetch_all()

	/**
	 * test_fetch_all 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch_all()
	{
		$sql = 'select * from unit_host where host_id > ?';
		$bind = array(1);
		$rev = $this->__db->fetch_all($sql, $bind);
		$expect = array(
			array(
				'host_id'  => '2',
				'group_id' => '1',
				'host_name' => 'lan-114',
			),
			array(
				'host_id'  => '3',
				'group_id' => '1',
				'host_name' => 'lan-115',
			),
		);
		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_fetch_row()

	/**
	 * test_fetch_row 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch_row()
	{
		$sql = 'select * from unit_host where host_id > ?';
		$bind = array(1);
		$rev = $this->__db->fetch_row($sql, $bind);
		$expect = array(
			'host_id'  => '2',
			'group_id' => '1',
			'host_name' => 'lan-114',
		);
		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_fetch_assoc()

	/**
	 * test_fetch_assoc
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch_assoc()
	{
		$sql = 'select * from unit_host where host_id > ?';
		$bind = array(1);
		$rev = $this->__db->fetch_assoc($sql, $bind);
		$expect = array(
			'2' => array(
				'host_id'  => '2',
				'group_id' => '1',
				'host_name' => 'lan-114',
			),
			'3' => array(
				'host_id'  => '3',
				'group_id' => '1',
				'host_name' => 'lan-115',
			),
		);
		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_fetch_col()

	/**
	 * test_fetch_col
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch_col()
	{
		$sql = 'select * from unit_host where host_id > ?';
		$bind = array(1);
		$rev = $this->__db->fetch_col($sql, $bind);
		$expect = array(2, 3);
		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_fetch_pairs()

	/**
	 * test_fetch_pairs
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch_pairs()
	{
		$sql = $this->__db->select()->from('unit_host', array('host_id', 'host_name'))
							 ->where('host_id > ?', 1);

		$rev = $this->__db->fetch_pairs($sql);
		$expect = array(
			2 => 'lan-114',
			3 => 'lan-115'
		);
		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_fetch_one()

	/**
	 * test_fetch_one
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fetch_one()
	{
		$sql = $this->__db->select()->from('unit_host', array('host_id', 'host_name'))
							 ->where('host_id > ?', 1);

		$rev = $this->__db->fetch_one($sql);
		$expect = 2; 
		$this->assertEquals($expect, $rev);
	}

	// }}}
	// {{{ public function test_quote()

	/**
	 * test_quote
	 * 
	 * @access public
	 * @return void
	 */
	public function test_quote()
	{
		$sw_select = $this->getMockBuilder('swan\db\select\sw_select')
						  ->setConstructorArgs(array($this->__db))
						  ->getMock();
		$sw_select->expects($this->any())
				  ->method('assemble')
				  ->will($this->returnValue('user_id >= 1'));

		$rev = $this->__db->quote($sw_select);
		$this->assertEquals('(user_id >= 1)', $rev);

		$sw_expr = $this->getMockBuilder('swan\db\sw_db_expr')
						  ->setConstructorArgs(array('aa'))
						  ->getMock();
		$sw_expr->expects($this->once())
				  ->method('__toString')
				  ->will($this->returnValue('aa'));

		$rev = $this->__db->quote($sw_expr);
		$this->assertEquals('aa', $rev);

		$arr = array('a', 'b');
		$rev = $this->__db->quote($arr);
		$this->assertEquals("'a', 'b'", $rev);

		$quote_value = 2;
		$rev = $this->__db->quote($quote_value, \swan\db\sw_db::INT_TYPE);
		$this->assertEquals(2, $rev);

		$quote_value = 2e5;
		$rev = $this->__db->quote($quote_value, \swan\db\sw_db::BIGINT_TYPE);
		$this->assertEquals(200000, $rev);

		$quote_value = -0x12;
		$rev = $this->__db->quote($quote_value, \swan\db\sw_db::BIGINT_TYPE);
		$this->assertEquals(-18, $rev);

		$quote_value = 2.111111111111;
		$rev = $this->__db->quote($quote_value, \swan\db\sw_db::FLOAT_TYPE);
		$this->assertEquals(2.111111, $rev);
	}

	// }}}
	// {{{ public function test_quote_into()

	/**
	 * test_quote_into
	 * 
	 * @access public
	 * @return void
	 */
	public function test_quote_into()
	{
		$text = 'WHERE date < ?';
		$value = '2012-01-01';
		$rev = $this->__db->quote_into($text, $value);

		$this->assertEquals('WHERE date < \'2012-01-01\'', $rev);

		$text = 'WHERE date < ? name > ?';
		$value = '2012-01-01';
		$rev = $this->__db->quote_into($text, $value, null, 1);

		$this->assertEquals('WHERE date < \'2012-01-01\' name > ?', $rev);
	}

	// }}}
	// {{{ public function test_quote_table_as()

	/**
	 * test_quote_table_as
	 * 
	 * @access public
	 * @return void
	 */
	public function test_quote_table_as()
	{
		$sw_expr = $this->getMockBuilder('swan\db\sw_db_expr')
						  ->setConstructorArgs(array('aa'))
						  ->getMock();
		$sw_expr->expects($this->once())
				  ->method('__toString')
				  ->will($this->returnValue('aa'));

		$rev = $this->__db->quote_table_as($sw_expr);
		$this->assertEquals('aa', $rev);

		$sw_select = $this->getMockBuilder('swan\db\select\sw_select')
						  ->setConstructorArgs(array($this->__db))
						  ->getMock();
		$sw_select->expects($this->any())
				  ->method('assemble')
				  ->will($this->returnValue('user_id >= 1'));

		$rev = $this->__db->quote_table_as($sw_select);
		$this->assertEquals('(user_id >= 1)', $rev);

		$str = 'user';
		$alias = 'U';
		$rev = $this->__db->quote_table_as($str);
		$this->assertEquals('`user`', $rev);
		$rev = $this->__db->quote_table_as($str, $alias);
		$this->assertEquals('`user` AS `U`', $rev);

		$ident = array('user', 'user_name');
		$rev = $this->__db->quote_table_as($ident);
		$this->assertEquals('`user`.`user_name`', $rev);

		$str = 'user.user_name';
		$alias = 'user_name';
		$rev = $this->__db->quote_table_as($str, $alias);
		$this->assertEquals('`user`.`user_name`', $rev);

		$str = 'user';
		$rev = $this->__db->quote_table_as($str, null, true);
		$this->assertEquals('`user`', $rev);
	}

	// }}}
	// {{{ public function test_fold_case()

	/**
	 * test_fold_case
	 * 
	 * @access public
	 * @return void
	 */
	public function test_fold_case()
	{
		$str = 'aBc';

		$this->__db->fold_case($str);
		$this->assertEquals('aBc', $str);
	}

	// }}}
	// {{{ public function test_supports_parameters()

	/**
	 * test_supports_parameters
	 * 
	 * @access public
	 * @return void
	 */
	public function test_supports_parameters()
	{
		$type = 'named';
		$rev = $this->__db->supports_parameters($type);
		$this->assertEquals(true, $rev);

		$type = 'namedi1';
		$rev = $this->__db->supports_parameters($type);
		$this->assertEquals(false, $rev);
	}

	// }}}
	// {{{ public function test_get_server_version()

	/**
	 * test_supports_parameters
	 * 
	 * @access public
	 * @return void
	 */
	public function test_get_server_version()
	{
		$rev = $this->__db->get_server_version();
		$this->assertContains('5.', $rev);
	}

	// }}}
	// {{{ public function test_limit()

	/**
	 * test_limit 
	 * 
	 * @access public
	 * @return void
	 */
	public function test_limit()
	{
		// 1
		try {
			$this->__db->limit('', -1, 0);
		} catch (sw_exception $e) {
			$this->assertContains("LIMIT argument count=", $e->getMessage());	
		}

		// 2
		try {
			$this->__db->limit('', 2, -2);	
		} catch (sw_exception $e) {
			$this->assertContains("LIMIT argument offset=", $e->getMessage());	
		}

		// 3
		$rev = $this->__db->limit('', 2, 5);
		$this->assertEquals(' LIMIT 2 OFFSET 5', $rev);
	}

	// }}}
	// {{{ public function test__where_expr()

	/**
	 * test__where_expr 
	 * 
	 * @access public
	 * @return void
	 */
	public function test__where_expr()
	{
		$mock = new mock_mysql();
		// 1
		$where = array();
		$rev = $mock->mock_where_expr($where);
		$this->assertEquals(array(), $rev);
		
		// 2
		$where = array('id > ?' => 3, 'name = ?' => 'test');
		$rev = $mock->mock_where_expr($where);
		$this->assertEquals("(id > 3) AND (name = 'test')", $rev);
	}

	// }}}
	// }}}
}
