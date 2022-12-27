<?php
session_start();

$db = new PDO('sqlite:../../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['gift']))
{
  $code = $_GET['gift'];
  
  $g = $db->prepare("SELECT * FROM gifts WHERE code=:code");
  $g->execute([':code' => $code]);
  $gift = $g->fetch();
  $g->closeCursor();

  if ($gift && $gift['by'] == $_SESSION['user']['userid'])
  {
    $d = $db->prepare("DELETE FROM gifts WHERE code=:code");
    $d->execute([':code' => $code]);
    $d->closeCursor();
  }
}

$db = null;
?>