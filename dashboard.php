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
  $updatetaskname = $updatedate = $updatedescription = ""; 

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
 // Update todos
if(isset($_GET['udpateId'])){
  $message = "inside update";
  echo "<script type='text/javascript'>alert('$message');</script>";
 /* $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
  // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } 
  $taskname = $_post['taskname'];
  $date = $_post['date'];
  $description = $_post['description'];
  $id = $_post['id'];
  $sql = "UPDATE todos SET taskname='".$taskname."' , date='"$date"' , description=$description WHERE id=$id";*/
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
    <tr id="<?php echo $row['id'];?>">
     <td><span class="c"><?php echo $row["id"] ?></span></td>
     <td data-target="updatetaskname"><span class="c"><?php echo  $row["taskname"] ?></span></td>
     <td data-target="updatedate"><span class="c"><?php echo $row["date"] ?></span></td>
     <td data-target="updatedescription"><span class="c"><?php echo $row["description"] ?></span></td>
     <td>
       <a class=" btn btn-default glyphicon glyphicon-edit" href="#" data-role="update" data-id="<?php echo $row['id'];?>"> Edit</a>
     </td>
     <td>
       <a class="btn btn-default glyphicon glyphicon-remove" href="dashboard.php?delete=<?php echo $row['id'];?>"> Delete </a>
     </td>
   </tr>
   <?php } ?>
 </tbody>
</table>     
</div>
<div class="col-sm-1"></div>
</div> 

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update task details</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
         <label>Taskname</label>
         <input type="text" id="updatetaskname" class="form-control" name="updatetaskname">
        </div>
        <div class="form-group">
         <label>Date</label>
         <input type="date" id="updatedate" class="form-control" name="updatedate">
        </div>
        <div class="form-group">
         <label>Description</label>
         <input type="text" id="updatedescription" class="form-control" name="updatedescription">
        </div>
        <div class="form-group">
         <input type="hidden" id="updateId" class="form-control" name="updateId">
        </div>
      </div>
      <div class="modal-footer">
      <a href="#" id="save" type="button" class="btn btn-primary pull-right">Update</a>
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

</body>
<script>
  $(document).ready(function(){
    $(document).on('click','a[data-role=update]',function(){
    var id = $(this).data('id');
      var taskname = $('#'+id).children('td[data-target=updatetaskname]').text();
      var date = $('#'+id).children('td[data-target=updatedate]').text();
      var description = $('#'+id).children('td[data-target=updatedescription]').text();
      $('#updatetaskname').val(taskname);
      $('#updatedate').val(date);
      $('#updateId').val(id);
      $('#updatedescription').val(description);
      $('#myModal').modal('toggle');

    })
  });
 // Creating event to get data from fields and update in database
  $('#save').click(function(){
    var id = $('#updateId').val();
    var taskname = $('#updatetaskname').val();
    var date = $('#updatedate').val();
    var description = $('#updatedescription').val();

    $.ajax({
      url    : 'update.php' ,
      method : 'post',
      data   : { id : id , taskname : taskname , date : date , description : description},
      success : function(response){
                 console.log(response);
      }
    });
  });
</script>
</html>