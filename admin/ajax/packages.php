<?php

    require('../inc/db_config.php');
    require('../inc/essentials.php');
    
    adminLogin();

    if(isset($_POST['add_package'])){

        $features = filteration(json_decode($_POST['features']));

        $frm_data = filteration($_POST);
        $flag = 0;

        $q1 = "INSERT INTO `packages` (`name`, `no_of_guests`, `no_of_days`, `vehicle_type`, `suggest_places`, `price`, `description`) VALUES (?,?,?,?,?,?,?)";
        $values = [$frm_data['name'],$frm_data['guests'],$frm_data['days'],$frm_data['vehicle'],$frm_data['places'],$frm_data['price'],$frm_data['desc']];

        if(insert($q1,$values,'siissis')){
            $flag = 1;
        }

        $package_id = mysqli_insert_id($conn);

        $q2 = "INSERT INTO `package_features` (`package_id`, `features_id`) VALUES (?,?)";
        if($stmt = mysqli_prepare($conn,$q2))
        {
            foreach($features as $f){
                mysqli_stmt_bind_param($stmt,'ii',$package_id,$f);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        else{
            $flag = 0;
            die('query cannot be prepared - insert');

        }

        if($flag){
            echo 1;
        }
        else{
            echo 0;
        }

    }

    if(isset($_POST['get_all_packages'])){
        $res = select("SELECT * FROM `packages` WHERE `removed`=?",[0],'i');
        $i = 1;

        $data = "";

        while($row = mysqli_fetch_assoc($res))
        {
            if($row['status'] == 1){
                $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
            }
            else{
                $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
            }


            $data.="
                <tr class='align-middle'>
                    <td>$i</td>
                    <td>$row[name]</td>
                    <td>
                        <span class='badge rounded-pill bg-light text-dark'>
                            No of guests: $row[no_of_guests]
                        </span>
                    </td>
                    <td>$row[no_of_days]</td>
                    <td>$row[vehicle_type]</td>
                    <td>$row[suggest_places]</td>
                    <td>$$row[price]</td>
                    <td>$status</td>
                    <td>
                        <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-package'>
                            <i class='bi bi-pencil-square'></i> 
                        </button> 
                        <button type='button' onclick=\"package_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#package-images'>
                            <i class='bi bi-images'></i> 
                        </button> 
                        <button type='button' onclick='remove_package($row[id])' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i> 
                        </button> 
                    </td>
                </tr>
            ";
            $i++;
        }
        echo $data;

    }

    if(isset($_POST['get_package'])){
        $frm_data = filteration($_POST);

        $res1 = select("SELECT * FROM `packages` WHERE `id`=?", [$frm_data['get_package']], 'i');
        $res2 = select("SELECT * FROM `package_features` WHERE `package_id`=?", [$frm_data['get_package']], 'i');

        $packagedata = mysqli_fetch_assoc($res1);
        $features = [];

        if(mysqli_num_rows($res2)>0)
        {
            while($row = mysqli_fetch_assoc($res2)){
                array_push($features,$row['features_id']);
            }
        }

        $data = ["packagedata" => $packagedata, "features" => $features];

        $data = json_encode($data);

        echo $data;

    }

    if(isset($_POST['edit_package'])){
        $features = filteration(json_decode($_POST['features']));
        $frm_data = filteration($_POST);
        $flag = 0;

        $q1 = "UPDATE `packages` SET `name`=?,`no_of_guests`=?,`no_of_days`=?,`vehicle_type`=?,`suggest_places`=?,`price`=?,`description`=? WHERE `id`=?";
        $values = [$frm_data['name'],$frm_data['guests'],$frm_data['days'],$frm_data['vehicle'],$frm_data['places'],$frm_data['price'],$frm_data['desc'],$frm_data['package_id']];

        if(update($q1,$values,'siissisi')){
            $flag = 1;
        }

        $del_features = delete("DELETE FROM `package_features` WHERE `package_id`=?", [$frm_data['package_id']],'i');

        if(!($del_features)){
            $flag = 0;
        }

        $q2 = "INSERT INTO `package_features` (`package_id`, `features_id`) VALUES (?,?)";
        if($stmt = mysqli_prepare($conn,$q2))
        {
            foreach($features as $f){
                mysqli_stmt_bind_param($stmt,'ii',$frm_data['package_id'],$f);
                mysqli_stmt_execute($stmt);
            }
            $flag = 1;
            mysqli_stmt_close($stmt);
        }
        else{
            $flag = 0;
            die('query cannot be prepared - insert');

        }

        if($flag){
            echo 1;
        }
        else{
            echo 0;
        }

        
    }

    if(isset($_POST['add_image']))
    {
        $frm_data = filteration($_POST);

        $img_r = uploadImage($_FILES['image'], PACKAGES_FOLDER);

        if($img_r == 'inv_img'){
            echo $img_r;
        }
        else if($img_r == 'inv_size'){
            echo $img_r;
        }
        else if($img_r == 'upd_failed'){
            echo $img_r;
        }
        else{
            $query = "INSERT INTO `package_images`(`package_id`, `image`) VALUES (?,?)";
            $values = [$frm_data['package_id'],$img_r];
            $res = insert($query,$values,'is');
            echo $res;
        }

    }

    if(isset($_POST['get_package_images']))
    {
        $frm_data = filteration($_POST);
        $res = select("SELECT * FROM `package_images` WHERE `package_id`=?",[$frm_data['get_package_images']],'i');

        $path = PACKAGES_IMG_PATH;

        while($row = mysqli_fetch_assoc($res))
        {
            if($row['thumb'] == 1){
                $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
            }
            else{
                $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[package_id])' class='btn btn-secondary shadow-none'>
                    <i class='bi bi-check-lg'></i>
                </button>";
            }


            echo<<<data
                <tr class='align-middle'>
                    <td><img src='$path$row[image]' class='img-fluid'></td>
                    <td>$thumb_btn</td>
                    <td>
                        <button onclick='rem_image($row[sr_no],$row[package_id])' class='btn btn-danger shadow-none'>
                            <i class='bi bi-trash'></i>
                        </button>
                    </td>
                </tr>
            data;
        }

    }

    if(isset($_POST['rem_image']))
    {
        $frm_data = filteration($_POST);  

        $values = [$frm_data['image_id'],$frm_data['package_id']]; 

        $pre_q = "SELECT * FROM `package_images` WHERE `sr_no`=? AND `package_id`=?";
        $res = select($pre_q,$values,'ii');
        $img = mysqli_fetch_assoc($res);

        if(deleteImage($img['image'],PACKAGES_FOLDER)){
            $query = "DELETE FROM `package_images` WHERE `sr_no`=? AND `package_id`=?";
            $res = delete($query,$values,'ii');
            echo $res;
        }
        else{
            echo 0;
        }
    }

    if(isset($_POST['toggle_status'])){
        $frm_data = filteration($_POST);

        $q = "UPDATE `packages` SET `status`=? WHERE `id`=?";
        $v = [$frm_data['value'],$frm_data['toggle_status']];

        if(update($q,$v,'ii')){
            echo 1;
        }
        else{
            echo 0;
        }

    }

    if(isset($_POST['thumb_image']))
    {
        $frm_data = filteration($_POST);  

        $pre_q = "UPDATE `package_images` SET `thumb`=? WHERE `package_id`=?";
        $pre_v = [0,$frm_data['package_id']];
        $pre_res = update($pre_q, $pre_v, 'ii');

        $q = "UPDATE `package_images` SET `thumb`=? WHERE `sr_no`=? AND `package_id`=?";
        $v = [1,$frm_data['image_id'],$frm_data['package_id']];
        $res = update($q, $v, 'iii');

        echo $res;

    }

    if(isset($_POST['remove_package'])){
        $frm_data = filteration($_POST); 

        $res1 = select("SELECT * FROM `package_images` WHERE `package_id`=?",[$frm_data['package_id']],'i');

        while($row = mysqli_fetch_assoc($res1)){
            deleteImage($row['image'],PACKAGES_FOLDER);
        }

        $res2 = delete("DELETE FROM `package_images` WHERE `package_id`=?",[$frm_data['package_id']],'i');
        $res3 = delete("DELETE FROM `package_features` WHERE `package_id`=?",[$frm_data['package_id']],'i');
        $res4 = update("UPDATE `packages` SET `removed`=? WHERE `id`=? ",[1,$frm_data['package_id']],'ii');

        if($res2 || $res3 || $res4){
            echo 1;
        }
        else{
            echo 0;
        }
    }

?>