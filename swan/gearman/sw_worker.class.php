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
 
namespace swan\client;
use \swan\gearman\exception\sw_exception;
use \GearmanWorker;

/**
+------------------------------------------------------------------------------
* gearman work 模块 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_worker extends GearmanWorker
{
	// {{{ members

	/**
	 * function maps 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__func_maps = array();

	/**
	 * log object 
	 * 
	 * @var \swan\log\sw_log
	 * @access protected
	 */
	protected $__log = null;

	/**
	 * last response 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__last_response = '';

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// }}}
	// {{{ public function add_servers_by_config()

	/**
	 * 通过配置添加 server 
	 * 
	 * @param string $config_name 
	 * @access public
	 * @return void
	 */
	public function add_servers_by_config($config_name)
	{
		if (!is_array($config_name)) {
			$config_name = array($config_name);	
		}

		$servers_all = array();
		foreach ($config_name as $sig_config) {
			$servers = \swan\config\sw_config::get_config($sig_config);
			if (!$servers) {
				throw new exception("get config failed: $sig_config");	
			}
			$servers_all[] = trim($servers);
		}

		$servers_all = array_unique($servers_all);
		$servers_all = implode(',', $servers_all);

		if (!parent::addServers($servers_all)) {
			throw new sw_exception('add servers failed: '. $servers_all);
		}
	}

	// }}}
	// {{{ public function set_log()

	/**
	 * 设置日志对象 
	 * 
	 * @param \swan\log\sw_log $log 
	 * @access public
	 * @return void
	 */
	public function set_log($log)
	{
		$this->__log = $log;
	}

	// }}}
	// {{{ public function add_function()

	/**
	 * 添加处理函数 
	 * 
	 * @param string $func_name 
	 * @param string $callback 
	 * @access public
	 * @return void
	 */
	public function add_function($func_name, $callback)
	{
		if (!is_callable($callback)) {
			$call_func = '';
			switch (gettype($callback)) {
				case 'array':
					if (count($callback) <> 2
						|| !isset($callback[0], $callback[1])
						|| !is_string($callback[1])) {
						$call_func = 'Array';
					} elseif (is_object($callback[0])) {
						$call_func = get_class($callback[0]) . ':' . $callback[1];	
					} elseif (is_string($callback[0])) {
						$call_func = implode(':', $callback);
					} else {
						$call_func = 'Array';	
					}
					break;
				case 'object':
					$call_func = 'Object:' . get_class($callback);
					break;
				case 'resource':
					$call_func = 'Resource';
					break;

				default:
					$call_func = $callback;
					break;
			}	

			$errmsg = "GearmanWorker::addFunction(): function `$call_func` is not callable";
			throw new sw_exception($errmsg);
		}

		if (!parent::addFunction($func_name, array($this, 'do_job'))) {
			$call_func = __CLASS__ . ':' . 'do_job';
			$errmsg = "GearmanWorker::addFunction(): function `$call_func` is not callable";
			throw new sw_exception($errmsg);
		}

		$this->__func_maps[$func_name] = array('callback' => $callback);
	}

	// }}}
	// {{{ public function do_job()

	/**
	 * worker 统一入口 
	 * 
	 * @param handle $job 
	 * @access public
	 * @return void
	 */
	public function do_job($job)
	{
		$this->_log_request($job);
		$func_name = $job->functionName();
		if (!isset($this->__func_maps[$func_name]['callback'])) {
			$errmsg = "Call an unregistered function: $func_name";
			$this->_log($errmsg, LOG_INFO, $job);
			$job->sendData($errmsg);
			$this->send_fail($job);	
			return;
		}

		$func = $this->__func_maps[$func_name];

		if (!is_callable($func['callback'])) {
			$errmsg = "Call function failed: $func_name";
			$this->_log($errmsg, LOG_INFO, $job);
			$this->sendData($errmsg);
			$this->send_fail($job);
			return;
		}

		try {
			$rs = call_user_func($func['callback'], $job);
			$this->_log_response($job, $rs);
			return $rs;	
		} catch (\swan\exception\sw_exception $e) {
			$this->_log_error($job, $e);
			if (!$errmsg) {
				$errmsg .= ':System Error';	
			} else {
				$errmsg .= ':' . $e->getMessage();	
			}
			$job->sendData('Error:' . $errmsg);
			$this->send_fail($job);
			return;
		}
	}

	// }}}
	// {{{ public function work_daemon()

	/**
	 * 以 daemon 方式运行 
	 * 
	 * @access public
	 * @return void
	 */
	public function work_daemon()
	{
		while(parent::work());		
	}

	// }}}
	// {{{ protected function _log()
	
	/**
	 * 记录日志 
	 * 
	 * @param string $message 
	 * @param integer $priority 
	 * @param object $job 
	 * @access protected
	 * @return void
	 */
	protected function _log($message, $priority, $job = null)
	{
		if (!$this->__log) {
			return;	
		}	

		if ($job) {
			$message = $this->_format_log($message, $job);
		}

		$this->__log->log($message, $priority);
	}

	// }}}
	// {{{ protected function _format_log()

	/**
	 * 格式化日志 
	 * 
	 * @param string $message 
	 * @param object $job 
	 * @access protected
	 * @return string
	 */
	protected function _format_log($message, $job)
	{
		if (strlen($message) > 1024) {
			$message = substr($message, 0, 1024);	
		}

		return $job->functionName() . '(' . $job->handle() . ')' . $message;
	}

	// }}}
	// {{{ protected function _log_request()

	/**
	 * 记录请求日志 
	 * 
	 * @param object $job 
	 * @access protected
	 * @return void
	 */
	protected function _log_request($job)
	{
		$workload = $job->workload();
		if ('' === $workload) {
			$workload = '(EMPTY)';	
		}

		$this->log('Called:' . $workload, LOG_DEBUG, $job);
	}

	// }}}
	// {{{ protected function _log_response()

	/**
	 * 记录请求结果 
	 * 
	 * @param object $job 
	 * @param string $rs 
	 * @access protected
	 * @return void
	 */
	protected function _log_response($job, $rs)
	{
		$handle = $job->handle();
		if ($handle == $this->__last_response) {
			return;	
		}	

		$this->__last_response = $handle;

		if ('' === (string)$rs) {
			$rs = '(EMPTY)';	
		}
		$this->_log('Result:' . $rs, LOG_DEBUG, $job);
	}

	// }}}
	// {{{ protected function _log_error()

	/**
	 * 记录错误日志 
	 * 
	 * @param object $job 
	 * @param object $e 
	 * @access protected
	 * @return void
	 */
	protected function _log_error($job, $e)
	{
		$errmsg = $e->getCode() . ':' . $e->getMessage() . "\n" . (string) $e;
		$this->_log('Error:' . $errmsg, lOG_INFO, $job);
	}

	// }}}
	// {{{ public function send_data()

	/**
	 * send data
	 * 
	 * @param object $job 
	 * @param string $data 
	 * @access public
	 * @return void
	 */
	public function send_data($job, $data)
	{
		$this->_log($data, LOG_DEBUG, $job);
		return $job->sendData($data);
	}

	// }}}
	// {{{ public function send_fail()

	/**
	 * send fail 
	 * 
	 * @param object $job 
	 * @access public
	 * @return void
	 */
	public function send_fail($job)
	{
		$this->_log_response($job, 'Fail');
		return $job->sendFail();	
	}

	// }}}
	// }}}
}
