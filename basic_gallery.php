<?php

if (!defined('IN_CMS')) {
	exit();
}


class Basic_gallery {

	public function __construct(&$page, $params) {
		$this->page = & $page;
		$this->params = $params;

		switch (count($params)) {
			case 0:
				$this->page->part->body->content_html .= bg_main();
				break;

			case 1:
				if($p = Page::findBySlug($params[0], $this->page)){
					$sep = Plugin::getSetting('bcsep', 'basic_gallery');
					$this->page->part->body->content_html = $p->content() . get_bg($p->id());
					$out = '';
					if($p->parent) $out .= $this->_inversedBreadcrumbs($p->parent, $sep);
					$bc = $out . '<span class="breadcrumb-current">' . $p->breadcrumb() . '</span>';
					$bcd = explode($sep, $bc);
					$x = array_shift($bcd);
					$this->page->breadcrumb = implode($sep, $bcd);
					$this->page->title = $p->title();
				}else page_not_found();
				break;

			default:
				page_not_found();
		}
	}

	private function _inversedBreadcrumbs($p, $separator) {
		$out = '<a href="'.$p->url().'" title="'.$p->breadcrumb.'">' . $p->breadcrumb.'</a><span class="breadcrumb-separator">'.$separator.'</span>';
		if ($p->parent) return $this->_inversedBreadcrumbs($p->parent, $separator) . $out;
		return $out;
	}

}
