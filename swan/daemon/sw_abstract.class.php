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
use \swan\error\sw_error;

/**
+------------------------------------------------------------------------------
* 守护进程 
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
	 * 进程 ID 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__pid;

	/**
	 * 父进程 ID 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__ppid;

	/**
	 * PID 文件 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__pid_file;

	/**
	 * PID 句柄 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__pid_fp;

	/**
	 * 日志对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__log = null;

	// }}}	
	// {{{ functions
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
	// {{{ public function log()

	/**
	 * 记录日志 
	 * 
	 * @param string $message 
	 * @param int $priority 
	 * @access public
	 * @return void
	 */
	public function log($message, $priority)
	{
		if (!$this->__log) {
			return;	
		}	

		try {
			$this->__log->log($message, $priority);	
		} catch (exception $e) {
			echo $e;// 记录日志失败	
		}
	}

	// }}}
	// {{{ public function error_handle()

	/**
	 * 错误处理句柄 
	 * 
	 * @access public
	 * @return void
	 */
	public function error_handle()
	{
		$this->log(sw_error::error_to_string(func_get_args()), LOG_DEBUG);	
	}

	// }}}
	// {{{ public function exception_handle()

	/**
	 * exception_handle 
	 * 
	 * @access public
	 * @return void
	 */
	public function exception_handle($e)
	{
		$this->log((string)$e, LOG_INFO);
		exit(1);
	}

	// }}}
	// {{{ public function init_daemon()

	/**
	 * 初始化守护进程 
	 * 
	 * @access public
	 * @return void
	 */
	public function init_daemon()
	{
		// 忽略终端 I/O 信号，STOP信号
		pcntl_signal(SIGTTOU, SIG_IGN);	
		pcntl_signal(SIGTTIN, SIG_IGN);	
		pcntl_signal(SIGTSTP, SIG_IGN);	
		pcntl_signal(SIGHUP, SIG_IGN);	

		// 父进程退出，程序进入后台运行
		$pid = pcntl_fork();
		if ($pid < 0) {
			$this->log('init daemon, fork child faild.', LOG_INFO);
			exit(1);	
		} elseif ($pid) { // 父进程，退出
			exit(0);
		}

		// 设置子进程为组长
		if (posix_setsid() < 0) {
			$this->log('init daemon, set child for the leader faild.', LOG_INFO);
			exit(1);	
		}

		// 子进程退出，孙进程没有控制终端
		$pid = pcntl_fork();
		if ($pid < 0) {
			$this->log('init daemon, fork grandson faild.', LOG_INFO);
			exit(1);	
		} elseif ($pid) { // 子进程退出
			exit(0);
		}

		// 改变工作目录
		if (!chdir('/tmp')) {
			$this->log('init daemon, change dir to /tmp faild.', LOG_INFO);	
			exit(1);
		}

		// 关闭所有打开的资源
		//...

		// 重设文件创建掩模
		umask(0);

		if (!pcntl_signal(SIGTERM, array($this, 'handler_sigterm'))) {
			$this->log('init daemonm, set signal handler for SIGTERM faild.', LOG_INFO);
			exit(1);	
		}

		if (!pcntl_signal(SIGCHLD, array($this, 'handler_sigchild'))) {
			$this->log('init daemonm, set signal handler for SIGCHLD faild.', LOG_INFO);
			exit(1);	
		}

		$this->log('init daemon success', LOG_DEBUG);
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
		while (1) {
			sleep(1);	
		}	
	}

	// }}}
	// {{{ public function fork()

	/**
	 * 创建一个子进程 
	 * 
	 * @access public
	 * @return void
	 */
	public function fork()
	{
		$pid = pcntl_fork();
		if ($pid) {
			return $pid;	
		}	

		$this->__pid = posix_getpid();
		$this->__ppid = posix_getppid();

		pcntl_signal(SIGTERM, SIG_DFL);
		pcntl_signal(SIGCHLD, SIG_DFL);

		if (is_resource($this->__pid_fp)) {
			\dio_close($this->__pid_fp);	
		}

		return $pid;
	}

	// }}}
	// {{{ public function set_pid_file()

	/**
	 * 设置 PID 文件 
	 * 
	 * @param string $file 
	 * @access public
	 * @return void
	 */
	public function set_pid_file($file)
	{
		$this->__pid_file = $file;	
	}

	// }}}
	// {{{ public function lock_pid()

	/**
	 * 设置 PID文件 
	 * 
	 * @param string $file 
	 * @param boolean $write_pid 
	 * @access public
	 * @return boolean
	 */
	public function lock_pid($file = null, $write_pid = true)
	{
		if (!isset($file)) {
			$file = $this->__pid_file;
		}

		$fp = \dio_open($file, O_WRONLY | O_CREAT, 0644);
		if (!$fp || \dio_fcntl($fp, F_SETLK, array('type' => F_WRLCK))) {
			return false;	
		}

		if (!$write_pid) {
			return true;	
		}

		if (!\dio_truncate($fp, 0)) {
			return false;	
		}
		\dio_write($fp, posix_getpid());
		$this->__pid_fp = $fp;

		return true;
	}

	// }}}
	// {{{ public function set_exec_user()

	/**
	 * 转化脚本的用户和组 
	 * 
	 * @access public
	 * @return void
	 */
	public function set_exec_user($user_name = null, $group_name = null)
	{
		if ($group_name) {
			$posix_gid = posix_getgrnam($group_name);
			if (!$posix_gid) {
				$this->log("set_exec_user, The group `$group_name` not exists.", LOG_INFO);
				exit(1);	
			}	

			if (!posix_setgid($posix_gid['gid'])) {
				$this->log("set_exec_user, Switch to group `$group_name` faild.", LOG_INFO);	
				exit(1);
			}
			$this->log("Switch to group `$group_name` success.", LOG_DEBUG);
		}	

		if ($user_name) {
			$posix_uid = posix_getpwnam($user_name);
			if (!$posix_uid) {
				$this->log("set_exec_user, The user `$user_name` not exists.", LOG_INFO);
				exit(1);	
			}	

			if (!posix_setuid($posix_uid['uid'])) {
				$this->log("set_exec_user, Switch to user `$user_name` faild.", LOG_INFO);	
				exit(1);
			}
			$this->log("Switch to user `$user_name` success.", LOG_DEBUG);
		}	

		return true;
	}

	// }}}
	// {{{ public function handler_sigterm()

	/**
	 * SIGTERM 信号处理函数 
	 * 
	 * @access public
	 * @return void
	 */
	public function handler_sigterm()
	{
		$this->log('catch signal SIGTERM, exiting...', LOG_DEBUG);
		if (!pcntl_signal(SIGCHLD, SIG_IGN)) {
			$this->log('set signal handler for SIGTERM faild.', LOG_INFO);	
		}
		if (!posix_kill(0, SIGTERM)) {
			$this->log(posix_strerror(posix_get_last_error()), LOG_INFO);	
		}
		while (($pid = pcntl_waitpid(-1, $status, WNOHANG)) > 0) {
			if ($pid < 0) {
				break;	
			}
			$this->log("stop child $pid success.", LOG_DEBUG);
		}
		$this->_free_parent(posix_getpid());
		$this->log('exit by signal SIGTERM', LOG_DEBUG);
		exit(0);
	}

	// }}}
	// {{{ public function handler_sigchild()

	/**
	 * SIGCHLD 信号处理函数，子进程退出，回收资源等 
	 * 
	 * @access public
	 * @return void
	 */
	public function handler_sigchild()
	{
		$this->log('catch signal SIGCHLD', LOG_DEBUG);
		while (($pid = pcntl_waitpid(-1, $status, WNOHANG)) > 0) {
			if ($pid < 0) { // 无可回收的进程
				break;	
			}
			$this->log("child $pid exit, status: $status.", LOG_DEBUG);
			$this->_free_child($pid, $status);
			usleep(100000);
		}
	}

	// }}}
	// {{{ protected function _free_child()

	/**
	 * 回收子进程 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _free_child($pid, $status)
	{
		
	}

	// }}}
	// {{{ protected function _free_parent()

	/**
	 * 父进程退出 
	 * 
	 * @param mixed $pid 
	 * @access protected
	 * @return void
	 */
	protected function _free_parent($pid)
	{
			
	}

	// }}}
	// }}}
}
