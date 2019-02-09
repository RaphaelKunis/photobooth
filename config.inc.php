<?php
/* edited by rk1
	added some parameters (image quality, cntdwn_time, ...)
	it is now possible to change the image quality of the camera before shooting tthe photo
		- needs to be adapted for other camera models than canon
		will be set back after taking the photo
*/

$config = array();
$config['os'] = (DIRECTORY_SEPARATOR == '\\') || (strtolower(substr(PHP_OS, 0, 3)) === 'win') ? 'windows' : 'linux';
$config['dev'] = false;
$config['use_print'] = false;
$config['use_qr'] = false;
$config['show_fork'] = false;
$config['file_format'] = 'date'; 	// comment in to get dateformat images
$config['timeout'] = 90000;			// time till startpage is shown again (originally 90000)

// FOLDERS
// change the folders to whatever you like
$config['folders']['images'] = 'images';
$config['folders']['thumbs'] = 'thumbs';
$config['folders']['qrcodes'] = 'qrcodes';
$config['folders']['print'] = 'print';

// GALLERY
// should the gallery list the newest pictures first?
$config['gallery']['newest_first'] = true;
$config['use_gallery'] = false;		// rk1, 22.12.18:   if false the pictures are not saved in data.txt and not shrinked

// LANGUAGE
// possible values: en, de, fr
$config['language'] = 'de';

// rk1 
//IMAGE QUALITY 0 = MAXIMUM, 7 = Minimum, should be at most 4
// Auslesbar über gphoto2 --list-all-config unter Pkt "/main/imgsettings/imageformat". Das kann für andere Kameras evtl. anders sein
$config['cntdwn_time'] = '8';
$config['gphoto_qty_setting'] = '/main/imgsettings/imageformat';        // sets the image format on eos 600d
$config['img_qty'] = '1';                                               // the quality
/* Quality eos 600d
Choice: 0 Large Fine JPEG
Choice: 1 Large Normal JPEG
Choice: 2 Medium Fine JPEG
Choice: 3 Medium Normal JPEG
Choice: 4 Small Fine JPEG
Choice: 5 Small Normal JPEG
Choice: 6 Smaller JPEG
Choice: 7 Tiny JPEG
Choice: 8 RAW + Large Fine JPEG
Choice: 9 RAW
 */
// end rk1

// COMMANDS and MESSAGES
switch($config['os']) {
	case 'windows':
            $config['take_picture']['cmd'] = 'digicamcontrol\CameraControlCmd.exe /capture /filename %s';
            $config['take_picture']['msg'] = 'Photo transfer done.';
            $config['print']['cmd'] = 'mspaint /pt "%s"';
            $config['print']['msg'] = '';
            break;
	case 'linux':
	default:
            $config['take_picture']['cmd'] = 'sudo gphoto2 --set-config '.$config['gphoto_qty_setting'].'='.$config['img_qty'].' --capture-image-and-download --force-overwrite --filename=%s --set-config '.$config['gphoto_qty_setting'].'=0';
            $config['take_picture']['msg'] = 'New file is in location';
            $config['print']['cmd'] = 'sudo lp -o landscape -o fit-to-page %s';
            $config['print']['msg'] = '';
	break;
}

// DON'T MODIFY
// preparation
foreach($config['folders'] as $directory) {
	if(!is_dir($directory)){
		mkdir($directory, 0777);
	}
}
