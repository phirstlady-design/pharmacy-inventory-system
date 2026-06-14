<?php
function user(){
if(isset ($_SESSION['userid'])){
    return true;
    }
}
function userconfirm(){
    if(!user()){ 
    echo "User's Login required";
    header("Location: login.php");
    
    }
}
function admin(){
    if(isset ($_SESSION['adminid'])){
        return true;
    }
}
function adminconfirm(){
    if(!admin()){
   echo "Admin's Login required";
   header("Location: login.php");
    }
}
?>