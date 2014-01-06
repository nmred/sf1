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
* sw_request 
+------------------------------------------------------------------------------
* 
* @package 
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$ 
+------------------------------------------------------------------------------
*/
class sw_request
{
    // {{{ const

    /**
     * header 操作的类型 
     */
    const INPUT_HEADER  = \EventHttpRequest::INPUT_HEADER;
    const OUTPUT_HEADER = \EventHttpRequest::OUTPUT_HEADER;

    // }}}
    // {{{ members

    /**
     * EventRequestHttp 对象
     * 
     * @var EventRequestHttp
     * @access protected
     */
    protected $__http_request = null;

    /**
     * 实例 
     * 
     * @var \swan\ehttp\sw_request
     * @access protected
     */
    protected static $__instance = null;

    /**
     * HTTP 请求方法 
     * 
     * @var array
     * @access protected
     */
    protected static $__method_map = array(
        \EventHttpRequest::CMD_GET  => 'GET',
        \EventHttpRequest::CMD_POST => 'POST',
        \EventHttpRequest::CMD_HEAD => 'HEAD',
        \EventHttpRequest::CMD_PUT  => 'PUT',
        \EventHttpRequest::CMD_DELETE  => 'DELETE',
        \EventHttpRequest::CMD_OPTIONS => 'OPTIONS',
    );

    /**
     * 是否已经响应 
     * 
     * @var boolean
     * @access protected
     */
    protected $__is_reply = false;

    // }}}
    // {{{ functions
    // {{{ protected function __construct()

    /**
     * __construct 
     * 
     * @access protected
     * @return void
     */
    protected function __construct()
    {
    }

    // }}}
    // {{{ public static function get_instance()

    /**
     * 获取实例 
     * 
     * @static
     * @access public
     * @return void
     */
    public static function get_instance()
    {
        if (!isset(self::$__instance)) {
            self::$__instance = new self();    
        }

        return self::$__instance;
    }

    // }}}
    // {{{ public function init()

    /**
     * 初始化
     *
     * @access public
     * @return void
     */
    public function init(\EventHttpRequest $request)
    {
        $this->__http_request = $request;
        $this->__is_reply = false;
    }

    // }}}
    // {{{ public function read_input_buffer()

    /**
     * 获取请求的 buffer 
     * 
     * @param int $max_size 
     * @access public
     * @return string
     */
    public function read_input_buffer($max_size = 1024)
    {
        return $this->_read_event_buffer($this->__http_request->getInputBuffer(), $max_size);
    }

    // }}}
    // {{{ public function add_input_buffer()

    /**
     * 添加内容到请求的 buffer 
     * 
     * @param string $content 
     * @access public
     * @return EventBuffer
     */
    public function add_input_buffer($content)
    {
        return $this->_add_event_buffer($this->__http_request->getInputBuffer(), $content);
    }

    // }}}
    // {{{ public function get_input_header()

    /**
     * 获取请求的 header 
     * 
     * @param null|string $header 
     * @access public
     * @return string
     */
    public function get_input_header($header = null)
    {
        $headers = $this->__http_request->getInputHeaders();
        if (null === $header) {
            return $headers;
        }

        return isset($headers[$header]) ? $headers[$header] : null;
    }

    // }}}
    // {{{ public function read_output_buffer()

    /**
     * 获取响应的 buffer 
     * 
     * @param int $max_size 
     * @access public
     * @return string
     */
    public function get_output_buffer($max_size = 1024)
    {
        return $this->_read_event_buffer($this->__http_request->getOutputBuffer(), $max_size);
    }

    // }}}
    // {{{ public function add_output_buffer()

    /**
     * 添加内容到响应的 buffer 
     * 
     * @param string $content 
     * @access public
     * @return EventBuffer
     */
    public function add_output_buffer($content)
    {
        return $this->_add_event_buffer($this->__http_request->getOutputBuffer(), $content);
    }

    // }}}
    // {{{ public function get_output_header()

    /**
     * 获取请求的 header 
     * 
     * @param null|string $header 
     * @access public
     * @return string
     */
    public function get_output_header($header = null)
    {
        $headers = $this->__http_request->getOutputHeaders();
        if (null === $header) {
            return $headers;
        }

        return isset($headers[$header]) ? $headers[$header] : null;
    }

    // }}}
    // {{{ public function get_host()

    /**
     * 获取主机名 
     * 
     * @access public
     * @return string
     */
    public function get_host()
    {
        return $this->__http_request->getHost();    
    }

    // }}}
    // {{{ public function get_method()

    /**
     * 获取 HTTP 操作方法
     * 
     * @access public
     * @return string
     */
    public function get_method()
    {
        $cmd = $this->__http_request->getCommand();
        $default = self::$__method_map[\EventHttpRequest::CMD_GET];
        return isset(self::$__method_map[$cmd]) ? self::$__method_map[$cmd] : $default;
    }

    // }}}
    // {{{ public function get_uri()

    /**
     * 获取 URI 地址 
     * 
     * @access public
     * @return string
     */
    public function get_uri()
    {
        return $this->__http_request->getUri();    
    }

    // }}}
    // {{{ public function send_error()

    /**
     * 发送错误信息 
     * 
     * @param mixed $code 
     * @access public
     * @return void
     */
    public function send_error($code, $reason = null)
    {
        if (!is_int($code) || (100 > $code) || (599 < $code)) {
			throw new sw_exception('Invalid HTTP response code');
        }
        $this->__http_request->sendError($code, $reason);        
        $this->__is_reply = true;
    }

    // }}}
    // {{{ public function send_reply()

    /**
     * 发送响应数据 
     * 
     * @param int $code 
     * @param string $reason 
     * @param string|EventBuffer $buffer 
     * @access public
     * @return void
     */
    public function send_reply($code, $reason = null, $buffer = null)
    { 
        if ($this->__is_reply) {
            return;   // 已经响应 
        }

        if (!is_int($code) || (100 > $code) || (599 < $code)) {
			throw new sw_exception('Invalid HTTP response code');
        }

        if (is_string($buffer)) {
            $buffer = $this->add_output_buffer($buffer);  
        }

        $this->__http_request->sendReply($code, $reason, $buffer);
        $this->__is_reply = true;
    }

    // }}}
    // {{{ public function set_file()

    /**
     * 响应文件 
     * 
     * @param string $file 
     * @access public
     * @return void
     */
    public function set_file($file)
    {
        if (is_string($file)) {
            if (!is_file($file) || !is_readable($file)) {
                $this->send_reply(404, 'Not Found');
                return;
            }

            $fp = fopen($file, 'rb');
        } else {
            $fp = $file;    
        }

        fseek($fp, 0, SEEK_END);
        $length = ftell($fp);
        rewind($fp);

        $buffer = fread($fp, $length);
        fclose($fp);
        
        // 附加头信息
        $this->add_header('Content-Length', $length, self::OUTPUT_HEADER);
        $this->send_reply(200, 'OK', $buffer);
        return true;
    }

    // }}}
    // {{{ public function add_header()

    /**
     * 添加 header 
     * 
     * @access public
     * @return void
     */
    public function add_header($key, $value, $type, $replace = true)
    { 
        $header = $this->__http_request->findHeader($key, $type);
        if (!$replace && (null !== $header)) {
            return true;
        }

        if (!$this->__http_request->addHeader($key, $value, $type)) {
			throw new sw_exception('add header fail.');
        }
    }

    // }}}
    // {{{ protected function _read_event_buffer()

    /**
     * 获取 EventBuffer 中的内容 
     * 
     * @param EventBuffer $buffer 
     * @param int $max_size 
     * @access protected
     * @return string
     */
    protected function _read_event_buffer(\EventBuffer $buffer, $max_size = 1024)
    {   
        $buffer_str = '';
        while ($read = $buffer->read($max_size)) {
            $buffer_str .= $read;    
        }

        return $buffer_str;
    }

    // }}}
    // {{{ protected function _add_event_buffer()

    /**
     * 在 EventBuffer 中追加内容 
     * 
     * @param EventBuffer $buffer 
     * @param string $content 
     * @access protected
     * @return EventBuffer
     */
    protected function _add_event_buffer(\EventBuffer $buffer, $content)
    {   
        if (!$buffer->add($content)) {
			throw new sw_exception('Add content to EventBuffer fail.');
        }

        return $buffer;
    }

    // }}}
    // }}}
}
