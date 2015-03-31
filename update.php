<?php
include_once 'lib.php';
include_once 'conf.php';

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Last-Modified: " . gmdate("D, d M Y H:i:s", time() - 60) . " GMT");
clean_tmp();

session_start();

$src = "images/tmp/" . session_id() . '.jpg';
$dst = $src;
$time = time();
if (isset($_SESSION['time'])) {
    if ($time - $_SESSION['time'] > 900) {
        if (file_exists($src)) {
            unlink($src);
        }
        session_destroy();
        session_start();
        $src = "images/tmp/" . session_id() . '.jpg';
        $dst = $src;
    }
}

$_SESSION['time'] = $time;


$upload_result = verifyFileUpload('inputImage');
$success = $upload_result === 'no error';

if ($success) {
    if (file_exists($src)) {
        unlink($src);
    }
    move_uploaded_file($_FILES["inputImage"]["tmp_name"], $src);
    $_SESSION['image'] = $_FILES["inputImage"]["name"];
}

if (!file_exists($src)) {
    $src = 'images/default.jpg';
}

if (!isset($_SESSION['image'])) {
    $img_name = 'Default image';
} else {
    $img_name = $_SESSION['image'];
}

$dims = getimagesize($src);
$width = $dims[0];
$height = $dims[1];



if (isset($_POST['height']) && isset($_POST['width']) && isset($_POST['algo'])) {
    $w = (int) htmlentities($_POST['width']);
    $h = (int) htmlentities($_POST['height']);
    $method = htmlentities($_POST['algo']);
    if ($w > 0 && $h > 0) {
        $width = $w;
        $height = $h;
        if (in_array($method, array('1', '2', '3'))) {
            set_time_limit(800);
            exec('export LD_LIBRARY_PATH=' . $lib . ' && ./seam-carving ' . escapeshellcmd($src) . ' ' . escapeshellcmd($width) . 'x' . escapeshellcmd($height) . ' ' . escapeshellcmd($dst) . ' ' . $method . ' 100 2>&1', $output, $ret);
            if (sizeof($output) > 0) {
                $parse = explode(' ', $output[0]);
                if ($parse[0] === 'Duration') {
                    $exec_time = $parse[2] . ' s';
                } else {
                    $exec_time = $output[0];
                }
            } else {
                $exec_time = 'timeout';
            }
        }
    }
}

if (!file_exists($dst)) {
    $dst = 'images/default.jpg';
}
?>

<img class="img-rounded" src="<?php echo $dst; ?>?<?php echo time(); ?>" >