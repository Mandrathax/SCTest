<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
session_start();
include_once 'lib.php';

$width = 800;
$height = 600;

if (verifyFileUpload('inputImage')) {
    move_uploaded_file($_FILES["inputImage"]["tmp_name"], "images/" . session_id());
    $src = "images/" . session_id();
    $dims = getimagesize($src);
    $width = $dims[0];
    $height = $dims[1];
} else {
    $src = 'images/default.jpg';
}

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
                    <a class="navbar-brand" href="#">
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
                    <!--                    <ul class="nav navbar-nav">
                                            <li>
                                                <a href="#">Navbar Item 1</a>
                                            </li>
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Navbar Item 2<b class="caret"></b></a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#">Navbar Item2 - Sub Item 1</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">Navbar Item 3</a>
                                            </li>
                                        </ul>-->
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                            <a href="#" id="nbAcctDD"><i class="icon-user"></i>Quit<i class="icon-sort-down"></i></a>
                            <!--                            <ul class="dropdown-menu pull-right">
                                                            <li><a href="#">Log Out</a></li>
                                                        </ul>-->
                        </li>
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
                            <input type="number" class="form-control" id="width" min="1" placeholder="800">
                        </div>
                        <div class="form-group">
                            <label for="height">Height</label>
                            <input type="number" class="form-control" id="height" min="1" placeholder="600">
                        </div>

                        <!--                        <div class="form-group">
                                                    <label>
                                                        <input type="checkbox"> Check me out
                                                    </label>
                                                </div>-->
                        <button type="submit" class="btn btn-info btn-block">Seam-carve me!</button>
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
    </body>
</html>
