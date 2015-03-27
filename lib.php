<?php

function verifyFileUpload($filename) {
    if (isset($_FILES[$filename]) AND $_FILES[$filename]['error'] == 0) {


        $fileInfos = pathinfo($_FILES[$filename]['name']);
        $uploadExtension = $fileInfos['extension'];
        $allowedExtensions = array('png','jpg','bmp');
        if (in_array($uploadExtension, $allowedExtensions)) {

            return true;
        }
    }
    return false;
}
