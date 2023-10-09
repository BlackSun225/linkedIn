<?php 
  $u_email = $_POST["u_email"];
  $u_password = $_POST["u_password"];
  include("config.php");

  try {
    $stmt = $conn->prepare("SELECT * FROM linkedinusers WHERE u_email = :u_email");
    $stmt->bindParam(":u_email", $u_email);
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    if(count($result) == 1) {
      if(password_verify($u_password, $result[0]["u_password"])) {
        session_start();
        $_SESSION["u_name"] = $result[0]["u_name"];
        $_SESSION["u_email"] = $result[0]["u_email"];
        header("Location: ../index.html");
      }else{
        echo("Le mot de passe ne correspond pas");
      }
    }else{
      echo("Vous n'êtes pas enregistré veuillez vous inscrire");
      header("Location: ../register.html");
    }
  }catch(PDOException $e) {
    echo("Error: " . $e->getMessage());
  }

?>