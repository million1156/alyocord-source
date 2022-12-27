<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
  header("Location: //alyocord.funwithalbi.xyz/login/");
}

date_default_timezone_set("Europe/Tirane");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src='//alyocord.funwithalbi.xyz/jquery-3.6.1.js'></script>
  <link rel="stylesheet" href="//alyocord.funwithalbi.xyz/chat/style.css?version=<?php echo time(); ?>">
  <script src='//alyocord.funwithalbi.xyz/chat/script.js?version=<?php echo time(); ?>'></script>
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <title>Alyocord Turbo</title>
</head>
<body>
  <center><h1 id="AlyocordTurboFont">Alyocord <span id="pink">Turbo</span></h1> 
    <br>
    <img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='200' width='200'> <br/><br/>
  <button class='button' onclick='window.location.href = "//alyocord.funwithalbi.xyz/chat/";'>Chat</button> <br> <br>
  <button class='button' onclick='history.back();'>Back</button> <br> <br> <br>
  <?php if (!$_SESSION['user']['turbo'] == true) { ?>
  <br>
  <h3>Get Alyocord Turbo and Unlock Special Perks!</h3>
  <br><br>
    <br>
    <h3>Send messages longer than 2,000 characters! (4,000)</h3>
    <h3>Change your tag! (e.g. FunWithAlbi#0001)</h3>
    <h3>Turbo badge on your profile!</h3>
<br><br>
    <?php } ?>

  <div id='alerting'>
    <!-- getenv('SECRET'); -->
  </div>
<div id="paypal-button-container-P-2EM018023U217283LMNEDWUY"></div>
<script src="https://www.paypal.com/sdk/js?client-id=ARwR-Lq8vGU6GfmPeVPWp8DENBfBz8Y2dQO-SCfnczoWPmn0kKXoTnmIVSudnUnPZWHrAkgwgKpxCMlI&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
<div id="paypal-button-container-P-2EM018023U217283LMNEDWUY"></div>
<script src="https://www.paypal.com/sdk/js?client-id=ARwR-Lq8vGU6GfmPeVPWp8DENBfBz8Y2dQO-SCfnczoWPmn0kKXoTnmIVSudnUnPZWHrAkgwgKpxCMlI&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
<form method='post'>
  <input type='text' name='code' autocomplete='off' placeholder='WKHid9AlxTNnGcbA' /> <button class='button' style='background:green;' type='submit' name='claim' value='yes'>Redeem</button>
</form>
    
  <?php
    $db = new PDO('sqlite:../../database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['claim']))
    {
      $yes = $db->prepare("SELECT * FROM gifts WHERE code=:code");
      $yes->execute([':code' => $_POST['code']]);
      $gift = $yes->fetch();
      $yes->closeCursor();
      $exists = false;
      if ($gift)
      {
        $claimed = $gift['claimed'] == 1 ? true : false;
        $expired = intval($gift['made'])+(3600*48) > time() ? false : true;
        $exists = true;
      }
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
          $sq->bindValue(":tc", intval($gift['months']) > 1 ? intval($roww['turbocredit'])+intval($gift['months']) : intval($roww['turbocredit'])+1);
          $sq->bindValue(":uid", $roww['id']);
          $sq->bindValue(":tt", intval($roww['timesturbo']));
          $sq->execute();
          $sq->closeCursor();
          $sqq = $db->prepare("UPDATE gifts SET claimed=1 WHERE code=:code");
          $sqq->bindValue(":code", $_POST['code']);
          $sqq->execute();
          $sqq->closeCursor();
          echo "<p class='c-g'>Successfully claimed gift!</p>";
        }
        else
        {
          $sq = $db->prepare("UPDATE users SET turbolast=:tc, timesturbo=:tt, turbocredit=:tcc WHERE id=:uid");
          $sq->bindValue(":tc", time());
          $sq->bindValue(":tt", intval($roww['timesturbo'])+1);
          $sq->bindValue(":uid", $roww['id']);
          $sq->bindValue(":tcc", intval($gift['months']) > 1 ? intval($roww['turbocredit'])+intval($gift['months'])-1 : roww['turbocredit']);
          $sq->execute();
          $sq->closeCursor();
          $sqq = $db->prepare("UPDATE gifts SET claimed=1 WHERE code=:code");
          $sqq->bindValue(":code", $_POST['code']);
          $sqq->execute();
          $sqq->closeCursor();
          echo "<p class='c-g'>Successfully claimed gift!</p>";
        }
      }
      else
      {
        echo "<p class='c-r'>Invalid Gift!</p>";
      }
    }

    echo "<br/><br/>";

    if ($_SESSION['user']['turbo'] == true) {
      $qr = $db->prepare("SELECT * FROM users WHERE id=:uid");
      $qr->bindValue(":uid", $_SESSION['user']['userid']);
      $qr->execute();
      $row = $qr->fetch();
      $trls = intval($row['turbolast']);
      $qr->closeCursor(); 
      // 
      echo "<h2 class='c-g'>You currently have turbo!</h2><h2 style='color:red;'>Expiring at <br><strong>".date("d/m/Y H:i", $trls + 2592000)."</strong></h2><br/>";
      echo "<h2>Turbo Credit:</h2><br/>";
      if ($row["turbocredit"] < 1)
      {
        echo "<h3>No Turbo credit!</h3>";
      }
      else
      {
        $mn = $row['turbocredit'];
        $y = $mn - 12 * round($mn / 12);
        $l = $y > 1 ? 's' : null;
        $m = $row['turbocredit'] < 12 ? $mn . ' Months' : round($mn / 12) . " Years, $y Month$l";
        echo "<h3>$m of Turbo Premium</h3>";
      }
      
    }
      $g = $db->prepare("SELECT * FROM gifts WHERE by=:uid");
      $g->execute([':uid' => $_SESSION['user']['userid']]);
      $gifts = $g->fetchAll();
      $g->closeCursor();
      if ($gifts)
      {
        echo "<br/><br/><h1>Your gifts:</h1>";
  
        foreach ($gifts as $gift)
        {
          $expire = intval($gift['made'])+(3600 * 48)-time();
          $expi = time()-(intval($gift['made'])+(3600 * 48));
          $time = $gift['months'];
          $tim = intval($gift['months']) > 1 ? 'months' : 'month';
          $type = $gift['type'];
          if (intval($gift['made'])+(3600 * 48) > time())
          {
            $cl = $gift['claimed'] == 1 ? '<span style="color:grey;font-size:15px;">(claimed)</span>' : null;
            $expir = round($expire / 3600) > 1 ? floor($expire / 3600) . " hours" : floor($expire / 60) . ' minutes';
            $expires = "Expires in $expir";
          }
          else
          {
            $cl = '<span style="color:grey;font-size:15px;">(expired)</span>';
            $expir = floor($expi / 3600) > 1 ? floor($expi / 3600) . " minutes" : floor($expi / 60) . ' hours';
            $expires = "Expired $expir ago";
          }
          $id = rand(1, 9899822);
          $code = $gift['code'];
          echo "<div id='$id'><h2>$time $tim of $type</h2>";
          echo "<p>code: <b id='$id-1'>$code</b></p>";
          echo "<p>$expires $cl</b></p> <a href='javascript:void(0)' onclick='copyText(document.getElementById(\"$id-1\").innerHTML)'>Copy</a> &nbsp; <a href='javascript:void(0)' style='color:red;' onclick='deleteGift(\"$id\", \"$code\")'>Delete</a> <br/><br/><br/></div>";
        }
      }

      $db = null;
   ?>

    <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>
</html>