<?php
session_start();


if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: index.php'); // Change to your actual login page
    exit();
}
include("include/connect.php");

If(isset($_POST['changepassword'])){
    $email = $_POST['email'];
$newpassword = $_POST['newpassword'];
$confirmpassword = $_POST['confirmpassword'];
if($_POST['object']=='forgot'){
    if($newpassword==$confirmpassword){
        $hash=password_hash($newpassword, PASSWORD_DEFAULT);
        $query = "UPDATE register SET password = '$hash', status = 1 WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        echo "password setup Sucessfully ";
        header("Location: index.php");
        exit();
    }
    else{
       
       echo "Wrong Password";
       header("Location: changepassword.php");
       exit();
    }    
    
}
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free-5.15.4-web/css/all.css">


   <style>
   

   .btn-color{
     background-color: #0e1c36;
     color: #fff;
     
   }
   
   .cardbody-color{
     background-color: #ebf2fa;
   }
   
   a{
     text-decoration: none;
   }
   .container{
       overflow-x: hidden;
       display: block;
       /* justify-content: center;
       align-items: center; */
      }
   
   /* body{
    background: url(image/7.jpg);
   } */
   </style>

</head>
<body >
 

 <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <h2 class="text-center text-dark mt-5">Change Password</h2>
            <!-- <div class="text-center mb-5 text-dark">Made with bootstrap</div> -->

 <div class="card bg-primary my-5">
 
    <form method="POST" class="card-body cardbody-color p-lg-5">   
 
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="text" class="input-box form-control" name="email" value="<?php echo $_SESSION['email'];?>" readonly>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password:</label>
            <input type="password" class="input-box form-control" name="newpassword" required>
        </div>

        <div class="mb-3">
            <label for="confirmpassword" class="form-label">Confirm Password:</label>
            <input type="password" class="input-box form-control" name="confirmpassword" required>
        </div>

                 <!-- <button type="submit" class="btn btn-danger" name="reset">Submit</button> -->
                <!-- line 37 is introduced for Password reset  -->

            <button type="submit" class="submit-btn btn-color ms-auto" name="changepassword">Change password</button>
            <a href="index.php" class="mx-5" >Back to Login</a>  
                <input type="hidden" name="object" value="forgot"> 


      


    </form>
            </div>
          </div>
        </div>
 </div>



        

</body>
</html>