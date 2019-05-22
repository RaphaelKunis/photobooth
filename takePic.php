<?php
/* edited by rk1
	shrink only if gallery is enabled
*/


require_once('db.php');
require_once('folders.php');
require_once('config.inc.php');

$file = md5(time()).'.jpg';

switch($config['file_format']){
	case 'date':
		$file = date('Ymd_His').'.jpg';
		break;
	default:
		$file = md5(time()).'.jpg';
		break;
 }

$filename_photo = $config['folders']['images'] . DIRECTORY_SEPARATOR . $file;
$filename_thumb = $config['folders']['thumbs'] . DIRECTORY_SEPARATOR . $file;

if($config['dev'] === false) {
	$shootimage = shell_exec(
		sprintf(
			$config['take_picture']['cmd'],
			$filename_photo
			)
		);
		if(strpos($shootimage, $config['take_picture']['msg']) === false) {
			die(json_encode(array('error' => true)));
		}
} else {
	$devImg = array('resources/img/bg.jpg');
	copy(
		$devImg[array_rand($devImg)],
		$filename_photo
	);
}


// image scale -> only when gallery enabled
if ($config['use_gallery']) {
    // shrink
    list($width, $height) = getimagesize($filename_photo);
    $newwidth = 500;
    $newheight = round(($height * ($newwidth / $width)), 0);
    $source = imagecreatefromjpeg($filename_photo);
    $thumb  = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    imagejpeg($thumb, $filename_thumb);

    // insert into database
    $images[] = $file;
    file_put_contents('data.txt', json_encode($images));
}   // end of if ($config['use_gallery'])
  
// send imagename to frontend
echo json_encode(array('success' => true, 'img' => $file));
