<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Packages</title>

</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR PACKAGES</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">Discover our exclusive travel packages designed to offer you 
            unforgettable experiences at unbeatable prices. <br>
            We have the perfect package for you. <br> 
            Explore our curated selections and start planning your dream getaway today!
        </p>
    </div>

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 align-self-start ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropDown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-3" id="filterDropDown">
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">CHECK AVAILABILITY</h5>
                                <label class="form-label">Arrival-date</label>
                                <input type="date" class="form-control shadow-none mb-3">
                                <label class="form-label">Leaving-date</label>
                                <input type="date" class="form-control shadow-none">
                            </div> 
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">VEHICLES</h5>
                                <div class="mb-2">
                                    <input type="checkbox" id="v1" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="v1">Car (1-3 pax)</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="v2" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="v2">Mini Van (1-6 pax)</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="v3" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="v3">Large Van (1-10 pax)</label>
                                </div>                                
                            </div>
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">GUESTS</h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">No. of Guests</label>
                                        <input type="number" class="form-control shadow-none">
                                    </div>
                                </div>                                                           
                            </div> 
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">Duration</h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">No. of days</label>
                                        <input type="number" class="form-control shadow-none">
                                    </div>
                                </div>                                                           
                            </div>                          
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4">

                <?php 
                    $package_res = select("SELECT * FROM `packages` WHERE `status`=? AND `removed`=?",[1,0],'ii');

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
                            $book_btn = "<a href='#' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</a>";
                        }

                        // print package card

                        echo<<<data
                            <div class="card mb-4 border-0 shadow">
                                <div class="row g-0 p-3 align-items-stretch">
                                    <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                        <img src="$package_thumb" class="img-fluid rounded w-100" style = "height:250px; object-fit:cover;">
                                    </div>
                                    <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                        <h5 class="mb-3">$package_data[name]</h5>
                                        <div class="places mb-3">
                                            <h6 class="mb-1">Suggest Places</h6>
                                            $package_data[suggest_places]
                                        </div>
                                        <div class="Features mb-3">
                                            <h6 class="mb-1">Features</h6>
                                            $features_data
                                        </div>                        
                                    </div>
                                    <div class="col-md-2 d-flex flex-column justify-content-center text-center">
                                        <h6 class="mb-4">$ $package_data[price]</h6>
                                        $book_btn
                                        <a href="package_details.php?id=$package_data[id]" class="btn btn-sm w-100 btn-outline-dark shadow-none">More details</a>
                                    </div>
                                </div>
                            </div>
                        data;

                    }
                ?>

            </div>
            
        </div>
    </div>


    <?php require('inc/footer.php'); ?>

</body>
</html>