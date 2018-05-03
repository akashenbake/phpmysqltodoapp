<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="dashboard.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
  <?php
  require_once 'config.php';
  $sql = "SELECT * FROM todos";
  $tasks = $conn->query($sql);
  $tasknameErr = $dateErr = $descriptionErr = "";
  $taskname = $date = $description = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["taskname"])) {
      $tasknameErr = "Please enter taskname";
    } 
    else{
     $taskname = test_input($_POST["taskname"]);
   }

   if (empty($_POST["date"])) {
    $dateErr = "Please enter date";
  }
  else{
    $date = test_input($_POST["date"]);
  }

  if(empty($_POST["description"])) {
    $descriptionErr = "Please enter the description";
  } 
  else {
    $description = test_input($_POST["description"]);

  }

// Add todos
  if(empty($tasknameErr) && empty($dateErr) && empty($descriptionErr)){
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
    $stmt = $conn->prepare("INSERT INTO todos (taskname, date, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $insert_taskname, $insert_date, $insert_description);
    $insert_taskname = $taskname;
    $insert_date = $date;
    $insert_description = $description;
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("location:dashboard.php");
  }

// Delete todos
  if(isset($_GET['delete'])){
    $deleteId = $_GET['delete'];
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
// Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } 
    $sql = "DELETE FROM todos WHERE id='".$deleteId."'";

    if ($conn->query($sql) === TRUE) {
      header("location:dashboard.php");
    } else {
      echo "Error deleting record: " . $conn->error;
    }

    $conn->close(); 
  }
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}



?>
<nav class="navbar navbar-default">
  <div class="container-fluid">

    <ul class="nav navbar-nav navbar-right">
      <li> <button type="button" class="btn btn-default" >
        <span class="glyphicon glyphicon-log-out"></span> LogOut
      </button></li>
    </ul>

  </div>
</nav>

<div class="absolute" > 

 <p class="sign">Dashboard</p>
 <div class="row">
   <div class="col-sm-3"></div>
   <div class="col-sm-6">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
      <div class="form-group">
       <input type="text"  class="form-control input-lg"  placeholder="Task Name" name="taskname">
       <span class="error"> <?php echo $tasknameErr;?></span>
       <br>
     </div>

     <div class="form-group" >
      <input type="date"  class="form-control input-lg"  placeholder="Date" name="date">
      <span class="error"> <?php echo $dateErr;?></span>
      <br>
    </div>

    <div class="form-group">
     <input type="text"  class="form-control input-lg" placeholder="Description" name="description">
     <span class="error"> <?php echo $descriptionErr;?></span>
     <br>
   </div>
   <div class="form-group">
     <input type="hidden"  class="form-control input-lg"  placeholder="Description" name="idUpdate">
   </div>
   <div class="input-group-btn">
     <button class="btn btn-info btn-block input-lg" type='submit' >Add Todo</button>
   </div>
 </div>
</form>
</div>
<div class="col-sm-3"></div>
</div>
</div>
<div class="row"> 
 <div class="col-sm-1"></div>  
 <div class="col-sm-10">
  <table class="table c" >
   <thead>
    <tr>
     <th>Task Id</th>
     <th>Task Name</th>
     <th>Date</th>
     <th>Description</th>
     <th>edit</th>
     <th>Delete</th>
   </tr>
 </thead>
 <tbody>
  <?php
  while($row = $tasks->fetch_assoc()){
    ?>
    <tr>
     <td><span class="c"><?php echo $row["id"] ?></span></td>
     <td><span class="c"><?php echo  $row["taskname"] ?></span></td>
     <td><span class="c"><?php echo $row["date"] ?></span></td>
     <td><span class="c"><?php echo $row["description"] ?></span></td>
     <td>
       <span class=" btn btn-default glyphicon glyphicon-edit"> Edit</span>
     </td>
     <td>
       <span class="btn btn-default glyphicon glyphicon-remove">  <a href="dashboard.php?delete=<?php echo $row['id'];?>"></a> Delete </span>
     </td>
   </tr>
   <?php } ?>
 </tbody>
</table>     
</div>
<div class="col-sm-1"></div>
</div> 
</body>
</html>