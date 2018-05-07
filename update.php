 <?php
 //update todos
 require_once 'config.php';  
 if(isset($_POST['taskname'])){
  echo json_encode(array('pop' => 'sazz'));
      $taskname= $_POST['taskname'];
      $date= $_POST['date'];
      $description= $_POST['description'];
      $id= $_POST['id'];
      echo json_encode($taskname);
   $conn = new mysqli($db_servername, $db_username, $db_password, 'phptodoapp');
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $sql = "UPDATE todos SET taskname='".$taskname."' , date='".$date."' , description='".$description."' WHERE id='".$id."'";
     if ($conn->query($sql) === TRUE) {
        echo json_encode("Record updated successfully");

    } else {
        echo json_encode("Error updating record: " . $conn->error);
    }

    $conn->close();
    }
 ?> 