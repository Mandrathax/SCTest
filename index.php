<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
include_once 'lib.php';
session_start();

$src = "images/" . session_id();

$time = time();
if (isset($_SESSION['time'])) {
    if ($time - $_SESSION['time'] > 20) {
        if (file_exists($src)) {
            unlink($src);
        }

        session_destroy();
        session_start();
    }
} else {
    $_SESSION['time'] = $time;
}



if (verifyFileUpload('inputImage')) {
    if (file_exists($src)) {
        unlink($src);
    }
    move_uploaded_file($_FILES["inputImage"]["tmp_name"], "images/" . session_id());
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



if (isset($_POST['height']) && isset($_POST['width'])) {
    $w = (int) htmlentities($_POST['width']);
    $h = (int) htmlentities($_POST['height']);
    if ($w > 0 && $h > 0) {
        $width = $w;
        $height = $h;
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>SCTest</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!--        <link href="css/bootstrap-theme.min.css" rel="stylesheet">-->
        <link href="css/bootflat.min_2.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet">
        <link rel="icon" href="images/brand.png" />
    </head>
    <body>
        <div id="header" class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <i class="icon-reorder"></i>
                    </button>
                    <a class="navbar-brand" href="#">
                        <img alt="Brand" width="20" height="20" src="images/brand.png">
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
                    <ul class="nav navbar-nav pull-right">
                        <p class="navbar-text navbar-right"><?php echo $img_name; ?></p>
                    </ul>
                </nav>
            </div>
        </div>
        <div id="wrapper">
            <div id="sidebar-wrapper" class="col-md-2">

                <div id="sidebar" class="container col-sm-12">
                    <h4>Parameters</h4>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="width">Width</label>
                            <input type="number" name="width" class="form-control" id="width" min="1" placeholder="target width">
                        </div>
                        <div class="form-group">
                            <label for="height">Height</label>
                            <input type="number" name="height" class="form-control" id="height" min="1" placeholder="target height">
                        </div>
                        <div class="form-group">
                            <select name="algo">
                                <optgroup label="Random">
                                    <option value="3">Random</option>
                                </optgroup>
                                <optgroup label="Dynamic">
                                    <option value="2">Forward</option>
                                    <option value="1">Backward</option>
                                </optgroup>
                            </select>
                        </div>

                        <!--                        <div class="form-group">
                                                    <label>
                                                        <input type="checkbox"> Check me out
                                                    </label>
                                                </div>-->
                        <button type="submit" class="btn btn-primary btn-block">Seam-carve me!</button>
                    </form>
                </div>
            </div>
            <div id="main-wrapper" class="col-md-10 pull-right">
                <div id="main" style="text-align: center;">
                    <div class="page-header" style="text-align: center;">
                        <h3>Image</h3>
                    </div>
                    <img class="img-rounded" src="<?php echo $src; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" >
                </div>
            </div>
        </div>
        <script src="js/jquery-1.11.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.fs.selecter.min.js"></script>
        <script src="js/index.js"></script>

    </body>
</html>


