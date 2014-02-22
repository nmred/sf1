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

namespace swan\curl;
 
/**
* curl 调用 HTTP 类
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
*/
class sw_curl
{
	// {{{ const

	const METHOD_POST = 1;
	const METHOD_GET  = 2;

	// }}}
	// {{{ members

	/**
	 * curl 资源对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__curl = null;

	/**
	 * 请求参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__params = array();

	/**
	 * curl 调用参数 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__options = array();

	/**
	 * 请求方式 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__method;

	/**
	 * 请求地址 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $__url = '';

	// }}}
	// {{{ functions
	// {{{ public function __construct()
	
	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($url, $method = self::METHOD_POST)
	{
		$this->__url = $url;
		if (in_array($method, array(self::METHOD_POST, self::METHOD_GET))) {
			$this->__method = $method;	
		} else {
			$this->__method = self::METHOD_POST;	
		}
	}

	// }}}
	// {{{ public function set_options()

	/**
	 * 设置参数 
	 * 
	 * @param array $options 
	 * @access public
	 * @return void
	 */
	public function set_options($options)
	{
		if (!is_array($options)) {
			$options = array($options);	
		}

		$this->__options = $options;
		return $this;
	}

	// }}}
	// {{{ public function set_params()

	/**
	 * 设置参数 
	 * 
	 * @param array $params 
	 * @access public
	 * @return void
	 */
	public function set_params($params)
	{
		if (!is_array($params)) {
			$params = array($params);	
		}

		if (!empty($params)) {
			$this->__params = http_build_query($params);
		}
		return $this;
	}

	// }}}
	// {{{ public function call()

	/**
	 * 调用 http 请求 
	 * 
	 * @access public
	 * @return void
	 */
	public function call()
	{
		if ($this->__method == self::METHOD_GET) {
			$url = $this->__url . $this->__params;	
		} else {
			$url = $this->__url;	
		}
		$this->__curl = curl_init($url);	
		curl_setopt($this->__curl, CURLOPT_HEADER, false);
		curl_setopt($this->__curl, CURLOPT_RETURNTRANSFER , 1);

		if ($this->__method == self::METHOD_POST) {
			curl_setopt($this->__curl, CURLOPT_POST, 1);
			curl_setopt($this->__curl, CURLOPT_POSTFIELDS, $this->__params);
		}

		if (!empty($this->__options)) {
			curl_setopt_array($this->__curl, $this->__options);
		}

		$data = curl_exec($this->__curl);
		return $data;
	}

	// }}}
	// }}}
}
