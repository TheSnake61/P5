
const username = document.getElementById("registration_username")
const mail = document.getElementById("registration_email")
const password = document.getElementById("registration_password")
const passwordconf = document.getElementById("registration_confirm_password")
const form = document.getElementById("inscform")


function validateEmail(mail) {
  const re = /\S+@\S+\.\S+/;
  return re.test(mail.value);
}

form.onsubmit = function(e) 
{
  
  if (username.value == "") {
    swal("Saisissez un nom d'utilisateur !");
    return false;
  }
  else if (mail.value == "") {
    swal("Saisissez un email !");
    return false;
  }
  else if (mail.value == "") {
    swal("Saisissez un email !");
    return false;
  }
  else if (!validateEmail(mail)) {
    swal("Saisissez un email valide !");
    return false;
  }
  else if (password.value == "") {
    swal("Saisissez un mot de passe !");
    return false;
  }
  else if (password.value.length < 8) {
    swal("Votre mot de passe doit faire au minimum 8 caractères !");
    return false;
  }
  else if (passwordconf.value == "") {
    swal("Veuillez confirmer votre mot de passe !");
    return false;
  }
  else if (passwordconf.value != password.value) {
    swal("La confirmation de votre mot de passe ne correspond pas à votre mot de passe !");
    return false;
  }
  else {
    return true;
  }
};