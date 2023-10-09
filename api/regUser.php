<?php 
  $u_name = $_POST["u_name"];
  $u_email = $_POST["u_email"];
  $u_password = password_hash($_POST["u_password"], PASSWORD_DEFAULT);
  include("config.php");
  
  try {
    $stmt = $conn->prepare("INSERT INTO linkedinusers (u_name, u_email, u_password) VALUES (:u_name, :u_email, :u_password)");

    $stmt->bindParam(":u_name", $u_name);
    $stmt->bindParam(":u_email", $u_email);
    $stmt->bindParam(":u_password", $u_password);

    $stmt->execute();
    echo("New user added successfully");
    header("Location: ../login.html");
  }catch(PDOException $e) {
    echo("Error: " . $e->getMessage());
  }

  $conn = null;
?>