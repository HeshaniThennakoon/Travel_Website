<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GH Travelers - Home</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
    <?php require('inc/links.php'); ?>

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


    <!-- Our Packages -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR PACKAGES</h2>

    <div class="container">
        <div class="row">

            <div class="col-lg-4 col-md-6 my-3">
                <div class="card-border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="images/packages/Colombo/colombo-2.png" class="card-img-top">
                    <div class="card-body">
                        <h5>Colombo and around</h5>
                        <p class="card-text">Center West - Capital City - 45 min from Bandaranaike International Airport</p>
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Read more</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 my-3">
                <div class="card-border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="images/packages/Sigiriya/sigiriya-6.jpg" class="card-img-top">
                    <div class="card-body">
                        <h5>Sigiriya and around</h5>
                        <p class="card-text">Northern Province - Cultural Triangle - 3/4h from Colombo</p>
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Read more</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 my-3">
                <div class="card-border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="images/packages/Kandy/kandy-1.jpeg" class="card-img-top">
                    <div class="card-body">
                        <h5>Kandy and around</h5>
                        <p class="card-text">Central Province - World Heritage site - 3,5h from Colombo</p>
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Read more</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 my-3">
                <div class="card-border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="images/packages/NuwaraEliya/nuwaraeliya-5.jpeg" class="card-img-top">
                    <div class="card-body">
                        <h5>Nuwara Eliya and around</h5>
                        <p class="card-text">Central Province - Tea Country Hills - 5,5 hours from Colombo or 2,5h from Kandy</p>
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Read more</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 my-3">
                <div class="card-border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="images/packages/South coast/southcost-1.jpeg" class="card-img-top">
                    <div class="card-body">
                        <h5>South coast and around</h5>
                        <p class="card-text">South Coast (North to South) - beach/surfing/snorkling (best season : October to April)</p>
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Read more</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 my-3">
                <div class="card-border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="images/packages/Ella/ella-1.jpeg" class="card-img-top">
                    <div class="card-body">
                        <h5>Ella and around</h5>
                        <p class="card-text">Central Province - Small yet popular town (Nine Arches Bridge) - 4h from Kandy</p>
                        <div class="d-flex">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Read more</a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-12 text-center mt-5">
                <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none ">More Packages >>></a>
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


    <?php require('inc/footer.php'); ?>


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
    </script>




</body>
</html>