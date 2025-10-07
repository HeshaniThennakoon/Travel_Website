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

let add_image_form = document.getElementById('add_image_form');

add_image_form.addEventListener('submit',function(e){
    e.preventDefault();
    add_image();
});

function add_image()
{
    let data = new FormData();
    data.append('image',add_image_form.elements['image'].files[0]);
    data.append('package_id',add_image_form.elements['package_id'].value);
    data.append('add_image','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/packages.php", true);

    xhr.onload = function()
    {
        if(this.responseText == 'inv_img'){
            alert('error','Only JPG, WEBP or PNG images are allowed!','image-alert');
        }
        else if(this.responseText == 'inv_size'){
            alert('error','Image should be less than 5MB.','image-alert');
        }
        else if(this.responseText == 'upd_failed'){
            alert('error','Image upload failed. Server Down!','image-alert');
        }
        else{
            alert('success','New Image added!','image-alert');
            package_images(add_image_form.elements['package_id'].value,document.querySelector("#package-images .modal-title").innerText);
            add_image_form.reset();
        }
    }

    xhr.send(data); 
}

function package_images(id,rname){
    document.querySelector("#package-images .modal-title").innerText = rname;
    add_image_form.elements['package_id'].value = id;
    add_image_form.elements['image'].value = '';

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/packages.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
        document.getElementById('package-image-data').innerHTML = this.responseText;
    }

    xhr.send('get_package_images='+id); 
}

function rem_image(img_id,package_id){
    let data = new FormData();
    data.append('image_id',img_id);
    data.append('package_id',package_id);
    data.append('rem_image','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/packages.php", true);

    xhr.onload = function()
    {
        if(this.responseText == 1){
            alert('success','Image Removed!','image-alert');  
            package_images(package_id,document.querySelector("#package-images .modal-title").innerText);       
        }
        else{                    
            alert('error','Image removal failed!','image-alert');
        }
    }

    xhr.send(data); 
}

function thumb_image(img_id,package_id){
    let data = new FormData();
    data.append('image_id',img_id);
    data.append('package_id',package_id);
    data.append('thumb_image','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/packages.php", true);

    xhr.onload = function()
    {
        if(this.responseText == 1){
            alert('success','Image Thumbnail Changed!','image-alert');  
            package_images(package_id,document.querySelector("#package-images .modal-title").innerText);       
        }
        else{                    
            alert('error','Thumbnail removal failed!','image-alert');
        }
    }

    xhr.send(data); 
}

function remove_package(package_id){
    if(confirm("Are you sure, you want to delete this room?"))
    {
        let data = new FormData();
        data.append('package_id',package_id);
        data.append('remove_package','');
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/packages.php", true);

        xhr.onload = function()
        {
            if(this.responseText == 1){
                alert('success','Package removed!');  
                get_all_packages();
            }
            else{                    
                alert('error','Room removal failed!');
            }
        }
        xhr.send(data); 
    }                     
}


window.onload = function(){
    get_all_packages();
}
