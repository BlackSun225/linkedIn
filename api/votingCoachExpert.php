<?php 
  session_start();
  $data = json_decode(file_get_contents("php://input"), true);
  $choice = $data["choice"];
  $novalue = "false";
  $cookieName = "coachExpert";
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
    
        $stmt = $conn->prepare("INSERT INTO coachExpert(ip, email, CISSE, KADIO, BOKA, KINGUE) VALUES(:ip, :email, :CISSE, :KADIO, :BOKA, :KINGUE)");
        
        $stmt->bindParam(":ip", $ip);
        $stmt->bindParam(":email", $email);
    
        if($choice == "CISSE") {
          $stmt->bindParam(":CISSE", $choice);
          $stmt->bindParam(":KADIO", $novalue);
          $stmt->bindParam(":BOKA", $novalue);
          $stmt->bindParam(":KINGUE", $novalue);
        }elseif($choice == "KADIO") {
          $stmt->bindParam(":CISSE", $novalue);
          $stmt->bindParam(":KADIO", $choice);
          $stmt->bindParam(":BOKA", $novalue);
          $stmt->bindParam(":KINGUE", $novalue);
        }elseif($choice == "BOKA") {
          $stmt->bindParam(":CISSE", $novalue);
          $stmt->bindParam(":KADIO", $novalue);
          $stmt->bindParam(":BOKA", $choice);
          $stmt->bindParam(":KINGUE", $novalue);
        }else{
          $stmt->bindParam(":CISSE", $novalue);
          $stmt->bindParam(":KADIO", $novalue);
          $stmt->bindParam(":BOKA", $novalue);
          $stmt->bindParam(":KINGUE", $choice);  
        }
    
        $x = $stmt->execute();
        setcookie($cookieName, $cookieValue, time() + (3600 * 24), "/");
    
        if($x) {
          if($choice == "CISSE") {
            $a = $conn->prepare("SELECT COUNT(CISSE) FROM coachExpert WHERE CISSE = '$choice'");
          }elseif($choice == "KADIO") {
            $a = $conn->prepare("SELECT COUNT(KADIO) FROM coachExpert WHERE KADIO = '$choice'");
          }elseif($choice == "BOKA") {
            $a = $conn->prepare("SELECT COUNT(BOKA) FROM coachExpert WHERE BOKA = '$choice'");
          }else{
            $a = $conn->prepare("SELECT COUNT(KINGUE) FROM coachExpert WHERE KINGUE = '$choice'");
          }
    
          $a->execute();
          $a->setFetchMode(PDO::FETCH_ASSOC);
          $b = $a->fetchAll();
          
          if($choice == "CISSE") {
            $c = $b[0]["COUNT(CISSE)"];
            $d = "UPDATE linkedinuserspoints SET CISSE = $c";
          }elseif($choice == "KADIO") {
            $c = $b[0]["COUNT(KADIO)"];
            $d = "UPDATE linkedinuserspoints SET KADIO = $c";
          }elseif($choice == "BOKA") {
            $c = $b[0]["COUNT(BOKA)"];
            $d = "UPDATE linkedinuserspoints SET BOKA = $c";
          }else{
            $c = $b[0]["COUNT(KINGUE)"];
            $d = "UPDATE linkedinuserspoints SET KINGUE = $c";
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