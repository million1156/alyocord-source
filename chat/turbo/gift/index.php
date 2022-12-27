<?php
session_start();

if (!isset($_SESSION['loggedin']))
{
  $ml = isset($_GET['code']) ? $_GET['code'] : '123';
  $thing = "chat/turbo/gift?code=" . $ml;
  echo("<script> window.location.href = '//alyocord.com/login/?to=$thing' </script>");
}

$db = new PDO('sqlite:../../../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$qr = $db->prepare("SELECT * FROM gifts WHERE code=:code");
$qr->bindValue(":code", isset($_GET['code']) ? $_GET['code'] : '123');
$qr->execute();
$row = $qr->fetch();
$qr->closeCursor();

$exists = false;
$expired = true;
$claimed = true;

$title = "Invalid Gift!";

if ($row) {
  $type = $row['type'];
  $exists = true;
  if (!(intval($row['made']) + (3600 * 48) <= time())) {
    $expired = false;
  }
  if ($row['claimed'] == 0) {
    $claimed = false;
    $mnth = intval($row['months']) > 1 ? "months" : "month";
    $title = "Claim ".$row['months']." ".$mnth." of $type";
  }
  $u = $db->prepare('SELECT * FROM users WHERE id=:user');
  $u->execute([':user' => $row['by']]);
  $gifter = $u->fetch();
  $u->closeCursor();
}

if (isset($_POST['claim']))
{
  if ($claimed == false && $expired == false && $exists == true)
  {
    $qrr = $db->prepare("SELECT * FROM users WHERE id=:uid");
    $qrr->bindValue(":uid", $_SESSION['user']['userid']);
    $qrr->execute();
    $roww = $qrr->fetch();
    $qrr->closeCursor();
    if (intval($roww['turbolast']) != 0 || time() - intval($roww['turbolast']) <= 2630000)
    {
      $sq = $db->prepare("UPDATE users SET turbocredit=:tc, timesturbo=:tt WHERE id=:uid");
      $sq->bindValue(":tc", intval($row['months']) > 1 ? intval($roww['turbocredit'])+intval($row['months']) : intval($roww['turbocredit'])+1);
      $sq->bindValue(":uid", $roww['id']);
      $sq->bindValue(":tt", intval($roww['timesturbo']));
      $sq->execute();
      $sq->closeCursor();
      $sqq = $db->prepare("UPDATE gifts SET claimed=1 WHERE code=:code");
      $sqq->bindValue(":code", $_GET['code']);
      $sqq->execute();
      $sqq->closeCursor();
      $tmmm = $row['months'];
      $claimed = true;
      $extra = "<h2>Successfully claimed $tmmm months of $type! Enjoy!</h2>";
    }
    else
    {
      $sq = $db->prepare("UPDATE users SET turbolast=:tc, timesturbo=:tt, turbocredit=:tcc WHERE id=:uid");
      $sq->bindValue(":tc", time());
      $sq->bindValue(":tt", intval($roww['timesturbo'])+1);
      $sq->bindValue(":uid", $roww['id']);
      $sq->bindValue(":tcc", intval($row['months']) > 1 ? intval($roww['turbocredit'])+intval($row['months'])-1 : $roww['turbocredit']);
      $sq->execute();
      $sq->closeCursor();
      $sqq = $db->prepare("UPDATE gifts SET claimed=1 WHERE code=:code");
      $sqq->bindValue(":code", $_GET['code']);
      $sqq->execute();
      $sqq->closeCursor();
      $tmmm = $row['months'];
      $claimed = true;
      $extra = "<h2>Successfully claimed $tmmm months of $type! Enjoy!</h2>";
    }
  }
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src='//alyocord.com//jquery-3.6.1.js'></script>
  <link rel="stylesheet" href="//alyocord.com/chat/style.css?version=<?php echo time(); ?>">
  <script src='//alyocord.com/chat/script.js?version=<?php echo time(); ?>'></script>
  <link rel='icon' type='image/x-icon' href='//alyocord.com/cdn-1/favicon.ico'>
  
  <meta content="Claim Gifts!" property="og:title" />
  <meta content="Here you can claim Alyocord gifts like Turbo, etc!" property="og:description" />
  <meta content="https://alyocord.com/gift?code=<?php echo isset($_GET['code']) ? $_GET['code'] : "123"; ?>" property="og:url" />
  <meta content="//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png" property="og:image" />
  <meta content="#43B581" data-react-helmet="true" name="theme-color" />
  
  <title><?php echo $title; ?></title>
</head>
<body>
  <center>
  <br/>
  <button class='button' onclick='window.location.href = "//alyocord.com/chat/";'>Chat</button> <br> <br>
  <button class='button' onclick='history.back();'>Back</button>
  <br/><br/><br/>
  <?php echo $extra; ?>
  <br/><br/>
  <?php if ($exists == true && $claimed == false && $expired == false) { ?>

  <h1><b><?php $nid = sprintf('%04d', $gifter['nameid']); echo $gifter['username']."#".$nid; ?></b></h1><h1> has gifted you <?php echo $row['months']." "; echo intval($row['months']) > 1 ? "months" : "month"; ?> of <?php echo $type; ?>!</h1>
  <p style='font-size:20px;color:grey;'>what a generous person!</p>
  <img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='100' width='100'><br/><br/>
  <form method='post'>
    <button type='submit' class='button' name='claim' value='mhm' style='background:purple;'>Claim</button>
  </form>

  <?php } elseif ($claimed == true && $exists == true) { ?>

  <h1>Gift already claimed!</h1>
  <img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='100' width='100'><br/><br/>
  <form method='post'>
    <button class='button' disabled style='background:grey;'>Claim</button>
  </form>

  <?php } elseif ($exists == false) { ?>

  <h1>Gift does not exist!</h1>
  <img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='100' width='100'><br/><br/>
  <form method='post'>
    <button class='button' disabled style='background:grey;'>Claim</button>
  </form>
    
  <?php } elseif ($expired == true) { ?>

  <h1>Gift has expired!</h1>
  <img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='100' width='100'><br/><br/>
  <form method='post'>
    <button class='button' disabled style='background:grey;'>Claim</button>
  </form>
    
  <?php } ?>

  </center>

  <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>

<?php $db = null; ?>
</html>