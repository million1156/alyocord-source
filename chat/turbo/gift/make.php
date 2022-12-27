<?php
session_start();

if (!isset($_SESSION['admin']))
{
  header("Location: //alyocord.com/");
}

$db = new PDO('sqlite:../../../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <script src='https://alyocord.com/jquery-3.6.1.js'></script>
  <link rel="stylesheet" href="//alyocord.com/chat/style.css?version=<?php echo time(); ?>">
  <script src='//alyocord.com/chat/script.js?version=<?php echo time(); ?>'></script>
  <link rel='icon' type='image/x-icon' href='//alyocord.com/cdn-1/favicon.ico'>
  <title>Make Turbo Gifts</title>
</head>
<body>
  <form method='post'>
    <input name='quantity' type='number' placeholder='Quantity'>
    <select name="type">
      <option value='Turbo Premium' selected>Turbo Premium</option>
    </select>
    <select name="months">
      <option value='1' selected>1 Month</option>
      <option value='3'>3 Months</option>
      <option value='12'>1 Year</option>
    </select>
    <select name="bywho">
      <?php
        $users = $db->query('SELECT * FROM users');
        foreach ($users as $user)
        {
          $yahh = $user['id'] == $_SESSION['user']['userid'] ? 'selected' : null;
          $unm = $user['username'];
          $uid = $user['id'];
          echo "<option value='$uid' ".$yahh.">$unm</option>\n";
        }
      ?>
    </select>
    <button type='submit' class='button' name='yeah' value='mhm'>Make</button>
  </form>
  <p>
  <?php
    if (isset($_POST['yeah']))
    {
      function makeCode($length=16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
      }

      $code = makeCode();
      $link = "https://alyocord.com/chat/turbo/gift?code=$code";

      for ($k = 0 ; $k < intval($_POST['quantity']); $k++)
      {
        $code = makeCode();
        $link = "https://alyocord.com/chat/turbo/gift?code=$code";
        
        $qr = $db->prepare("INSERT INTO gifts (made, code, claimed, type, months, by) VALUES (:made, :code, 0, :type, :months, :by)");
        $qr->bindValue(":type", $_POST['type']);
        $qr->bindValue(":code", $code);
        $qr->bindValue(":made", time());
        $qr->bindValue(":months", intval($_POST['months']));
        $qr->bindValue(":by", intval($_POST['bywho']));
        $qr->execute();
        $qr->closeCursor();
        
        echo $link."<br/>";
      }
    }
    ?>
  </p>

  <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>

<?php
$db = null;
?>
</html>