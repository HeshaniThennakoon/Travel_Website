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
        $res = selectAll('packages');
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

?>