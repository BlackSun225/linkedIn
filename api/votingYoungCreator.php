<?php 
  session_start();
  $data = json_decode(file_get_contents("php://input"), true);
  $choice = $data["choice"];
  $novalue = "false";
  $cookieName = "youngCreator";
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
    
        $stmt = $conn->prepare("INSERT INTO youngcontentcreator(ip, email, BLE, SOOLA, ETE, BAMBA) VALUES(:ip, :email, :BLE, :SOOLA, :ETE, :BAMBA)");
    
        $stmt->bindParam(":ip", $ip);
        $stmt->bindParam(":email", $email);
    
        if($choice == "BLE") {
          $stmt->bindParam(":BLE", $choice);
          $stmt->bindParam(":SOOLA", $novalue);
          $stmt->bindParam(":ETE", $novalue);
          $stmt->bindParam(":BAMBA", $novalue);
        }elseif($choice == "SOOLA") {
          $stmt->bindParam(":BLE", $novalue);
          $stmt->bindParam(":SOOLA", $choice);
          $stmt->bindParam(":ETE", $novalue);
          $stmt->bindParam(":BAMBA", $novalue);
        }elseif($choice == "ETE") {
          $stmt->bindParam(":BLE", $novalue);
          $stmt->bindParam(":SOOLA", $novalue);
          $stmt->bindParam(":ETE", $choice);
          $stmt->bindParam(":BAMBA", $novalue);
        }else{
          $stmt->bindParam(":BLE", $novalue);
          $stmt->bindParam(":SOOLA", $novalue);
          $stmt->bindParam(":ETE", $novalue);
          $stmt->bindParam(":BAMBA", $choice);
        }
        $x = $stmt->execute();
        setcookie($cookieName, $cookieValue, time() + (3600 * 24), "/");
        if($x) {
          if($choice == "BLE") {
            $a = $conn->prepare("SELECT COUNT(BLE) FROM youngcontentcreator WHERE BLE = 'BLE'");
          }elseif($choice == "SOOLA") {
            $a = $conn->prepare("SELECT COUNT(SOOLA) FROM youngcontentcreator WHERE SOOLA = 'SOOLA'");
          }elseif($choice == "ETE") {
            $a = $conn->prepare("SELECT COUNT(ETE) FROM youngcontentcreator WHERE ETE = 'ETE'");
          }else{
            $a = $conn->prepare("SELECT COUNT(BAMBA) FROM youngcontentcreator WHERE BAMBA = 'BAMBA'");
          }
          
          $a->execute();
          $a->setFetchMode(PDO::FETCH_ASSOC);
          $b = $a->fetchAll();
    
          if($choice == "BLE") {
            $c = $b[0]["COUNT(BLE)"];
            $d = "UPDATE linkedinuserspoints SET BLE = $c";
          }elseif($choice == "SOOLA") {
            $c = $b[0]["COUNT(SOOLA)"];
            $d = "UPDATE linkedinuserspoints SET SOOLA = $c";
          }elseif($choice == "ETE") {
            $c = $b[0]["COUNT(ETE)"];
            $d = "UPDATE linkedinuserspoints SET ETE = $c";
          }else{
            $c = $b[0]["COUNT(BAMBA)"];
            $d = "UPDATE linkedinuserspoints SET BAMBA = $c";
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