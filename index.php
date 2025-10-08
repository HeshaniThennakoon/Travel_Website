<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Home</title>
    <style>
        .card {
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        }
        .card-img-top {
        height: 200px;
        object-fit: cover;
        }
        .card-body {
        display: flex;
        flex-direction: column;
        position: relative;
        padding-bottom: 50px;
        }

    </style>

</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <!-- Carousel, Swipper -->
    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                    $res = selectAll('carousel');
                    while($row = mysqli_fetch_assoc($res))
                    {
                        $imgPath = CAROUSEL_IMG_PATH.$row['image'];                        
                        echo <<<data
                            
                            <div class="swiper-slide">
                                <img src="$imgPath" class="w-100 d-block" />
                            </div>
                        data;
                    }
                ?>                
            </div>
        </div>
    </div>


    <!-- Packages Details -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR PACKAGES</h2>

    <div class="container">
        <div class="row d-flex flex-wrap align-items-stretch">

            <?php 
                $package_res = select("SELECT * FROM `packages` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 6",[1,0],'ii');

                while($package_data = mysqli_fetch_assoc($package_res))
                {
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

                    if(!$settings_r['shutdown']){
                        $book_btn = "<a href='#' class='btn btn-sm text-white custom-bg shadow-none mb-2'>Book Now</a>";
                    }

                    // print package card

                    echo<<<data
                        <div class="col-lg-4 col-md-6 my-3 d-flex align-items-stretch">
                            <div class="card border-0 shadow w-100" style="max-width: 350px; margin: auto;">
                                <img src="$package_thumb" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column position-relative">
                                    <h5 class="fw-bold mb-2">$package_data[name]</h5><br>
                                    <h6 class="mb-1">Suggest Places</h6>
                                    <p class="card-text flex-grow-1">$package_data[suggest_places]</p>
                                    $book_btn
                                    <a href="package_details.php?id=$package_data[id]" class="btn btn-sm w-100 btn-outline-dark shadow-none">Read more</a>
                                </div>
                            </div>
                        </div>

                    data;

                }
            ?>

            <div class="col-lg-12 text-center mt-5">
                <a href="packages.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none ">More Packages >>></a>
            </div>
        </div>
    </div>


    <!-- Reach Us -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy" ></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Call us</h5>
                    <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
                    </a>
                    <br>
                    <?php
                        if($contact_r['pn2']!=''){
                            echo<<<data
                                <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                                </a>
                            data;
                        }                        
                    ?>

                </div>


                <div class="bg-white p-4 rounded mb-4">
                    <h5>Follow us</h5>
                    <?php
                        if($contact_r['fb']!=''){
                            echo<<<data
                            <a href="$contact_r[fb]" class="d-inline-block mb-3">
                                <span class="badge bg-light text-dark fs-6 p-2">
                                    <i class="bi bi-facebook"></i> Facebook
                                </span>
                            </a>
                            data;
                        }
                    ?>                    
                    <br>
                    <a href="<?php echo $contact_r['tiktok']?>" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-tiktok"></i> Tik tok
                        </span>
                    </a>
                    <br>
                    <a href="<?php echo $contact_r['insta']?>" class="d-inline-block">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram"></i> Instragram
                        </span>
                    </a>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Password reset modal and code -->

    <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="recovery-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-shield-lock fs-3 me-2"></i> Set Up New Password
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">New password</label>
                            <input type="password" name="pass" required class="form-control shadow-none">
                            <input type="hidden" name="email">
                            <input type="hidden" name="token">
                        </div>
                        <div class="text-end mb-2">
                            <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <?php require('inc/footer.php'); ?>

    <?php 

        if(isset($_GET['account_recovery']))
        {
            $data = filteration($_GET);

            $t_date = date("Y-m-d");

            $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
                [$data['email'],$data['token'],$t_date],'sss');

            if(mysqli_num_rows($query) == 1)
            {
                echo<<<showModal
                    <script>
                        var myModal = document.getElementById('recoveryModal');

                        myModal.querySelector("input[name='email']").value = '$data[email]';
                        myModal.querySelector("input[name='token']").value = '$data[token]';

                        var modal = bootstrap.Modal.getOrCreateInstance(myModal);
                        modal.show();
                    </script>                    
                showModal;
            }
            else{
                alert("error","Invalid or Expired Link!");
            }
        }


    ?>


    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 0,
            slidesPerView: 1,
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            effect: "slide",
            pagination: {
                el: ".swiper-pagination",
            },
            
        }); 

        // recover account

        let recovery_form = document.getElementById('recovery-form');

        recovery_form.addEventListener('submit', (e)=>{
            e.preventDefault();

            let data = new FormData();

            data.append('email',recovery_form.elements['email'].value);
            data.append('token',recovery_form.elements['token'].value);
            data.append('pass',recovery_form.elements['pass'].value);
            data.append('recover_user','');

            var myModal = document.getElementById('recoveryModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/login_register.php", true);

            xhr.onprogress = function(){
                
            }

            xhr.onload = function(){
                if(this.responseText == 'failed'){
                    alert('error',"Account reset failed!");
                }
                else{
                    alert('success',"Account Reset Successful!");
                    recovery_form.reset();
                }

            }

            xhr.send(data); 
            
        });

    </script>




</body>
</html>