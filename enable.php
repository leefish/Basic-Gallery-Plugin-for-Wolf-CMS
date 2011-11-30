<?php

if (!defined('IN_CMS')) { exit(); }


$conn = Record::getConnection();

$conn->exec("CREATE TABLE IF NOT EXISTS `bgphoto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dosya` varchar(255) NOT NULL,
  `bolum` int(11) NOT NULL,
  `bilgi` varchar(255) NOT NULL,
  `ana` tinyint(1) NOT NULL DEFAULT '0',
  `sira` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

if(!@file_exists(CMS_ROOT . '/public/images/basic_gallery')) mkdir(CMS_ROOT . '/public/images/basic_gallery');

if (Plugin::getSetting('width', 'basic_gallery') === false) {
	$settings = array('width' 		=> 500,
					  'height' 		=> 400,
					  'thumb_width' 	=> 150,
					  'thumb_height'	=> 120,
					  'folder'		=> 'basic_gallery',
					  'page'		=> 1,
					  'title'		=> 0,
					  'bcsep'		=> ' &raquo; '
					 );

	Plugin::setAllSettings($settings, 'basic_gallery');
}

exit();
