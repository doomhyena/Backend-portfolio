function showForm(form){
        
    if(form == "login"){
        document.getElementById("login").style.display = "block";
        document.getElementById("reg").style.display = "none";
    } else {
        document.getElementById("reg").style.display = "block";
        document.getElementById("login").style.display = "none";
    }        
}