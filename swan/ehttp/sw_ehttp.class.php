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
 
namespace swan\ehttp;
use \swan\ehttp\exception\sw_exception;

/**
+------------------------------------------------------------------------------
* sw_ehttp 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_ehttp
{
    // {{{ const

    /**
     * 定义 HTTP 方法
     */
    const CMD_GET  = \EventHttpRequest::CMD_GET;
    const CMD_POST = \EventHttpRequest::CMD_POST;

    /**
     * 默认主机
     */
    const DEFAULT_HOST = '0.0.0.0';

    /**
     * 默认端口
     */
    const DEFAULT_PORT = '80';

    // }}}
    // {{{ members

    /**
     * Event Http
     *
     * @var mixed
     * @access protected
     */
    protected $__http = null;

    /**
     * 允许的 HTTP 方法
     *
     * @var array
     * @access protected
     */
    protected $__allow_methods = array(
        'get'  => self::CMD_GET,
        'post' => self::CMD_POST,
    );

    /**
     * 服务器 IP 
     * 
     * @var mixed
     * @access protected
     */
    protected $__service_ip = self::DEFAULT_HOST;

    /**
     * 服务器端口 
     * 
     * @var int
     * @access protected
     */
    protected $__service_port = self::DEFAULT_PORT;

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($event_base, $params)
    {
        if (!($event_base instanceof \EventBase)) {
			throw new sw_exception('event base is invalid.');
        }
        $this->__http = new \EventHttp($event_base);
        if (!($this->__http instanceof \EventHttp)) {
			throw new sw_exception('init event http fail.');
        }

        // 设置主机名
        if (isset($params['server_host'])) {
            if (strpos($params['server_host'], ':')) {
                list($this->__service_ip, $this->__service_port) = explode(':', $params['server_host']);
            } else {
                $this->__service_ip = $params['server_host'];
            }
        }

        // 设置端口
        if (isset($params['server_port'])) {
            $this->__service_port = $params['server_port'];    
        }
    }

    // }}}
    // {{{ public function bind()

    /**
     * 绑定主机 
     * 
     * @access public
     * @return void
     */
    public function bind()
    {
        $bind = $this->__http->bind($this->__service_ip, $this->__service_port);
        return $bind;
    }

    // }}}
    // {{{ public function set_allow_methods()

    /**
     * 设置允许的 HTTP 方法
     *
     * @param array $allow_method
     * @access public
     * @return em_httpapi_phphttp_server
     */
    public function set_allow_methods(array $allow_method)
    {
        if (empty($allow_method)) {
            return $this;
        }

        $flag = 0;
        foreach ($allow_method as $method) {
            if (!array_key_exists($method, $this->__allow_methods)) {
                continue;
            }

            $flag |= $this->__allow_methods[$method];
        }

        if ($flag) {
            $this->__http->setAllowedMethods($flag);
        }

        return $this;
    }

    // }}}
    // {{{ public function add_alias()

    /**
     * 添加别名
     *
     * @param string $alias_name
     * @access public
     * @return em_httpapi_phphttp_http
     */
    public function add_alias($alias_name)
    {
        if (!is_string($alias_name)) {
			throw new sw_exception('Add server alias name must is string.');
        }

        if (!$this->__http->addServerAlias($alias_name)) {
			throw new sw_exception('Add server alias fail.');
        }

        return $this;
    }

    // }}}
    // {{{ public function remove_alias()

    /**
     * 删除别名
     *
     * @param string $alias_name
     * @access public
     * @return em_httpapi_phphttp_http
     */
    public function remove_alias($alias_name)
    {
        if (!is_string($alias_name)) {
			throw new sw_exception('Remove server alias name must is string.');
        }

        if (!$this->__http->removeServerAlias($alias_name)) {
			throw new sw_exception('Remove server alias fail.');
        }

        return $this;
    }

    // }}}
    // {{{ public function set_max_headers_size()

    /**
     * 设置 server 的头信息最大值
     *
     * @param integer $size
     * @access public
     * @return em_httpapi_phphttp_http
     */
    public function set_max_headers_size($size)
    {
        if (!is_numeric($size)) {
			throw new sw_exception('Set server max headers size must is integer.');
        }

        $this->__http->setMaxHeadersSize($size);
        return $this;
    }

    // }}}
    // {{{ public function set_max_body_size()

    /**
     * 设置 HTTP body 的最大值
     *
     * @access public
     * @param int $size
     * @return em_httpapi_phphttp_http
     */
    public function set_max_body_size($size)
    {
        if (!is_numeric($size)) {
			throw new sw_exception('Set server max body size must is integer.');
        }

        $this->__http->setMaxBodySize($size);
        return $this;
    }

    // }}}
    // {{{ public function set_default_callback()

    /**
     * 设置默认回调
     *
     * @access public
     * @return void
     */
    public function set_default_callback($callback, $params = null)
    {
        if (!is_callable($callback)) {
			throw new sw_exception('Set default callback is disable.');
        }

        $this->__http->setDefaultCallback($callback, $params);
        return $this;
    }

    // }}}
    // {{{ public function set_timeout()

    /**
     * 设置请求响应的超时时间
     *
     * @param int $value
     * @access public
     * @return em_httpapi_phphttp_http
     */
    public function set_timeout($value)
    {
        if (!is_numeric($value)) {
			throw new sw_exception('Set server request timeout must is integer.');
        }

        $this->__http->setTimeout($value);
        return $this;
    }

    // }}}
    // }}}
}
