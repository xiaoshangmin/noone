# noone
### 环境
在mac和window快速搭建运行环境
[docker镜像](https://github.com/xiaoshangmin/dnmp)

### 主要新特性
采用PHP7强类型  
标量参数关联传值


## 目录结构
```
app                         应用目录
│   ├── config              配置目录
│   │   ├── app.php         应用配置
│   │   ├── cache.php       缓存配置
│   │   └── database.php    数据库配置
│   ├── controller          控制器目录
│   │   ├── Index.php
│   │   ├── User
│   │   └── User.php
│   ├── model               模型目录
│   │   ├── Content
│   │   └── User
│   ├── route               路由定义目录
│   │   └── route.php
│   └── runtime             应用的运行时目录（可写)
│       └── log
├── composer.json
├── lib                     框架目录
│   └── src
│       └── noone
└── public                  对外访问目录
    ├── favicon.ico
    └── index.php
```
## 下载
```
git clone https://github.com/xiaoshangmin/noone.git

```

### nginx
```
    root   /path/noone/public;
    location / {
        index  index.php index.html index.htm;
        if (!-e $request_filename) {
            rewrite  ^(.*)$  /index.php/$1 last;
            break;
        }
    }
```

## 示例
```
    路由定义(不配置路由默认pathinfo模式)
    Route::get('/', function (noone\Request $req, $b = 123, $c) {
        return $c + $b;
    });
    Route::get('/index', 'index/index');
    Route::get('/user', 'user/user/index');
```

```
    控制器获取配置文件
    $this->config['cache']['redis'];
    控制器获取请求对象
    $this->request;

    以上也可以用注入的方式
    foo(\noone\Config $config,\noone\Request $req){
        print_r($config);
        print_r($req);
    }
```

```
    标量参数关联传值
    function foo($name, $age = 27, $sex)
    {

    }
    //可以这样传值 age有默认值可以跳过
    foo($name = 'big cat', $sex = 'male')
```

### TODO
    orm的实现