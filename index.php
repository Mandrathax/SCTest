<?php
include_once 'lib.php';
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
            sleep(30);
            exec('export LD_LIBRARY_PATH=/kunden/homepages/24/d570757684/htdocs/sctest/lib/lib && ./seam-carving ' . escapeshellcmd($src) . ' ' . escapeshellcmd($width) . 'x' . escapeshellcmd($height) . ' ' . escapeshellcmd($dst) . ' ' . $method . ' 100 2>&1', $output, $ret);

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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SCTest</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootflat.min_2.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet">
        <link rel="icon" href="images/brand.gif" />
    </head>
    <body>

        <div id="header" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <i class="icon-reorder"></i>
                    </button>
                    <a class="navbar-brand" href="">
                        <img alt="Brand" width="20" height="20" src="images/brand.gif">
                    </a>
                    <a class="navbar-brand" href="">
                        SCTest
                    </a>
                </div>
                <nav class="collapse navbar-collapse">
                    <form class="navbar-form navbar-left" method="post" action="index.php" enctype="multipart/form-data">
                        <label for="inputImage">Input image</label>
                        <div class="form-group">
                            <input type="file" id="inputImage" name="inputImage">
                        </div>
                        <button type="submit" class="btn btn-success">Upload image</button>
                    </form>
                    <?php if (!$success) { ?>
                        <ul class="nav navbar-nav">
                            <p class="navbar-text danger"><?php echo $upload_result; ?></p>
                        </ul>
                    <?php } ?>
                    <ul class="nav navbar-nav pull-right">
                        <a class="navbar-text" href="https://github.com/Mandrathax/seam-carving" data-toggle="tooltip" data-placement="left" title="See sources on github">Get sources</a>
                    </ul>
                </nav>
            </div>
        </div>
        <div id="wrapper">
            <div id="sidebar-wrapper" class="col-md-2">

                <div id="sidebar" class="container col-sm-12">
                    <h4>Parameters</h4>
                    <form>
                        <div class="form-group">
                            <label for="width">Width</label>
                            <input type="number" name="width" class="form-control" id="width" min="1" value="<?php echo $width; ?>">
                        </div>
                        <div class="form-group">
                            <label for="height">Height</label>
                            <input type="number" name="height" class="form-control" id="height" min="1" value="<?php echo $height; ?>">
                        </div>
                        <div class="form-group">
                            <select name="algo" id="algo">
                                <optgroup label="Random">
                                    <option value="3">Random</option>
                                </optgroup>
                                <optgroup label="Dynamic">
                                    <option value="2">Forward</option>
                                    <option value="1">Backward</option>
                                </optgroup>
                            </select>
                        </div>
                        <button class="btn btn-primary btn-block" id="submit_carve">Seam-carve me!</button>
                    </form>


                    <h4>
                        Informations
                    </h4>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <b>Name : </b><?php echo $img_name; ?>
                        </li>
                        <li class="list-group-item">
                            <b>Dimensions : </b><span id='original_width'><?php echo $width; ?></span>x<span id='original_height'><?php echo $height; ?></span> (px)
                        </li>
                        <?php if (isset($exec_time)) { ?>
                            <li class="list-group-item">
                                <b>Execution time : </b><?php echo $exec_time; ?>
                            </li>
                        <?php } ?>
                    </ul>

                </div>
                <footer class="footer-copyright">
                    <div class="container col-xs-12">
                        <p class="text-muted"><small>&copy; Paul Michel &amp; Antoine Prouvost 2015</small></p>
                    </div>
                </footer>
            </div>
            <div id="main-wrapper" class="col-md-10 pull-right">
                <img src='images/loading.gif' id='loading' style="display: none;"/>
                <div id="main" style="text-align: center;">
                    
                    <!--                    <div class="page-header" style="text-align: center;">
                                            <h3>Image</h3>
                                        </div>-->
                    <img class="img-rounded" src="<?php echo $dst; ?>?<?php echo time(); ?>" >
                </div>
            </div>
        </div>
        <script src="js/jquery-1.11.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.fs.selecter.min.js"></script>
        <script src="js/index.js"></script>
    </body>
</html>


