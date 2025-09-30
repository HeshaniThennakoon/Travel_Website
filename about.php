<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GH Travelers - About</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>
    <?php require('inc/links.php'); ?>

    <style>
        .box{
            border-top-color: var(--teal) !important;        
        }

        .box img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin: 0 auto;
        }

        .swiper-slide img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

    </style>

</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center" >ABOUT US</h2>
    </div>
        <div class="h-line bg-dark"> </div>
            <p class="text-center mt-3"> 
                “We are a travel company dedicated to creating memorable journeys, <br>
                connecting travelers with authentic experiences and cultures.”
            </p>
        </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">why choose us?</h3>
                <p>
                    We are committed to providing exceptional travel experiences that go beyond the ordinary. <br>
                    Our team of experienced travel experts is passionate about curating personalized journeys that cater to your unique interests and preferences. <br>
                    From the moment you contact us to the end of your trip, we prioritize your satisfaction and comfort. <br>
                    We work closely with trusted partners and local guides to ensure that every aspect of your journey is seamless and enjoyable. <br>
                    Whether you're seeking adventure, relaxation, cultural immersion, or a combination of all three, we have the expertise to make it happen. <br>
                    Choose us for a travel experience that is not only memorable but also meaningful.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/about/about.jpg" class="w-100 rounded">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <h2 class="fw-bold h-font text-center">Our Services</h2>
            <p class="text-center mt-3">
                We offer a range of services to make your travel experience seamless and enjoyable.
            </p>
        </div>
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 mb-4 px-4 d-flex">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box flex-fill d-flex flex-column">
                    <img src="images/about/icon-1.png" width="200px" height="200px">
                    <h4 class="mt-3 text-center">Tour transport and guide</h4><br>
                    <p class="flex-grow-1">
                        Your driver will be delighted to take you around, and show you around based on your chosen itinerary. 
                        He can give tips and suggestions along the way to make your trip memorable and comfortable.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4 d-flex">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box flex-fill d-flex flex-column">
                    <img src="images/about/icon-2.jpg" width="200px" height="200px">
                    <h4 class="mt-3 text-center">(Airport) Transfers</h4><br>
                    <p class="flex-grow-1">
                        Just need a simple transfer from point A to B. From the airport, or from anywhere else? No problem. We can do that!
                        <br>
                        (not by tuk tuk :) )
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4 d-flex">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box flex-fill d-flex flex-column">
                    <img src="images/about/icon-3.jpg" width="200px" height="200px">
                    <h4 class="mt-3 text-center">Trip customization</h4><br>
                    <p class="flex-grow-1">
                        Want to come to Sri Lanka and don't know where to start? We got you covered! 
                        We're happy to collaborate with you to understand your needs and vision for your trip and we can then put together a custom itinerary.
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4 d-flex">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box flex-fill d-flex flex-column">
                    <img src="images/about/icon-4.jpg" width="200px" height="200px">
                    <h4 class="mt-3 text-center">Accommodation and activity booking</h4><br>
                    <p class="flex-grow-1">
                        Looking for unique experiences during your trip? Such as the iconic train ride over the Nine Arches Bridge, 
                        or private jeep safari, or whale watching or a local cooking class? We can arrange this for you.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <h3 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h3>

    <div class="container px-4">

        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/Team/1.jpg" class="w-100">
                    <h5 class="mt-2">Name one</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/Team/2.jpg" class="w-100">
                    <h5 class="mt-2">Name two</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/Team/3.jpg" class="w-100">
                    <h5 class="mt-2">Name three</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/Team/4.jpg" class="w-100">
                    <h5 class="mt-2">Name four</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/Team/5.jpg" class="w-100">
                    <h5 class="mt-2">Name five</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/Team/6.jpg" class="w-100">
                    <h5 class="mt-2">Name six</h5>
                </div>
                <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                    <img src="images/about/Team/7.jpg" class="w-100">
                    <h5 class="mt-2">Name seven</h5>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>

    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {

            slidesPerView: 3,
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>



</body>
</html>