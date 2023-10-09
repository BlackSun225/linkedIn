<?php 
  include("config.php");
  try {
    $stmt = $conn->prepare("SELECT BLE, SOOLA, ETE, BAMBA, KOUAKOU, KOKO, DEBLEZA, BROUAHIMA, CISSE, KADIO, BOKA, KINGUE, ADJE, KOUADIO, SIBAHI, N_DRI FROM linkedinuserspoints");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();
    $a = json_encode($result[0]);
    
    echo($a);
  }catch(PDOException $e) {
    echo("Error: " . $e->getMessage());
  }
?>