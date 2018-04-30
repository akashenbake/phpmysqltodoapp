<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="login.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
 <?php
 require_once 'config.php';
// define variables and set to empty values
 $emailErr = $passwordErr = "";
 $email = $password = "";

 if ($_SERVER["REQUEST_METHOD"] == "POST") { 
  if (empty($_POST["email"])) {
    $emailErr = "Please enter your email";
  }
    // check if e-mail address is well-formed
  elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid email format"; 
  }
  else{
   $sql = 'SELECT email FROM users';
   $result = $conn->query($sql);
   $emailExist = false ;
   if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
      if($row["email"] == $_POST["email"])
      { 
       $emailExist = true ;
     }
   }
   if($emailExist == true){
    if(empty($_POST["password"])) {
      $passwordErr = "Please enter your password";
    } 
    else {
      $email=$_POST["email"];
      $sql = "SELECT password FROM users where email='".$email."'";
      $result = $conn->query($sql);
      if ($result ->num_rows > 0) {
       while($row = $result->fetch_assoc()) {
        if($row["password"] == $_POST["password"]){
         header("location: dashboard.php");
       }
       else {
        $passwordErr="Id-password do not match";
      }

    }
    $conn->close();

  }  
}

}
else{
 $emailErr="Email does not exist";
}

$conn->close();
}
}
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}



?>
<div class="absolute" > 

  <p class="sign">Login to your account</p>
  <div class="row">
   <div class="col-lg-4"></div>
   <div >
     <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

       <div class="form-group" >
         <input type="text"  class="form-control input-lg" placeholder="Email" name="email">
         <span class="error"> <?php echo $emailErr;?></span>
         <br>
       </div>
       
       <div class="form-group">
         <input type="password" class="form-control input-lg" placeholder="Password" name="password">
         <span class="error"> <?php echo $passwordErr;?></span>
         <br>
       </div>
       
       <div  class="form-group">
         <button class="btn btn-info btn-block input-lg" id="btn" type='submit'>LogIn</button>
       </div>
       
     </form> 

   </div>
   <div class="col-lg-4"></div>
 </div>
 
 <div>
  <p class="a"> Don't have an account? <a class="a"  href="registration.php">Register account</a></p>
</div>

</div>

</body>
</html>