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
		
	}

	// }}}
	// }}}
}
