<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GH Travelers - Package Details</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
    <?php require('inc/links.php'); ?>

</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <?php
        if(!isset($_GET['id'])){
            redirect('packages.php');
        }

        $data = filteration($_GET);

        $package_res = select("SELECT * FROM `packages` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

        if(mysqli_num_rows($package_res)==0){
            redirect('packages.php');
        }

        $package_data = mysqli_fetch_assoc($package_res);

    ?>


    <div class="container">
        <div class="row">
        
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold"><?php echo $package_data['name'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="packages.php" class="text-secondary text-decoration-none">PACKAGES</a>

                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <div id="packageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php                            
                            $package_img = PACKAGES_IMG_PATH."thumbnail.png";
                            $img_q = mysqli_query($conn,"SELECT * FROM `package_images` 
                                WHERE `package_id`='$package_data[id]'");

                            if(mysqli_num_rows($img_q)>0){
                                $active_class = 'active';

                                while($img_res = mysqli_fetch_assoc($img_q))
                                {
                                    echo"<div class='carousel-item $active_class'>
                                        <img src='".PACKAGES_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
                                    </div>";
                                    $active_class='';
                                }

                            }
                            else{
                                echo"<div class='carousel-item active'>
                                    <img src='$package_img' class='d-block w-100'>
                                </div>";
                            }
                        ?>                    
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    </div>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <?php
                            echo<<<price
                                <h4>$ $package_data[price]</h4>
                            price;

                            echo<<<places
                                <div class="mb-3">
                                    <h6 class="mb-1">Suggest Places</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        <i class="bi bi-check-lg me-1"></i> $package_data[suggest_places]
                                    </span>
                                </div>  
                            places;

                            echo<<<guests
                                <div class="mb-3">
                                    <h6 class="mb-1">Guests</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        <i class="bi bi-check-lg me-1"></i> $package_data[no_of_guests] guests
                                    </span>
                                </div>  
                            guests;
                                
                            echo<<<days
                                <div class="mb-3">
                                    <h6 class="mb-1">No of Days</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        <i class="bi bi-check-lg me-1"></i> $package_data[no_of_days] days
                                    </span>
                                </div>  
                            days;

                            echo<<<vehicle
                                <div class="mb-3">
                                    <h6 class="mb-1">Vehicle Type</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        <i class="bi bi-check-lg me-1"></i> $package_data[vehicle_type]
                                    </span>
                                </div>  
                            vehicle;

                            echo<<<book
                                <a href="#" class="btn w-100 text-white custom-bg shadow-none mb-1">Book Now</a>
                            book;
                        ?>
                    </div>

                </div>
            </div>

            <div class="col-12 mt-4 px-4">
                <div class="mb-4">

                    <?php
                        $fea_q = mysqli_query($conn,"SELECT f.name FROM `features` f 
                            INNER JOIN `package_features` rfea ON f.id = rfea.features_id 
                            WHERE rfea.package_id = '$package_data[id]'");

                        $features_data = "<ul class='list-unstyled mb-4'>";
                        while($fea_row = mysqli_fetch_assoc($fea_q)){
                            $features_data .= "<li><i class='bi bi-check-lg text-success me-2'></i> $fea_row[name]</li>";
                        }
                        $features_data .= "</ul>";

                        echo<<<features
                            <div class="mb-3">
                                <h5 class="mb-1">Features</h5>
                                $features_data
                            </div> 
                        features;
                    ?>

                    <h5>Description</h5>
                    <p>
                        <?php echo $package_data['description'] ?>
                    </p>

                </div>

                
            </div>
            
        </div>
    </div>


    <?php require('inc/footer.php'); ?>

</body>
</html>