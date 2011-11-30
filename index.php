<?php

if (!defined('IN_CMS')) { exit(); }

define('BG_ROOT', URI_PUBLIC . 'wolf/plugins/basic_gallery');

Plugin::setInfos(array(
	'id'          => 'basic_gallery',
	'title'       => __('Basic Gallery'),
	'description' => __('Create galleries and put photos.'),
	'version'     => '1.0',
	'license'     => 'GPL',
	'author'      => 'Tayfun Duran',
	'website'     => 'http://www.tayfunduran.com/',
	'require_wolf_version' => '0.7.5'
));

Plugin::addController('basic_gallery', 'Basic Gallery', 'page_view', true);
Observer::observe('page_delete', 'silme');
Behavior::add('basic_gallery', 'basic_gallery/basic_gallery.php');

function silme($p){
	global $__CMS_CONN__;
	$sql = "UPDATE bgphoto SET bolum = '0' WHERE bolum = '{$p->id()}'";
	$kyt = $__CMS_CONN__->query($sql);
}

function get_bg($id){
	global $__CMS_CONN__;
	$sart = " WHERE bgphoto.bolum = '$id'";
	$k = $__CMS_CONN__->query('SELECT COUNT(*) FROM bgphoto' . $sart);
	$t = $k->fetchColumn();
	$sql = 'SELECT bgphoto.* FROM bgphoto' . $sart . ' ORDER BY bgphoto.ana DESC, bgphoto.sira';
	$kyt = $__CMS_CONN__->query($sql);
	$kla = Plugin::getSetting('folder', 'basic_gallery');
	$wid = Plugin::getSetting('thumb_width', 'basic_gallery');
	$hei = Plugin::getSetting('thumb_height', 'basic_gallery');
	$title = Plugin::getSetting('title', 'basic_gallery');
	$ici = '
	<link rel="stylesheet" href="' . PLUGINS_URI . 'basic_gallery/bg.php?w=' . $wid . '&h=' . $hei . '&t=' . $title . '" />
	<link rel="stylesheet" href="' . PLUGINS_URI . 'basic_gallery/colorbox/colorbox.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
	<script src="' . PLUGINS_URI . 'basic_gallery/colorbox/jquery.colorbox-min.js"></script>

	<script>
		$(document).ready(function(){
			$(".bg").colorbox({
				rel:"bg",
				current: "{current} / {total}",
				previous: "<strong>&laquo;&laquo;</strong>",
				next: "<strong>&raquo;&raquo;</strong>",
				close: "<strong>&times</strong>"
			});
		});
	</script>


	<ul id="basicgallery">';
	foreach($kyt as $k){
		$ici .= '<li id="foto_' . $k['id'] . '">
			<a class="bg" href="/public/images/' . $kla . '/' . $k['dosya'] . '" title="' . $k['bilgi'] . '">
				<img src="/public/images/' . $kla . '/zz_' . $k['dosya'] . '" border="0" title="' . $k['bilgi'] . '" />
			</a>';
		if($title) $ici .= '<span class="title">' . $k['bilgi'] . '<span>';
		$ici .= '</li>';
	}
	$ici .= '</ul><br style="clear: both;" />';
	return $ici;
}

function bg_main(){
	global $__CMS_CONN__;
	$bolum = intval(Plugin::getSetting('page', 'basic_gallery'));
	$kla = Plugin::getSetting('folder', 'basic_gallery');
	$wid = Plugin::getSetting('thumb_width', 'basic_gallery');
	$hei = Plugin::getSetting('thumb_height', 'basic_gallery');
	$gurl = Page::urlById($bolum);
	$p = Page::findById($bolum);
	$kytb = $p->children();
	$ici = '
	<link rel="stylesheet" href="' . PLUGINS_URI . 'basic_gallery/bg.php?w=' . $wid . '&h=' . $hei . '" />
	<ul id="basicgallery">';
	foreach($kytb as $k){
		$sec = $__CMS_CONN__->query("SELECT dosya FROM bgphoto WHERE bolum = '{$k->id()}' ORDER BY ana DESC, sira LIMIT 0,1");
		$s = $sec->fetch();
		$ici .= '
		<li id="foto_' . $k->id() . '">
			<a href="' . $gurl . '/' . $k->slug() . '">
				<img src="/public/images/' . $kla . '/zz_' . $s['dosya'] . '" border="0" title="' . $k->title() . '" />
			</a><span class="title2">' . $k->title() . '<span>
		</li>';
	}
	$ici .= '</ul><br style="clear: both;" />';
	return $ici;
}
