function get_bookings(search='')
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function(){
        document.getElementById('table-data').innerHTML = this.responseText;
    }

    xhr.send('get_bookings&search='+search); 
}

let assign_package_form = document.getElementById('assign_package_form');

function assign_package(id)
{
    assign_package_form.elements['booking_id'].value = id;
}

assign_package_form.addEventListener('submit',function(e){
    e.preventDefault();

    let data = new FormData();
    data.append('no',assign_package_form.elements['no'].value);
    data.append('booking_id',assign_package_form.elements['booking_id'].value);
    data.append('assign_package','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/new_bookings.php",true);

    xhr.onload = function(){
        var myModal = document.getElementById('assign-package');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if(this.responseText == 1){
            alert('success','Tour Number Alloted! Booking Finalized!');
            assign_package_form.reset();
            get_bookings();
        }
        else{
            alert('error','Server Down!');
        }
    }

    xhr.send(data);
    
});

function cancel_booking(id)
{
    if(confirm("Are you sure, you want to cancel this booking?"))
    {
        let data = new FormData();
        data.append('booking_id',id);
        data.append('cancel_booking','');
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/new_bookings.php", true);

        xhr.onload = function()
        {
            if(this.responseText == 1){
                alert('success','Booking Cancelled!');  
                get_bookings();
            }
            else{                    
                alert('error','Server Down!');
            }
        }
        xhr.send(data); 
    } 
}


window.onload = function(){
    get_bookings();
}
