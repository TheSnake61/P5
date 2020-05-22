
function validateForm() {
      
    if( document.getElementById("registration_username").value == "" ) {
       alert( "Entrez un nom d'utilisateur valide !" );
       document.getElementById("registration_username").focus() ;
       return false;
    }
    if( document.getElementById("registration_email").value == "" ) {
       alert( "Entrez un Email valide !" );
       document.getElementById("registration_email").focus() ;
       return false;
    }
    if( document.getElementById("registration_password").value == "") {
       
       alert( "Veuillez entrer un mot de passe !" );
       document.getElementById("registration_password").focus() ;
       return false;
    }
    if( document.getElementById("registration_confirm_password").value == "" ) {
       alert( "Veuillez confirmer votre mot de passe !" );
       document.getElementById("registration_confirm_password").focus() ;
       return false;
    }
    return( true );
 }