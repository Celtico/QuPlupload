QuPlupload
==========

ZF2 Module for Plupload, PhpThumb and included Database
0.0.1-dev

Screen Shots
==================================

![QuDemo example screenshot](http://dibuixa.com/screen.png)

Requirements
==================================
- ZendSkeletonApplication https://github.com/zendframework/ZendSkeletonApplication
- ZfcBase https://github.com/ZF-Commons/ZfcUser
- WebinoImageThumb https://github.com/webino/WebinoImageThumb


Installation
========================
- Drag a folder into modules folder or vendor folder
- Download the latest version Plupload and place in the public folder (you can place in somewhere)
- Enable the module application.config.php and configure the routes module.config.php

Installation by Composer
========================

your project

```
cd YourFolderProject/
php composer.phar require "qu-modules/qu-plupload":"dev-master"
```

or installs all application

```
cd my/project/dir
git clone git://github.com/zendframework/ZendSkeletonApplication.git
cd ZendSkeletonApplication
php composer.phar self-update
php composer.phar install
php composer.phar require "zf-commons/zfc-base":"dev-master"
php composer.phar require "webino/webino-image-thumb":"dev-master"
php composer.phar require "qu-modules/qu-plupload":"dev-master"
```

Integration
========================

- Instance

```html
<div class="PluploadLoad">
    echo $this->PluploadHelpLoad($id);
</div>
```
```html
<div id="uploader"></div>
 echo $this->PluploadHelp('uploader');
```


- Add table in your database


```mysql
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

