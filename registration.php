<!DOCTYPE html>
<html lang="en">
<head>
  <title>Registration</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="registration.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

  <?php
  require_once 'config.php';
// define variables and set to empty values
  $nameErr = $emailErr = $passwordErr = "";
  $name = $email = $password = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
      $nameErr = "Please enter your name";
    } 
    // check if name only contains letters and whitespace
    elseif (!preg_match("/^[a-zA-Z ]*$/",$_POST["name"])) {
      $nameErr = "Only letters and white space allowed"; 
    }
    else{
     $name = test_input($_POST["name"]);
   }


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
       $emailErr="Email already exist";
    }
    else{
      $message = "email stored";
        echo "<script type='text/javascript'>alert('$message');</script>";
       
      $email = test_input($_POST["email"]);
    }
  }
   
  $conn->close();
}


if(empty($_POST["password"])) {
  $passwordErr = "Please enter your password";
} 
else {
  $password = test_input($_POST["password"]);

} 

if(empty($nameErr) && empty($passwordErr) && empty($emailErr)){
  $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $insert_name, $insert_email, $insert_password);

// set parameters and execute
  $insert_name = $name;
  $insert_email = $email;
  $insert_password = $password;
  $stmt->execute();
  $stmt->close();
  $conn->close();
  header( "Location: login.php" );

}

}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}



?>

<div class="absolute"> 

  <p class="sign">Register for your account</p>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
   <div class="form-group">
     <input type="text" id="name" class="form-control input-lg" placeholder="Name" name="name">
     <span class="error"> <?php echo $nameErr;?></span>
     <br>

   </div>


   <div class="form-group" >
     <input type="text" id="email" class="form-control input-lg" placeholder="Email" name="email">
     <span class="error"> <?php echo $emailErr;?></span>
     <br>
   </div>

   <div class="form-group">
     <input type="password" id="password" class="form-control input-lg" placeholder="Password" name="password">
     <span class="error"> <?php echo $passwordErr;?></span>
     <br>
   </div>



   <div  class="input-group-btn">
     <button class="btn btn-info btn-block" id="btn" type='submit' >Register email</button>
   </div>

 </form> 

 <div>
  <p class="last"> Already have an account? <a class="last" href="login.php"> login</a></p>
</div>

</div>

</body>
</html>