<?php
session_start();

$setting_keys = ['protocol', 'host'];
$verify_key = '';

function GetSettingsFileHandle($read_only)
{
	$filename = 'synology_nas.ini';
	$mode = 'w+';

	if (true === $read_only) {
		$mode = 'a+';
	}

	$handle = @fopen($filename, $mode);

	if (!$handle) {
		$handle = @fopen(sys_get_temp_dir() . '/' . $filename, $mode);
		if (!$handle) {
			return false;
		}
	}

	return $handle;
}

function ReadFromSettingsFile()
{
	$handle = GetSettingsFileHandle(true);

	if (!$handle) {
		return [];
	}

	$settings = parse_ini_string(stream_get_contents($handle));
	if (!is_array($settings)) {
		$settings = [];
	}

	fclose($handle);

	return $settings;
}

function WriteToSettingsFile($settings) {
	if (!is_array($settings)) {
		return false;
	}

	$content = "";
	foreach ($settings as $key => $elem) {
		if (is_array($elem)) {
			for($i = 0;$i < count($elem);$i++) {
				$content .= $key."[] = \"".$elem[$i]."\"\n";
			}
		} else if($elem == "") {
			$content .= $key." = \n";
		} else {
			$content .= $key." = \"".$elem."\"\n";
		}
	}

	$handle = GetSettingsFileHandle(false);

	if (!$handle) {
		return false;
	}

	$success = fwrite($handle, $content);
	fclose($handle);

	return false !== $success ;
}

function GetSettings()
{
	global $setting_keys;

	$settings = ReadFromSettingsFile();

	foreach ($setting_keys as $key) {
		if (!array_key_exists($key, $settings)) {
			$settings[$key] = '';
		}
	}

	return $settings;
}

function SetSettings(&$request)
{
	global $setting_keys;

	if (!is_array($request)) {
		return false;
	}

	$settings = ReadFromSettingsFile();

	foreach ($setting_keys as $key) {
		if (array_key_exists($key, $request)) {
			$settings[$key] = $request[$key];
		}
	}

	return WriteToSettingsFile($settings);
}

$result = [
	'success' => false
];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$result['success'] = true;
	$result['data'] = GetSettings();
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_SESSION['syno_ps_tinymce_setting_skip_verify']) && $_SESSION['syno_ps_tinymce_setting_skip_verify'] == true) {
		$result['success'] = SetSettings($_REQUEST);
	} else if (!empty($verify_key) && $_REQUEST['verify_key'] === $verify_key) {
		$result['success'] = SetSettings($_REQUEST);
	} else {
		$result['success'] = false;
	}
}

header('Content-Type: application/json; charset="UTF-8"');

echo json_encode($result);
?>
