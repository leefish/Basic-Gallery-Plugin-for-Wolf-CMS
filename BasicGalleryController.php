<?php

if (!defined('IN_CMS')) { exit(); }


class BasicGalleryController extends PluginController {

	public function __construct() {
		global $__CMS_CONN__;
		$this->conn = $__CMS_CONN__;
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/basic_gallery/views/sidebar'));
	}

	public function index() {
		$this->photos();
	}

	public function documentation() {
		$this->display('basic_gallery/views/documentation');
	}

	function settings() {
		$this->display('basic_gallery/views/settings', array('settings' => Plugin::getAllSettings('basic_gallery')));
	}

	function save() {
		if(AuthUser::hasRole('administrator')){
			if (isset($_POST['settings'])) {
				$settings = $_POST['settings'];
				foreach ($settings as $key => $value) {
					$settings[$key] = mysql_escape_string($value);
				}
				
				$ret = Plugin::setAllSettings($settings, 'basic_gallery');

				if ($ret) {
					Flash::set('success', __('The settings have been saved.'));
				}
				else {
					Flash::set('error', __('An error occured trying to save the settings.'));
				}
			}
			else {
				Flash::set('error', __('Could not save settings, no settings found.'));
			}
		}
		redirect(get_url('plugin/basic_gallery/settings'));
	}

	public function photos() {
		$bolum = Plugin::getSetting('page', 'basic_gallery');
		if(isset($_GET['gal'])) $gal = intval($_GET['gal']); else $gal = 0;
		$sart = " WHERE bgphoto.bolum = '$gal'";
		$k = $this->conn->query('SELECT COUNT(*) FROM bgphoto' . $sart);
		$t = $k->fetchColumn();
		$sql = 'SELECT bgphoto.*, page.title AS bisim FROM bgphoto
			LEFT JOIN page ON bgphoto.bolum = page.id
			' . $sart . ' ORDER BY bgphoto.ana DESC, bgphoto.sira';
		$kyt = $this->conn->query($sql);
//		$kytb = $this->conn->query('SELECT * FROM bgsection ORDER BY isim');
		$p = Page::findById($bolum);
		$kytb = $p->children(null, array(), true);
		$this->display('basic_gallery/views/photos', array('gal' => $gal, 'kyt' => $kyt, 'toplam' => $t, 'kytb' => $kytb));
	}

	public function photo_record(){
		$bolum = Plugin::getSetting('page', 'basic_gallery');
		$id = intval($_POST['id']);
		$gal = $_POST['gal'];
		$sql = "SELECT * FROM bgphoto WHERE id='$id'";
		$sec = $this->conn->query($sql);
		if(!($kyt = $sec->fetch())){
			$kyt = array('id' => 0, 'dosya' => '', 'bolum' => $gal, 'bilgi' => '', 'sira' => '');
		}
		$p = Page::findById($bolum);
		$kytb = $p->children(null, array(), true);
//		$kytb = $this->conn->query('SELECT * FROM bgsection ORDER BY isim');
		?>
		<ul class="satir">
			<input type="hidden" name="id" value="<?php echo $id?>" />
			<input type="hidden" name="veri[dosya]" value="<?php echo $kyt['dosya']?>" />
			<li><span><?php echo __('Photo')?></span><input type="file" class="textbox" name="dosya" id="dosya" /></li>
			<li><span><?php echo __('Information')?></span><input type="text" class="textbox" name="veri[bilgi]" id="bilgi" value="<?php echo $kyt['bilgi'];?>" style="width: 250px;" /></li>
			<li><span><?php echo __('Gallery')?></span>
				<select name="veri[bolum]" id="bolum" class="textbox" style="width: 250px;">
						<option value="0"<?php if($kyt['bolum'] == 0) echo ' selected="selected"';?>><?php echo __('None'); ?></option>
						<?php foreach($kytb as $k){?>
							<option value="<?php echo $k->id() ?>"<?php if($kyt['bolum'] == $k->id()) echo ' selected="selected"';?>><?php echo $k->title() ?></option>
							<?php
						}?>
				</select>
			</li>
			<li><span><?php echo __('Order')?></span><input type="text" class="textbox" name="veri[sira]" id="sira" value="<?php echo $kyt['sira'];?>" style="width: 25px; text-align: right;" /></li>
			<li>
				<input type="button" value="<?php echo __('Cancel')?>" class="button" style="width: 100px;" onclick="$('#kayit').hide();">
				<input type="submit" value="<?php echo __('Save')?>" class="button" style="width: 100px;">
			</li>
		</ul>
		<? $folder = Plugin::getSetting('folder', 'basic_gallery');
		if($id != 0 && $kyt['dosya']){?>
			<p><?php echo __('If you do not choose an image above, this will not be changed!');?>.<br />
			<img src="<?php echo URI_PUBLIC . 'public/images/' . $folder . '/zz_' . $kyt['dosya']; ?>" /></p>
			<?
		}
	}

	public function photo_save(){
		$id = $_POST['id'];
		$veri = $_POST['veri'];
		$gal = $_POST['gal'];

		$kaynak = $_FILES['dosya'];
		if(is_uploaded_file($kaynak['tmp_name'])){
			$klasor = CMS_ROOT . '/public/images/' . Plugin::getSetting('folder', 'basic_gallery') . '/';
			$width = Plugin::getSetting('width', 'basic_gallery');
			$height = Plugin::getSetting('height', 'basic_gallery');
			$thumb_width = Plugin::getSetting('thumb_width', 'basic_gallery');
			$thumb_height = Plugin::getSetting('thumb_height', 'basic_gallery');

			$gecici = $kaynak['tmp_name'];
			$isim = $this->cleanName($kaynak['name']);
			$isimd = explode('.', $isim);
			$tip = array_pop($isimd);
			$isim = implode('.', $isimd);
			$dosya = $klasor . $isim . '.' . $tip;
			$ii = 0;
			while(file_exists($dosya)){
				$ii++;
				$dosya = $klasor . $isim . '-' . $ii . '.' . $tip;
			}
			if($ii != 0) $thdosya = $klasor . 'zz_' . $isim . '-' . $ii . '.' . $tip; else $thdosya = $klasor . 'zz_' . $isim . '.' . $tip;
			move_uploaded_file($gecici, $dosya);
			$sonuc = $this->kucult($dosya, $tip, $width, $height);
			if($sonuc != false){
				if($veri['dosya'] != ''){
					unlink($klasor . $veri['dosya']);
					unlink($klasor . 'zz_' . $veri['dosya']);
				}
				$veri['dosya'] = $sonuc;
				$this->kucult($dosya, $tip, $thumb_width, $thumb_height, $thdosya);
			}
		}

		if($id == 0)
			$sql = "INSERT INTO bgphoto (dosya, bolum, bilgi, sira) VALUES ('" . $veri['dosya'] . "', '" . $veri['bolum'] . "', '" . $veri['bilgi'] . "', '" . $veri['sira'] . "')";
		else
			$sql = "UPDATE bgphoto SET dosya = '" . $veri['dosya'] . "', bolum = '" . $veri['bolum'] . "', bilgi = '" . $veri['bilgi'] . "', sira = '" . $veri['sira'] . "' WHERE id='$id'";
		$sonuc = $this->conn->query($sql);
		if($sonuc != false){
			Flash::set('success', __('The photo data have been saved.'));
		}else{
			Flash::set('error', __('An error occured trying to save the data.'));
		}
		redirect(get_url('plugin/basic_gallery/photos?gal=' . $gal));
	}

	public function photo_ok(){
		$say = $_POST['say'];
		$sir = explode('&', $_POST['sir']);
		$ana = intval($_POST['ana']);
		$gal = $_POST['gal'];
		$klasor = CMS_ROOT . '/public/images/' . Plugin::getSetting('folder', 'basic_gallery') . '/';
		for($i = 0; $i < count($sir); $i++){
			$d = explode('=', $sir[$i]);
			$this->conn->query("UPDATE bgphoto SET sira='" . $i . "' WHERE id = '" . $d[1] . "'");
		}
		$this->conn->query("UPDATE bgphoto SET ana = '0'");
		$this->conn->query("UPDATE bgphoto SET ana = '1' WHERE id = '$ana'");
		$sil = 0;
		for($i = 0; $i < $say; $i++){
			$id = $_POST['id' . $i];
			if($_POST['sil' . $i]){
				$sonuc = $this->conn->query("DELETE FROM bgphoto WHERE id='$id'");
				if($sonuc != false){
					$d = $_POST['dosya' . $i];
					unlink($klasor . $d);
					unlink($klasor . 'zz_' . $d);
					$sil++;
				}
			}
		}
		Flash::set('success', __('Order saved and ') . $sil . __(' photo(s) have been deleted.'));
		redirect(get_url('plugin/basic_gallery/photos?gal=' . $gal));
	}


	private function kucult($dosya, $tip, $width, $height, $th = ''){
		$ret = false;
		if($th != '') $yenidosya = $th; else $yenidosya = $dosya;
		switch($tip){
			case "png":
				$im = @imagecreatefrompng($dosya);
				break;
			case "gif":
				$im = @imagecreatefromgif($dosya);
				break;
			default:
				$im = @imagecreatefromjpeg($dosya);
				break;
		}
		$olcu = getimagesize($dosya);
		if($width < $olcu[0] || $height < $olcu[1]){
			if($olcu[0] / $width > $olcu[1] / $height)
				$oran = $olcu[0] / $width;
			else $oran = $olcu[1] / $height;
			if(!$this->strech && $oran < 1) $oran = 1;
			$resimGenislik = $olcu[0] / $oran;
			$resimYukseklik = $olcu[1] / $oran;
			if($this->exact){
				$x = round(($width - $resimGenislik) / 2);
				$y = round(($height - $resimYukseklik) / 2);
				$yeniGenislik = $width;
				$yeniYukseklik = $height;
			}else{
				$x = 0;
				$y = 0;
				$yeniGenislik = $resimGenislik;
				$yeniYukseklik = $resimYukseklik;
			}
			$yenisi = imagecreatetruecolor($yeniGenislik, $yeniYukseklik);
			imagecopyResampled($yenisi, $im, $x, $y, 0, 0, $resimGenislik, $resimYukseklik, $olcu[0], $olcu[1]);
			imagedestroy($im);
			$yarat = touch($yenidosya);
			if($yarat){
				switch($tip){
					case "png":
						$yaz = imagepng($yenisi, $yenidosya, 9);
						break;
					case "gif":
						$yaz = imagegif($yenisi, $yenidosya);
						break;
					default:
						$yaz = imagejpeg($yenisi, $yenidosya, 85);
						break;
				}
				chmod($yenidosya, 0666);
				imagedestroy($yenisi);
				if($yaz) $ret = basename($yenidosya);
			}
		}else $ret = basename($yenidosya);
		return $ret;
	}

	private function cleanName($str) {
		$ozelHarfler = array(
			'a' => array('á','à','â','ä','ã'),
			'A' => array('Ã','Ä','Â','À','Á'),
			'e' => array('é','è','ê','ë'),
			'E' => array('Ë','É','È','Ê'),
			'i' => array('í','ì','î','ï','ı'),
			'I' => array('Î','Í','Ì','İ','Ï'),
			'o' => array('ó','ò','ô','ö','õ'),
			'O' => array('Õ','Ö','Ô','Ò','Ó'),
			'u' => array('ú','ù','û','ü'),
			'U' => array('Ú','Û','Ù','Ü'),
			'c' => array('ç'),
			'C' => array('Ç'),
			's' => array('ş'),
			'S' => array('Ş'),
			'n' => array('ñ'),
			'N' => array('Ñ'),
			'y' => array('ÿ'),
			'Y' => array('Ÿ')
		);

		$ozelKarakterler = array ('#', '$', '%', '^', '&', '*', '!', '~', '"', '\'', '=', '?', '/', '[', ']', '(', ')', '|', '<', '>', ';', ':', '\\', ', ');
		$str = str_replace($ozelKarakterler, '', $str);
		$str = str_replace(' ', '-', $str);
		foreach($ozelHarfler as $harf => $ozeller){
			foreach($ozeller as $tektek){
				$str = str_replace($tektek, $harf, $str);
			}
		}
		return preg_replace("/[^a-zA-Z0-9\-\.]/", "_", $str);
	}

}
