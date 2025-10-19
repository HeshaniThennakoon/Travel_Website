<?php
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    
    date_default_timezone_set("Asia/Kolkata");


    session_start();

    if(isset($_GET['fetch_packages']))
    {
        // Check availability data decodes
        $chk_avail = json_decode($_GET['chk_avail'],true);

        // Arrival date and Leaving date filter validations
        if($chk_avail['arrival_date'] != '' && $chk_avail['leaving_date'] != '')
        {            
            $today_date = new DateTime(date("Y-m-d"));
            $arrival_date = new DateTime($chk_avail['arrival_date']);
            $leaving_date = new DateTime($chk_avail['leaving_date']);

            if($arrival_date == $leaving_date){
                echo"<h3 class='text-center text-danger'>Invalid Dates Enters!</h3>";
                exit;
            }
            else if($leaving_date < $arrival_date){
                echo"<h3 class='text-center text-danger'>Invalid Dates Enters!</h3>";
                exit;
            }
            else if($arrival_date < $today_date){
                echo"<h3 class='text-center text-danger'>Invalid Dates Enters!</h3>";
                exit;
            }
        }

        // Guests data decode
        $guests = json_decode($_GET['guests'],true);
        $guests = ($guests['guests'] != '') ? $guests['guests'] : 0;

        // Days data decode
        $days = json_decode($_GET['days'],true);
        $days = ($days['days'] != '') ? $days['days'] : 0;

        // Vehicles data decode
        $vehicles = json_decode($_GET['vehicles'], true);
        $vehicle_filter = "";
        $vehicle_values = [];

        if (!empty($vehicles['vehicles'])) {
            $vehicle_list = $vehicles['vehicles'];
            $placeholders = implode(',', array_fill(0, count($vehicle_list), '?'));
            $vehicle_filter = "AND `vehicle_type` IN ($placeholders)";
            $vehicle_values = $vehicle_list;
        }


        // Count no. of packages and output variable to store package cards
        $count_packages = 0;
        $output = "";

        // Fetching settings table to check website is shutdown or not
        $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=1";
        $settings_r = mysqli_fetch_assoc(mysqli_query($conn,$settings_q)); 

        // Query for package cards with guests filter and Duration filter
       $query = "SELECT * FROM `packages` WHERE `no_of_days`>=? AND `no_of_guests`>=? AND `status`=? AND `removed`=? $vehicle_filter";

        $params = array_merge([$days, $guests, 1, 0], $vehicle_values);

        // Bind types dynamically (i = integer, s = string)
        $types = str_repeat('i', 4) . str_repeat('s', count($vehicle_values));

        $package_res = select($query, $params, $types);

        while($package_data = mysqli_fetch_assoc($package_res))
        {
            // Check availability filter
            if($chk_avail['arrival_date'] != '' && $chk_avail['leaving_date'] != '')
            {
                $query = "SELECT COUNT(*) AS total_conflicts
                    FROM `booking_order`
                    WHERE `booking_status`='booked'
                    AND `package_id`=?
                    AND (
                        (`arrival_date` < ? AND `leaving_date` > ?)
                        OR (`arrival_date` BETWEEN ? AND ?)
                        OR (`leaving_date` BETWEEN ? AND ?)
                    )
                ";

                $values = [
                    $package_data['id'],
                    $chk_avail['leaving_date'], $chk_avail['arrival_date'],
                    $chk_avail['arrival_date'], $chk_avail['leaving_date'],
                    $chk_avail['arrival_date'], $chk_avail['leaving_date']
                ];

                $result = select($query, $values, 'issssss');
                $fetch = mysqli_fetch_assoc($result);

                if ($fetch['total_conflicts'] > 0) {
                    continue;
                }
            }

            // get features of package

            $fea_q = mysqli_query($conn,"SELECT f.name FROM `features` f 
                INNER JOIN `package_features` rfea ON f.id = rfea.features_id 
                WHERE rfea.package_id = '$package_data[id]'");

            $features_data = "";
            while($fea_row = mysqli_fetch_assoc($fea_q)){
                $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                    <i class='bi bi-check-lg me-1'></i> $fea_row[name]
                </span>";
            }

            // get thumbnail of image

            $package_thumb = PACKAGES_IMG_PATH."thumbnail.png";
            $thumb_q = mysqli_query($conn,"SELECT * FROM `package_images` 
                WHERE `package_id`='$package_data[id]' 
                AND `thumb`='1'");

            if(mysqli_num_rows($thumb_q)>0){
                $thumb_res = mysqli_fetch_assoc($thumb_q);
                $package_thumb = PACKAGES_IMG_PATH.$thumb_res['image'];
            }

            
            $book_btn = "";

            if(!$settings_r['shutdown'])
            {
                $login = 0;
                if(isset($_SESSION['login']) && $_SESSION['login']==true){
                    $login = 1;
                }
                
                $book_btn = "<button onclick='checkLoginToBook($login,$package_data[id])' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";
            }

            // print package card

            $output.="
                <div class='card mb-4 border-0 shadow'>
                    <div class='row g-0 p-3 align-items-stretch'>
                        <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                            <img src='$package_thumb' class='img-fluid rounded w-100' style = 'height:250px; object-fit:cover;'>
                        </div>
                        <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                            <h5 class='mb-3'>$package_data[name]</h5>
                            <div class='places mb-3'>
                                <h6 class='mb-1'>Suggest Places</h6>
                                $package_data[suggest_places]
                            </div>
                            <div class='Features mb-3'>
                                <h6 class='mb-1'>Features</h6>
                                $features_data
                            </div>                        
                        </div>
                        <div class='col-md-2 d-flex flex-column justify-content-center text-center'>
                            <h6 class='mb-4'>$ $package_data[price]</h6>
                            $book_btn
                            <a href='package_details.php?id=$package_data[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More details</a>
                        </div>
                    </div>
                </div>
            ";

            $count_packages++;

        }

        if($count_packages>0){
            echo $output;
        }
        else{
            echo"<h3 class='text-center text-danger'>No packages to show!</h3>";
        }


    }
?>