<?php

function setErrorMessages($errors = NULL) {

    if (!empty($errors)):

        $error = [];

        foreach ($errors as $key => $value) {

            foreach ($value as $v) {

                $error[] = $v;
            }
        }

        return $error;

    endif;
}

function pr($vars = NULL) {

    echo '<pre>';

    print_r($vars);

    echo '</pre>';
}

function getDecrypt($string = null) {
    $Crypt = new Illuminate\Support\Facades\Crypt;
    return $Crypt::decrypt($string);
}

function getCrypt($string = null) {
    $Crypt = new Illuminate\Support\Facades\Crypt;
    return $Crypt::encrypt($string);
}


function uploadAnyFile($file = null, $upload_dir = null) {
    if ($file) {
        $actual_name = $file->getClientOriginalName();
        $name_of_file = preg_replace('!\s+!', '-', $actual_name);
        $fileName = uniqid()."-" . $name_of_file;
        $return_url = $file->move( $upload_dir, $fileName);

        if ($return_url) {
            $url = $upload_dir . $fileName;
            $return_array = array('url' => $url, 'file_name' => $fileName);
            return $return_array;
        } else {
            return false;
        }
    }
}