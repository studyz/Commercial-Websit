<?php
/*
Plugin Name: Photo Station WordPress Plugin
Plugin URI: http://www.synology.com
Description: This plugin displays photo and album from your Photo Station.
Version: 1.0.3
Author: Synology Inc.
Author URI:  http://www.synology.com/
*/

class WP_PhotoStation extends WP_Widget {
	// constructor
	function WP_PhotoStation() {
		parent::WP_Widget(false, $name = __('Photo Station', 'wp_photostation'));
	}

	// widget form creation
	function form($instance) {
		if ($instance) {
			$diskstation = esc_attr($instance['diskstation']);
			$protocol = esc_attr($instance['protocol']);
		} else {
			$diskstation = 'localhost';
			$protocol = 'http';
		}
		$id_diskstation = $this->get_field_id('diskstation');
		$name_diskstation = $this->get_field_name('diskstation');
		$id_protocol = $this->get_field_id('protocol');
		$name_protocol = $this->get_field_name('protocol');
?>
<p>
	<label for="<?php echo $id_diskstation; ?>"><b>DiskStation</b></label>
	<input class="widefat" id="<?php echo $id_diskstation; ?>" name="<?php echo $name_diskstation; ?>" type="text" value="<?php echo $diskstation; ?>">
</p>
<p>
	<label for="<?php echo $id_protocol; ?>"><b>Protocol</b></label>
	<input class="widefat" name="<?php echo $name_protocol; ?>" type="radio" value="http"<?php echo (($protocol === 'http') ? ' checked' : ''); ?>>http
	<input class="widefat" name="<?php echo $name_protocol; ?>" type="radio" value="https"<?php echo (($protocol === 'https') ? ' checked' : ''); ?>>https
</p>
<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['diskstation'] = strip_tags($new_instance['diskstation']);
		$instance['protocol'] = strip_tags($new_instance['protocol']);
		return $instance;
	}

	public static function transformLanguage($lang) {
		switch ($lang) {
			case 'zh_TW':
				return 'cht';
			case 'ja':
				return 'jpn';
			case 'en_US':
				// fall through
			default:
				return 'enu';
		}
	}

	// widget display
	function widget($args, $instance) {
		extract($args);
		$host = $instance['protocol'] . '://' . $instance['diskstation'];
		$id = get_option('photostation_id', '');
		$share = get_option('photostation_share', '');
		$lang = self::transformLanguage(get_locale());

		// Display the widget
		echo $before_widget;
		echo $before_title . $widget_name . $after_title;
?>
<link rel="stylesheet" href="wp-content/plugins/photostation/photostation.css">
<script src="wp-content/plugins/photostation/photostation.js"></script>
<script>
ModPhotoStationUtil.target = '<?php echo $host; ?>';
ModPhotoStationUtil.lang = '<?php echo $lang; ?>';
ModPhotoStationUtil.photostation_id = '<?php echo $id; ?>';
ModPhotoStationUtil.photostation_share = '<?php echo $share; ?>';
</script>
<?php
		$user = wp_get_current_user();
		if ($user && $user->allcaps['administrator']) {
?>
<input type="button" id="photostation-button-chooser" class="photostation-button-chooser" onclick="ModPhotoStationUtil.showChooser();" value="Photo Station Selector" disabled>
<?php
		}
?>
<div id="photostation-div-chooser" class="photostation-div-chooser">
	<iframe src="about:blank"></iframe>
</div>
<div id="photostation-div-slideshow" class="photostation-div-slideshow">
	<div>Connecting to <?php echo $host; ?>...
		<iframe id="photostation-iframe-slideshow" class="photostation-iframe-slideshow" src="about:blank"></iframe>
	</div>
</div>
<iframe id="photostation-iframe-lightbox" class="photostation-iframe-lightbox" src="about:blank"></iframe>
<script>
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function () {
	if (this.readyState !== this.DONE) {
		return;
	}
	try {
		var resp = JSON.parse(this.responseText);
		if (resp.boot_done && resp.success) {
			var t = setInterval(function () {
				if (document.querySelector('#photostation-div-chooser')
						&& document.querySelector('#photostation-div-slideshow')
						&& document.querySelector('#photostation-iframe-lightbox')) {
					clearInterval(t);
					ModPhotoStationUtil.initChooser();
					ModPhotoStationUtil.initSlideShow();
					ModPhotoStationUtil.initLightBox();
				}
			}, 100);
		} else {
			throw -1;
		}
	} catch (e) {
		document.querySelector('#photostation-div-slideshow').children[0].innerHTML = 'Invalid DiskStation: ' + ModPhotoStationUtil.target;
	}
};
xhr.open('GET', ModPhotoStationUtil.target + '/webman/pingpong.php?action=cors');
xhr.send();
</script>
<?php
		echo $after_widget;
	}
} // class WP_PhotoStation

// load language files
function photo_load_text () {
	$res = load_plugin_textdomain('photostation', false, dirname( plugin_basename( __FILE__ ) ) .'/languages');
}
add_action('init', photo_load_text);

// register widget
add_action('widgets_init', create_function('', 'return register_widget("WP_PhotoStation");'));

add_action('wp_ajax_photostation_save', 'photostation_save_callback');

function photostation_save_callback() {
	if (!add_option('photostation_id', $_REQUEST['id'])) {
		update_option('photostation_id', $_REQUEST['id']);
	}
	if (!add_option('photostation_share', $_REQUEST['share'])) {
		update_option('photostation_share', $_REQUEST['share']);
	}
	wp_die();
}
