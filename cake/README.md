# wxmp-微信公众号管理系统后端

## 环境
````
CakePHP 3.6
PHP >=5.6
````

## 安装

````
composer install
````

## 使用

### 配置文件
复制`app.default.php`并重命名为`app.php`,在`app.php`中进行相关配置

### 数据库迁移
在`app.php`中正确配置mysql后，运行以下代码进行数据库迁移
````
$ bin/cake migrations migrate
````
- 用户名: admin
- 密码: 123456
