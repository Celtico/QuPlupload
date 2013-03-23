QuPlupload
==========

ZF2 Module for Plupload, PhpThumb and included Database
0.0.1-dev

Screen Shot
==================================

![QuDemo example screenshot](http://dibuixa.com/screen.png)

Demo example
==================================

http://qumodules.com/

Requirements
==================================
- ZendSkeletonApplication https://github.com/zendframework/ZendSkeletonApplication
- ZfcBase https://github.com/ZF-Commons/ZfcUser
- WebinoImageThumb https://github.com/webino/WebinoImageThumb

Installation
========================
- Drag the folder into modules folder
- Move folder plugin in to public/js folder (look current version http://www.plupload.com/)
- Enable modules ( QuPlupload & WebinoImageThumb ) application.config.php and configure the routes module.config.php
- Folder permissions to upload

Installation by Composer
========================

```
cd YourFolderProject/
php composer.phar require "qu-modules/qu-plupload":"dev-master"
```


Integration
========================

- Instance

```html
<div class="PluploadLoad">
    <?php echo $this->PluploadHelpLoad($id); ?>
</div>
```
```html
<div id="uploader"></div>
    <?php echo $this->PluploadHelp('uploader'); ?>
```

Table database
========================

```mysql
CREATE DATABASE IF NOT EXISTS `qu-modules`;
use `qu-modules`;
CREATE TABLE IF NOT EXISTS `qu-plupload` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tmp_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `error` int(255) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
```


Installation all application
========================

```
cd my/project/dir
git clone git://github.com/zendframework/ZendSkeletonApplication.git
cd ZendSkeletonApplication
php composer.phar self-update
php composer.phar install
php composer.phar require "zf-commons/zfc-base":"0.*"
php composer.phar require "webino/webino-image-thumb":"dev-master"
php composer.phar require "qu-modules/qu-plupload":"dev-master"
```

Config
========================

- In global.php/local.php

```php
return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn'            => 'mysql:dbname=qu-modules;hostname=localhost',
        'username'       => 'root',
        'password'       => '',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),

    ),

);
```

Virtual Host
========================
Afterwards, set up a virtual host to point to the public/ directory of the project and you should be ready to go!

