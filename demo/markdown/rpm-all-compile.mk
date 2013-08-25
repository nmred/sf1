###RPM 打包所有开源软件编译说明
  
#####发行软件版本

######SWAN 开发工具集 (dev_swan)
  
- 软件包说明：

该软件包是 SWAN 通用的开发工具集，里面集成了最新的 LAMP 环境以及一些软件工程中的开发工具.
  
- 集成软件说明：
  
   1. PHP (V5.4.13)

   - 加入时间: 2013-03-28
   - 更新时间: 2013-03-28
   - [编译概述][php-compile]: 编译 GD 库时可能有问题，需要替换 gd_io.h ,以后版本可能不用 
   - [下载地址][php-download]
 
   2. MYSQL (V5.6.0)
  
   - 加入时间: 2013-03-28
   - 更新时间: 2013-03-28
   - [编译概述][mysql-compile]: 下载的是二进制 tar 包无需编译, 载后安装到目标目录后删除 mysql-test、lib/plugin/libdaemon_example* 防止不必要的依赖
   - [下载地址][mysql-download]

   3. REDIS (V2.6.12)

   - 加入时间: 2013-03-30 
   - 更新时间: 2013-03-30 
   - [编译概述][redis-compile]: make PREFIX=/usr/local/dev_swan/opt/redis/ install 
   - [下载地址][redis-download]
  
   4. HTTPD (V2.4.4)

   - 加入时间: 2013-03-28 
   - 更新时间: 2013-03-28 
   - [编译概述][apache-compile]: 安装依赖 pcre 、apr、apr-util 
   - [下载地址][apache-download]
  
   5. RRDTOOL (V1.4.7)

   - 加入时间: 2013-03-30
   - 更新时间: 2013-03-30 
   - [编译概述][rrdtool-compile]: 安装依赖 pcre 、apr、apr-util 
   - [下载地址][rrdtool-download]


[php-download]: http://cn2.php.net/distributions/php-5.4.13.tar.gz
[apache-download]: http://124.254.47.35/download/38073556/53207897/7/gz/50/165/1361840964402_677/httpd-2.4.4.tar.gz
[mysql-download]: http://www.mysql.com/
[redis-download]: http://redis.googlecode.com/files/redis-2.6.12.tar.gz
[rrdtool-download]: http://218.249.165.47/download/31535906/39751470/5/gz/14/20/1351756608782_532/rrdtool-1.4.7.tar.gz

