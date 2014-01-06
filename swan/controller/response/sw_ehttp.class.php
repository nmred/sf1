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

namespace swan\controller\response;
use swan\controller\response\sw_abstract;

/**
* HTTP 响应类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_ehttp extends sw_abstract
{
    // {{{ const
    // }}}
    // {{{ members

    /**
     * Event 的 http request 
     * 
     * @var mixed
     * @access protected
     */
    protected $__http_request = null;

    /**
     * body 体 
     * 
     * @var array
     * @access protected
     */
    protected $__body = array();

    /**
     * 头信息 
     * 
     * @var array
     * @access protected
     */
    protected $__headers = array();

    /**
     * 响应状态码 
     * 
     * @var float
     * @access protected
     */
    protected $__http_response_code = 200;

    /**
     * HTTP 状态码描述 
     * 
     * @var string
     * @access protected
     */
    protected $__code_reason = null;

    // }}}
    // {{{ functions
    // {{{ protected function __construct()

    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct($request)
    {
        $this->__http_request = $request;
        $this->__http_response_code = 200;
        $this->__headers = array();
        $this->__body = array();
    }

    // }}}
    // {{{ public function set_body()

    /**
     * 设置响应内容 
     * 
     * @param string $content 
     * @param string $name 
     * @access public
     * @return em_httpapi_response_phphttp
     */
    public function set_body($content, $name = null)
    {
        if ((null === $name) || !is_string($name)) {
            $this->__body = array('default' => (string) $content);  
        } else {
            $this->__body[$name] = (string) $content;   
        }

        return $this;
    }

    // }}}
    // {{{ public function output_body()

    /**
     * 输出内容 
     * 
     * @access public
     * @return void
     */
    public function output_body()
    {
        $body = implode('', $this->__body);
        echo $body; 
    }

    // }}}
    // {{{ public function get_body()

    /**
     * 获取缓存中的内容 
     * 
     * @param boolean|string $spec 
     * @access public
     * @return null|string
     */
    public function get_body($spec = false)
    {
        if (false === $spec) {
            ob_start();
            $this->output_body();
            return ob_get_clean();
        } else if (true === $spec) {
            return $this->__body;   
        } else if(is_string($spec) && isset($this->__body[$spec])) {
            return $this->__body[$spec];
        }

        return null;
    }

    // }}}
    // {{{ public function send_response()
    
    /**
     * 发送响应数据 
     * 
     * @access public
     * @return void
     */
    public function send_response()
    {
        $code = $this->get_response_code();
        $code_reason = $this->get_code_reason();
        $body = $this->get_body();

		if ($this->is_exception()) {
			$code = 503;
			$body = '';
			if ($this->render_exceptions()) {
				$exceptions = '';
				foreach ($this->get_exception() as $e) {
					$exceptions .= $e->__toString() . PHP_EOL;  
				}

				$body = $exceptions;
			}
		}


        // 设置头信息
        $charset = 'text/html;charset=utf-8';
        $this->__http_request->add_header('Content-Type', $charset, \swan\ehttp\sw_request::OUTPUT_HEADER, false);
        $this->__http_request->send_reply($code, $code_reason, $body);
    }

    // }}}send_response
    // {{{ public function send_error()

    /**
     * 发送错误的 HTTP 状态 
     * 
     * @param mixed $code 
     * @param mixed $code_reason 
     * @access public
     * @return void
     */
    public function send_error($code, $code_reason = null)
    {
        $this->__http_request->send_error($code, $code_reason); 
    }

    // }}}
    // {{{ public function set_file()

    /**
     * 响应一个文件 
     * 
     * @param string $file_path 
     * @access public
     * @return void
     */
    public function set_file($file_path)
    {
        $this->__http_request->set_file($file_path); 
    }

    // }}}
    // {{{ public function set_header()

    /**
     * 设置响应头 
     * 
     * @param string $key 
     * @param string $value 
     * @access public
     * @return void
     */
    public function set_header($key, $value)
    {
        $this->__http_request->add_header($key, $value, \swan\ehttp\sw_request::OUTPUT_HEADER);
    }

    // }}}
    // {{{ public function set_response_code()

    /**
     * 设置响应的 CODE 
     * 
     * @access public
     * @return em_httpapi_response_phphttp
     */
    public function set_response_code($code)
    {   
        if (!is_int($code) || (100 > $code) || (599 < $code)) {
            throw new sw_exception('Invalid HTTP response code');
        }

        $this->__http_response_code = $code;
        return $this;
    }

    // }}}
    // {{{ public function get_response_code()

    /**
     * 获取 http 响应状态码 
     * 
     * @access public
     * @return int
     */
    public function get_response_code()
    {
        return $this->__http_response_code; 
    }

    // }}}
    // {{{ public function get_code_reason()

    /**
     * 获取 HTTP 的状态码描述 
     * 
     * @access public
     * @return void
     */
    public function get_code_reason()
    {
        return $this->__code_reason;     
    }

    // }}}
    // {{{ public function set_code_reason()

    /**
     * 设置 HTTP 状态码描述 
     * 
     * @param string $reason 
     * @access public
     * @return em_httpapi_response_phphttp
     */
    public function set_code_reason($reason = null)
    {
        if (!is_string($reason)) {
            throw new sw_exception('Invalid HTTP response code reason');
        } 

        $this->__code_reason = $reason;
        return $this;
    }

    // }}}
    // }}}
}
