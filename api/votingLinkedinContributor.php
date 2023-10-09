<?php 
  session_start();
  $data = json_decode(file_get_contents("php://input"), true);
  $choice = $data["choice"];
  $novalue = "false";
  $cookieName = "linkedinContributor";
  $cookieValue = "ok";

  include("config.php");
  try {
    if(isset($_SESSION["u_email"])) {
      if(!isset($_COOKIE[$cookieName])) {
        $email = $_SESSION["u_email"];
        $ip = "";
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
          $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
          $ip = $_SERVER["REMOTE_ADDR"];
        }
    
        $stmt = $conn->prepare("INSERT INTO linkedInContributor(ip, email, ADJE, KOUADIO, SIBAHI, N_DRI) VALUES(:ip, :email, :ADJE, :KOUADIO, :SIBAHI, :N_DRI)");
    
        $stmt->bindParam(":ip", $ip);
        $stmt->bindParam(":email", $email);
    
        if($choice == "ADJE") {
          $stmt->bindParam(":ADJE", $choice);
          $stmt->bindParam(":KOUADIO", $novalue);
          $stmt->bindParam(":SIBAHI", $novalue);
          $stmt->bindParam(":N_DRI", $novalue);
        }elseif($choice == "KOUADIO") {
          $stmt->bindParam(":ADJE", $novalue);
          $stmt->bindParam(":KOUADIO", $choice);
          $stmt->bindParam(":SIBAHI", $novalue);
          $stmt->bindParam(":N_DRI", $novalue);
        }elseif($choice == "SIBAHI") {
          $stmt->bindParam(":ADJE", $novalue);
          $stmt->bindParam(":KOUADIO", $novalue);
          $stmt->bindParam(":SIBAHI", $choice);
          $stmt->bindParam(":N_DRI", $novalue);
        }else{
          $stmt->bindParam(":ADJE", $novalue);
          $stmt->bindParam(":KOUADIO", $novalue);
          $stmt->bindParam(":SIBAHI", $novalue);
          $stmt->bindParam(":N_DRI", $choice);  
        }
    
        $x = $stmt->execute();
        setcookie($cookieName, $cookieValue, time() + (3600 * 24), "/");
  
        if($x) {
          if($choice == "ADJE") {
            $a = $conn->prepare("SELECT COUNT(ADJE) FROM linkedInContributor WHERE ADJE = '$choice'");
          }elseif($choice == "KOUADIO") {
            $a = $conn->prepare("SELECT COUNT(KOUADIO) FROM linkedInContributor WHERE KOUADIO = '$choice'");
          }elseif($choice == "SIBAHI") {
            $a = $conn->prepare("SELECT COUNT(SIBAHI) FROM linkedInContributor WHERE SIBAHI = '$choice'");
          }else{
            $a = $conn->prepare("SELECT COUNT(N_DRI) FROM linkedInContributor WHERE N_DRI = 'N_DRI'");
          }
    
          $a->execute();
          $a->setFetchMode(PDO::FETCH_ASSOC);
          $b = $a->fetchAll();
          
          if($choice == "ADJE") {
            $c = $b[0]["COUNT(ADJE)"];
            $d = "UPDATE linkedinuserspoints SET ADJE = $c";
          }elseif($choice == "KOUADIO") {
            $c = $b[0]["COUNT(KOUADIO)"];
            $d = "UPDATE linkedinuserspoints SET KOUADIO = $c";
          }elseif($choice == "SIBAHI") {
            $c = $b[0]["COUNT(SIBAHI)"];
            $d = "UPDATE linkedinuserspoints SET SIBAHI = $c";
          }else{
            $c = $b[0]["COUNT(N_DRI)"];
            $d = "UPDATE linkedinuserspoints SET N_DRI = $c";
          }
          
          $conn->exec($d);
          echo($c);
        }
      }else{
        echo("Vous devez patienter 24 heures avant de voter dans cette catégorie");
      }
    }else{
      echo("Veuillez vous enregistrer puis connectez vous");
    }
  }catch(PDOException $e) {
    echo("Error: " . $e->getMessage());
  }

  $conn = null;
?>