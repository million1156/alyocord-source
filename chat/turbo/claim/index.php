<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
  header("Location: //alyocord.funwithalbi.xyz/login/");
}  // what is it (the idea)
  // actually nvm i have a better idea
  // hi, im trying to get the download link for the db for aimee
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="//alyocord.funwithalbi.xyz/chat/style.css?version=<?php echo time(); ?>">
  <script src='//alyocord.funwithalbi.xyz/chat/script.js?version=<?php echo time(); ?>'></script>
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <title>Claim Turbo | Alyocord</title>
</head>
<body>
  <center><br><br>
    <button class='button' onclick='window.location.href = "//alyocord.funwithalbi.xyz/chat/";'>Chat</button> <br> <br>
    <button class='button' onclick='history.back();'>Back</button> <br> <br> <br> <br>
    <div id='gifts'>
      <?php
      if (isset($_SESSION['admin']) && $_SESSION['user']['turbo'] == false) {
        $db = new PDO('sqlite:../../../database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // make it a form
        echo "<form method='post'><img src='https://cdn.discordapp.com/attachments/1028244276590686218/1030940228514480228/turbo.png' height='50' width='50'> &nbsp &nbsp<button class='button' style='background:purple;' type='submit' value='staff' name='submit'>Claim Staff Turbo 1 Month</button></form>";
        if (isset($_POST['submit'])) {
         $type = $_POST['submit'];
          
          if ($type == "staff") { // can't you just.. set your session to admin?
            // set lastturbo from the database to time()
            // set session['turbo'] to true
            // ok lemme do this
            // stop!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            // but ive never done it before so let me do it and change the stuff that wont work please
            try {
              $usrr = $db->prepare("SELECT * FROM users WHERE id=:uid");
              $usrr->bindValue(":uid", $_SESSION['user']['userid']);
              $usrr->execute();
              $row2 = $usrr->fetch();
              $usrr->closeCursor();
              // oh god php is ugly
              // like the arrows are ugly, theres no spacing, why cant it be $uusr -> function() instead of $uusr->function() oh
              // it can i just dont like doing spaces some times also actually nvm idk if it can be like that but lets just go with my hopothosis i think its called
              $turtm = intval($row2['timesturbo']) + 1;
              
              $uusr = $db->prepare("UPDATE users SET turbolast=:time, timesturbo=:ttr WHERE id=:uid");
              $uusr->bindValue(":time", time());
              $uusr->bindValue(":ttr", $turtm);
              $uusr->bindValue(":uid", $_SESSION['user']['userid']);
              $uusr->execute();
              $uusr->closeCursor();
              $_SESSION['user']['turbo'] = true;
              echo "<script>window.location.href = '//alyocord.funwithalbi.xyz/chat/turbo/claim/'</script>";
            } catch (PDOException $ex) {
              echo "<br><br><p style='color:red;'>".$ex->getMessage()."</p>";
            }
          }; // bye
          // bye
        };
          // lemme do this
        // i wanna, i havent coded much on this site
        // true
      } else {
        echo "<h1>There's nothing for you to claim! Come back later.</h1>";
      }
      ?>
    </div>
  </center>

  <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>
</html>