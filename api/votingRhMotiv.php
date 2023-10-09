<?php 
  session_start();
  $data = json_decode(file_get_contents("php://input"), true);
  $choice = $data["choice"];
  $novalue = "false";
  $cookieName = "rhMotiv";
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
  
        $stmt = $conn->prepare("INSERT INTO rhmotiv(ip, email, KOUAKOU, KOKO, DEBLEZA, BROUAHIMA) VALUES(:ip, :email, :KOUAKOU, :KOKO, :DEBLEZA, :BROUAHIMA)");
  
        $stmt->bindParam(":ip", $ip);
        $stmt->bindParam(":email", $email);
  
        if($choice == "KOUAKOU") {
          $stmt->bindParam(":KOUAKOU", $choice);
          $stmt->bindParam(":KOKO", $novalue);
          $stmt->bindParam(":DEBLEZA", $novalue);
          $stmt->bindParam(":BROUAHIMA", $novalue);
        }elseif($choice == "KOKO") {
          $stmt->bindParam(":KOUAKOU", $novalue);
          $stmt->bindParam(":KOKO", $choice);
          $stmt->bindParam(":DEBLEZA", $novalue);
          $stmt->bindParam(":BROUAHIMA", $novalue);
        }elseif($choice == "DEBLEZA") {
          $stmt->bindParam(":KOUAKOU", $novalue);
          $stmt->bindParam(":KOKO", $novalue);
          $stmt->bindParam(":DEBLEZA", $choice);
          $stmt->bindParam(":BROUAHIMA", $novalue);
        }else{
          $stmt->bindParam(":KOUAKOU", $novalue);
          $stmt->bindParam(":KOKO", $novalue);
          $stmt->bindParam(":DEBLEZA", $novalue);
          $stmt->bindParam(":BROUAHIMA", $choice);  
        }
  
        $x = $stmt->execute();
        setcookie($cookieName, $cookieValue, time() + (3600 * 24), "/");
  
        if($x) {
          if($choice == "KOUAKOU") {
            $a = $conn->prepare("SELECT COUNT(KOUAKOU) FROM rhmotiv WHERE KOUAKOU = '$choice'");
          }elseif($choice == "KOKO") {
            $a = $conn->prepare("SELECT COUNT(KOKO) FROM rhmotiv WHERE KOKO = '$choice'");
          }elseif($choice == "DEBLEZA") {
            $a = $conn->prepare("SELECT COUNT(DEBLEZA) FROM rhmotiv WHERE DEBLEZA = '$choice'");
          }else{
            $a = $conn->prepare("SELECT COUNT(BROUAHIMA) FROM rhmotiv WHERE BROUAHIMA = 'BROUAHI'");
          }
  
          $a->execute();
          $a->setFetchMode(PDO::FETCH_ASSOC);
          $b = $a->fetchAll();
        
          if($choice == "KOUAKOU") {
            $c = $b[0]["COUNT(KOUAKOU)"];
            $d = "UPDATE linkedinuserspoints SET KOUAKOU = $c";
          }elseif($choice == "KOKO") {
            $c = $b[0]["COUNT(KOKO)"];
            $d = "UPDATE linkedinuserspoints SET KOKO = $c";
          }elseif($choice == "DEBLEZA") {
            $c = $b[0]["COUNT(DEBLEZA)"];
            $d = "UPDATE linkedinuserspoints SET DEBLEZA = $c";
          }else{
            $c = $b[0]["COUNT(BROUAHIMA)"];
            $d = "UPDATE linkedinuserspoints SET BROUAHIMA = $c";
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