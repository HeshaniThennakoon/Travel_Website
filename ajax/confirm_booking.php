<?php
    require('../admin/inc/db_config.php');
    require('../admin/inc/essentials.php');
    require('../inc/sendgrid/sendgrid-php.php');

    date_default_timezone_set("Asia/Kolkata");

    if(isset($_POST['check_availability']))
    {
        $frm_data = filteration($_POST);
        $status = "";
        $result = "";

        // Arrival date and leaving date validations

        $today_date = new DateTime(date("Y-m-d"));
        $arrival_date = new DateTime($frm_data['arrival_date']);
        $leaving_date = new DateTime($frm_data['leaving_date']);

        if($arrival_date == $leaving_date){
            $status = 'arrival_leaving_equal';
            $result = json_encode(["status" => $status]);
        }
        else if($leaving_date < $arrival_date){
            $status = 'leaving_earlier';
            $result = json_encode(["status" => $status]);
        }
        else if($arrival_date < $today_date){
            $status = 'arrival_date_earlier';
            $result = json_encode(["status" => $status]);
        }

        // ckeck booking availability if status is blank else return the error

        if($status!=''){
            echo $result;
        }
        else{
            session_start();

            // run query to ckeck the days are available or not
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
                $_SESSION['package']['id'],
                $frm_data['leaving_date'], $frm_data['arrival_date'],
                $frm_data['arrival_date'], $frm_data['leaving_date'],
                $frm_data['arrival_date'], $frm_data['leaving_date']
            ];

            $result = select($query, $values, 'issssss');
            $fetch = mysqli_fetch_assoc($result);

            if ($fetch['total_conflicts'] > 0) {
                echo json_encode(["status" => "unavailable"]);
                exit;
            }



            $count_days = date_diff($arrival_date,$leaving_date) -> days;
            $payement = $_SESSION['package']['price'];

            $_SESSION['package']['payment'] = $payement;
            $_SESSION['package']['available'] = true;

            $result = json_encode(["status"=>'available', "days"=>$count_days, "payment"=>$payement]);
            echo $result;
        }
    }



?>