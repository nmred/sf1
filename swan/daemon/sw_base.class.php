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
 
namespace swan\daemon;

/**
+------------------------------------------------------------------------------
* sw_base 
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
class sw_base extends sw_abstract
{
	// {{{ consts

	const SLEEP_SECS = 60; // 主进程休眠时间 

	// }}}
	// {{{ members

	/**
	 * 配置文件 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__ini_file;

	/**
	 * 是否初始化 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__has_init = false;

	/**
	 * 父进程的相关配置 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__parent_cfg;

	/**
	 * 子进程的相关配置 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__process_cfg;

	/**
	 * 保存子进程进程ID信息 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__pids = array();

	/**
	 * 日志模块的 LOG ID 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__log_id;

	/**
	 * message 对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__message;

	/**
	 * 进程名 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__proc_name;

	// }}}	
	// {{{ functions
	// {{{ public function set_ini_file()

	/**
	 * 设置配置文件 
	 * 
	 * @param string $file 
	 * @access public
	 * @return void
	 */
	public function set_ini_file($file)
	{	
		if (!file_exists($file)) {
			$this->log("config file not exists `{$file}`", LOG_INFO);
			exit(1);	
		}

		$this->__ini_file = $file;
	}

	// }}}
	// {{{ public function set_process_cfg()

	/**
	 * 设置进程的配置信息 
	 * 
	 * @param string $cfg 
	 * @access public
	 * @return void
	 */
	public function set_process_cfg($cfg)
	{
		if (isset($cfg['parent'])) {
			$this->__parent_cfg = $cfg['parent'];
			unset($cfg['parent']);	
		}
		$this->__process_cfg = $cfg;
	}

	// }}}
	// {{{ public function process_cfg()

	/**
	 * 解析配置 
	 * 
	 * @access public
	 * @return array 成功返回解析后台配置的数组，失败返回false
	 */
	public function process_cfg()
	{
		$this->log('this method must be overwrite', LOG_INFO);
		return false;	
	}

	// }}}
	// {{{ public function run()

	/**
	 * 运行 
	 * 
	 * @access public
	 * @return void
	 */
	public function run()
	{
		$process_cfg = $this->process_cfg();
		if (false === $process_cfg) {
			$this->log('process config failed.', LOG_INFO);
			exit(1);	
		}
		$this->set_process_cfg($process_cfg);

		$this->_init();

		// 初始化子进程
		$this->_init_process();

		sleep(1);
		$this->__has_init = true;

		while(1) {
			sleep(self::SLEEP_SECS);
			foreach ($this->__pids as $pid => $type) {
				if (!posix_kill($pid, 0)) {
					$this->log("child $pid id not active", LOG_DEBUG);
					pcntl_waitpid($pid, $status, WNOHANG); // 回收
					$this->_free_child($pid, $status);	
				}
			}
		}
	}

	// }}}
	// {{{ public function stop()
	
	/**
	 * 停止进程 
	 * 
	 * @access public
	 * @return void
	 */
	public function stop()
	{
		$pid = file_get_contents($this->__pid_file);
		if (!$pid) {
			$this->log('get the pid filed.', LOG_INFO);
			exit(1);	
		}

		if ($this->lock_pid(null, false)) {
			$this->log("process [$pid] is not runing.", LOG_INFO);
			exit(1);	
		}

		if (!posix_kill($pid, SIGTERM)) {
			$this->log(posix_strerror(posix_get_last_error()), LOG_INFO);
			exit(1);
		}
		return;
	}

	// }}}
	// {{{ public function watch()

	/**
	 * 查看进程状态 
	 * 
	 * @access public
	 * @return void
	 */
	public function watch()
	{
		
	}

	// }}}
	// {{{ protected function _init()

	/**
	 * 初始化 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init()
	{
		if (!$this->lock_pid()) {
			$this->log('The process has already running.', LOG_INFO);
			exit(1);	
		}	
		unset($this->__pid_fp);
		$this->init_daemon();
		if (!$this->lock_pid()) {
			$this->log('The process has already runing.', LOG_INFO);	
			exit(1);
		}

		if (!pcntl_signal(SIGUSR1, array($this, 'handler_sigusr1'))) {
			$this->log('set signal handler for SIGUSR1 failed.', LOG_INFO);
			exit(1);
		}

		if (!pcntl_signal(SIGUSR2, array($this, 'handler_sigusr2'))) {
			$this->log('set signal handler for SIGUSR2 failed.', LOG_INFO);
			exit(1);
		}

		if (!pcntl_signal(SIGHUP, array($this, 'handler_sighup'))) {
			$this->log('set signal handler for SIGHUP failed.', LOG_INFO);
			exit(1);
		}

		// 关闭一切资源
	}

	// }}}
	// {{{ protected function _init_process()

	/**
	 * 初始化子进程 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_process()
	{
		$this->log('init child process ...', LOG_DEBUG);
		foreach ($this->__process_cfg as $proc_name => $cfg) {
			while ($cfg['max_process'] > 0) {
				if (false === $this->_fork($proc_name)) {
					$this->log("fork child `$proc_name` faild, exit", LOG_INFO);
					$this->__pids = array();
					posix_kill(0, SIGTERM);
					exit(1);
				}
				$cfg['max_process']--;
				usleep(100000);
			}
		}
	}

	// }}}
	// {{{ protected function _fork()

	/**
	 * 创建子进程 
	 * 
	 * @param string $proc_name 
	 * @access protected
	 * @return void
	 */
	protected function _fork($proc_name)
	{
		$this->log("fork child $proc_name ...", LOG_DEBUG);
		if (!isset($this->__process_cfg[$proc_name])) {
			$this->log("The process `$proc_name` is not allow.", LOG_INFO);
			return false;	
		}

		if ($this->_is_max_process($proc_name)) {
			$this->log("has max processs for `$proc_name`.", LOG_INFO);
			return false;	
		}

		$process = '_process_' . $proc_name;
		if (isset($this->__process_cfg[$proc_name]['exec'])) {
			$type = 'exec';	
		} elseif (method_exists($this, $process)) {
			$type = 'method';	
		} else {
			$type = 'implements';
			$this->log("will process `$proc_name`, method `$process`", LOG_INFO);	
		}

		$this->_pause();

		$pid = $this->fork();
		if ($pid < 0) {
			$this->_resume();
			$this->log(posix_strerror(posix_get_last_error()), LOG_INFO);
			return false;	
		} elseif ($pid) {
			$this->_resume();
			$this->__pids[$pid] = $proc_name;
			$this->log("fork child `$proc_name` success, pid: $pid", LOG_DEBUG);
			
			return $pid;	
		}

		// 子进程
		$this->_init_log($proc_name, $this->__process_cfg[$proc_name]['debug']);
		$this->log('starting...', LOG_DEBUG);

		$this->__pids = null;

		switch ($type) {
			case 'exec':
				pcntl_exec($this->__process_cfg[$proc_name]['exec']);
				break;
			case 'method':
				$this->$process();	
				break;
			case 'implements':
				$proc_class_name = $this->get_implement_class_name($proc_name);
				$this->_run_process($proc_name, $proc_class_name);
				break;	 
			default:
				break;
		}

		$this->log('exit.', LOG_DEBUG);
		exit(0);
	}

	// }}}
	// {{{ protected function _run_process()

	/**
	 * 运行子进程 
	 * 
	 * @param string $proc_name 
	 * @param string $class_name 
	 * @access protected
	 * @return void
	 */
	protected function _run_process($proc_name, $class_name)
	{
		$proc = new $class_name();
		$proc->set_log($this->__log);
		$proc->set_message($this->__message);
		$proc->set_proc_config($this->__process_cfg[$proc_name]);
		$this->log("process `$proc_name` runing...", LOG_DEBUG);
		$proc->run();
	}

	// }}}
	// {{{ protected function _is_max_process()

	/**
	 * 是否到达最大进程数 
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function _is_max_process($proc_name)
	{
		$proc_info = array_count_values($this->__pids);
		if (!isset($proc_info[$proc_name])) {
			return false;	
		}

		if (!isset($this->__process_cfg[$proc_name]['max_process'])) {
			return false;	
		}

		return $proc_info[$proc_name] >= $this->__process_cfg[$proc_name]['max_process'];
	}

	// }}}
	// {{{ protected function _pause()

	/**
	 * fork 子前暂停一切资源，避免不可预料的结果
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _pause()
	{
		$this->__log = null;
		$this->__message = null;
	}

	// }}}
	// {{{ protected function _resume()

	/**
	 * fork 完成，恢复资源 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _resume()
	{
		$this->_init_log($this->__proc_name, $this->__parent_cfg['debug']);
		if (!$this->__has_init) {
			return;	
		}
	}

	// }}}
	// {{{ protected function _init_log()

	/**
	 * 初始化日志对象 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _init_log($proc_name, $debug)
	{		
		$this->__message = $this->_get_log_message($proc_name);
		$writer = \swan\log\sw_log::writer_factory('logsvr', array('log_id' => $this->__log_id));
		$log = new \swan\log\sw_log();
		$log->add_writer($writer);
		$this->set_log($log);
	}

	// }}}
	// {{{ protected function _get_log_message()
	
	/**
	 * 获取日志的 message 对象 
	 * 
	 * @param string $proc_name 
	 * @access protected
	 * @return void
	 */
	protected function _get_log_message($proc_name)
	{
		$message = \swan\log\sw_log::message_factory($this->__proc_name);
		$message->proc_name = $proc_name;
		return $message;	
	}
	 
	// }}}
	// {{{ public function handler_sighup()

	/**
	 * 处理重置配置信号 
	 * 
	 * @access public
	 * @return void
	 */
	public function handler_sighup()
	{
		
	}

	// }}}
	// {{{ public function handler_sigusr1()

	/**
	 * handler_sigusr1 
	 * 
	 * @access public
	 * @return void
	 */
	public function handler_sigusr1()
	{
		
	}

	// }}}
	// {{{ public function handler_sigusr2()

	/**
	 * handler_sigusr2
	 * 
	 * @access public
	 * @return void
	 */
	public function handler_sigusr2()
	{
		
	}

	// }}}
	// {{{ protected function _free_parent()

	/**
	 * 父进程退出 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _free_parent($pid)
	{
		
	}

	// }}}
	// {{{ protected function _free_child()

	/**
	 * _free_child 
	 * 
	 * @param mixed $pid 
	 * @param mixed $status 
	 * @access protected
	 * @return void
	 */
	protected function _free_child($pid, $status)
	{
		if (!isset($this->__pids[$pid])) {
			return;	
		}	

		$proc_name = $this->__pids[$pid];
		unset($this->__pids[$pid]);

		$this->log("free child {$proc_name}[{$pid}], child status: $status", LOG_DEBUG);

		if (!$this->__has_init) { // 初始化阶段
			if (pcntl_wifexited($status) && !pcntl_wexitstatus($status)) { // 正常退出
				return;	
			}
			$this->__pids = array();
			posix_kill(0, SIGTERM);
			$this->log("fork process `$proc_name` faild, exit.", LOG_INFO);
			exit(1);
		}
	}

	// }}}
	// }}}
}
