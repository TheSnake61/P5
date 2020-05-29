
const username = document.getElementById("registration_username")
const mail = document.getElementById("registration_email")
const password = document.getElementById("registration_password")
const passwordconf = document.getElementById("registration_confirm_password")
const form = document.getElementById("inscform")

form.onsubmit = function() 
{
  if (username.value == "") {
    alert("Saisissez un nom d'utilisateur !");
    return false;
  }
  else {
    return true;
  }
};