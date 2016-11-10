<?php

if(!function_exists('create_editor')){
    function create_editor($id,$value='',$config=array()){
        // Include the CKEditor class.
        include_once "ckeditor/ckeditor.php";
        // Create a class instance.
        $CKEditor = new CKEditor( 'http://' . $_SERVER['HTTP_HOST'] . '/ckeditor/');
        // Path to the CKEditor directory, ideally use an absolute path instead of a relative dir.
        //   $CKEditor->basePath = '/ckeditor/'
        // If not set, CKEditor will try to detect the correct path.

        // Replace a textarea element with an id (or name) of "editor1".

        $_config['filebrowserBrowseUrl'] = '/ckfinder/ckfinder.html';
        $_config['filebrowserImageBrowseUrl'] = '/ckfinder/ckfinder.html?Type=Images';

        //$_config['filebrowserBrowseUrl'] = '/Public/Ckfinder';
        //$_config['filebrowserImageBrowseUrl'] = '/Public/Ckfinder?Type=Images';

        //$_config['disallowedContent'] = 'img[width,height]';

        //$_config['filebrowserUploadUrl'] = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
        //$_config['filebrowserImageUploadUrl'] = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
        if(!empty($config)){
            $_config = array_merge($_config,$config);
        }
        $CKEditor->editor($id,$value,$_config);
        //$CKEditor->replace("describe");
    }
}

function password_encrypt($password){
    return md5(md5($password) . \think\Config::get('auth_key'));
}