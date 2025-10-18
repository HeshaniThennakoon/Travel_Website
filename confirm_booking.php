<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Confirm Booking</title>


</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <?php
        if(!isset($_GET['id']) || $settings_r['shutdown'] == true){
            redirect('packages.php');
        }
        else if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
            redirect('packages.php');
        }

        // filter and get package and user data

        $data = filteration($_GET);

        $package_res = select("SELECT * FROM `packages` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

        if(mysqli_num_rows($package_res)==0){
            redirect('packages.php');
        }

        $package_data = mysqli_fetch_assoc($package_res);

        $_SESSION['package'] = [
            "id" => $package_data['id'],
            "name" => $package_data['name'],
            "price" => $package_data['price'],
            "payment" => null,
            "available" => false,
        ];

        $user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']],"i");
        $user_data = mysqli_fetch_assoc($user_res);
    ?>


    <div class="container">
        <div class="row">
        
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">CONFIRM BOOKING</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="packages.php" class="text-secondary text-decoration-none">PACKAGES</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>

                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <?php 
                    $package_thumb = PACKAGES_IMG_PATH."thumbnail.png";
                    $thumb_q = mysqli_query($conn,"SELECT * FROM `package_images` 
                        WHERE `package_id`='$package_data[id]' 
                        AND `thumb`='1'");

                    if(mysqli_num_rows($thumb_q)>0){
                        $thumb_res = mysqli_fetch_assoc($thumb_q);
                        $package_thumb = PACKAGES_IMG_PATH.$thumb_res['image'];
                    }

                    echo<<<data
                        <div class="card p-3 shadow-sm rounded">
                            <img src="$package_thumb" class="img-fluid rounded w-100 mb-3" style = "height:250px; object-fit:cover;">
                            <h5>$package_data[name]</h5>
                            <h6>$$package_data[price]</h6>
                            <h6 class="text-secondary">Duration: $package_data[no_of_days] days</h6>
                        </div>
                    data;
                
                ?>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="pay_now.php" id="booking_form" method="POST">
                            <h6 class="mb-3">BOOKING DETAILS</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_data['address'] ?></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Tour Duration</label>
                                    <input type="text" id="tour_days" class="form-control shadow-none" value="<?php echo $package_data['no_of_days'].' days'; ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Arrival Date</label>
                                    <input name="arrival" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Leaving Date</label>
                                    <input name="leaving" onchange="check_availability()" type="date" class="form-control shadow-none" readonly>
                                </div>
                                <div class="col-12">
                                    <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>

                                    <h6 class="mb-3 text-danger" id="pay_info">Provide arrival-date & leaving-date !</h6>

                                    <button name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay Now</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            
        </div>
    </div>


    <?php require('inc/footer.php'); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const booking_form = document.getElementById('booking_form');
            const info_loader = document.getElementById('info_loader');
            const pay_info = document.getElementById('pay_info');
            const tour_days_input = document.getElementById('tour_days');

            if (!booking_form || !tour_days_input) return;

            const tour_days = parseInt(tour_days_input.value);

            function check_availability() {
                const arrival_input = booking_form.elements['arrival'];
                const leaving_input = booking_form.elements['leaving'];
                const pay_now_btn = booking_form.elements['pay_now'];

                const arrival_val = arrival_input.value;
                let leaving_val = leaving_input.value;

                pay_now_btn.setAttribute('disabled', true);

                // Auto-select leaving date based on tour duration
                if (arrival_val !== '' && (leaving_val === '' || document.activeElement === arrival_input)) {
                    const arrivalDate = new Date(arrival_val);
                    arrivalDate.setDate(arrivalDate.getDate() + tour_days);
                    const leavingDateStr = arrivalDate.toISOString().split('T')[0];
                    leaving_input.value = leavingDateStr;
                    leaving_val = leavingDateStr;
                }

                // Proceed to check availability only if both dates are set
                if (arrival_val !== '' && leaving_val !== '') {
                    pay_info.classList.add('d-none');                    
                    pay_info.classList.replace('text-dark','text-danger');                    
                    info_loader.classList.remove('d-none');

                    let data = new FormData();

                    data.append('check_availability', '');
                    data.append('arrival_date', arrival_val);
                    data.append('leaving_date', leaving_val);

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "ajax/confirm_booking.php", true);

                    xhr.onload = function () {
                        info_loader.classList.add('d-none');
                        let data = JSON.parse(this.responseText);

                        if (data.status === 'arrival_leaving_equal') {
                            pay_info.innerText = "You cannot leaving on the same day!";
                        } 
                        else if(data.status === 'leaving_earlier') {
                            pay_info.innerText = "Leaving date is earlier than arrival date!"
                        }
                        else if(data.status === 'arrival_date_earlier') {
                            pay_info.innerText = "Arrival date is earlier than today's date!"
                        }
                        else if(data.status === 'unavailable') {
                            pay_info.innerText = "Tour not available for this selected dates!"
                        }
                        else{
                            pay_info.innerHTML = "No. of Days: "+data.days+"<br>Total Amount to Pay: $"+data.payment;
                            pay_info.classList.replace('text-danger','text-dark'); 
                            booking_form.elements['pay_now'].removeAttribute('disabled');
                        }

                        pay_info.classList.remove('d-none');
                        info_loader.classList.add('d-none');
                    };

                    xhr.send(data);
                }
            }

            // Make function accessible to HTML onchange event
            window.check_availability = check_availability;
        });
    </script>


</body>
</html>