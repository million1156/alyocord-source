<?php
$db = new PDO('sqlite:../database.sqlite'); 
$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

if (isset($_GET['user'])) {
  $code = true;
  $resul = $db->prepare("SELECT * FROM users WHERE id=:uid");
  $resul->bindValue(":uid", $_GET['user']);
  $resul->execute();
  $result = $resul->fetch();
  $resul->closeCursor();
  $data = array(
    "username" => $result['username'],
    "userid" => $result['id'],
    "lastTurbo" => $result['turbolast'],
    "timesTurbo" => $result['timesturbo'],
    "pfp" => $result['pfp'],
    "nameid" => $result['nameid'],
    "tag" => $result['username']."#".sprintf('%04d', $result['nameid'])
  );
  echo json_encode($data);
}

if (isset($_GET['do'])) {
  $code = true;
  $kill = $db->query("DELETE FROM users WHERE userid=:uid");
  $kill->bindValue(":uid", $_SESSION['user']['userid']);
  $kill->execute();
  $kill->closeCursor();
  echo "<script>window.location.href = \"//alyocord.com/login?del=true\";</script>";
}

$db = null;

if (!isset($code)) {
  $data = array(
    "code" => 400,
    "error" => "Bad Request!"
  );
  echo json_encode($data);
}
?>