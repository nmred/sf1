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

namespace lib\controller\action;
use lib\controller\action\exception\sw_exception;

/**
* 助手-经纪人类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_broker_stack implements IteratorAggregate, ArrayAccess, Countable
{
	// {{{ consts
	// }}}
	// {{{ members

	/**
	 * __helpers_by_priority 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__helpers_by_priority = array();

	/**
	 * __helpers_by_name_ref 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__helpers_by_name_ref = array();

	/**
	 * 默认的索引的起始值 
	 * 
	 * @var float
	 * @access protected
	 */
	protected $__next_default_priority = 1;

	// }}}
	// {{{ functions
	// {{{ public function __get()

	/**
	 * 获取属性的魔术方法 
	 * 
	 * @param string $helper_name 
	 * @access public
	 * @return boolean|\lib\controller\action\helper\sw_abstract
	 */
	public function __get($helper_name)
	{
		if (!array_key_exists($helper_name, $this->__helpers_by_name_ref)) {
			return false;	
		}

		return $this->__helpers_by_name_ref[$helper_name];
	}

	// }}}
	// {{{ public function __isset()

	/**
	 * __isset 
	 * 
	 * @param string $helper_name 
	 * @access public
	 * @return boolean
	 */
	public function __isset($helper_name)
	{
		return array_key_exists($helper_name, $this->__helpers_by_name_ref);	
	}

	// }}}
	// {{{ public function __unset()

	/**
	 * __unset 
	 * 
	 * @param string $helper_name 
	 * @access public
	 * @return void
	 */
	public function __unset($helper_name)
	{
		return $this->offsetUnset($helper_name);	
	}

	// }}}
	// {{{ public function push()

	/**
	 * 添加一个助手 
	 * 
	 * @param \lib\controller\actionhelper\sw_abstract $helper 
	 * @access public
	 * @return \lib\controller\action\sw_broker_stack
	 */
	public function push(helper\sw_abstract $helper)
	{
		$this->offsetSet($this->get_next_free_higher_priority(), $helper);
		return $this;	
	}

	// }}}
	// {{{ public function getIterator()

	/**
	 * 获取一个迭代器 
	 * 
	 * @access public
	 * @return array
	 */
	public function getIterator()
	{
		return new ArrayObject($this->__helpers_by_priority);	
	}

	// }}}
	// {{{ public function offsetExists()

	/**
	 * 实现接口中的 offsetExists 方法 
	 * 
	 * @param string|int $helper_name_or_priority 
	 * @access public
	 * @return boolean
	 */
	public function offsetExists($helper_name_or_priority)
	{
		if (is_string($helper_name_or_priority)) {
			return array_key_exists($helper_name_or_priority, $this->__helpers_by_name_ref);	
		} else {
			return array_key_exists($helper_name_or_priority, $this->__helpers_by_priority);	
		}
	}

	// }}}
	// {{{ public function offsetGet()

	/**
	 * 实现接口 offsetGet 方法
	 * 
	 * @param string|int $helper_name_or_priority 
	 * @access public
	 * @return \lib\controller\action\helper\sw_abstract 
	 */
	public function offsetGet($helper_name_or_priority)
	{
		if (!$this->offsetExists($helper_name_or_priority)) {
			throw new sw_exception('A helper with priority ' . $helper_name_or_priority . ' does not exist.');	
		}	

		if (is_string($helper_name_or_priority)) {
			return $this->__helpers_by_name_ref[$helper_name_or_priority];	
		} else {
			return $this->__helpers_by_priority[$helper_name_or_priority];	
		}
	}

	// }}}
	// {{{ public function offsetSet()

	/**
	 * 实现接口 offsetSet 方法 
	 * 
	 * @param int $priority 
	 * @param \lib\controller\action\helper\sw_abstract $helper 
	 * @access public
	 * @return \lib\controller\action\sw_broker_stack
	 */
	public function offsetSet($priority, $helper)
	{
		$priority = (int) $priority;
		
		if (array_key_exists($helper->get_name(), $this->__helpers_by_name_ref)) {
			$this->offsetUnset($helper->get_name());	
		}

		if (array_key_exists($priority, $this->__helpers_by_priority)) {
			$priority = $this->get_next_free_higher_priority($priority);
			trigger_error("A helper with the same priority already exists, reassigning to $priority", E_USER_WARNING);	
		}

		$this->__helpers_by_priority[$priority] = $helper;
		$this->__helpers_by_name_ref[$helper->get_name()] = $helper;

		if ($priority == ($next_free_default = $this->get_next_free_higher_priority($this->__next_default_priority))) {
			$this->__next_default_priority = $next_free_default;	
		}

		// 按键名倒序排序
		krsort($this->__helpers_by_priority);
		return $this;
	}

	// }}}
	// {{{ public function offsetUnset()

	/**
	 * 实现接口 offsetUnset 方法 
	 * 
	 * @param string|int $helper_name_or_priority 
	 * @access public
	 * @return \lib\controller\action\sw_broker_stack
	 */
	public function offsetUnset($helper_name_or_priority)
	{
		if (!$this->offsetExists($helper_name_or_priority)) {
			throw new sw_exception('A helper with priority ' . $helper_name_or_priority . ' does not exist.');	
		}

		if (is_string($helper_name_or_priority)) {
			$helper_name = $helper_name_or_priority;
			$helper = $this->__helpers_by_priority[$helper_name];
			$priority = array_search($helper, $this->__helpers_by_priority, true);	
		} else {
			$priority = $helper_name_or_priority;
			$helper_name = $this->__helpers_by_priority[$helper_name_or_priority]->get_name();	
		}

		unset($this->__helpers_by_priority[$priority]);
		unset($this->__helpers_by_name_ref[$helper_name]);
		return $this;
	}

	// }}}
	// {{{ public function count()

	/**
	 * 统计助手个数 
	 * 
	 * @access public
	 * @return int
	 */
	public function count()
	{
		return count($this->__helpers_by_priority);	
	}

	// }}}
	// {{{ public function get_next_free_higher_priority()

	/**
	 * 获取数值更高的没有被利用的KEY 
	 * 
	 * @param int $index_priority 
	 * @access public
	 * @return int
	 */
	public function get_next_free_higher_priority($index_priority = null)
	{
		if ($index_priority === null) {
			$index_priority = $this->__next_default_priority;	
		}

		$priorities = array_keys($this->__helpers_by_priority);

		while (in_array($index_priority, $priorities)) {
			$index_priority++;	
		}

		return $index_priority;
	}

	// }}}
	// {{{ public function get_next_free_lower_priority()

	/**
	 * 获取数值更低的没有被利用的KEY 
	 * 
	 * @param int $index_priority 
	 * @access public
	 * @return int
	 */
	public function get_next_free_lower_priority($index_priority = null)
	{
		if ($index_priority === null) {
			$index_priority = $this->__next_default_priority;	
		}

		$priorities = array_keys($this->__helpers_by_priority);

		while (in_array($index_priority, $priorities)) {
			$index_priority--;	
		}

		return $index_priority;
	}

	// }}}
	// {{{ public function get_highest_priority()

	/**
	 * 获取最大的索引数组的KEY 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_highest_priority()
	{
		return max(array_keys($this->__helpers_by_priority));	
	}

	// }}}
	// {{{ public function get_lowest_priority()

	/**
	 * 获取最小的索引数组的KEY 
	 * 
	 * @access public
	 * @return int
	 */
	public function get_lowest_priority()
	{
		return min(array_keys($this->__helpers_by_priority));	
	}

	// }}}
	// {{{ public function get_helpers_by_name()

	/**
	 * 获取所有的助手 
	 * 
	 * @access public
	 * @return array
	 */
	public function get_helpers_by_name()
	{
		return $this->__helpers_by_name_ref;	
	}

	// }}}
	// }}}
}
