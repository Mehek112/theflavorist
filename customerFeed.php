<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Sanitize input
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);
    //choose file option
    if(isset($_FILES['Select_file']))
    {
      echo"<pre>";
      print_r($_FILES);
      echo "</pre>";
      $file_name=$_FILES['Select_file']['name'];
      $file_size=$_FILES['Select_file']['size'];
      $file_type=$_FILES['Select_file']['type'];
      $file_tmp=$_FILES['Select_file']['tmp_name'];
      if(move_uploaded_file($file_tmp,"uploaded/". $file_name)){
          echo "uploaded successfully";
      }
      else
      {
          echo "errors";
      }
    // Insert data
    $sql = "INSERT INTO feedback (name, email, message) VALUES ('$name', '$email', '$message')";
    
    if ($conn->query($sql) === TRUE) 
    {
        echo "<script>
                alert('Feedback submitted successfully!');
                window.location.href = 'customerFeedback.html';
              </script>";
    } 
    else 
    {
        echo "<script>
                alert('Error submitting feedback: " . addslashes($conn->error) . "');
                window.location.href = 'customerFeedback.html';
              </script>";
    }
}
}

$conn->close();
?>

