DB调用方式:
hProseServer_Factory::NewDB();
使用池子提供链接:hProseServer_Factory::NewDB('pool');
使用轻量级的PHP数据库框架:hProseServer_Factory::NewDB();

redis池调用方式:
一) new Cache_RedisPool();
二）new Cache_RedisCache();

rpc 使用:
定义类库的目录/application/library/hproseserver
定义需要发布的function：/application/function

发布配置:
/hprose/server.php 
