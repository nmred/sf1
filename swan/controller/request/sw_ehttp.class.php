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

namespace swan\controller\request;
use swan\controller\request\sw_abstract;

/**
* HTTP 请求类
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright $_SWANBR_COPYRIGHT_$
* @author $_SWANBR_AUTHOR_$
*/
class sw_ehttp extends sw_abstract
{
	// {{{ members

	/**
	 * http 服务器请求对象 
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $__http_request = null;

    /**
     * 获取 request uri 
     * 
     * @var mixed
     * @access protected
     */
    protected $__request_uri = null;

    /**
     * 获取 PATH INFO 
     * 
     * @var mixed
     * @access protected
     */
    protected $__pathinfo = null;

    /**
     * 设置基础地址 
     * 
     * @var string
     * @access protected
     */
    protected static $__base_url;

	/**
	 * __query 
	 * 
	 * @var array
	 * @access protected
	 */
	protected $__query = array();

	// }}}
	// {{{ functions
	// {{{ public function __construct()

	/**
	 * __construct 
	 * 
	 * @param mixed $erequest 
	 * @access public
	 * @return void
	 */
	public function __construct($erequest)
	{
		$this->__http_request = $erequest;
	}

	// }}}
    // {{{ public function set_query()

    /**
     * 设置 GET 方式的参数 
     * 
     * @access public
     * @return void
     */
    public function set_query($spec, $value = null)
    {
        if (is_string($spec) && !isset($value)) {
            throw new sw_exception('Invalid value passed to set_query(); must be either array of values or key/value pair');
        }

        if (is_string($spec) && isset($value)) {
            $this->__query[$spec] = $value;    
        }

        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $this->set_query($key, $val);
            }     
        }
        
        return $this;
    }

    // }}}
    // {{{ public function get_query()

    /**
     * 获取 GET 参数 
     * 
     * @access public
     * @return mixed
     */
    public function get_query($key = null, $default = null)
    {
        if (empty($this->__query)) {
            $this->set_request_uri();    
        }

        if (!isset($key)) {
            return $this->__query;    
        }

        return isset($this->__query[$key]) ? $this->__query[$key] : $default;
    }

    // }}}
    // {{{ public function set_post()

    /**
     * 设置 POST 
     * 
     * @param string|array $spec 
     * @param null|mixed $value 
     * @access public
     * @return void
     */
    public function set_post($spec = null, $value = null)
    {
        if (is_string($spec) && !isset($value)) {
            throw new sw_exception('Invalid value passed to set_post(); must be either array of values or key/value pair');
        }

        if (is_string($spec) && isset($value)) {
            $this->__post[$spec] = $value;    
        }

        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $this->set_post($key, $val);
            }     
        }
        
        return $this;
    }

    // }}}
    // {{{ public function get_post()

    /**
     * 获取 POST 数据 
     * 
     * @param string $key 
     * @param mixed $default 
     * @access public
     * @return mixed
     */
    public function get_post($key = null, $default = null)
    {
        if (empty($this->__post)) {
            $post_str = $this->__http_request->read_input_buffer();
            parse_str($post_str, $post);
            if (isset($post)) {
                $this->set_post($post);    
            }
        }

        if (null === $key) {
            return $this->__post;
        }

        return (isset($this->__post[$key])) ? $this->__post[$key] : $default;
    }

    // }}}
    // {{{ public function set_request_uri()

    /**
     * 获取 request_uri 
     * 
     * @access public
     * @return void 
     */
    public function set_request_uri()
    {
        $request_uri = $this->__http_request->get_uri();
		list($module, $request_uri) = explode('?', $request_uri, 2);
        if (false != strpos($request_uri, '?')) {
            $url = parse_url($request_uri);
            if (isset($url['query'])) {
                parse_str($url['query'], $vars);
                $this->set_query($vars);    
            }
        }

        $this->__request_uri = $request_uri;

        return $this;
    }

    // }}}
    // {{{ public function get_request_uri()
    
    /**
     * 获取 request uri 
     * 
     * @access public
     * @return void
     */
    public function get_request_uri()
    {
        if (!isset($this->__request_uri)) {
            $this->set_request_uri();
        }
            
        return $this->__request_uri;
    }

    // }}}
    // {{{ public static function set_base_url()

    /**
     * 设置基础地址
     * 
     * @param string|null $base_url 
     * @access public
     * @return void
     */
    public static function set_base_url($base_url = null)
    {
        if ((null !== $base_url) && !is_string($base_url)) {
            return $this;    
        }

        self::$__base_url = $base_url;
    }

    // }}}
    // {{{ public function get_base_url()

    /**
     * 获取基本地址 
     * 
     * @access public
     * @return string
     */
    public static function get_base_url()
    {
        return self::$__base_url;
    }

    // }}}
    // {{{ public function set_pathinfo()

    /**
     * 设置 PATHINFO 
     * 
     * @param string $pathinfo 
     * @access public
     * @return void
     */
    public function set_pathinfo($pathinfo = null)
    {
        if (null == $pathinfo) {
            $request_uri = $this->get_request_uri();
            $base_url = self::get_base_url();

            if (!isset($request_uri)) {
                return $this;    
            }

            if ($pos = strpos($request_uri, '?')) {
                $request_uri = substr($request_uri, 0, $pos);
            }

            if ((null !== $base_url) && (false === ($pathinfo = substr($request_uri, strlen($base_url))))) {
                $pathinfo = '';
            } else if (null === $base_url){
                $pathinfo = $request_uri;
            }
        }

        $this->__pathinfo = (string) $pathinfo;
        return $this;
    }

    // }}}
    // {{{ public function get_pathinfo()

    /**
     * 获取 PATHINFO 
     * 
     * @access public
     * @return string
     */
    public function get_pathinfo()
    {
        if (!isset($this->__pathinfo)) {
            $this->set_pathinfo();
        }
            
        return $this->__pathinfo;
    }

    // }}}
    // {{{ public function get_method()

    /**
     * 获取 HTTP 请求方式 
     * 
     * @access public
     * @return string
     */
    public function get_method()
    {
        return $this->__http_request->get_method();    
    }

    // }}}
    // {{{ public function is_post()

    /**
     * 是否是 POST 方式请求 
     * 
     * @access public
     * @return boolean
     */
    public function is_post()
    {
        if ('POST' == $this->get_method()) {
            return true;
        }

        return false;
    }

    // }}}
    // {{{ public function is_get()

    /**
     * 是否是 GET 请求方式 
     * 
     * @access public
     * @return boolean
     */
    public function is_get()
    {
        if ('GET' == $this->get_method()) {
            return true;    
        } 

        return false;
    }

    // }}}
    // {{{ public function is_put()

    /**
     * 是否是 PUT 请求方式 
     * 
     * @access public
     * @return boolean
     */
    public function is_put()
    {
        if ('PUT' == $this->get_method()) {
            return true; 
        } 

        return false;
    }

    // }}}
    // {{{ public function is_delete()

    /**
     * 是否是 DELETE 请求方式 
     * 
     * @access public
     * @return boolean
     */
    public function is_delete()
    {
        if ('DELETE' == $this->get_method()) {
            return true; 
        } 

        return false;
    }

    // }}}
    // {{{ public function is_head()

    /**
     * 是否是 HEAD 请求方式 
     * 
     * @access public
     * @return boolean
     */
    public function is_head()
    {
        if ('HEAD' == $this->get_method()) {
            return true; 
        } 

        return false;
    }

    // }}}
    // {{{ public function is_options()

    /**
     * 是否是 OPTIONS 请求方式 
     * 
     * @access public
     * @return boolean
     */
    public function is_options()
    {
        if ('OPTIONS' == $this->get_method()) {
            return true; 
        } 

        return false;
    }

    // }}}
    // {{{ public function get_header()

    /**
     * 获取头信息 
     * 
     * @param string $header 
     * @access public
     * @return string
     */
    public function get_header($header)
    {
        if (empty($header)) {
            throw new sw_exception('An HTTP header name is required');
        }

        return $this->__http_request->get_input_header($header);
    }

    // }}}
    // {{{ public function get_scheme()

    /**
     * 获取 URI scheme 
     * 
     * @access public
     * @return void
     */
    public function get_scheme()
    {
        return self::SCHEME_HTTP; 
    }

    // }}}
    // {{{ public function get_http_host()

    /**
     * 获取 HTTP 主机 
     * 
     * @access public
     * @return string
     */
    public function get_http_host()
    {
        return $this->__http_request->get_host();    
    }

    // }}}
	// }}}
}
