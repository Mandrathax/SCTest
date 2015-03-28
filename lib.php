<?php

function verifyFileUpload($filename) {
    if (isset($_FILES[$filename])) {
        if ($_FILES[$filename]['error'] == 0) {



            $fileInfos = pathinfo($_FILES[$filename]['name']);
            $uploadExtension = $fileInfos['extension'];
            $allowedExtensions = array('png', 'jpg', 'bmp');
            if (in_array($uploadExtension, $allowedExtensions)) {

                return 'no error';
            } else {
                $err = 'Error : Wrong file extension (only jpg, png and bmp are allowed)';
            }
        } else {
            $err = 'Error :';
            switch ($_FILES[$filename]['error']) {
                case 1:
                    $err.=' The uploaded file is too big';
                    break;
                case 2:
                    $err.=' The uploaded file is too big';
                    break;
                case 3:
                    $err.=' Upload interrupted';
                    break;
                case 4:
                    $err.=' No file uploaded';
                    break;
                case 6:
                    $err.=' Temp folder is missing';
                    break;
                case 7:
                    $err.=' Couldn\'t write to disk';
                    break;
                case 8:
                    $err.=' A php extension stopped the upload';
                    break;
                default:
                    $err.=' Unknown error';
                    break;
            }
        }
    }else{
        $err=' ';
    }

    return $err;
}

function clean_tmp() {
    $dir = scandir('images/tmp');
    $time = time();
    foreach ($dir as $file) {
        if (end(explode('.', $file)) == 'jpg' AND file_exists($file)) {
            $diff = $time - filemtime($file);
            if ($diff > 1800) {
                print_r($file);
                unlink($file);
            }
        }
    }
}
