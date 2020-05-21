
function validateForm() {
    if(document.registration.registration_username.value !="") {
        document.registration.submit();
    }
    else {
        alert("Saisissez un nom d'utilisateur")
    }
}
