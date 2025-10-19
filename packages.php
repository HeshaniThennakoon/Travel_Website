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
                            <!-- Check Availability -->
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>CHECK AVAILABILITY</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn shadow-none btn-sm text-secondary">Reset</button>
                                </h5>
                                <label class="form-label">Arrival-date</label>
                                <input type="date" class="form-control shadow-none mb-3" id="arrival_date" onchange="chk_avail_filter()">
                                <label class="form-label">Leaving-date</label>
                                <input type="date" class="form-control shadow-none" id="leaving_date" onchange="chk_avail_filter()">
                            </div> 

                            <!-- Vehicle Type -->
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>VEHICLES</span>
                                    <button id="vehicle_btn" onclick="vehicle_clear()" class="btn shadow-none btn-sm text-secondary">Reset</button>
                                </h5>
                                <div class="mb-2">
                                    <input type="checkbox" id="v1" onclick="fetch_packages()" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="v1">Car (1-3 pax)</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="v2" onclick="fetch_packages()" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="v2">Mini Van (1-6 pax)</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="v3" onclick="fetch_packages()" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="v3">Large Van (1-10 pax)</label>
                                </div>                                
                            </div>

                            <!-- Guests -->
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>GUESTS</span>
                                    <button id="guests_btn" onclick="guests_clear()" class="btn shadow-none btn-sm text-secondary">Reset</button>
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">No. of Guests</label>
                                        <input type="number" min="1" id="guests" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                </div>                                                           
                            </div> 

                            <!-- Duration -->
                            <div class="border lg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>Duration</span>
                                    <button id="days_btn" onclick="days_clear()" class="btn shadow-none btn-sm text-secondary">Reset</button>
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">No. of days</label>
                                        <input type="number" min="1" id="days" oninput="days_filter()" class="form-control shadow-none">
                                    </div>
                                </div>                                                           
                            </div>                          
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4" id="packages-data">
            </div>
            
        </div>
    </div>

    <script>
        
        let packages_data = document.getElementById('packages-data');
        let arrival_date = document.getElementById('arrival_date');
        let leaving_date = document.getElementById('leaving_date');
        let chk_avail_btn = document.getElementById('chk_avail_btn');

        let guests_btn = document.getElementById('guests_btn');
        let guests = document.getElementById('guests');

        let days_btn = document.getElementById('days_btn');
        let days = document.getElementById('days');

        function fetch_packages(){
            let chk_avail = JSON.stringify({
                arrival_date : arrival_date.value,
                leaving_date : leaving_date.value
            });

            let guestData = JSON.stringify({
                guests: guests.value
            });

            let daysData = JSON.stringify({
                days : days.value
            });

            let vehicle_types = [];
            if(document.getElementById('v1').checked) vehicle_types.push('Car');
            if(document.getElementById('v2').checked) vehicle_types.push('Mini Van');
            if(document.getElementById('v3').checked) vehicle_types.push('Large Van');

            let vehicleData = JSON.stringify({
                vehicles: vehicle_types
            });

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "ajax/packages.php?fetch_packages&chk_avail="+chk_avail+"&guests="+guestData+"&days="+daysData+"&vehicles="+vehicleData, true);

            xhr.onprogress = function(){
                packages_data.innerHTML = '<div class="spinner-border text-info mb-3 d-block mx-auto" id="loader" role="status"><span class="visually-hidden">Loading...</span></div>';
            }

            xhr.onload = function(){
                packages_data.innerHTML = this.responseText;
            }

            xhr.send();
        }

        function chk_avail_filter(){
            if(arrival_date.value != '' && leaving_date.value != ''){
                fetch_packages();
                chk_avail_btn.classList.remove('d-none');
            }
        }

        function chk_avail_clear(){
            arrival_date.value='';
            leaving_date.value='';
            chk_avail_btn.classList.add('d-none');
            fetch_packages();            
        }

        function guests_filter(){
            if(guests.value > 0){
                fetch_packages();
                guests_btn.classList.remove('d-none');
            }
        }

        function guests_clear(){
            guests.value = '';
            guests_btn.classList.add('d-none');
            fetch_packages();
        }

        function days_filter(){
            if(days.value > 0){
                fetch_packages();
                days_btn.classList.remove('d-none');
            }
        }

        function days_clear(){
            days.value = '';
            days_btn.classList.add('d-none');
            fetch_packages();
        }

        function vehicle_clear(){
            document.getElementById('v1').checked = false;
            document.getElementById('v2').checked = false;
            document.getElementById('v3').checked = false;
            fetch_packages();
        }

        window.onload = function(){
            fetch_packages();
        }

    </script>


    <?php require('inc/footer.php'); ?>

</body>
</html>