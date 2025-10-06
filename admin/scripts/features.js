let feature_s_form = document.getElementById('feature_s_form');

feature_s_form.addEventListener('submit', function(e){
    e.preventDefault();
    add_feature();
})

function add_feature()
{
    let data = new FormData();
    data.append('name',feature_s_form.elements['feature_name'].value);
    data.append('add_feature','');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_crud.php", true);

    xhr.onload = function()
    {
        var myModal = document.getElementById('feature-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if(this.responseText == 1){                    
            alert('success','New feature added!');
            feature_s_form.elements['feature_name'].value = '';
            get_features();
        }
        else{
            alert('error','Server Down!');
        }
    }

    xhr.send(data); 
}

function get_features()
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function()
    {                
        document.getElementById('features-data').innerHTML = this.responseText;
    }

    xhr.send('get_features');            
}

function rem_feature(val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        if(this.responseText==1){
            alert('success','Feature removed!');
            get_features();
        }
        else if(this.responseText == 'package_added'){
            alert('error','Feature is added in package!')

        }
        else{
            alert('error','Server down!')
        }
    }

    xhr.send('rem_feature='+val);            
}

window.onload = function(){
    get_features();
}