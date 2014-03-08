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
use \GearmanClient;

/**
+------------------------------------------------------------------------------
* gearman client 模块 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_client extends GearmanClient
{
	// {{{ members
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
		$this->setTimeout(30000);	
	}

	// }}}
	// {{{ public function add_servers()

	/**
	 * 添加 server 
	 * 
	 * @access public
	 * @return void
	 */
	public function add_servers($servers)
	{
		if (!parent::addServers($servers)) {
			throw new sw_exception("add Servers failed: $servers");	
		}	
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
		$servers = \swan\config\sw_config::get_config($config_name);
		if (!$servers) {
			throw new sw_exception("get config failed: $config_name");
		}

		$this->add_servers($servers);
	}

	// }}}
	// {{{ public function do_job()

	/**
	 * 同步调用 
	 * 
	 * @param string $func_name 
	 * @param string $workload 
	 * @param handle $rs_handle 
	 * @access public
	 * @return void
	 */
	public function do_job($func_name, $workload = '', $rs_handle = null)
	{
		$job_handle = parent::doJobHandle();
		$rs = '';
		while(1) {
			$part_data = parent::doHigh($func_name, $workload);
			if ($job_handle && $job_handle == parent::doJobHandle()) {
				continue;	
			}

			$return_code = parent::returnCode();
			if ($this->timeout() >= 0) {
				$this->setTimeout(-1);	
			}
			switch($return_code) {
				case GEARMAN_WORK_DATA:
					if ($rs_handle) {
						fwrite($rs_handle, $part_data);	
					} else {
						$rs .= $part_data;	
					}
					break;

				case GEARMAN_WORK_STATUS:
					break;

				case GEARMAN_SUCCESS:
					if ($rs_handle) {
						fwrite($rs_handle, $part_data);	
					} else {
						$rs .= $part_data;	
					}
					break;
				default:
					$errmsg = parent::error();
					if (!$errmsg) {
						$errmsg = $part_data ? $part_data : $rs;	
					}
					throw new sw_exception($errmsg, $return_code);
			}
		}
	}

	// }}}
	// }}}
}
