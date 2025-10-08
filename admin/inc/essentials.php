<?php

    // Base URL
    define('SITE_URL','http://127.0.0.1:8080/');

    // Team images for frontend
    define('ABOUT_IMG_PATH', SITE_URL.'images/about/Team/');
    define('CAROUSEL_IMG_PATH', SITE_URL.'images/carousel/');
    define('PACKAGES_IMG_PATH',SITE_URL.'images/packages/');
    define('USERS_IMG_PATH',SITE_URL.'images/users/');

    // Backend upload process 
    define('UPLOAD_IMAGE_PATH', 'E:/5.Projects/Travel Website/images/');
    define('ABOUT_FOLDER','about/Team/');
    define('CAROUSEL_FOLDER','carousel/');
    define('PACKAGES_FOLDER','packages/');
    define('USERS_FOLDER','users/');

    // sendgrid api key
    define('SENDGRID_API_KEY',"<Sendgrid API Key>"); //Sendgrid API Key should be added here!
    define('SENDGRID_EMAIL',"heshanithennakoon118@gmail.com");
    define('SENDGRID_NAME',"GH WEBDEV");

    function adminLogin()
    {
        session_start();
        if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
            echo"<script>
                window.location.href='index.php';
            </script>";
            exit;
        }
        
    }

    function redirect($url)
    {
        echo"<script>
            window.location.href='$url'
        </script>";
        exit;
    }

    function alert($type, $msg)
    {
        $bs_class = ($type=='success') ? 'alert-success' : 'alert-danger';

        echo <<<alert
            <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
                <strong class="me-3">$msg</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        alert;        
    }

    function uploadImage($image, $folder)
    {
        $valid_mime = ['image/jpeg','image/png','image/webp','image/jpg'];
        $img_mime = $image['type'];

        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_img';
        }
        else if (($image['size'] / (1024*1024)) > 5) {
            return 'inv_size';
        }
        else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $rname = 'IMG_' . random_int(11111,99999) . ".$ext";

            // make sure target folder exists
            $targetDir = UPLOAD_IMAGE_PATH . $folder;
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $img_path = $targetDir . $rname;

            if (move_uploaded_file($image['tmp_name'], $img_path)) {
                return $rname; // return new random filename
            } else {
                return 'upd_failed';
            }
        }
    }


    function deleteImage($image, $folder)
    {   
        $path = UPLOAD_IMAGE_PATH.$folder.$image;
        if(file_exists($path) && unlink($path)){
            return true;
        }
        return false;
    }

    function uploadUserImage($image)
    {
        $valid_mime = ['image/jpeg','image/png','image/webp','image/jpg'];
        $img_mime = $image['type'];

        if (!in_array($img_mime, $valid_mime)) {
            return 'inv_img';
        }
        else {
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $rname = 'IMG_' . random_int(11111,99999) . ".jpeg";

            $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

            if($ext == 'png' || $ext == 'PNG'){
                $img = imagecreatefrompng($image['tmp_name']);
            }
            else if($ext == 'webp' || $ext == 'WEBP'){
                $img = imagecreatefromwebp($image['tmp_name']);
            }
            else{
                $img = imagecreatefromjpeg($image['tmp_name']);
            }


            if (imagejpeg($img,$img_path,75)) {
                return $rname;
            } 
            else {
                return 'upd_failed';
            }
        }
    }



?>