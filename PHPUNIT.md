# PHP Unit

### 1.安装全局包
下载 phpunit.phar 并放入php 文件所在位置
下载地址 http://phar.phpunit.cn/phpunit.phar
### 2.建立入口命令
```shell
echo @php "%~dp0phpunit.phar" %* > phpunit.cmd
```
### 3.添加目录到环境变量
### 4.校验
```shell
C:\Users\Administrator>phpunit --version
PHPUnit 6.5.3 by Sebastian Bergmann and contributors.
```

### 5.安装项目依赖 phpunit
在项目中安装依赖
composer require --dev phpunit/phpunit ^6
### 编写测试类
```shell
<?php
# 未能加载类请在跟目录添加 phpunit.xml 或者 phpunit.xml.dist
use JoinPhpCommon\utils\Pinyin;
use PHPUnit\Framework\TestCase;
class  indexTest extends TestCase{
    /**
     * 方法名称已test开始
     */
    function  testPinyin(){
        $res = Pinyin::convert('我是个神仙');
//        $res = 'woshidashenxian';
        print_r($res);
        // 相等断言
        $this->assertEquals(
            $res,
            'woshidashenxian'
        );
    }
}
```
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit backupGlobals="false"
         colors="true"
         bootstrap="vendor/autoload.php"
>
    <testsuites>
        <testsuite name="JoinPhpCommon Test Suite">
            <directory suffix="Test.php">./tests/</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist>
            <directory suffix=".php">src</directory>
        </whitelist>
    </filter>
</phpunit>
```
### 运行
```shell
# 单类测试
phpunit ./tests/indexTest
```