<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Packages</title>

    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Packages</h3>

                <div class="card border-0 shadow-sm mb-4"> 
                    <div class="card-body">
                        
                        <div class="text-end mb-4"> 
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-package">
                                <i class="bi bi-plus-square"></i> Add 
                            </button> 
                        </div>

                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Guests</th>
                                        <th scope="col">No of Days</th>
                                        <th scope="col">Vehicle Type</th>
                                        <th scope="col">Suggest Places</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="package-data">                                    
                                </tbody>
                            </table>
                        </div>                        
                    </div> 
                </div>         
            </div>
        </div>
    </div>

    <!-- Add package modal -->

    <div class="modal fade" id="add-package" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_package_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Package</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Guests</label>
                                <input type="number" min="1" name="guests" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No of Days</label>
                                <input type="number" min="1" name="days" class="form-control shadow-none" required>
                            </div>   
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vehicle Type</label>
                                <input type="text" name="vehicle" class="form-control shadow-none" required>
                            </div>  
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Suggest Places</label>
                                <input type="text" name="places" class="form-control shadow-none" required>
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Features</label>
                                <div class="row">
                                    <?php
                                        $res = selectAll('features');
                                        while($opt = mysqli_fetch_assoc($res)){
                                            echo"
                                                <div class='col-md-3 mb-1'>
                                                    <label>
                                                        <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                        $opt[name]
                                                    </label>
                                                </div>
                                            ";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>                           
                            </div>

                        </div>
                               
                    </div>
                    
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>

    <!-- Edit package modal -->

    <div class="modal fade" id="edit-package" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_package_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Package</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Guests</label>
                                <input type="number" min="1" name="guests" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No of Days</label>
                                <input type="number" min="1" name="days" class="form-control shadow-none" required>
                            </div>   
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vehicle Type</label>
                                <input type="text" name="vehicle" class="form-control shadow-none" required>
                            </div>  
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Suggest Places</label>
                                <input type="text" name="places" class="form-control shadow-none" required>
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Features</label>
                                <div class="row">
                                    <?php
                                        $res = selectAll('features');
                                        while($opt = mysqli_fetch_assoc($res)){
                                            echo"
                                                <div class='col-md-3 mb-1'>
                                                    <label>
                                                        <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                        $opt[name]
                                                    </label>
                                                </div>
                                            ";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>                           
                            </div>
                            <input type="hidden" name="package_id">

                        </div>
                               
                    </div>
                    
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>
    <script>
        let add_package_form = document.getElementById('add_package_form');

        add_package_form.addEventListener('submit',function(e){
            e.preventDefault();
            add_packages();
        });

        function add_packages()
        {
            let data = new FormData();
            data.append('add_package','');
            data.append('name',add_package_form.elements['name'].value);
            data.append('guests',add_package_form.elements['guests'].value);
            data.append('days',add_package_form.elements['days'].value);
            data.append('vehicle',add_package_form.elements['vehicle'].value);
            data.append('places',add_package_form.elements['places'].value);
            data.append('price',add_package_form.elements['price'].value);
            data.append('desc',add_package_form.elements['desc'].value);  
            
            let features = [];
            add_package_form.elements['features'].forEach(el => {
                if(el.checked){
                    features.push(el.value);
                }                
            });

            data.append('features',JSON.stringify(features));  

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/packages.php", true);

            xhr.onload = function(){
                var myModal = document.getElementById('add-package');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                if(this.responseText == 1){                    
                    alert('success','New package added!');
                    add_package_form.reset();
                    get_all_packages();
                    
                }
                else{
                    alert('error','Server Down!');
                }
            }

            xhr.send(data); 
        }

        function get_all_packages()
        {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/packages.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function(){
                document.getElementById('package-data').innerHTML = this.responseText;
            }

            xhr.send('get_all_packages'); 
        }

        let edit_package_form = document.getElementById('edit_package_form');

        function edit_details(id)
        {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/packages.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function(){ 
                let data = JSON.parse(this.responseText);
                
                edit_package_form.elements['name'].value = data.packagedata.name;
                edit_package_form.elements['guests'].value = data.packagedata.no_of_guests;
                edit_package_form.elements['days'].value = data.packagedata.no_of_days;
                edit_package_form.elements['vehicle'].value = data.packagedata.vehicle_type;
                edit_package_form.elements['places'].value = data.packagedata.suggest_places;
                edit_package_form.elements['price'].value = data.packagedata.price;
                edit_package_form.elements['desc'].value = data.packagedata.description;
                edit_package_form.elements['package_id'].value = data.packagedata.id;

                edit_package_form.elements['features'].forEach(el => {
                    if(data.features.includes(Number(el.value))){
                        el.checked = true;
                    }                
                });
            }

            xhr.send('get_package='+id); 
        }

        edit_package_form.addEventListener('submit',function(e){
            e.preventDefault();
            submit_edit_package();
        });

        function submit_edit_package()
        {
            let data = new FormData();
            data.append('edit_package','');
            data.append('package_id',edit_package_form.elements['package_id'].value);
            data.append('name',edit_package_form.elements['name'].value);
            data.append('guests',edit_package_form.elements['guests'].value);
            data.append('days',edit_package_form.elements['days'].value);
            data.append('vehicle',edit_package_form.elements['vehicle'].value);
            data.append('places',edit_package_form.elements['places'].value);
            data.append('price',edit_package_form.elements['price'].value);
            data.append('desc',edit_package_form.elements['desc'].value);  
            
            let features = [];
            edit_package_form.elements['features'].forEach(el => {
                if(el.checked){
                    features.push(el.value);
                }                
            });

            data.append('features',JSON.stringify(features));  

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/packages.php", true);

            xhr.onload = function(){
                var myModal = document.getElementById('edit-package');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                if(this.responseText == 1){                    
                    alert('success','Package data edited!');
                    edit_package_form.reset();
                    get_all_packages();
                    
                }
                else{
                    alert('error','Server Down!');
                }
            }

            xhr.send(data); 
        }

        function toggle_status(id, value)
        {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/packages.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function(){
                if(this.responseText == 1){
                   alert('success','Status toggled!');
                   get_all_packages();
                }
                else{
                    alert('error','Server Down!'); 
                }
            }

            xhr.send('toggle_status='+id+'&value='+value); 
        }


        window.onload = function(){
            get_all_packages();
        }

    </script>

</body>
</html>